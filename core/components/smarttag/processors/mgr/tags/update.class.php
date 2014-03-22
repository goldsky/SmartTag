<?php

class TagsUpdateProcessor extends modObjectUpdateProcessor {

    public $classKey = 'smarttagTags';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TagsUpdate';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $tag = $this->getProperty('tag');
        if (empty($tag)) {
            $this->addFieldError('tag', $this->modx->lexicon('smarttag.tag_err_ns'));
            return FALSE;
        }
        return parent::initialize();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function beforeSet() {
        $smarttagTagresources = $this->object->getMany('Tagresources');
        if ($smarttagTagresources) {
            foreach ($smarttagTagresources as $smarttagTagresource) {
                $resource = $this->modx->getObject('modResource', $smarttagTagresource->get('resource_id'));
                if (!$resource) {
                    continue;
                }
                $tvValue = $resource->getTVValue($smarttagTagresource->get('tmplvar_id'));
                if (!$tvValue) {
                    continue;
                }
                $allValues = array();
                // getting values from 'default' output
                $tvValues = @explode('||', $tvValue);
                // getting values from parsed 'smarttag' output
                foreach ($tvValues as $oriValue) {
                    $allValues = array_merge($allValues, @explode(',', $oriValue));
                }
                $key = array_search($this->object->get('tag'), $allValues);
                $allValues[$key] = $this->getProperty('tag');
                $tvValue = @implode('||', $allValues);
                $resource->setTVValue($smarttagTagresource->get('tmplvar_id'), $tvValue);
            }
        }
        return parent::beforeSet();
    }

}

return 'TagsUpdateProcessor';
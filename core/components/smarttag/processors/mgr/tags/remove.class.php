<?php

class TagsRemoveProcessor extends modObjectRemoveProcessor {

    public $classKey = 'smarttagTags';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TagsRemove';

    /**
     * Can contain pre-removal logic; return false to prevent remove.
     * @return boolean
     */
    public function beforeRemove() {
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
                unset($allValues[$key]);
                $tvValue = @implode('||', $allValues);
                $resource->setTVValue($smarttagTagresource->get('tmplvar_id'), $tvValue);
            }
        }
        return parent::beforeRemove();
    }

}

return 'TagsRemoveProcessor';

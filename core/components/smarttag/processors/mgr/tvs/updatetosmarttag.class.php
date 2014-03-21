<?php

class TVsUpdateToSmartTagProcessor extends modObjectUpdateProcessor {

    public $classKey = 'modTemplateVar';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TVsUpdateToSmartTag';

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function afterSave() {
        $existingTVRes = $this->modx->getCollection('modTemplateVarResource', array(
            'tmplvarid' => $this->getProperty('id')
        ));
        if ($existingTVRes) {
            foreach ($existingTVRes as $v) {
                $value = $v->get('value');
                $value = str_replace(',', '||', $value);
                $v->set('value', $value);
                $v->save();
                $this->_insertTags($v->toArray());
            }
        }
        return true;
    }

    private function _insertTags($TVRes) {
        $values = explode('||', $TVRes['value']);
        foreach ($values as $value) {
            $tag = $this->modx->getObject('smarttagTags', array(
                'tag' => $value
            ));
            if (!$tag) {
                $tag = $this->modx->newObject('smarttagTags');
                $tag->set('tag', $value);
                $tag->save();
            }
            $smarttagTagresources = $this->modx->newObject('smarttagTagresources');
            $params = array(
                'tag_id' => $tag->getPrimaryKey(),
                'tmplvar_id' => $TVRes['tmplvarid'],
                'resource_id' => $TVRes['contentid'],
            );
            $smarttagTagresources->fromArray($params, NULL, TRUE, TRUE);
            $smarttagTagresources = array($smarttagTagresources);
            $tag->addMany($smarttagTagresources);
            $tag->save();
        }
    }
}

return 'TVsUpdateToSmartTagProcessor';

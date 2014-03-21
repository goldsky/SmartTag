<?php

class TVsUpdateFromSmartTagProcessor extends modObjectUpdateProcessor {

    public $classKey = 'modTemplateVar';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TVsUpdateFromSmartTag';

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
                $value = str_replace('||', ',', $value);
                $v->set('value', $value);
                $v->save();
                $this->_removeTags($v->toArray());
            }
        }
        return true;
    }

    private function _removeTags($TVRes) {
        return $this->modx->removeCollection('smarttagTagresources', array(
            'tmplvar_id' => $TVRes['tmplvarid'],
            'resource_id' => $TVRes['contentid'],
        ));
    }
}

return 'TVsUpdateFromSmartTagProcessor';
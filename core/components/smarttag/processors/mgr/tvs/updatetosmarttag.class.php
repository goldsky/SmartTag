<?php

/**
 * SmartTag
 *
 * Copyright 2014 by goldsky <goldsky@virtudraft.com>
 *
 * This file is part of SmartTag, a MODX's custom Template Variable for tagging
 *
 * SmartTag is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation version 3.
 *
 * SmartTag is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * SmartTag; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package smarttag
 * @subpackage processor
 */
class TVsUpdateToSmartTagProcessor extends modObjectUpdateProcessor {

    public $classKey = 'modTemplateVar';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TVsUpdateToSmartTag';

    public function initialize() {
        $this->setProperty('display', 'smarttag');
        return parent::initialize();
    }
    
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

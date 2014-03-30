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

// Apache's timeout: 600 secs
if (function_exists('ini_get') && !ini_get('safe_mode')) {
    if (function_exists('set_time_limit')) {
        set_time_limit(600);
    }
    if (function_exists('ini_set')) {
        if (ini_get('max_execution_time') !== 600) {
            ini_set('max_execution_time', 600);
        }
    }
}

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
                $values = array_map('trim', @explode(',', $value));
                $value = @implode('||', $values);
                $v->set('value', $value);
                $v->save();
                $this->_insertTags($v->toArray());
            }
        }
        return true;
    }

    private function _insertTags($TVRes) {
        $values = array_map('trim', @explode('||', $TVRes['value']));
        foreach ($values as $value) {
            $tag = $this->modx->getObject('smarttagTags', array(
                'tag' => $value
            ));
            if (!$tag) {
                $tag = $this->modx->newObject('smarttagTags');
                $tag->set('tag', $value);
                $tag->save();
            }
            $params = array(
                'tag_id' => $tag->getPrimaryKey(),
                'tmplvar_id' => $TVRes['tmplvarid'],
                'resource_id' => $TVRes['contentid'],
            );
            $smarttagTagresources = $this->modx->getObject('smarttagTagresources', $params);
            if (!$smarttagTagresources) {
                $smarttagTagresources = $this->modx->newObject('smarttagTagresources');
                $smarttagTagresources->fromArray($params, NULL, TRUE, TRUE);
                $smarttagTagresources->save();
            }
        }
    }
}

return 'TVsUpdateToSmartTagProcessor';

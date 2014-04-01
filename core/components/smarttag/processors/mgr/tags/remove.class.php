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
                // getting values from parsed 'smarttag' output
                $tvValue = str_replace(',', '||', $tvValue);
                // getting values from 'default' output
                $tvValues = @explode('||', $tvValue);
                $tvValues = array_unique($tvValues);
                $key = array_search($this->object->get('tag'), $tvValues);
                if (is_numeric($key)) {
                    unset($tvValues[$key]);
                    $tvValue = @implode('||', $tvValues);
                    $resource->setTVValue($smarttagTagresource->get('tmplvar_id'), $tvValue);
                }
            }
        }
        return parent::beforeRemove();
    }

}

return 'TagsRemoveProcessor';

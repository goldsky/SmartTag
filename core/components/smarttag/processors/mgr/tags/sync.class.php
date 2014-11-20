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

class TagsSyncProcessor extends modObjectGetProcessor {

    public $classKey = 'smarttagTags';
    public $languageTopics = array('smarttag:default');
    public $objectType = 'smarttag.TagsSync';

    public function beforeOutput() {
        // smarttagTagresources
        $this->modx->removeCollection('smarttagTagresources', array(
            'tag_id' => $this->object->get('id')
        ));
        
        $c = $this->modx->newQuery('modResource');
        $c->leftJoin('modTemplateVarResource', 'TemplateVarResources', 'TemplateVarResources.contentid = modResource.id');
        $c->leftJoin('modTemplateVar', 'TemplateVar', 'TemplateVar.id = TemplateVarResources.tmplvarid');
        $c->select(array(
            'modResource.id',
            'tmplvarid' => 'TemplateVar.id',
            'value' => 'TemplateVarResources.value',
        ));
        $tag = $this->object->get('tag');
        $c->where(array(
            'TemplateVar.type:=' => 'smarttag',
            array(
                "`TemplateVarResources`.`value` LIKE '$tag' OR "
                . "`TemplateVarResources`.`value` LIKE '$tag||%' OR "
                . "`TemplateVarResources`.`value` LIKE '%||$tag' OR "
                . "`TemplateVarResources`.`value` LIKE '%||$tag||%'",
            )
        ));
        $resources = $this->modx->getCollection('modResource', $c);
        if ($resources) {
            $smarttagTagresources = array();
            foreach ($resources as $resource) {
                $resourceArray = $resource->toArray('', null, true);
                $Tagresource = $this->modx->newObject('smarttagTagresources');
                $Tagresource->fromArray(array(
                    'tag_id' => $this->object->get('id'),
                    'tmplvar_id' => $resourceArray['tmplvarid'],
                    'resource_id' => $resourceArray['id'],
                ), '', true, true);
                $smarttagTagresources[] = $Tagresource;
            }
            $this->object->addMany($smarttagTagresources);
            $this->object->save();
        }
    }

}

return 'TagsSyncProcessor';

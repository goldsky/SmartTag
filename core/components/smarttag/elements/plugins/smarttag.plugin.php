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
 * @subpackage plugin
 */
$corePath = $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/smarttag/';
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->lexicon->load('smarttag:default');
        $modx->event->output($corePath . 'elements/tv/mgr/input/');
        break;
    case 'OnTVOutputRenderList':
        $modx->lexicon->load('smarttag:default');
        $modx->event->output($corePath . 'elements/tv/web/output/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->lexicon->load('smarttag:default');
        $modx->event->output($corePath . 'elements/tv/mgr/inputproperties/');
        break;
    case 'OnTVOutputRenderPropertiesList':
        $modx->lexicon->load('smarttag:default');
        $modx->event->output($corePath . 'elements/tv/mgr/outputrenderproperties/');
        break;
    case 'OnDocFormSave':
        $tvs = $resource->getTemplateVars();
        if ($tvs) {
            $tablePrefix = $modx->getOption('smarttag.table_prefix', null, $modx->config[modX::OPT_TABLE_PREFIX] . 'smarttag_');
            $modx->addPackage('smarttag', $corePath . 'model/', $tablePrefix);
            foreach ($tvs as $tv) {
                $tvArray = $tv->toArray();
                if ($tvArray['type'] === 'smarttag') {
                    $modx->removeCollection('smarttagTagresources', array(
                        'tmplvar_id' => $tvArray['id'],
                        'resource_id' => $resource->get('id'),
                    ));
                    $values = array_map('trim', @explode('||', $tvArray['value']));
                    if (!empty($values)) {
                        $smarttagTagresources = array();
                        foreach ($values as $value) {
                            if (empty($value)) {
                                continue;
                            }
                            $tag = $modx->getObject('smarttagTags', array(
                                'tag:LIKE' => $value
                            ));
                            if (!$tag) {
                                $tag = $modx->newObject('smarttagTags');
                                $tag->set('tag', $value);
                                $tag->save();
                            }
                            $newTagged = $modx->newObject('smarttagTagresources');
                            $params = array(
                                'tag_id' => $tag->getPrimaryKey(),
                                'tmplvar_id' => $tvArray['id'],
                                'resource_id' => $resource->get('id'),
                            );
                            $newTagged->fromArray($params, NULL, TRUE, TRUE);
                            $newTagged->save();
                        }
                    }
                }
            }
        }
        break;
    case 'OnEmptyTrash':
        if ($resources) {
            $tablePrefix = $modx->getOption('smarttag.table_prefix', null, $modx->config[modX::OPT_TABLE_PREFIX] . 'smarttag_');
            $modx->addPackage('smarttag', $corePath . 'model/', $tablePrefix);
            foreach ($resources as $resource) {
                $modx->removeCollection('smarttagTagresources', array(
                    'resource_id' => $resource->get('id'),
                ));
            }
        }
        break;
    case 'OnTemplateVarRemove':
        if ($templateVar) {
            $tablePrefix = $modx->getOption('smarttag.table_prefix', null, $modx->config[modX::OPT_TABLE_PREFIX] . 'smarttag_');
            $modx->addPackage('smarttag', $corePath . 'model/', $tablePrefix);
            $modx->removeCollection('smarttagTagresources', array(
                'tmplvar_id' => $templateVar->get('id'),
            ));
        }
        break;
    default:
        break;
}

return;
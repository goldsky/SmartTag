<?php

/**
 * SmartTag
 *
 * Copyright 2014-2015 by goldsky <goldsky@virtudraft.com>
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
 * SmartTag build script
 *
 * @package smarttag
 * @subpackage build
 */
$events = array();

$events['OnTVInputRenderList'] = $modx->newObject('modPluginEvent');
$events['OnTVInputRenderList']->fromArray(array(
    'event' => 'OnTVInputRenderList',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

$events['OnTVOutputRenderList'] = $modx->newObject('modPluginEvent');
$events['OnTVOutputRenderList']->fromArray(array(
    'event' => 'OnTVOutputRenderList',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

$events['OnTVInputPropertiesList'] = $modx->newObject('modPluginEvent');
$events['OnTVInputPropertiesList']->fromArray(array(
    'event' => 'OnTVInputPropertiesList',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

$events['OnTVOutputRenderPropertiesList'] = $modx->newObject('modPluginEvent');
$events['OnTVOutputRenderPropertiesList']->fromArray(array(
    'event' => 'OnTVOutputRenderPropertiesList',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

$events['OnDocFormSave'] = $modx->newObject('modPluginEvent');
$events['OnDocFormSave']->fromArray(array(
    'event' => 'OnDocFormSave',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

$events['OnEmptyTrash'] = $modx->newObject('modPluginEvent');
$events['OnEmptyTrash']->fromArray(array(
    'event' => 'OnEmptyTrash',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

$events['OnTemplateVarRemove'] = $modx->newObject('modPluginEvent');
$events['OnTemplateVarRemove']->fromArray(array(
    'event' => 'OnTemplateVarRemove',
    'priority' => 0,
    'propertyset' => 0,
        ), '', true, true);

return $events;

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
/**
 * @param   string  $filename   filename
 * @return  string  file content
 */
$settings = array();

$settings['smarttag.limit'] = $modx->newObject('modSystemSetting');
$settings['smarttag.limit']->fromArray(array(
    'key' => 'smarttag.limit',
    'value' => 50,
    'xtype' => 'textfield',
    'namespace' => 'smarttag',
    'area' => '',
        ), '', true, true);

$settings['smarttag.use_filter'] = $modx->newObject('modSystemSetting');
$settings['smarttag.use_filter']->fromArray(array(
    'key' => 'smarttag.use_filter',
    'value' => 1,
    'xtype' => 'combo-boolean',
    'namespace' => 'smarttag',
    'area' => '',
        ), '', true, true);

return $settings;
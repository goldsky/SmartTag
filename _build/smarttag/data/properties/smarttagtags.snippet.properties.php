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
 * SmartTag build script
 *
 * @package smarttag
 * @subpackage build
 */

if (!function_exists("fixJson")) {

    function fixJson(array $array) {
        $fixed = array();
        foreach ($array as $k => $v) {
            $fixed[] = array(
                'name' => $v['name'],
                'desc' => $v['desc'],
                'type' => $v['xtype'],
                'options' => empty($v['options']) ? '' : $v['options'],
                'value' => $v['value'],
                'lexicon' => $v['lexicon'],
            );
        }
        return $fixed;
    }

}

ob_start();
include dirname(__FILE__) . '/default.smarttagtags.snippet.properties.js';
$json = ob_get_contents();
ob_end_clean();

$properties = $modx->fromJSON($json);
$properties = fixJson($properties);

return $properties;
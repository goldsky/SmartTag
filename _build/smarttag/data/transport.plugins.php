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
if (!function_exists('getSnippetContent')) {

    function getSnippetContent($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<?php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }

}

$plugins = array();

$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->fromArray(array(
    'id' => 0,
    'property_preprocess' => 1,
    'name' => 'SmartTag',
    'description' => '',
    'plugincode' => getSnippetContent($sources['source_core'] . '/elements/plugins/smarttag.plugin.php'),
        ), '', true, true);

/* add plugin events */
$events = include $sources['data'] . 'transport.plugin.events.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO, 'Packaged in ' . count($events) . ' Plugin Events.');
    flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events!');
}

return $plugins;

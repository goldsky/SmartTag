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
$chunks = array();

$chunks[0] = $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'smarttagtags.item',
    'description' => 'Chunk for item template of tag list',
    'snippet' => file_get_contents($sources['source_core'] . '/elements/chunks/smarttagtags.item.chunk.tpl'),
    'properties' => '',
        ), '', true, true);

$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'smarttagtags.wrapper',
    'description' => 'Chunk for wrapper template of tag list',
    'snippet' => file_get_contents($sources['source_core'] . '/elements/chunks/smarttagtags.wrapper.chunk.tpl'),
    'properties' => '',
        ), '', true, true);

return $chunks;
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
 * @subpackage snippet
 */
$includeEmptyTags = intval($modx->getOption('includeEmptyTags', $scriptProperties));
$limit = intval($modx->getOption('limit', $scriptProperties, 10));
$sort = $modx->getOption('sort', $scriptProperties, 'count desc,tag asc');
$tplWrapper = $modx->getOption('tplWrapper', $scriptProperties, 'smarttagtags.wrapper');
$tplItem = $modx->getOption('tplItem', $scriptProperties, 'smarttagtags.item');
$phsPrefix = $modx->getOption('phsPrefix', $scriptProperties, 'smarttag');

$smartTag = $modx->getService('smarttag', 'SmartTag', MODX_CORE_PATH . 'components/smarttag/model/');

if (!($smartTag instanceof SmartTag))
    return '';

$c = $modx->newQuery('smarttagTags');
$c->select(array(
    'smarttagTags.*',
    'count' => "(SELECT COUNT(*) FROM {$modx->getTableName('smarttagTagresources')} AS smarttagTagresources " .
    " WHERE smarttagTagresources.tag_id = smarttagTags.id)",
));
$sorts = @explode(',', $sort);
foreach ($sorts as $v) {
    $sorter = @explode(' ', strtolower($v));
    $sortBy = $sorter[0];
    $sortDir = isset($sorter[1]) && in_array($sorter[1], array('asc', 'desc')) ? $sorter[1] : 'desc';
    $c->sortby($sortBy, $sortDir);
}

if (empty($includeEmptyTags)) {
    $c->having('count > 0');
}

if ($limit) {
    $c->limit($limit);
}

$collection = $modx->getCollection('smarttagTags', $c);
$output = '';
$outputArray = array();
$wrapper = array(
    'tags' => null
);
if ($collection) {
    $items = array();
    foreach ($collection as $item) {
        $phs = $smartTag->setPlaceholders($item->toArray(), $phsPrefix);
        $items[] = $smartTag->parseTpl($tplItem, $phs);
    }
    $wrapper['tags'] = @implode("\n", $items);
    $phs = $smartTag->setPlaceholders($wrapper, $phsPrefix);
    $output = $smartTag->parseTpl($tplWrapper, $phs);
}

return $output;

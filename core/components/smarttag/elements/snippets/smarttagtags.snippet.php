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
 * @package smarttag
 * @subpackage snippet
 */
$docIds = $modx->getOption('docIds', $scriptProperties);
if (!empty($docIds)) {
    $docIds = array_map('trim', @explode(',', $docIds));
}
$parentIds = $modx->getOption('parentIds', $scriptProperties);
if (!empty($parentIds)) {
    $parentIds = array_map('trim', @explode(',', $parentIds));
}
$includeHiddenDocs = $modx->getOption('includeHiddenDocs', $scriptProperties);

// to prevent empty inputs from above properties where meant to be existed
$allTags = $modx->getOption('allTags', $scriptProperties);
if (empty($docIds) && empty($parentIds) && empty($allTags)) {
    return;
}

$includeEmptyTags = intval($modx->getOption('includeEmptyTags', $scriptProperties));
$tvNames = $modx->getOption('tvNames', $scriptProperties);
if (!empty($tvNames)) {
    $tvNames = array_map('trim', @explode(',', $tvNames));
}
$limit = intval($modx->getOption('limit', $scriptProperties, 10));
$sort = $modx->getOption('sort', $scriptProperties, 'count desc,tag asc');
$tplWrapper = $modx->getOption('tplWrapper', $scriptProperties, 'smarttagtags.wrapper');
$tplItem = $modx->getOption('tplItem', $scriptProperties, 'smarttagtags.item');
$phsPrefix = $modx->getOption('phsPrefix', $scriptProperties, 'smarttag');

$smartTag = $modx->getService('smarttag', 'SmartTag', MODX_CORE_PATH . 'components/smarttag/model/');

if (!($smartTag instanceof SmartTag)) {
    return '';
}

$c = $modx->newQuery('smarttagTags');
$c->select(array(
    'smarttagTags.*',
));
$c->distinct();
$c->leftJoin('smarttagTagresources', 'Tagresources', 'Tagresources.tag_id = smarttagTags.id');
$c->leftJoin('modResource', 'Resource', 'Resource.id = Tagresources.resource_id');
$c->where(array(
    'Resource.published:=' => 1,
    'Resource.deleted:!=' => 1,
));

/**
 * start criteria for 'count' alias
 */
$countCriteria = $modx->newQuery('smarttagTagresources');
$countCriteria->select('COUNT(*)');
$countCriteria->where(array(
    'smarttagTagresources.tag_id = smarttagTags.id',
));

$countCriteria->leftJoin('modResource', 'modResource', 'modResource.id = smarttagTagresources.resource_id');
$countCriteria->where(array(
    'modResource.published:=' => 1,
    'modResource.deleted:!=' => 1,
));
if (!empty($docIds)) {
    $c->where(array(
        'Tagresources.resource_id:IN' => $docIds
    ));
    $countCriteria->where(array(
        'smarttagTagresources.resource_id:IN' => $docIds,
    ));
}
if (!empty($parentIds)) {
    $c->where(array(
        'Resource.parent:IN' => $parentIds
    ));
    $countCriteria->where(array(
        'modResource.parent:IN' => $parentIds,
    ));
}
if (empty($includeHiddenDocs)) {
    $c->where(array(
        'Resource.hidemenu:!=' => 1,
    ));
    $countCriteria->where(array(
        'modResource.hidemenu:!=' => 1
    ));
}
if (!empty($tvNames)) {
    $c->leftJoin('modTemplateVar', 'TemplateVar', 'TemplateVar.id = Tagresources.tmplvar_id');
    $c->where(array(
        'TemplateVar.name:IN' => $tvNames,
    ));
    $countCriteria->leftJoin('modTemplateVar', 'modTemplateVar', 'modTemplateVar.id = smarttagTagresources.tmplvar_id');
    $countCriteria->where(array(
        'modTemplateVar.name:IN' => $tvNames,
    ));
}

$countCriteria->prepare();
$countCriteriaSql = $countCriteria->toSQL();

$c->select(array(
    'count' => '(' . $countCriteriaSql . ')',
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
$toArray = $modx->getOption('toArray', $scriptProperties);

if ($collection) {
    $items = array();
    foreach ($collection as $item) {
        $phs = $smartTag->setPlaceholders($item->toArray(), $phsPrefix);
        if ($toArray) {
            $items[] = $phs;
        } else {
            $items[] = $smartTag->parseTpl($tplItem, $phs);
        }
    }
    if ($toArray) {
        $wrapper['tags'] = $items;
        $phs = $smartTag->setPlaceholders($wrapper, $phsPrefix);
        $outputArray = array(
            'properties' => $scriptProperties,
            'output' => $phs,
        );
        $output = '<pre>' . print_r($outputArray, 1) . '</pre>';
    } else {
        $wrapper['tags'] = @implode("\n", $items);
        $phs = $smartTag->setPlaceholders($wrapper, $phsPrefix);
        $output = $smartTag->parseTpl($tplWrapper, $phs);
    }
}
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties);
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return;
}

return $output;

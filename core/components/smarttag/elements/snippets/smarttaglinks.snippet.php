<?php

$limit = intval($modx->getOption('limit', $scriptProperties, 10));
$sort = $modx->getOption('sort', $scriptProperties, 'count asc');

$basePath = $modx->getOption('smarttag.core_path', $scriptProperties, $modx->getOption('core_path') . 'components/smarttag/');
$modx->addPackage('smarttag', $basePath . 'model/');

$c = $modx->newQuery('smarttagTags');
$c->select(array(
    '*',
    $modx->escape('count') => "(SELECT COUNT(*) FROM {$modx->getTableName('smarttagTagresources')}
        WHERE {$modx->getTableName('smarttagTagresources')}.`tag_id` = {$modx->getTableName('smarttagTags')}.`id`)",
));
$sorts = @explode(',', $sort);
foreach ($sorts as $v) {
    $sorter = @explode(' ', strtolower($v));
    $sortBy = $sorter[0];
    $sortDir = isset($sorter[1]) && in_array($sorter[1], array('asc', 'desc')) ? $sorter[1] : 'desc';
    $c->sortby($sortBy, $sortDir);
}

if ($limit) {
    $c->limit($limit);
}

$collection = $modx->getCollection('smarttagTags', $c);
$output = '';
if ($collection) {
    foreach ($collection as $item) {

    }
}

return $output;
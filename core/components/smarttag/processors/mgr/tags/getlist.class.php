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
class TagsGetListProcessor extends modObjectGetListProcessor {

    public $classKey = 'smarttagTags';
    public $languageTopics = array('smarttag:default');
    public $defaultSortField = 'tag';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'smarttag.TagsGetList';

    /**
     * Can be used to adjust the query prior to the COUNT statement
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $props = $this->getProperties();
        
        $c->distinct();
        // from combobox
        if (isset($props['valuesqry'])) {
            if ($props['valuesqry'] === 'true') {
                if (isset($props['query'])) {
                    if (!empty($props['query'])) {
                        $query = urldecode($props['query']);
                        $queries = @explode('||', $query);
                        $i = 0;
                        $where = '';
                        foreach ($queries as $query) {
                            $where .= ($i !== 0 ? ' OR ' : '')  . "`smarttagTags`.`tag` = '$query'";
                            $i++;
                        }
                        $c->where("($where)");
                    }
                }
            } else {
                if (isset($props['query']) && !empty($props['query'])) {
                    $c->where(array(
                        'tag:LIKE' => "{$props['query']}%",
                        'OR:tag:LIKE' => "% {$props['query']}%",
                    ));
                }
            }
        }
        // from textfield
        else {
            if (isset($props['query']) && !empty($props['query'])) {
                $c->where(array(
                    'tag:LIKE' => "%{$props['query']}%",
                ));
            }
        }
        
        $tvId = isset($props['tvId']) && is_numeric($props['tvId']) ? intval($props['tvId']) : '';
        // for tagcloud
        if (strtolower($props['sort']) === 'count') {
            $c->select(array(
                'smarttagTags.id',
                'smarttagTags.tag',
                'count' => "(SELECT COUNT(*) FROM {$this->modx->getTableName('smarttagTagresources')} AS Tagresources " .
                'WHERE (Tagresources.tag_id = smarttagTags.id ' .
                (!empty($tvId) ? 'AND Tagresources.tmplvar_id=' . $tvId : '') .
                '))'
            ));
            $c->sortby('count', 'desc');
            $c->sortby('tag', 'asc');
        }
        
        if (!empty($tvId)) {
            $c->leftJoin('smarttagTagresources', 'Tagresources', 'Tagresources.tag_id = smarttagTags.id');
            $c->where(array(
                'Tagresources.tmplvar_id' => $props['tvId']
            ));
        }
        return $c;
    }

    /**
     * Get the data of the query
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $props = $this->getProperties();
        if ($props['sort'] === 'count') {
            $data['total'] = $this->modx->smarttag->getQueryCount($this->classKey, $c);
        } else {
            // this fails for query with function
            $data['total'] = $this->modx->getCount($this->classKey, $c);
        }
        $c = $this->prepareQueryAfterCount($c);

        if (strtolower($props['sort']) !== 'count') {
            $sortClassKey = $this->getSortClassKey();
            $sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', array($this->getProperty('sort')));
            if (empty($sortKey)) {
                $sortKey = $this->getProperty('sort');
            }
            $c->sortby($sortKey, $this->getProperty('dir'));
        }
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $data['results'] = $this->modx->getCollection($this->classKey, $c);
        return $data;
    }

    /**
     * Return arrays of objects (with count) converted to JSON.
     *
     * The JSON result includes two main elements, total and results. This format is used for list
     * results.
     *
     * @access public
     * @param array $array An array of data objects.
     * @param mixed $count The total number of objects. Used for pagination.
     * @return string The JSON output.
     */
    public function outputArray(array $array, $count = false) {
        if ($count === false) {
            $count = count($array);
        }
        return '{"success":true,"total":' . $count . ',"results":' . $this->modx->toJSON($array) . '}';
    }

}

return 'TagsGetListProcessor';

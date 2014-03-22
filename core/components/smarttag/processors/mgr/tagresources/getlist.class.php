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
class TagResourcesGetListProcessor extends modObjectGetListProcessor {

    public $classKey = 'smarttagTagresources';
    public $languageTopics = array('smarttag:default');
    public $defaultSortField = 'resource_id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'smarttag.TagResourcesGetList';

    /**
     * Can be used to adjust the query prior to the COUNT statement
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $props = $this->getProperties();
        if (!empty($props['tagId'])) {
            $c->where(array(
                'tag_id' => $props['tagId'],
            ));
        }
        $c->innerJoin('smarttagResource', 'smarttagResource', 'smarttagResource.id = smarttagTagresources.resource_id');
        $c->select(array(
            'smarttagTagresources.*',
            'smarttagResource.pagetitle'
        ));
        
        return $c;
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
        $count = count($array);
        return '{"success":true,"total":' . $count . ',"results":' . $this->modx->toJSON($array) . '}';
    }

}

return 'TagResourcesGetListProcessor';

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

// Apache's timeout: 600 secs
if (function_exists('ini_get') && !ini_get('safe_mode')) {
    if (function_exists('set_time_limit')) {
        set_time_limit(600);
    }
    if (function_exists('ini_set')) {
        if (ini_get('max_execution_time') !== 600) {
            ini_set('max_execution_time', 600);
        }
    }
}

class TVsSyncProcessor extends modObjectGetListProcessor {
    public $classKey = 'modTemplateVarResource';
    public $defaultSortField = 'tmplvarid';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TVsSync';
    private $_count = 0;
    
    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $this->setProperty('limit', 0);
        return parent::initialize();
    }

    /**
     * Can be used to adjust the query prior to the COUNT statement
     *
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $props = $this->getProperties();
        $c->where(array(
            'tmplvarid' => $props['tvId']
        ));
        return $c;
    }

    /**
     * Allow stoppage of process before the query
     * @return boolean
     */
    public function beforeQuery() {
        return true;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $values = array_map('trim', @explode('||', $objectArray['value']));
        if (!empty($values)) {
            foreach ($values as $value) {
                if (empty($value)) {
                    continue;
                }
                $tag = $this->modx->getObject('smarttagTags', array(
                    'tag' => $value
                ));
                if (!$tag) {
                    $tag = $this->modx->newObject('smarttagTags');
                    $tag->set('tag', $value);
                    $tag->save();
                }
                $params = array(
                    'tag_id' => $tag->getPrimaryKey(),
                    'tmplvar_id' => $objectArray['tmplvarid'],
                    'resource_id' => $objectArray['contentid'],
                );
                $smarttagTagresource = $this->modx->getObject('smarttagTagresources', $params);
                if (!$smarttagTagresource) {
                    $smarttagTagresource = $this->modx->newObject('smarttagTagresources');
                    $smarttagTagresource->fromArray($params, NULL, TRUE, TRUE);
                    $smarttagTagresource->save();
                    $this->_count++;
                }
            }
        }
        
        
        return $objectArray;
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
    public function outputArray(array $array,$count = false) {
        return '{"success":true,"total":"' . $this->_count . '","results":""}';
    }

}

return 'TVsSyncProcessor';
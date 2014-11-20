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
    public $defaultSortField = 'contentid';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TVsSync';
    private $_count = 0;
    
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
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        
        $value = $objectArray['value'];
        $value = str_replace(',', '||', $value);
        $values = array_map('trim', @explode('||', $value));
        if (!empty($values)) {
            $valuesArray = array();
            foreach ($values as $value) {
                if (empty($value)) {
                    continue;
                }
                $valuesArray[] = $value;
                $tag = $this->modx->getObject('smarttagTags', array(
                    'tag:LIKE' => $value
                ));
                if (!$tag) {
                    $tag = $this->modx->newObject('smarttagTags');
                    $tag->set('tag', $value);
                    if ($tag->save() === false) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, __LINE__ . ': Error on saving new tag data: ' . $value);
                        continue;
                    }
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
                    if ($smarttagTagresource->save() === false) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, __LINE__ . ': Error on saving new tag resource data: ' . print_r($params, 1));
                        continue;
                    }
                    $this->_count++;
                }
            }
            $valuesArray = array_unique($valuesArray);
            if (!empty($valuesArray)) {
                $newValue = @implode('||', $valuesArray);
                if ($objectArray['value'] !== $newValue) {
                    $object->set('value', $newValue);
                    if ($object->save()) {
                        $objectArray['value'] = $newValue;
                    }
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
    public function outputArray(array $array, $count = false) {
        if ($count === false) {
            $count = count($array);
        }
        $output = array(
            'success' => true,
            'total' => $count,
            'totalSynced' => $this->_count,
            'nextStart' => intval($this->getProperty('start')) + intval($this->getProperty('limit')),
        );
        return json_encode($output);
    }

}

return 'TVsSyncProcessor';
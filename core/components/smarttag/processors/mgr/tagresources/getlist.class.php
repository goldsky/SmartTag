<?php

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

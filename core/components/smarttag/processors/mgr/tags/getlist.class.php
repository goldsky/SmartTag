<?php

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
        if (isset($props['valuesqry']) 
                && empty($props['valuesqry'])
                && isset($props['query'])
                && !empty($props['query'])) {
            $c->where(array(
                'tag:LIKE' => "%{$props['query']}%"
            ));
        }
        
        return $c;
    }

}

return 'TagsGetListProcessor';
<?php

require_once MODX_CORE_PATH . 'model/modx/processors/element/tv/getlist.class.php';

class GetTVsListProcessor extends modTemplateVarGetListProcessor {

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modCategory','Category');
        $c->where(array(
            'type:IN' => array('autotag', 'tag', 'smarttag')
        ));
        return $c;
    }
}

return 'GetTVsListProcessor';

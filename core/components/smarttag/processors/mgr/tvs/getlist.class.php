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

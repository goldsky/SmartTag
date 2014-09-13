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
class TagsFilterProcessor extends modProcessor {
    
    public function process() {
        $tag = $this->getProperty('tag');
        // replace space with temporary delimiter to avoid alias cleaner
        $time = time();
        $filteredTag = preg_replace('/\s+/u', $time, $tag);
        if (empty($this->modx->resource)) $this->modx->getService('resource','modResource');
        // lend resource's method
        $filteredTag = $this->modx->resource->cleanAlias($filteredTag);
        $filteredTag = preg_replace("/$time+/u", ' ', $filteredTag);
        return $this->success('', array('tag' => $tag, 'filtered' => $filteredTag));
    }
}
return 'TagsFilterProcessor';
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
require_once dirname(__FILE__) . '/model/smarttag.class.php';

abstract class SmartTagManagerController extends modExtraManagerController {

    /** @var SmartTag $smarttag */
    public $smarttag;

    public function initialize() {
        $this->smarttag = new SmartTag($this->modx);

        $version = str_replace(' ', '', $this->smarttag->config['version']);

        $isCssCompressed = $this->modx->getOption('compress_css');
        $withVersion = $isCssCompressed ? '' : '?v=' . $version;
        
        $this->addCss($this->smarttag->config['cssUrl'] . 'mgr.css' . $withVersion);
        
        $isJsCompressed = $this->modx->getOption('compress_js');
        $withVersion = $isJsCompressed ? '' : '?v=' . $version;

        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/smarttag.js' . $withVersion);
        $limit = $this->modx->getOption('smarttag.limit', '', 50);
        $configs = array_merge($this->smarttag->config, array('limit' => $limit));
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            SmartTag.config = ' . $this->modx->toJSON($configs) . ';
            MODx.perm["smarttag.tools_page"] = ' . ($this->modx->hasPermission('smarttag.tools_page') ? 1 : 0) . ';
        });
        </script>');

        return parent::initialize();
    }

    public function getLanguageTopics() {
        return array('smarttag:default', 'smarttag:cmp');
    }

    public function checkPermissions() {
        return true;
    }

}

class IndexManagerController extends SmartTagManagerController {

    public static function getDefaultController() {
        return 'cmp/home';
    }

}

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
 * @subpackage controller
 */
class SmartTagCmpHomeManagerController extends SmartTagManagerController {

    public function process(array $scriptProperties = array()) {
        
    }

    public function getPageTitle() {
        return $this->modx->lexicon('smarttag');
    }

    public function loadCustomCssJs() {
        $version = str_replace(' ', '', $this->smarttag->config['version']);

        $isCssCompressed = $this->modx->getOption('compress_css');
        $withVersion = $isCssCompressed ? '' : '?v=' . $version;

        $this->addCSS($this->smarttag->config['jsUrl'] . 'ux/fileuploadfield/css/fileuploadfield.css' . $withVersion);

        $isJsCompressed = $this->modx->getOption('compress_js');
        $withVersion = $isJsCompressed ? '' : '?v=' . $version;

        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/window.tag.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/grid.tagresources.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/combo.tvs.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/page.tools.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'ux/fileuploadfield/FileUploadField.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/panel.csvimporter.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/panel.converter.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/page.tagcloud.js' . $withVersion);
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/panel.home.js' . $withVersion);
        $this->addLastJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/sections/index.js' . $withVersion);
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            MODx.load({xtype: "smarttag-page-home"});
        });
        </script>');
    }

    public function getTemplateFile() {
        return $this->smarttag->config['templatesPath'] . 'home.tpl';
    }

}

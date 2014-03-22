<?php

class SmartTagCmpHomeManagerController extends SmartTagManagerController {

    public function process(array $scriptProperties = array()) {
        
    }

    public function getPageTitle() {
        return $this->modx->lexicon('smarttag');
    }

    public function loadCustomCssJs() {
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/window.tag.js');
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/grid.tagresources.js');
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/combo.tvs.js');
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/page.converter.js');
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/page.tagcloud.js');
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/widgets/panel.home.js');
        $this->addLastJavascript($this->smarttag->config['jsUrl'] . 'mgr/cmp/sections/index.js');
    }

    public function getTemplateFile() {
        return $this->smarttag->config['templatesPath'] . 'home.tpl';
    }

}

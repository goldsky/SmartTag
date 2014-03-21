<?php

require_once dirname(__FILE__) . '/model/smarttag.class.php';

abstract class SmartTagManagerController extends modExtraManagerController {

    /** @var SmartTag $smarttag */
    public $smarttag;

    public function initialize() {
        $this->smarttag = new SmartTag($this->modx);

        $this->addCss($this->smarttag->config['cssUrl'] . 'mgr.css');
        $this->addJavascript($this->smarttag->config['jsUrl'] . 'mgr/smarttag.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            SmartTag.config = ' . $this->modx->toJSON($this->smarttag->config) . ';
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
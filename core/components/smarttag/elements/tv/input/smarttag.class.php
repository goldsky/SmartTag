<?php

if (!class_exists('SmartTagInputRender')) {

    class SmartTagInputRender extends modTemplateVarInputRender {

        public function __construct(modTemplateVar $tv, array $config = array()) {
            parent::__construct($tv, $config);
            $assetsUrl = $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/smarttag/';
            $this->modx->controller->addJavascript($assetsUrl . 'js/mgr/smarttag.js');
            $connectorUrl = $assetsUrl . 'conn/mgr.php';
            $this->modx->controller->addHTML('
        <script type="text/javascript">
        // <![CDATA[
        SmartTag.config.connectorUrl = "' . $connectorUrl . '";
        // ]]>
        </script>');
        }

        public function getTemplate() {
            return $this->modx->getOption('core_path') . 'components/smarttag/tv/input/tpl/smarttag.tpl';
        }

        /**
         * @param string $value
         * @param array $params
         * @return void|mixed
         */
        public function process($value, array $params = array()) {
            return $value;
        }

        /**
         * Return the input options parsed for the TV
         * @return mixed
         */
        public function getInputOptions() {
            return '';
        }

    }

}
return 'SmartTagInputRender';

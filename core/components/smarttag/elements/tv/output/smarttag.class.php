<?php

if (!class_exists('SmartTagOutputRender')) {

    class SmartTagOutputRender extends modTemplateVarOutputRender {

        public function process($value, array $params = array()) {
            return $value;
        }

    }

}
return 'SmartTagOutputRender';
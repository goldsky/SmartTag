<?php

if (!class_exists('SmartTagOutputRender')) {

    class SmartTagOutputRender extends modTemplateVarOutputRender {

        public function process($value, array $params = array()) {
            return str_replace('||', ',', $value);
        }

    }

}
return 'SmartTagOutputRender';
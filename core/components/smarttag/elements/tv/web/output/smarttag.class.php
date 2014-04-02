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
 * @subpackage tv
 */
if (!class_exists('SmartTagOutputRender')) {

    class SmartTagOutputRender extends modTemplateVarOutputRender {

        public function process($value, array $params = array()) {
            $values = $this->tv->parseInput($value, "||", 'array');
            $delimiter = isset($params['delimiter']) && !empty($params['delimiter']) ? $params['delimiter'] : ', ';
            if ($delimiter == "\\n") {
                $delimiter = "\n";
            }

            $smartTag = $this->modx->getService('smarttag', 'SmartTag', MODX_CORE_PATH . 'components/smarttag/model/');

            if (!($smartTag instanceof SmartTag)) {
                return '';
            }

            $oArray = array();
            if (!empty($params['href'])) {
                foreach ($values as $value) {
                    $attributes = '';
                    /* setup the link attributes */
                    $attr = array(
                        'href' => $params['href'],
                        'title' => !empty($params['title']) ? htmlspecialchars($params['title']) : $value,
                        'class' => !empty($params['class']) ? $params['class'] : null,
                        'style' => !empty($params['style']) ? $params['style'] : null,
                        'target' => !empty($params['target']) ? $params['target'] : null,
                    );
                    foreach ($attr as $k => $v) {
                        $attributes .= ($v ? ' ' . $k . '="' . $v . '"' : '');
                    }
                    if (!empty($params['attrib'])) {
                        $attributes .= ' ' . $params['attrib']; /* add extra */
                    }

                    /* Output the link */
                    $output = '<a' . rtrim($attributes) . '>' . (!empty($params['text']) ? htmlspecialchars($params['text']) : $value) . '</a>';
                    $phs = array(
                        'value' => $value,
                    );
                    $output = $smartTag->parseTplCode($output, $phs);
                    $output = $smartTag->processElementTags($output);
                    $oArray[] = $output;
                }
                $values = $oArray;
            }
            
            return @implode($delimiter, $values);
        }

    }

}
return 'SmartTagOutputRender';

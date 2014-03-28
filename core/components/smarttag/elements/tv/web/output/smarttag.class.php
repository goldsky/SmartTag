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
            $delimiter = isset($params['delimiter']) ? $params['delimiter'] : ',';
            if ($delimiter == "\\n") {
                $delimiter = "\n";
            }

            $oArray = array();
            if (!empty($params['href'])) {
                for ($i = 0; $i < count($value); $i++) {
                    $attributes = '';
                    /* setup the link attributes */
                    $attr = array(
                        'href' => $params['href'],
                        'title' => !empty($params['title']) ? htmlspecialchars($params['title']) : $name,
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
                    $oArray[] = '<a' . rtrim($attributes) . '>' . (!empty($params['text']) ? htmlspecialchars($params['text']) : $name) . '</a>';
                }
                $values = $oArray;
            }
            $values = @implode($delimiter, $values);
            return $value;
        }

    }

}
return 'SmartTagOutputRender';

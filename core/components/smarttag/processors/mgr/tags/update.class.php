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
class TagsUpdateProcessor extends modObjectUpdateProcessor {

    public $classKey = 'smarttagTags';
    public $languageTopics = array('smarttag:cmp');
    public $objectType = 'smarttag.TagsUpdate';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $tag = $this->getProperty('tag');
        if (empty($tag)) {
            $this->addFieldError('tag', $this->modx->lexicon('smarttag.tag_err_ns'));
            return FALSE;
        }
        return parent::initialize();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function beforeSet() {
        $smarttagTagresources = $this->object->getMany('Tagresources');
        if ($smarttagTagresources) {
            $oldTag = $this->object->get('tag');
            $oldTag = trim($oldTag);

            foreach ($smarttagTagresources as $smarttagTagresource) {
                $resource = $this->modx->getObject('modResource', $smarttagTagresource->get('resource_id'));
                if (!$resource) {
                    continue;
                }
                $tvValue = $resource->getTVValue($smarttagTagresource->get('tmplvar_id'));
                if (!$tvValue) {
                    continue;
                }
                
                // getting values from parsed 'smarttag' output
                $tvValue = str_replace(',', '||', $tvValue);
                // getting values from 'default' output
                $tvValues = array_map('trim', @explode('||', $tvValue));
                $tvValues = array_unique($tvValues);
                $key = array_search($oldTag, $tvValues);
                if (is_numeric($key)) {
                    $tvValues[$key] = $this->getProperty('tag');
                    $tvValue = @implode('||', $tvValues);
                    $resource->setTVValue($smarttagTagresource->get('tmplvar_id'), $tvValue);
                }
            }
        }
        return parent::beforeSet();
    }

}

return 'TagsUpdateProcessor';
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
    public $merged = null;

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
        $exists = $this->modx->getObject($this->classKey, array('tag:LIKE' => $tag));
        if ($exists) {
            $this->merged = $this->modx->getObject($this->classKey, $this->getProperty('id'));
            $this->setProperty('id', $exists->get('id'));
        }
        
        return parent::initialize();
    }

    /**
     * Override in your derivative class to do functionality before save() is run
     * @return boolean
     */
    public function beforeSet() {
        if ($this->merged) {
            $smarttagTagresources = $this->merged->getMany('Tagresources');
            if ($smarttagTagresources) {
                $params = array();
                foreach ($smarttagTagresources as $smarttagTagresource) {
                    $params[] = array(
                        'tag_id' => $this->object->get('id'),
                        'tmplvar_id' => $smarttagTagresource->get('tmplvar_id'),
                        'resource_id' => $smarttagTagresource->get('resource_id'),
                    );
                }
                $this->modx->removeCollection('smarttagTagresources', array(
                    'tag_id' => $this->merged->get('id'),
                ));
                foreach ($params as $param) {
                    $smarttagTagresource = $this->modx->newObject('smarttagTagresources');
                    $smarttagTagresource->fromArray($param, '', true, true);
                    if ($smarttagTagresource->save() === false) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, __FILE__ . ' ');
                        $this->modx->log(modX::LOG_LEVEL_ERROR, __METHOD__ . ' ');
                        $this->modx->log(modX::LOG_LEVEL_ERROR, __LINE__ . ': Could not save new data ' . print_r($param, 1));
                    } else {
                        $this->updateTVResourceValue($smarttagTagresource);
                    }
                }
            }
        }
        $smarttagTagresources = $this->object->getMany('Tagresources');
        if ($smarttagTagresources) {
            foreach ($smarttagTagresources as $smarttagTagresource) {
                $this->updateTVResourceValue($smarttagTagresource);
            }
        }
        return parent::beforeSave();
    }

    public function updateTVResourceValue(smarttagTagresources $smarttagTagresource) {
        if ($this->merged) {
            $oldTag = $this->merged->get('tag');
        } else {
            $oldTag = $this->object->get('tag');
        }
        $oldTag = trim($oldTag);
        $resource = $this->modx->getObject('modResource', $smarttagTagresource->get('resource_id'));
        if (!$resource) {
            return false;
        }
        // don't use this, this is a parsed output.
        // $tvValue = $resource->getTVValue($smarttagTagresource->get('tmplvar_id'));
        // use the raw one:
        $tv = $this->modx->getObject('modTemplateVar', $smarttagTagresource->get('tmplvar_id'));
        $tvValue = $tv->getValue($smarttagTagresource->get('resource_id'));
        if (!$tvValue) {
            return false;
        }

        // @deprecated: getting values from parsed 'smarttag' output
        // $tvValue = str_replace(',', '||', $tvValue);
        // getting values from 'default' output
        $tvValues = array_map('trim', @explode('||', $tvValue));
        $tvValues = array_unique($tvValues);
        $key = array_search($oldTag, $tvValues);
        if (is_numeric($key)) {
            $tvValues[$key] = $this->getProperty('tag');
        }
        $tvValue = @implode('||', $tvValues);
        $resource->setTVValue($smarttagTagresource->get('tmplvar_id'), $tvValue);
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup() {
        $output = $this->object->toArray();
        if ($this->merged) {
            $output['merged'] = $this->merged->toArray();
            $this->merged->remove();
        }
        unset($output['action']);
        return $this->success('', $output);
    }

}

return 'TagsUpdateProcessor';

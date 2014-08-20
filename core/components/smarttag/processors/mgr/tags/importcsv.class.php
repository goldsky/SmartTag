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
include_once MODX_CORE_PATH . 'model/modx/processors/browser/file/upload.class.php';

class vnewsSubscribersImportCsv extends modBrowserFileUploadProcessor {

    public function getLanguageTopics() {
        return array('file', 'smarttag:cmp');
    }

    public function initialize() {
        $this->modx->setOption('upload_files', 'csv');
        $this->modx->setOption('base_path', $this->modx->smarttag->config['corePath']);
        $path = 'imports/';
        $this->setProperty('path', $path);
        $this->setProperty('source', 0);
        return parent::initialize();
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->createContainer($this->getProperty('path'), '/');
        $this->source->errors = array();
        $success = $this->source->uploadObjectsToContainer($this->getProperty('path'), $_FILES);

        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->modx->error->addField($k, $msg);
            }
            return $this->failure($msg);
        }

        ini_set("auto_detect_line_endings", 1);
        $filename = $_FILES['file']['name'];
        $props = $this->getProperties();
        $filepath = $this->modx->getOption('base_path') . $props['path'] . $filename;
        if (($handle = fopen($filepath, "r")) !== FALSE) {
            $contents = fread($handle, filesize($filepath));
            if (!empty($contents)) {
                $contentsX = array_map('trim', explode(',', $contents));
                foreach ($contentsX as $tag) {
                    if (empty($tag)) {
                        continue;
                    }
                    $this->modx->smarttag->addTag($tag);
                }
            }
            fclose($handle);
        } else {
            return $this->failure($this->modx->lexicon('smarttag.read_file_err'));
        }
        $this->source->removeContainer($this->getProperty('path'));

        return $this->success($this->modx->lexicon('smarttag.tags_imported'));
    }

}

return 'vnewsSubscribersImportCsv';

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
 * SmartTag build script
 *
 * @package smarttag
 * @subpackage build
 */
if ($modx = & $object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modelPath = $modx->getOption('core_path') . 'components/smarttag/model/';
            $tablePrefix = $modx->getOption('smarttag.table_prefix', null, $modx->config[modX::OPT_TABLE_PREFIX] . 'smarttag_');
            $modx->addPackage('smarttag', $modelPath, $tablePrefix);
            $manager = $modx->getManager();
            if (!$manager->createObjectContainer('smarttagTagresources')) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[SmartTag] `modx_smarttag_tagresources` table was unable to be created');
                return false;
            }
            if (!$manager->createObjectContainer('smarttagTags')) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[SmartTag] `modx_smarttag_tags` table was unable to be created');
                return false;
            }
            break;
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}

return true;
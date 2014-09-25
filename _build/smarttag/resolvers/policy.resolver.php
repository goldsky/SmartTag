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
 * Auto-assign the SmartTagPolicy to the Administrator User Group
 *
 * @package smarttag
 * @subpackage build
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx = & $object->xpdo;
            $modelPath = $modx->getOption('smarttag.core_path', null, $modx->getOption('core_path') . 'components/smarttag/') . 'model/';
            $modx->addPackage('smarttag', $modelPath);

            $modx->setLogLevel(modX::LOG_LEVEL_ERROR);

            /* assign policy to template */
            $policy = $transport->xpdo->getObject('modAccessPolicy', array(
                'name' => 'SmartTag'
            ));
            if ($policy) {
                $template = $transport->xpdo->getObject('modAccessPolicyTemplate', array('name' => 'SmartTagTemplate'));
                if ($template) {
                    $policy->set('template', $template->get('id'));
                    $policy->save();
                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, '[SmartTag] Could not find SmartTagTemplate Access Policy Template!');
                }
            } else {
                $modx->log(xPDO::LOG_LEVEL_ERROR, '[SmartTag] Could not find SmartTag Access Policy!');
                break;
            }

            /* assign policy to admin group */
            $adminGroup = $modx->getObject('modUserGroup', array('name' => 'Administrator'));
            if ($policy && $adminGroup) {
                $params = array(
                    'target' => 'mgr',
                    'principal_class' => 'modUserGroup',
                    'principal' => $adminGroup->get('id'),
                    'authority' => 9999,
                    'policy' => $policy->get('id'),
                );
                $access = $modx->getObject('modAccessContext', $params);
                if (!$access) {
                    $access = $modx->newObject('modAccessContext');
                    $access->fromArray($params);
                    $access->save();
                }
            }
            $modx->setLogLevel(modX::LOG_LEVEL_INFO);
            break;
    }
}
return true;

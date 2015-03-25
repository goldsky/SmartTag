<?php

/**
 * SmartTag
 *
 * Copyright 2014-2015 by goldsky <goldsky@virtudraft.com>
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
/**
 * Default SmartTag Policy Templates
 *
 * @package smarttag
 * @subpackage build
 */
$templates = array();

/* administrator template/policy */
$templates['1']= $modx->newObject('modAccessPolicyTemplate');
$templates['1']->fromArray(array(
    'id' => 1,
    'name' => 'SmartTagTemplate',
    'description' => 'A policy template for SmartTag package.',
    'lexicon' => 'smarttag:permissions',
    'template_group' => 1,
));
$permissions = include dirname(__FILE__).'/permissions/smarttag.policy.php';
if (is_array($permissions)) {
    $templates['1']->addMany($permissions);
} else {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not load SmartTag Policy Template.'); 
}

return $templates;
<?php
$xpdo_meta_map['smarttagResource']= array (
  'package' => 'smarttag',
  'version' => '1.1',
  'extends' => 'modResource',
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'Tagresources' => 
    array (
      'class' => 'smarttagTagresources',
      'local' => 'id',
      'foreign' => 'resource_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

<?php
$xpdo_meta_map['smarttagTags']= array (
  'package' => 'smarttag',
  'version' => '1.1',
  'table' => 'tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'tag' => NULL,
  ),
  'fieldMeta' => 
  array (
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'composites' => 
  array (
    'Tagresources' => 
    array (
      'class' => 'smarttagTagresources',
      'local' => 'id',
      'foreign' => 'tag_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

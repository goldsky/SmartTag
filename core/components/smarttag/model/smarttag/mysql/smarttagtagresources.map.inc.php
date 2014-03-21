<?php
$xpdo_meta_map['smarttagTagresources']= array (
  'package' => 'smarttag',
  'version' => '1.1',
  'table' => 'tagresources',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'tag_id' => NULL,
    'tmplvar_id' => NULL,
    'resource_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'tag_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'tmplvar_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'tag_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'tmplvar_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'resource_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Tags' => 
    array (
      'class' => 'smarttagTags',
      'local' => 'tag_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'TemplateVar' => 
    array (
      'class' => 'smarttagTemplateVar',
      'local' => 'tmplvar_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Resource' => 
    array (
      'class' => 'smarttagResource',
      'local' => 'resource_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);

<?php
$xpdo_meta_map['smarttagTemplateVar']= array (
  'package' => 'smarttag',
  'version' => '1.1',
  'extends' => 'modTemplateVar',
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
      'foreign' => 'tmplvar_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

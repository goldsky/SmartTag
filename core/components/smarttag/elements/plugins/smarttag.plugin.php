<?php

$corePath = $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/smarttag/';
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath . 'elements/tv/input/');
        break;
    case 'OnTVOutputRenderList':
        $modx->event->output($corePath . 'elements/tv/output/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath . 'elements/tv/inputproperties/');
        break;
    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath . 'elements/tv/properties/');
        break;
    case 'OnDocFormSave':
        $tvs = $resource->getTemplateVars();
        if ($tvs) {
            foreach ($tvs as $tv) {
                $tvArray = $tv->toArray();
                if ($tvArray['type'] === 'smarttag') {
                    $tablePrefix = $modx->getOption('smarttag.table_prefix', null, $modx->config[modX::OPT_TABLE_PREFIX] . 'smarttag_');
                    $modx->addPackage('smarttag', $corePath . 'model/', $tablePrefix);
                    $modx->removeCollection('smarttagTagresources', array(
                        'tmplvar_id' => $tvArray['id'],
                        'resource_id' => $resource->get('id'),
                    ));
                    $values = array_map('trim', @explode('||', $tvArray['value']));
                    if (!empty($values)) {
                        $smarttagTagresources = array();
                        foreach ($values as $value) {
                            if (empty($value)) {
                                continue;
                            }
                            $tag = $modx->getObject('smarttagTags', array(
                                'tag:LIKE' => $value
                            ));
                            if (!$tag) {
                                $tag = $modx->newObject('smarttagTags');
                                $tag->set('tag', $value);
                                $tag->save();
                            }
                            $newTagged = $modx->newObject('smarttagTagresources');
                            $params = array(
                                'tag_id' => $tag->getPrimaryKey(),
                                'tmplvar_id' => $tvArray['id'],
                                'resource_id' => $resource->get('id'),
                            );
                            $newTagged->fromArray($params, NULL, TRUE, TRUE);
                            $newTagged->save();
                        }
                    }
                }
            }
        }
        break;
    default:
        break;
}

return;
<?php

if (empty($scriptProperties['ids'])) {
    return $this->failure($modx->lexicon('smarttag.tag_err_ns_resource_id'));
}

$ids = @explode(',', $scriptProperties['ids']);
foreach ($ids as $id) {
    $tagResIds = explode(':', $id);
    $smarttagTagresources = $modx->getCollection('smarttagTagresources', array(
        'tag_id' => $tagResIds[0],
        'resource_id' => $tagResIds[1],
    ));
    if ($smarttagTagresources) {
        foreach ($smarttagTagresources as $smarttagTagresource) {
            $resource = $modx->getObject('modResource', $smarttagTagresource->get('resource_id'));
            if ($resource) {
                $tvValue = $resource->getTVValue($smarttagTagresource->get('tmplvar_id'));
                if (!$tvValue) {
                    continue;
                }
                $allValues = array();
                // getting values from 'default' output
                $tvValues = @explode('||', $tvValue);
                // getting values from parsed 'smarttag' output
                foreach ($tvValues as $oriValue) {
                    $allValues = array_merge($allValues, @explode(',', $oriValue));
                }
                $tag = $modx->getObject('smarttagTags', $tagResIds[0])->get('tag');
                $key = array_search($tag, $allValues);
                unset($allValues[$key]);
                $tvValue = @implode('||', $allValues);
                $resource->setTVValue($smarttagTagresource->get('tmplvar_id'), $tvValue);
            }
            $smarttagTagresource->remove();
        }
    }
}
return $this->success();

var SmartTag = function(config) {
    config = config || {};
    SmartTag.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, extra: {}
    , connector_url: ''
});
Ext.reg('smarttag', SmartTag);

SmartTag = new SmartTag();
SmartTag.combo.TVs = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url: SmartTag.config.connectorUrl
        , baseParams: {
            action: 'mgr/tvs/getlist',
            addBlank: config.addBlank || false,
            onlySmartTag: config.onlySmartTag || false
        }
        , pageSize: 10
        , lazyRender: true
        , fields: ['id', 'name']
        , width: 190
        , name: 'tv'
        , hiddenName: 'tv'
        , displayField: 'name'
        , valueField: 'id'
    });
    SmartTag.combo.TVs.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.combo.TVs, MODx.combo.ComboBox);
Ext.reg('smarttag-combo-tvs', SmartTag.combo.TVs);
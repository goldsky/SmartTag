SmartTag.window.Tag = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url: SmartTag.config.connectorUrl,
        autoHeight: true,
        preventRender: true,
        fields: [
            {
                xtype: 'hidden',
                name: 'id',
                value: config.record &&
                        config.record.tagId ? config.record.tagId : 0
            }, {
                xtype: 'textfield',
                fieldLabel: _('smarttag.tag') + ':',
                name: 'tag',
                allowBlank:  false,
                anchor: '100%',
                value: config.record &&
                        config.record.tag ? config.record.tag : ''
            }
        ]
    });
    SmartTag.window.Tag.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.window.Tag, MODx.Window);
Ext.reg('smarttag-window-tag', SmartTag.window.Tag);
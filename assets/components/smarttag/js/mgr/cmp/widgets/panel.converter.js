SmartTag.panel.Converter = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        id: 'smarttag-panel-converter',
        baseCls: 'modx-formpanel',
        items: [
            {
                html: '<h2>' + _('smarttag.converter') + '</h2><p>' + _('smarttag.converter_desc') + '</p>',
                bodyCssClass: 'with-title panel-desc',
                border: false,
                bodyStyle: 'margin-bottom: 15px;'
            }, {
                layout: 'hbox',
                id: 'smarttag-converter-hbox',
                bodyStyle: 'padding: 10px; background-color: transparent;',
                border: false,
                items: [
                    {
                        xtype: 'smarttag-combo-tvs',
                        id: 'smarttag-combo-tvs',
                        listeners: {
                            select: {
                                fn: function(combo, record, index) {
                                    this.toggleButton(record.json.type);
                                },
                                scope: this
                            }
                        }
                    }, {
                        xtype: 'button',
                        id: 'smarttag-convert-button-to-smarttag',
                        text: _('smarttag.to_smarttag'),
                        disabled: true,
                        handler: this.convertToSmartTag
                    }, {
                        xtype: 'button',
                        id: 'smarttag-convert-button-to-autotag',
                        text: _('smarttag.to_autotag'),
                        disabled: true,
                        handler: this.convertToAutoTag
                    }, {
                        xtype: 'button',
                        id: 'smarttag-convert-button-to-tag',
                        text: _('smarttag.to_tag'),
                        disabled: true,
                        handler: this.convertToTag
                    }, {
                        xtype: 'button',
                        id: 'smarttag-convert-button-sync',
                        text: _('smarttag.sync_existing_tags'),
                        disabled: true,
                        handler: function(){
                            this.syncTags();
                        },
                        scope: this
                    }
                ]
            }
        ]
    });

    SmartTag.panel.Converter.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.panel.Converter, MODx.Panel, {
    toggleButton: function(type) {
        if (type === 'smarttag') {
            Ext.getCmp('smarttag-convert-button-to-smarttag').disable();
            Ext.getCmp('smarttag-convert-button-to-autotag').enable();
            Ext.getCmp('smarttag-convert-button-to-tag').enable();
            Ext.getCmp('smarttag-convert-button-sync').enable();
        } else {
            Ext.getCmp('smarttag-convert-button-to-smarttag').enable();
            Ext.getCmp('smarttag-convert-button-to-autotag').disable();
            Ext.getCmp('smarttag-convert-button-to-tag').disable();
            Ext.getCmp('smarttag-convert-button-sync').disable();
        }
    },
    loadMask: function() {
        if (!this.loadConverterMask){
            var domHandler = Ext.getCmp('smarttag-converter-hbox').body.dom;
            this.loadConverterMask = new Ext.LoadMask(domHandler, {
                msg: _('smarttag.please_wait')
            });
        }
        this.loadConverterMask.show();
    },
    hideMask: function() {
        if (this.loadConverterMask) {
            this.loadConverterMask.hide();
        }
    },
    convertToSmartTag: function(btn, e) {
        var comboBox = Ext.getCmp('smarttag-combo-tvs');
        var _this = Ext.getCmp('smarttag-panel-converter');
        _this.loadMask();
        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tvs/updatetosmarttag',
                id: comboBox.getValue(),
                type: 'smarttag'
            },
            listeners: {
                'success': {
                    fn: function() {
                        Ext.getCmp('smarttag-combo-tvs').getStore().reload();
                        _this.toggleButton('smarttag');
                        _this.hideMask();
                    }
                }
            }
        });
    },
    convertToAutoTag: function(btn, e) {
        var comboBox = Ext.getCmp('smarttag-combo-tvs');
        var _this = Ext.getCmp('smarttag-panel-converter');
        _this.loadMask();
        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tvs/updatefromsmarttag',
                id: comboBox.getValue(),
                type: 'autotag'
            },
            listeners: {
                'success': {
                    fn: function() {
                        Ext.getCmp('smarttag-combo-tvs').getStore().reload();
                        _this.toggleButton('');
                        _this.hideMask();
                    }
                }
            }
        });
    },
    convertToTag: function(btn, e) {
        var comboBox = Ext.getCmp('smarttag-combo-tvs');
        var _this = Ext.getCmp('smarttag-panel-converter');
        _this.loadMask();
        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tvs/updatefromsmarttag',
                id: comboBox.getValue(),
                type: 'tag'
            },
            listeners: {
                'success': {
                    fn: function() {
                        Ext.getCmp('smarttag-combo-tvs').getStore().reload();
                        _this.toggleButton('');
                        _this.hideMask();
                    }
                }
            }
        });
    },
    syncTags: function(limit, start) {
        limit = limit ? limit : 500;
        start = start ? start : 0;
        console.info('limit, start', limit, start);
        
        var comboBox = Ext.getCmp('smarttag-combo-tvs');
        var _this = Ext.getCmp('smarttag-panel-converter');
        _this.loadMask();
        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tvs/sync',
                tvId: comboBox.getValue(),
                limit: limit,
                start: start
            },
            listeners: {
                'success': {
                    fn: function(response) {
                        Ext.getCmp('smarttag-combo-tvs').getStore().reload();
                        
                        if (response.success) {
                            var total = response.total - 0; // typecasting
                            if (total >= response.nextStart) {
                                // recursive loop
                                _this.syncTags(limit, response.nextStart);
                            } else {
                                _this.hideMask();
                            }
                        }
                    }
                }
            }
        });
    }
});
Ext.reg('smarttag-panel-converter', SmartTag.panel.Converter);
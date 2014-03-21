SmartTag.panel.Converter = function(config) {
    config = config || {};

    Ext.apply(config, {
        id: 'smarttag-panel-converter',
        baseCls: 'modx-formpanel',
        bodyStyle: 'min-height: 500px; overflow-y: scroll;',
        preventRender: true,
        defaults: {
        },
        items: [
            {
                html: '<h2>' + _('smarttag.converter') + '</h2><p>' + _('smarttag.converter_desc') + '</p>',
                bodyCssClass: 'with-title panel-desc',
                border: false,
                bodyStyle: 'margin-bottom: 15px;'
            }, {
                xtype: 'buttongroup',
                id: 'smarttag-converter-buttongroup',
                title: _('action'),
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
                        text: 'To SmartTag',
                        disabled: true,
                        handler: this.convertToSmartTag
                    }, {
                        xtype: 'button',
                        id: 'smarttag-convert-button-to-autotag',
                        text: 'To AutoTag',
                        disabled: true,
                        handler: this.convertToAutoTag
                    }, {
                        xtype: 'button',
                        id: 'smarttag-convert-button-to-tag',
                        text: 'To Tag',
                        disabled: true,
                        handler: this.convertToTag
                    }
                ]
            }
        ],
        listeners: {
            beforerender: {
                fn: function(panel) {
                    var homeCenter = Ext.getCmp('smarttag-panel-home-center');
                    panel.height = homeCenter.lastSize.height;
                },
                scope: this
            }
        }
    });

    SmartTag.panel.Converter.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.panel.Converter, MODx.Panel, {
    toggleButton: function(type) {
        if (type === 'smarttag') {
            Ext.getCmp('smarttag-convert-button-to-smarttag').disable();
            Ext.getCmp('smarttag-convert-button-to-autotag').enable();
            Ext.getCmp('smarttag-convert-button-to-tag').enable();
        } else {
            Ext.getCmp('smarttag-convert-button-to-smarttag').enable();
            Ext.getCmp('smarttag-convert-button-to-autotag').disable();
            Ext.getCmp('smarttag-convert-button-to-tag').disable();
        }
    },
    loadMask: function() {
        if (!this.loadConverterMask){
            var domHandler = Ext.getCmp('smarttag-converter-buttongroup').body.dom;
            this.loadConverterMask = new Ext.LoadMask(domHandler, {
                msg: _('smarttag.please_wait')
            });
        }
        this.loadConverterMask.show();
    },
    hideMask: function() {
        this.loadConverterMask.hide();
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
    }
});
Ext.reg('smarttag-panel-converter', SmartTag.panel.Converter);
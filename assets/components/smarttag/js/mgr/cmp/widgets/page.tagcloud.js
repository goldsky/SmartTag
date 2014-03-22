SmartTag.page.TagCloud = function(config) {
    config = config || {};

    Ext.apply(config, {
        id: 'smarttag-page-tagcloud',
        baseCls: 'modx-formpanel',
        bodyStyle: 'min-height: 500px; overflow-y: scroll;',
        preventRender: true,
        defaults: {
        },
        items: [
            {
                xtype: 'modx-tabs',
                id: 'smarttag-panel-tagcloud-tabs',
                defaults: {
                    border: false,
                    autoHeight: true
                },
                border: true,
                items: [
                    {
                        title: _('smarttag.tags'),
                        defaults: {
                            autoHeight: true
                        },
                        items: [
                            {
                                html: '<p>' + _('smarttag.tagcloud_desc') + '</p>',
                                bodyCssClass: 'panel-desc',
                                border: false
                            }, {
                                id: 'smarttag-panel-tagcloud',
                                border: false,
                                bodyStyle: 'padding: 5px; background-color: transparent; min-height: 300px;',
                                defaults: {
                                    margins: '0 5 0 0',
                                    pressed: false,
                                    toggleGroup: 'btns',
                                    allowDepress: false
                                },
                                items: [],
                                listeners: {
                                    afterrender: {
                                        fn: function(cmp) {
                                            this.loadCloud();
                                        },
                                        scope: this
                                    }
                                }
                            }
                        ]
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

    SmartTag.page.TagCloud.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.page.TagCloud, MODx.Panel, {
    loadCloud: function() {
        var _this = this;
        var cmp = Ext.getCmp('smarttag-panel-tagcloud');
        cmp.removeAll();
        _this.loadMask();
        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tags/getlist',
                sort: 'count'
            },
            listeners: {
                'success': {
                    fn: function(response) {
                        if (response.success && response.total > 0) {
                            Ext.each(response.results, function(item, idx) {
                                cmp.add({
                                    xtype: 'button',
                                    id: 'smarttag-tagcloud-btn-' + item.id,
                                    text: item.tag + ' | ' + item.count,
                                    cls: 'smarttag-tag-btn',
                                    handler: function(btn, e) {
                                        _this.openTagTab(item);
                                    }
                                });
                            });
                            _this.doLayout();
                            cmp.doLayout();
                            _this.hideMask();
                        }
                    }
                }
            }
        });
    },
    loadMask: function() {
        if (!this.loadCloudMask){
            var domHandler = Ext.getCmp('smarttag-panel-tagcloud').body.dom;
            this.loadCloudMask = new Ext.LoadMask(domHandler, {
                msg: _('smarttag.please_wait')
            });
        }
        this.loadCloudMask.show();
    },
    hideMask: function() {
        this.loadCloudMask.hide();
    },
    openTagTab: function(item) {
        var tabsWrapper = Ext.getCmp('smarttag-panel-tagcloud-tabs');
        var check = Ext.getCmp('smarttag-panel-tagcloud-tab-' + item.id);
        if (!check) {
            tabsWrapper.add({
                title: item.tag,
                id: 'smarttag-panel-tagcloud-tab-' + item.id,
                defaults: {
                    autoHeight: true
                },
                closable: true,
                bodyStyle: 'padding: 15px;',
                items: [
                    {
                        xtype: 'smarttag-grid-tagresources',
                        record: {
                            tagId: item.id
                        }
                    }
                ]
            });
        }
        tabsWrapper.setActiveTab('smarttag-panel-tagcloud-tab-' + item.id);
    }
});
Ext.reg('smarttag-page-tagcloud', SmartTag.page.TagCloud);
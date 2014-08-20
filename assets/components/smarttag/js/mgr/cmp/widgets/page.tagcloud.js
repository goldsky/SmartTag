SmartTag.page.TagCloud = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        id: 'smarttag-page-tagcloud',
        baseCls: 'modx-formpanel',
        bodyStyle: 'min-height: 500px; overflow-y: scroll;',
        preventRender: true,
        start: 0,
        loadMore: false,
        countBtns: 0,
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
                                border: false,
                                tbar: [
                                    {
                                        html: _('search') + ' : ',
                                        border: false,
                                        xtype: 'panel',
                                        padding: 10
                                    }, {
                                        xtype: 'textfield',
                                        name: 'smarttag-tagcloud-search',
                                        id: 'smarttag-tagcloud-search',
                                        fieldLabel: _('search'),
                                        width: 100,
                                        listeners: {
                                            'render': {
                                                fn: function(cmp) {
                                                    var _this = this;
                                                    new Ext.KeyMap(cmp.getEl(), {
                                                        key: Ext.EventObject.ENTER,
                                                        fn: function() {
                                                            _this.loadCloud();
                                                            this.blur();
                                                            return true;
                                                        },
                                                        scope: cmp
                                                    });
                                                },
                                                scope: this
                                            }
                                        }
                                    }, {
                                        html: _('smarttag.limit') + ' : ',
                                        border: false,
                                        xtype: 'panel',
                                        padding: 10
                                    }, {
                                        xtype: 'numberfield',
                                        fieldLabel: _('smarttag.limit'),
                                        name: 'smarttag-tagcloud-limit',
                                        id: 'smarttag-tagcloud-limit',
                                        width: 100,
                                        value: SmartTag.config.limit
                                    }, {
                                        html: _('name') + ' : ',
                                        border: false,
                                        xtype: 'panel',
                                        padding: 10
                                    }, {
                                        name: 'smarttag-tagcloud-tvid',
                                        id: 'smarttag-tagcloud-tvid',
                                        fieldLabel: _('name'),
                                        xtype: 'smarttag-combo-tvs',
                                        onlySmartTag: true,
                                        addBlank: true,
                                        listeners: {
                                            select: function(comp, record, index) {
                                                if (comp.getValue() == "" || comp.getValue() == "&nbsp;")
                                                    comp.setValue(null);
                                            }
                                        }
                                    }, {
                                        xtype: 'button',
                                        text: _('smarttag.go!'),
                                        handler: function() {
                                            this.start = 0;
                                            this.loadMore = false;
                                            this.countBtns = 0;
                                            Ext.getCmp('smarttag-tagcloud-total-count').update('0/0');
                                            this.loadCloud();
                                        },
                                        scope: this
                                    }, '->', {
                                        xtype: 'panel',
                                        id: 'smarttag-tagcloud-total-count',
                                        html: '0/0',
                                        border: false,
                                        bodyStyle: 'font-weight: bold; font-size: large;'
                                    }
                                ]
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
                            }, {
                                layout: 'hbox',
                                bodyCssClass: 'panel-desc',
                                border: false,
                                layoutConfig: {
                                    pack: 'center',
                                    align: 'middle'
                                },
                                items: [
                                    {
                                        xtype: 'button',
                                        id: 'smarttag-panel-tagcloud-loadmore-button',
                                        text: 'Load More',
                                        disabled: true,
                                        handler: function() {
                                            var limit = Ext.getCmp('smarttag-tagcloud-limit').getValue();
                                            limit = limit ? limit - 0 : 50;
                                            this.start = this.start + limit;
                                            this.loadMore = true;
                                            this.loadCloud();
                                        },
                                        scope: this
                                    }
                                ]
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
        var limit = Ext.getCmp('smarttag-tagcloud-limit').getValue();
        limit = limit ? limit - 0 : 50;
        var search = Ext.getCmp('smarttag-tagcloud-search').getValue();
        search = search || '';
        var tvId = Ext.getCmp('smarttag-tagcloud-tvid').getValue();

        var _this = this;
        var cmp = Ext.getCmp('smarttag-panel-tagcloud');
        if (!this.loadMore) {
            cmp.removeAll();
        }
        _this.loadMask();

        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tags/getlist',
                sort: 'count',
                dir: 'desc',
                limit: limit,
                start: this.start,
                query: search,
                //valuesqry: '',
                tvId: tvId
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
                                        _this.openTagTab(item, tvId);
                                    }
                                });
                                _this.countBtns++;
                            });
                            var loadmoreButton = Ext.getCmp('smarttag-panel-tagcloud-loadmore-button');
                            if (loadmoreButton) {
                                if (_this.countBtns >= response.total) {
                                    loadmoreButton.setDisabled(true);
                                } else {
                                    loadmoreButton.setDisabled(false);
                                }
                            }
                            var totalCount = Ext.getCmp('smarttag-tagcloud-total-count');
                            if (totalCount) {
                                totalCount.update(_this.countBtns + '/' + response.total);
                            }
                            _this.doLayout();
                            cmp.doLayout();
                        }
                        _this.hideMask();
                    }
                }
            }
        });
    },
    loadMask: function() {
        if (!this.loadCloudMask) {
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
    openTagTab: function(item, tvId) {
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
                        id: 'smarttag-grid-tagresources-' + item.id,
                        record: {
                            tag: item.tag,
                            tagId: item.id,
                            tvId: tvId
                        }
                    }
                ]
            });
        }
        tabsWrapper.setActiveTab('smarttag-panel-tagcloud-tab-' + item.id);
    }
});
Ext.reg('smarttag-page-tagcloud', SmartTag.page.TagCloud);
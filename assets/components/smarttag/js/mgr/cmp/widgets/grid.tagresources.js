SmartTag.grid.TagResources = function(config) {
    config = config || {};

    var checkBoxSelMod = new Ext.grid.CheckboxSelectionModel();
    
    Ext.applyIf(config, {
        url: SmartTag.config.connectorUrl,
        baseParams: {
            action: 'mgr/tagresources/getlist',
            tagId: config.record.tagId,
            tvId: config.record.tvId
        },
        fields: ['id', 'tag_id', 'resource_id', 'pagetitle', 'action_edit', 'resource_id', 'preview_url'],
        paging: true,
        remoteSort: true,
        autoExpandColumn: 'pagetitle',
        sm: checkBoxSelMod,
        columns: [
            checkBoxSelMod,
            {
                header: _('id'),
                dataIndex: 'id',
                sortable: true,
                fixed: true,
                hidden: true,
                width: 40
            }, {
                header: _('smarttag.tag_id'),
                dataIndex: 'tag_id',
                sortable: true,
                fixed: true,
                hidden: true,
                width: 40
            }, {
                header: _('id'),
                dataIndex: 'resource_id',
                sortable: true,
                fixed: true,
                width: 80
            }, {
                header: _('pagetitle'),
                dataIndex: 'pagetitle',
                sortable: true,
                renderer: {
                    fn: this._renderPageTitle,
                    scope: this
                }
            }
        ],
        tbar: [
            {
                text: _('smarttag.delete_tag'),
                handler: function() {
                    return this.deleteTag(config.record.tagId);
                },
                scope: this
            }, {
                text: _('smarttag.rename_tag'),
                handler: function() {
                    return this.renameTag(config.record.tagId);
                },
                scope: this
            }, {
                text: _('smarttag.remove_tagresource'),
                handler: function() {
                    return this.removeTagresources();
                },
                scope: this
            }, {
                text: _('smarttag.sync_this_tag'),
                handler: function() {
                    return this.sync(config.record.tagId);
                },
                scope: this
            }
        ]
    });

    SmartTag.grid.TagResources.superclass.constructor.call(this, config);
    this._makeTemplates();
};
Ext.extend(SmartTag.grid.TagResources, MODx.grid.Grid, {
    getMenu: function() {
        var menu = [
            {
                text: _('edit'),
                handler: function(btn, e) {
                    MODx.loadPage(MODx.action['resource/update'], 'id=' + this.menu.record.resource_id);
                }
            }, {
                text: _('view'),
                handler: function(btn, e) {
                    window.open(this.menu.record.preview_url);
                }
            }
        ];
        
        return menu;
    },
    deleteTag: function(tagId) {
        MODx.msg.confirm({
            title: _('smarttag.remove'),
            text: _('smarttag.remove_confirm'),
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tags/remove',
                id: tagId
            },
            listeners: {
                'success': {
                    fn: function() {
                        var tabsWrapper = Ext.getCmp('smarttag-panel-tagcloud-tabs');
                        var tab = Ext.getCmp('smarttag-panel-tagcloud-tab-' + tagId);
                        Ext.getCmp('smarttag-tagcloud-btn-' + tagId).destroy();
                        tabsWrapper.remove(tab);
                    },
                    scope: this
                }
            }
        });
    },
    renameTag: function(tagId) {
        var renameTag = new SmartTag.window.Tag({
            title: _('smarttag.rename_tag'),
            baseParams: {
                action: 'mgr/tags/update'
            },
            record: {
                tag: this.config.record.tag,
                id: tagId
            },
            listeners: {
                'success': {
                    fn: function(response) {
                        Ext.getCmp('smarttag-page-tagcloud').loadCloud();
                        if (response.a.result.object.merged) {
                            var tabsWrapper = Ext.getCmp('smarttag-panel-tagcloud-tabs');
                            var oldTab = Ext.getCmp('smarttag-panel-tagcloud-tab-' + response.a.result.object.merged.id);
                            if (tabsWrapper && oldTab) {
                                tabsWrapper.remove(oldTab);
                            }
                        }
                        var checkTab = Ext.getCmp('smarttag-panel-tagcloud-tab-' + tagId);
                        if (checkTab) {
                            checkTab.setTitle(response.a.result.object.tag);
                            if (response.a.result.object.merged) {
                                Ext.getCmp('smarttag-grid-tagresources-' + tagId).refresh();
                            }
                        }
                        this.config.record.tag = response.a.result.object.tag;
                    },
                    scope: this
                }
            }
        });
        return renameTag.show();
    },
    getSelectedAsList: function() {
        var selected = this.getSelectionModel().getSelections();
        if (selected.length <= 0)
            return false;
        var cs = [];
        Ext.each(selected, function(item, idx) {
            cs.push(item.data.tag_id + ':' + item.data.resource_id);
        });
        return cs.join();
    },
    removeTagresources: function() {
        var ids = this.getSelectedAsList();
        if (!ids) {
            return false;
        }
        MODx.msg.confirm({
            title: _('smarttag.remove'),
            text: _('smarttag.remove_selected_confirm'),
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tagresources/batchremove',
                ids: ids
            },
            listeners: {
                'success': {
                    fn: function() {
                        Ext.getCmp('smarttag-page-tagcloud').loadCloud();
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    },
    _makeTemplates: function() {
        this.tplPageTitle = new Ext.XTemplate('<tpl for="."><a href="{action_edit}" title="' + _('edit') + ' {pagetitle}" class="smarttag-pagetitle">{pagetitle}</a></tpl>', {
            compiled: true
        });
    },
    _renderPageTitle: function(v, md, rec) {
        return this.tplPageTitle.apply(rec.data);
    },
    sync: function(tagId) {
        var grid = Ext.getCmp("smarttag-grid-tagresources-" + tagId);
        var mask = new Ext.LoadMask(grid.getEl(), {
            msg: _('smarttag.please_wait')
        });
        mask.show();
        MODx.Ajax.request({
            url: SmartTag.config.connectorUrl,
            params: {
                action: 'mgr/tags/sync',
                id: tagId
            },
            listeners: {
                'success': {
                    fn: function() {
                        grid.refresh();
                        mask.hide();
                    }
                }
            }
        });
    }
});
Ext.reg('smarttag-grid-tagresources', SmartTag.grid.TagResources);
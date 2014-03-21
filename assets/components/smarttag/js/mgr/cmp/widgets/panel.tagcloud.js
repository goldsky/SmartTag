SmartTag.panel.TagCloud = function(config) {
    config = config || {};

    Ext.apply(config, {
        id: 'smarttag-panel-tagcloud',
        baseCls: 'modx-formpanel',
        bodyStyle: 'min-height: 500px; overflow-y: scroll;',
        preventRender: true,
        defaults: {
        },
        items: [
            {
                html: '<h2>' + _('smarttag.tagcloud') + '</h2><p>' + _('smarttag.tagcloud_desc') + '</p>',
                bodyCssClass: 'with-title panel-desc',
                border: false
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

    SmartTag.panel.TagCloud.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.panel.TagCloud, MODx.Panel, {
});
Ext.reg('smarttag-panel-tagcloud', SmartTag.panel.TagCloud);
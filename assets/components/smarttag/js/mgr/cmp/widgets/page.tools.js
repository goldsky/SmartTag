SmartTag.page.Tools = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        id: 'smarttag-page-tools',
        bodyStyle: 'min-height: 500px; overflow-y: scroll;',
        preventRender: true,
        items: [
            {
                xtype: 'smarttag-panel-converter'
            }, {
                xtype: 'smarttag-panel-csvimporter'
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

    SmartTag.page.Tools.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.page.Tools, MODx.Panel);
Ext.reg('smarttag-page-tools', SmartTag.page.Tools);
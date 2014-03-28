SmartTag.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
                xtype: 'smarttag-panel-home'
                , renderTo: 'smarttag-panel-home-div'
            }]
    });
    SmartTag.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.page.Home, MODx.Component);
Ext.reg('smarttag-page-home', SmartTag.page.Home);
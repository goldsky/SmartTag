SmartTag.panel.Home = function(config) {
    config = config || {};

    var btns = [];
    btns.push({
        text: _('smarttag.tagcloud'),
        listeners: {
            'click': {
                fn: function() {
                    return this.openPage('tagcloud');
                },
                scope: this
            }
        }
    });
    if (MODx.perm["smarttag.tools_page"]) {
        btns.push({
            text: _('smarttag.tools'),
            listeners: {
                'click': {
                    fn: function() {
                        return this.openPage('tools');
                    },
                    scope: this
                }
            }
        });
    }
    btns.push('->', {
        xtype: 'panel',
        html: '<a href="javascript:void(0);" id="smarttag_about">' + _('smarttag.about') + '</a>',
        border: false,
        bodyStyle: 'font-size: 10px; margin: 5px; background-color: transparent;',
        listeners: {
            afterrender: function() {
                Ext.get('smarttag_about').on('click', function() {
                    var msg = '&copy; 2014, ';
                    msg += '<a href="http://www.virtudraft.com" target="_blank">';
                    msg += 'www.virtudraft.com';
                    msg += '</a><br/>';
                    msg += 'License GPL v3';
                    Ext.MessageBox.alert('SmartTag', msg);
                });
            }
        }
    });
    Ext.applyIf(config, {
        id: 'smarttag-panel-home',
        baseCls: 'modx-formpanel',
        layout: 'border',
        defaults: {
            collapsible: false,
            split: true,
            bodyStyle: 'background-color: transparent;',
//            border: false,
            autoHeight: true
        },
        bodyStyle: 'min-height: 500px; background-color: transparent;',
        preventRender: false,
        items: [
            {
                region: 'north',
                id: 'smarttag-panel-home-north',
                defaults: {
                    border: false,
                    autoHeight: true
                },
                border: false,
                bodyStyle: 'padding: 5px; background-color: transparent;',
                items: [
                    {
                        layout: 'hbox',
                        border: false,
                        bodyStyle: 'background-color: transparent;',
                        defaults: {
                            border: false
                        },
                        items: [
                            {
                                html: '<span style="margin-right: 10px; line-height: 39px;">' +
                                        '<span style="font-weight: bold; font-size: 16px;">' +
                                        _('smarttag') +
                                        '</span> ' + SmartTag.config.version +
                                        '</span>',
                                border: false,
                                cls: 'modx-page-header'
                            }, {
                                xtype: 'toolbar',
                                id: 'smarttag-top-nav',
                                bodyStyle: 'background-color: transparent;',
                                items: btns
                            }
                        ]
                    }
                ]
            }, {
                region: 'center',
                id: 'smarttag-panel-home-center',
                border: false,
                layout: 'fit',
                items: [
                    {
                        xtype: 'smarttag-page-tagcloud'
                    }
                ]
            }
        ],
        listeners: {
            beforerender: {
                fn: function(panel) {
                    var modxHeaderHeight = Ext.get('modx-header').getHeight();
                    var modxContentHeight = Ext.get('modx-content').getHeight();
                    this.height = modxContentHeight - modxHeaderHeight;
                },
                scope: this
            }
        }
    });

    SmartTag.panel.Home.superclass.constructor.call(this, config);
    /**
     * @todo: relayouting
     */
    var modxLeftBar = Ext.getCmp('modx-leftbar');
    if (modxLeftBar) {
        var _this = this;
        modxLeftBar.on('collapse', function() {
            console.log('collapse');
            _this.doLayout();
        });
        modxLeftBar.on('expand', function() {
            console.log('expand');
            _this.doLayout();
        });
        modxLeftBar.on('afterlayout', function() {
            console.log('afterlayout');
            _this.doLayout();
        });
    }
};
Ext.extend(SmartTag.panel.Home, MODx.Panel, {
    openPage: function(page) {
        var contentPanel = Ext.getCmp('smarttag-panel-home-center');
        contentPanel.removeAll();
        contentPanel.update({
            layout: 'fit'
        });

        contentPanel.add({
            xtype: 'smarttag-page-' + page
        });

        var container = Ext.getCmp('modx-content');
        return container.doLayout();
    }
});
Ext.reg('smarttag-panel-home', SmartTag.panel.Home);


/**
 * @author goldsky
 * For some reason, the original of this method doesn't work well for treePanel.
 * treePanel comes as an object, not an array.
 * This affects removeAll() in the openPage() method.
 * @class Array
 */
Ext.apply(Array.prototype, {
    /**
     * Removes the specified object from the array. If the object is not found nothing happens.
     * @param {Object} o The object to remove
     * @return {Array} this array
     */
    remove: function(o) {
        if (typeof (this) === 'array') {
            var index = this.indexOf(o);
            if (index != -1) {
                this.splice(index, 1);
            }
        }
        return this;
    }
});
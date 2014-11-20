SmartTag.panel.CSVImporter = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        id: 'smarttag-panel-csvimporter',
        baseCls: 'modx-formpanel',
        fileUpload: true,
        items: [
            {
                html: '<h2>' + _('smarttag.csvimporter') + '</h2><p>' + _('smarttag.csvimporter_desc') + '</p>',
                bodyCssClass: 'with-title panel-desc',
                border: false,
                bodyStyle: 'margin-bottom: 15px;'
            }, {
                layout: 'hbox',
                bodyStyle: 'padding: 10px; background-color: transparent;',
                id: 'smarttag-csvimporter-hbox',
                border: false,
                items: [
                    {
                        layout: 'form',
                        id: 'smarttag-importer-form',
                        baseCls: 'modx-formpanel',
                        labelWidth: 100,
                        labelAlign: 'left',
                        items: [
                            {
                                fieldLabel: _('smarttag.select_file'),
                                xtype: 'fileuploadfield',
                                id: 'smarttag-input-file',
                                emptyText: '',
                                name: 'file',
                                buttonText: _('smarttag.browse'),
                                width: 300
                            }
                        ]
                    }, {
                        xtype: 'button',
                        text: _('smarttag.import'),
                        handler: this.import,
                        scope: this
                    }, {
                        xtype: 'button',
                        text: _('reset'),
                        handler: function(){
                            this.form.reset();
                        },
                        scope: this
                    }
                ]
            }
        ]
    });

    SmartTag.panel.CSVImporter.superclass.constructor.call(this, config);
};
Ext.extend(SmartTag.panel.CSVImporter, MODx.FormPanel, {
    import: function() {
        var form = this.form;
        if (form.isValid()) {
            var file = Ext.get('smarttag-input-file').getValue();
            if (!file) {
                Ext.MessageBox.alert(_('smarttag.error'), _('smarttag.file_err_ns'));
                return false;
            }
            return form.submit({
                url: SmartTag.config.connectorUrl,
                params: {
                    action: 'mgr/tags/importcsv'
                },
                waitMsg: _('smarttag.waiting_msg'),
                success: function(fp, o) {
                    Ext.MessageBox.alert(_('smarttag.success'), o.result.message);
                    fp.reset();
                },
                failure: function(fp, o) {
                    Ext.MessageBox.alert(_('smarttag.fail'), o.result.message);
                    fp.reset();
                }
            });
        }
    }
});
Ext.reg('smarttag-panel-csvimporter', SmartTag.panel.CSVImporter);
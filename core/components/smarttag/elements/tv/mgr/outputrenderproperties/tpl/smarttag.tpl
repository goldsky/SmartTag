<div id="tv-wprops-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelAlign: 'top'
    ,cls: 'form-with-labels'
    ,border: false
    ,defaults: {
        bodyStyle: 'margin-bottom: 15px;'
    }
    ,items: [
        {
            xtype: 'panel'
            ,layout: 'form'
            ,title: _('delimiter')
            ,labelAlign: 'top'
            ,cls: 'form-with-labels'
            ,padding: '15'
            ,items: [
                {
                    xtype: 'textfield'
                    ,fieldLabel: _('delimiter')
                    ,description: MODx.expandHelp ? '' : _('delimiter_desc')
                    ,name: 'prop_delimiter'
                    ,id: 'prop_delimiter{/literal}{$tv}{literal}'
                    ,value: params['delimiter'] || ''
                    ,anchor: '100%'
                    ,listeners: oc
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'prop_delimiter{/literal}{$tv}{literal}'
                    ,html: _('delimter_desc')
                    ,cls: 'desc-under'
                }
            ]
        }, {
            xtype: 'panel'
            ,layout: 'form'
            ,title: _('url')
            ,labelAlign: 'top'
            ,cls: 'form-with-labels'
            ,padding: '15'
            ,items: [
                {
                    xtype: 'textfield' 
                    ,fieldLabel: _('smarttag.href') || 'href'
                    ,name: 'prop_href'
                    ,value: params['href'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                },{
                    xtype: 'textfield' 
                    ,fieldLabel: _('url_display_text')
                    ,name: 'prop_text'
                    ,value: params['text'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                },{
                    xtype: 'textfield' 
                    ,fieldLabel: _('title')
                    ,name: 'prop_title'
                    ,value: params['title'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                },{
                    xtype: 'textfield' 
                    ,fieldLabel: _('class')
                    ,name: 'prop_class'
                    ,value: params['class'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                },{
                    xtype: 'textfield' 
                    ,fieldLabel: _('style')
                    ,name: 'prop_style'
                    ,value: params['style'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                },{
                    xtype: 'textfield' 
                    ,fieldLabel: _('target')
                    ,name: 'prop_target'
                    ,value: params['target'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                },{
                    xtype: 'textfield' 
                    ,fieldLabel: _('attributes')
                    ,name: 'prop_attrib'
                    ,value: params['attrib'] || ''
                    ,listeners: oc
                    ,anchor: '100%'
                }
            ]
        }
    ]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
    ,listeners: {
        afterrender: {
            fn: function(cmp) {
                cmp.doLayout();
            }
            , scope: this
        }
    }
});
// ]]>
</script>
{/literal}

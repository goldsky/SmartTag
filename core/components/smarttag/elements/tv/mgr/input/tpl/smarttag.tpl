<div id="tv{$tv->id}"></div>
<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var tagStore = new Ext.data.JsonStore({
        url: SmartTag.config.connectorUrl
        ,totalProperty:'total'
        ,root:'results'
        ,baseParams: {
            action: 'mgr/tags/getlist'
            ,tvId: {/literal}{$tv->id}{literal}
            ,sort: 'tag'
            ,dir: 'ASC'
            ,limit: {/literal}{if $params.queryLimit}{$params.queryLimit}{else}20{/if}{literal}
        }
        ,autoLoad: true
        ,autoSave: false
        ,dir: 'ASC'
        ,fields: ['id', 'tag']
    });

    var fld{/literal}{$tv->id}{literal} = new Ext.ux.form.SuperBoxSelect({
    {/literal}
        xtype:'superboxselect'
        ,transform: 'tv{$tv->id}'
        ,id: 'tv{$tv->id}'
        ,name: "tv{$tv->id}[]"
        ,triggerAction: 'all'
        ,mode: 'remote'
        ,store: tagStore
        ,pageSize: 20
        ,minChars: 1
        ,allowAddNewData: true
        ,addNewDataOnBlur : true
        ,value: "{$tv->get('value')|escape}"
        ,valueDelimiter: ","
        ,queryValuesDelimiter: "||"
        ,originalValue: "{$tv->get('value')|escape}"
        ,extraItemCls: 'x-tag'
        ,width: 400
        ,displayField: "tag"
        ,valueField: "tag"
        ,queryDelay: 1000
        ,resizable: true
        ,hideTrigger: true
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}

        {if $params.title},title: '{$params.title}'{/if}
        {if $params.listWidth},listWidth: {$params.listWidth}{/if}
        ,maxHeight: {if $params.maxHeight}{$params.maxHeight}{else}300{/if}
        {if $params.typeAhead}
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay && $params.typeAheadDelay != ''}{$params.typeAheadDelay}{else}250{/if}
            ,editable: true
        {else}
            ,typeAhead: false
        {/if}
        {if $params.listEmptyText}
            ,listEmptyText: '{$params.listEmptyText}'
        {/if}
        ,autoSelect: false
        ,forceSelection: false
        ,stackItems: {if $params.stackItems && $params.stackItems != 'false'}true{else}false{/if}
        ,msgTarget: 'under'
        ,singleMode: {if $params.singleMode && $params.singleMode != 'false'}true{else}false{/if}
        {literal}
        ,listeners: {
            'select': {fn:MODx.fireResourceFormChange, scope:this}
            ,'beforeadditem': {fn:MODx.fireResourceFormChange, scope:this}
            ,'newitem': {fn:function(bs,v,f) {
                if (!SmartTag.loadNewItemMask){
                    var domHandler = bs.outerWrapEl.dom;
                    SmartTag.loadNewItemMask = new Ext.LoadMask(domHandler, {
                        msg: _('cleaningup')
                    });
                }
                SmartTag.loadNewItemMask.show();
                return MODx.Ajax.request({
                    url: SmartTag.config.connectorUrl
                    ,params: {
                        action: 'mgr/tags/filter'
                        ,tag: v
                    }
                    ,listeners: {
                        success: {
                            fn: function(response){
                                if (response.success) {
                                    var _this = fld{/literal}{$tv->id}{literal};
                                    if (_this.singleMode) {
                                        _this.removeAllItems();
                                    }
                                    v = response.object.filtered;
                                    bs.addNewItem({"id": v,"tag": v});
                                    MODx.fireResourceFormChange();
                                    if (SmartTag.loadNewItemMask) {
                                        SmartTag.loadNewItemMask.hide();
                                    }
                                }
                            },scope: this
                        }
                        ,failure:{
                            fn: function(response){
                                if (SmartTag.loadNewItemMask) {
                                    SmartTag.loadNewItemMask.hide();
                                }
                            },scope: this
                        }
                    }
                });
            },scope:this}
            ,'beforeremoveitem': {fn:MODx.fireResourceFormChange, scope:this}
            ,'clear': {fn:MODx.fireResourceFormChange, scope:this}
        }
    });
    
    Ext.getCmp('modx-panel-resource').getForm().add(fld{/literal}{$tv->id}{literal});
});
{/literal}
// ]]>
</script>

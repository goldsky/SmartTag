<input id="tv{$tv->id}" name="tv{$tv->id}[]"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>

<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = new Ext.ux.form.SuperBoxSelect({
    {/literal}
        xtype:'superboxselect'
        ,transform: 'tv{$tv->id}'
        ,id: 'tv{$tv->id}'
        ,triggerAction: 'all'
        ,mode: 'remote'
        ,store: new Ext.data.JsonStore({
            autoLoad: true,
            root: 'results',
            dir: 'ASC',
            fields: ['id', 'tag'],
            url: SmartTag.config.connectorUrl,
            baseParams: {
                action: 'mgr/tags/getlist'
                ,tvId: {$tv->id}
                ,sort: 'tag'
                ,dir: 'ASC'
            }
        })
        ,pageSize: 20
        ,minChars: 1
        ,allowAddNewData: true
        ,value: "{$tv->get('value')|escape}"
        //,valueDelimiter: "||"
        //,queryValuesDelimiter: "||"
        ,originalValue: "{$tv->get('value')|escape}"
        ,extraItemCls: 'x-tag'
        ,width: 400
        ,displayField: "tag"
        ,valueField: "tag"
        ,queryDelay: 30
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
        ,forceSelection: false
        ,stackItems: {if $params.stackItems && $params.stackItems != 'false'}true{else}false{/if}
        ,msgTarget: 'under'

        {literal}
        ,listeners: {
            'select': {fn:MODx.fireResourceFormChange, scope:this}
            ,'beforeadditem': {fn:MODx.fireResourceFormChange, scope:this}
            ,'newitem': {fn:function(bs,v,f) {
                bs.addNewItem({"id": v,"tag": v});
                MODx.fireResourceFormChange();
                return true;
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

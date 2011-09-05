<div id="tv{$tv->id}-form"></div>
<input type="hidden" id="tv{$tv->id}" name="tv{$tv->id}" value="{$tv->get('value')|escape}" />
<div id="clichethumbnail-{$tv->id}"></div>

<script type="text/javascript">
Ext.onReady(function() {
// <![CDATA[
	{literal}
	MODx.load({
	{/literal}
		xtype: 'panel'
		,tv: '{$tv->id}'
		,tvValue: '{$tv->value|escape}'
		,renderTo: 'clichethumbnail-{$tv->id}'
		,tvId: '{$tv->id}'
		,width: '97%'
        ,border: false
		,items: [{
			xtype: 'cliche-thumbnail-tv'
			,id: 'clichethumbnail-pw-{$tv->id}'
			,tvId: '{$tv->id}'
			,resourceId: '{$resourceId}'
		}]
		{if $itemjson},data: {$itemjson}{/if}
	{literal}
	});
	{/literal}
// ]]>
});
</script>
<div id="tv{$tv->id}-form"></div>
<input type="hidden" id="tv{$tv->id}" name="tv{$tv->id}" value="{$tv->get('value')|escape}" />
<div id="thumb-{$tv->id}"></div>

<script type="text/javascript">
Ext.onReady(function() {
// <![CDATA[
	{literal}
	MODx.load({
	{/literal}
		xtype: 'panel'
		,tv: '{$tv->id}'
		,tvValue: '{$tv->value|escape}'
		,renderTo: 'thumb-{$tv->id}'
		,tvId: '{$tv->id}'
		,width: '97%'
		,items: [{
			xtype: 'cliche-thumb-tv'
			,id: 'thumb-pw-{$tv->id}'
			,tvId: '{$tv->id}'
		}]
		{if $itemjson},data: {$itemjson}{/if}
	{literal}
	});
	{/literal}
// ]]>
});
</script>
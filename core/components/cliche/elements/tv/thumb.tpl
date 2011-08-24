<div id="tv{$tv->id}-form"></div>
<input type="hidden" id="tv{$tv->id}" name="tv{$tv->id}" value="{$tv->get('value')|escape}" />
<div id="testing"></div>

<script type="text/javascript">
// <![CDATA[
	var win;	

	{literal}
	MODx.load({
	{/literal}
		xtype: 'postthumb-panel-tv'
		,tv: '{$tv->id}'
		,tvValue: '{$tv->value|escape}'
		,renderTo:'testing'
		,width: '97%'
		{if $itemjson},data: {$itemjson}{/if}
	{literal}
	});
	{/literal}
// ]]>
</script>
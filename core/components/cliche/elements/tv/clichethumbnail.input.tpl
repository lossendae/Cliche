<div id="clichethumbnail-{$tv->id}"></div>
<script type="text/javascript">
Ext.onReady(function() {
// <![CDATA[
	{literal}
	MODx.load({
	{/literal}
		xtype: 'clichethumbnail'
		,renderTo: 'clichethumbnail-{$tv->id}'
		,tv: {
			id: '{$tv->id}'
			,value: {if $itemjson}{$itemjson}{else}''{/if}
			,output_properties: {if $configjson}{$configjson}{else}''{/if}	
		}	
	{literal}
	});
	{/literal}
// ]]>
});
</script>
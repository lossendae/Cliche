<div id="tv-wprops-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {/literal}{$params}{literal};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
var tv = '{/literal}{$tv}{literal}';
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
		xtype: 'textfield'
		// ,fieldLabel:_('thumbwidth')
		,fieldLabel:'Thumbnail width'
		,name: 'prop_thumbwidth'
		,id: 'prop_thumbwidth{/literal}{$tv}{literal}'
		,value: params['thumbwidth'] || 400
		,width: 300
		,listeners:oc
	},{
		xtype: 'textfield'
		// ,fieldLabel:_('thumbheight')
		,fieldLabel:'Thumbnail height'
		,name: 'prop_thumbheight'
		,id: 'prop_thumbheight{/literal}{$tv}{literal}'
		,value: params['thumbheight'] || 200
		,width: 300
		,listeners:oc
	}]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}
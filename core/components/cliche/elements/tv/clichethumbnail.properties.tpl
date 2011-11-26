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
        ,description: 'The width of the generated thumbnail'
		,name: 'prop_thumbwidth'
		,id: 'prop_thumbwidth{/literal}{$tv}{literal}'
		,value: params.thumbwidth || 400
		,width: 300
		,listeners:oc
	},{
		xtype: 'textfield'
		// ,fieldLabel:_('thumbheight')
		,fieldLabel:'Thumbnail height'
        ,description: 'The height of the generated thumbnail'
		,name: 'prop_thumbheight'
		,id: 'prop_thumbheight{/literal}{$tv}{literal}'
		,value: params.thumbheight || 200
		,width: 300
		,listeners:oc
	},{
        xtype: 'combo-boolean'
//        ,fieldLabel: _('load_jquery')
        ,fieldLabel: 'Load jQuery'
        ,description: 'If set to yes, jQuery X.X is going to be loaded in the header on the resource editing page'
        ,name: 'prop_load_jquery'
        ,hiddenName: 'prop_load_jquery'
        ,id: 'prop_load_jquery{/literal}{$tv}{literal}'
        ,value: params['load_jquery'] == 0 || params['load_jquery'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
//        ,fieldLabel: _('image_baseurl_prepend_check_slash')
        ,fieldLabel: 'Keep aspect ratio'
        ,description: 'If set to yes, The cropper will keep the thumbnail aspect ratio for the cropper'
        ,name: 'prop_keep_aspect_ratio'
        ,hiddenName: 'prop_keep_aspect_ratio'
        ,id: 'prop_keep_aspect_ratio{/literal}{$tv}{literal}'
        ,value: params['keep_aspect_ratio'] == 0 || params['keep_aspect_ratio'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}
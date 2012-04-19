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
    ,labelAlign: 'top'
    ,items: [{
        xtype: 'textfield'
        // ,fieldLabel:_('thumbwidth')
        ,fieldLabel:'Thumbnail width'
        ,name: 'prop_thumbwidth'
        ,id: 'prop_thumbwidth{/literal}{$tv}{literal}'
        ,value: params.thumbwidth || 400
        ,width: 300
        ,listeners:oc
    },{
        xtype: 'label'
        ,forId: 'prop_thumbwidth{/literal}{$tv}{literal}'
        ,text: 'The default width of the generated thumbnail'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        // ,fieldLabel:_('thumbheight')
        ,fieldLabel:'Thumbnail height'
        ,name: 'prop_thumbheight'
        ,id: 'prop_thumbheight{/literal}{$tv}{literal}'
        ,value: params.thumbheight || 200
        ,width: 300
        ,listeners:oc
    },{
        xtype: 'label'
        ,forId: 'prop_thumbheight{/literal}{$tv}{literal}'
        ,text: 'The default height of the generated thumbnail'
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
//        ,fieldLabel: _('load_jquery')
        ,fieldLabel: 'Load jQuery'
        ,name: 'prop_load_jquery'
        ,hiddenName: 'prop_load_jquery'
        ,id: 'prop_load_jquery{/literal}{$tv}{literal}'
        ,value: params['load_jquery'] == 0 || params['load_jquery'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'label'
        ,forId: 'prop_load_jquery{/literal}{$tv}{literal}'
        ,text: 'If set to yes, jQuery latest version will be loaded in the header on the resource editing page (Required from image cropper - Set to no if jquery is already loaded elsewhere)'
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: 'Keep aspect ratio'
        ,name: 'prop_keep_aspect_ratio'
        ,hiddenName: 'prop_keep_aspect_ratio'
        ,id: 'prop_keep_aspect_ratio{/literal}{$tv}{literal}'
        ,value: params['keep_aspect_ratio'] == 0 || params['keep_aspect_ratio'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'label'
        ,forId: 'prop_keep_aspect_ratio{/literal}{$tv}{literal}'
        ,text: 'If set to yes, The cropper will keep the thumbnail aspect ratio for the cropper'
        ,cls: 'desc-under'
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}
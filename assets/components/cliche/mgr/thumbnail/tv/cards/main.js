/**
 * The panel container for Cliche TV thumbnail
 * @class Cliche.ThumbnailMainCard
 * @extend MODx.TemplatePanel
 * @xtype clichethumbnail-main-panel
 */
Cliche.ThumbnailMainCard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		bodyCssClass: 'cliche-pw-panel'
		,cls: 'main-wrapper modx-template-detail'
		,bodyCssClass: 'body-wrapper'
		,initial: false
		,startingText: _('clichethumbnail.main_empty_msg')
		,markup: '<div class="cropped centered">'
			+'<img src="{thumbnail}?t={timestamp}" alt="Your thumbnail preview" class="thumb_pw"/>'
        +'</div>'
		,tbar: [{
			text: _('clichethumbnail.btn_browse_album')
			,iconCls: 'icon-add'
			,handler: function(btn,e){
				Ext.getCmp(this.albumViewCard).activate();
                Ext.getCmp(this.selectImageBtn).hide();
			}
			,scope: this
		},{
			text: _('clichethumbnail.btn_crop_thumbnail')
			,iconCls: 'icon-crop'
			,handler: function(btn,e){
                if(typeof(this.initial) == "object"){
                    Ext.getCmp(this.cropperCard).activate(this.image, this.initial);
                } else {
                    Ext.getCmp(this.cropperCard).activate(this.image);
                }
                Ext.getCmp(this.selectImageBtn).hide();
			}
            ,id: 'cliche-crop-btn-'+ config.tv
            ,hidden: true
			,scope: this
		}]
	});
	Cliche.ThumbnailMainCard.superclass.constructor.call(this,config);
}
Ext.extend(Cliche.ThumbnailMainCard, MODx.TemplatePanel,{
	activate: function(){
		Ext.getCmp(this.breadcrumbs).reset(_('clichethumbnail.breadcrumb_root_desc'));
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);        
    }
	
	,updateThumbnail: function(id, initial){
        /* Merge options for ajax request */
        params = this.mergeOptions({
            action: 'clichethumbnail/create_thumbnail'
            ,ctx: 'mgr'
            ,id: id
        }, this.tvConfig);

        if(initial !== undefined){
            this.initial = initial
            params = this.mergeOptions(params, initial);
        }

        /* Get the generated thumbnail */
        Ext.Ajax.request({
            url: this.url
            ,params: params
            ,success: function( result, request ) {
                response = Ext.util.JSON.decode(result.responseText);
                if(response.success){
                    this.updateDetail(response.data);
                    this.image = response.data;
                    this.image.tv = this.tv;
                    Ext.getCmp('cliche-crop-btn-'+ this.tv).show();
                    Ext.getCmp(this.selectImageBtn).show();
                    this.initial.reload = false
                }
            }
            ,failure: function( result, request ){
                console.log(result);
            }
            ,scope: this
        });

        Ext.getCmp(this.breadcrumbs).reset(_('clichethumbnail.breadcrumb_root_desc_with_thumb'))
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
	}
	
	,mergeOptions: function(obj1,obj2){
        var obj3 = {};
        for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
        for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
        return obj3;
    }
	
	,getThumb: function(){
        Ext.getCmp(this.previewPanel).onUpdateThumbnailPreview(this.image);
		tv = Ext.getCmp('tv'+this.tv).setValue(Ext.encode(this.image));
		if(typeof Ext.getCmp('modx-panel-resource').markDirty == "function"){
			Ext.getCmp('modx-panel-resource').markDirty();
		}        
    }
});
Ext.reg("clichethumbnail-main-panel", Cliche.ThumbnailMainCard);
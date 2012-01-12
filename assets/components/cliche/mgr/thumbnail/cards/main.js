/**
 * The panel container for Cliche TV thumbnail
 * @class MODx.ClicheThumbMainCard
 * @extend MODx.TemplatePanel
 * @xtype cliche-thumbnail-tv
 */
MODx.ClicheThumbMainCard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		bodyCssClass: 'cliche-pw-panel'
		,cls: 'main-wrapper modx-template-detail'
		,bodyCssClass: 'body-wrapper'
		,initial: false
		,startingText: '<h4>There is no thumbnail set yet for this document</p>'
		,markup: '<div class="cropped centered">'
			+'<img src="{thumbnail}?t={timestamp}" alt="Your thumbnail preview" class="thumb_pw"/>'
        +'</div>'
		,tbar: [{
			text: 'Browse album'
			,iconCls: 'icon-add'
			,handler: function(btn,e){
				Ext.getCmp(this.albumViewCard).activate();
                Ext.getCmp(this.selectImageBtn).hide();
			}
			,scope: this
		},{
			text: 'Crop the current thumbnail'
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
	MODx.ClicheThumbMainCard.superclass.constructor.call(this,config);
}
Ext.extend(MODx.ClicheThumbMainCard, MODx.TemplatePanel,{
	activate: function(){
		Ext.getCmp(this.breadcrumbs).reset('Choose an image from the dedicated album or upload an image for this thumbnail');
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

        Ext.getCmp(this.breadcrumbs).reset('The image below shows a preview of your thumbnail.<br/>You can crop the selected image by using the button "Crop Image"')
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
	}
	
	,mergeOptions: function(obj1,obj2){
        var obj3 = {};
        for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
        for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
        return obj3;
    }
	
	,getThumb: function(){
        Ext.getCmp('clichethumbnail-pw-'+this.tv).updateDetail(this.image);
        Ext.getCmp('clichethumbnail-pw-'+this.tv).onUpdateThumbnailPreview();
        tv = Ext.select('#tv'+this.tv);
        tv.elements[0].value = Ext.encode(this.image);
        Ext.getCmp('modx-panel-resource').markDirty();
    }
});
Ext.reg("cliche-thumb-main-card", MODx.ClicheThumbMainCard);
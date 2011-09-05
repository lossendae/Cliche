Ext.ns('MODx');
/**
 * @class MODx.ClicheThumbnailManager
 * @extend MODx.AbstractWindow 
 * The window container for Cliche TV thumbnail manager
 */
MODx.ClicheThumbnailManager = Ext.extend(MODx.AbstractWindow , {
	title: 'Thumbnail manager'
	,width: 960
	,cls: 'custom-window'
	,modal: false
	,layout: 'form'
	,tvId: null
	,configTv: null
	//Use buildItem constructor because of tvID
	,buildItems: function(config){
		config.items = [{
			xtype: 'panel'
			,bodyCssClass: ''
			,layout: 'anchor'
			,items:[{		
				xtype: 'my-breadcrumbs'
				,id: 'cliche-thumb-bd-'+this.tvId
				,startingText: { 
					 text : 'Select an image from an existing Album or upload a picture from your computer<br/>The selected picture will appear below'
					,trail : [{
						text : 'Your thumbnail'
					}]
				}
			}]
		},{
			layout: 'card'
			,layoutOnCardChange: true
			,activeItem: 0
			,border: false
			,deferredRender: true
			,id: 'cliche-thumb-cards-'+this.tvId
            ,bodyCssClass: ''
			,defaults:{
				url: MODx.ClicheConnectorUrl
				,cls: 'spaced-wrapper'
//				,bodyCssClass: ''
				,border: false
                ,resourceId: this.resourceId
                ,tvId: this.tvId
                ,configTv: this.configTv
                ,cardContainer: 'cliche-thumb-cards-'+this.tvId
                ,breadcrumbs: 'cliche-thumb-bd-'+this.tvId
                ,mainCard: 'cliche-main-'+this.tvId
                ,albumViewCard: 'cliche-thumb-album-view-'+this.tvId
                ,uploaderCard: 'cliche-thumb-uploader-'+this.tvId
			}
			,items: [{
				xtype: 'cliche-thumb-main-card'
				,id: 'cliche-main-'+this.tvId
			},{
                xtype: 'cliche-thumb-album-view'
				,id: 'cliche-thumb-album-view-'+this.tvId
                ,cls: ''
            },{
				xtype: 'cliche-panel-uploader'
				,id: 'cliche-thumb-uploader-'+this.tvId
            }]

            ,setActiveItem: function(id){
                this.getLayout().setActiveItem(id);
            }
		}]
	}

	,buildUI: function(config){
		config.buttons = [config.cancelBtn,{
			text: 'Select this image'
			,handler: this.onSelect
			,scope: this
		}];
	}

	,onSelect: function(btn, e){
		Ext.getCmp('cliche-main-'+this.tvId).getThumb();
		this.hide();		
	}
    
	,setCurrentThumb: function(data){
		var initial = [ data.x, data.y, data.x2, data.y2 ];
		Ext.getCmp('cliche-main-'+this.tvId).activate(data, initial);
	}
});
Ext.reg("cliche-thumbnail-manager", MODx.ClicheThumbnailManager);
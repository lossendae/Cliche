/**
 * @class MODx.window.ClicheThumbnailManager
 * @extends Ext.Window
 * @param {Object} config An object of configuration parameters
 * @xtype cliche-thumbnail-manager
 */
MODx.window.ClicheThumbnailManager = function(config) {
    config = config || {};	
    Ext.applyIf(config,{ 
		title: _('clichethumbnail.window_title')
		,width: 960
		,cls: 'cliche'
		,layout: 'form'
		,data: null
		,autoHeight: true
		,items: [{
			xtype: 'modx-breadcrumbs-panel'
			,bodyCssClass: 'win breadcrumbs'
			,id: 'cliche-thumb-bd-'+config.tv
			,desc: _('clichethumbnail.breadcrumb_root_desc')
			,root : { 
				text : _('clichethumbnail.breadcrumb_root')
				,className: 'first'
				,root: true
				,pnl: 'cliche-main-'+config.tv
			}
		},{
			layout: 'card'
			,layoutOnCardChange: true
			,activeItem: 0
			,border: false
			,deferredRender: true
			,id: 'cliche-thumb-cards-'+config.tv
            ,bodyCssClass: ''
			,autoHeight: true
			,defaults:{
				url: MODx.ClicheConnectorUrl
				,border: false
                ,image: config.image
                ,tvConfig: config.tvConfig
                ,tv: config.tv
                ,cardContainer: 'cliche-thumb-cards-'+config.tv
                ,breadcrumbs: 'cliche-thumb-bd-'+config.tv
				,mainCard: 'cliche-main-'+config.tv
                ,selectImageBtn: 'cliche-thumb-select-image-'+ config.tv
                ,albumViewCard: 'cliche-thumb-album-view-'+config.tv
                ,uploadCard: 'cliche-panel-upload-'+config.tv
                ,cropperCard: 'cliche-thumb-cropper-'+config.tv
			}
			,items: [{
				xtype: 'cliche-thumb-main-card'
				,id: 'cliche-main-'+config.tv
				,autoHeight: true
			},{
				xtype: 'cliche-thumb-album-view'
				,id: 'cliche-thumb-album-view-'+config.tv
			},{
				xtype: 'cliche-panel-upload'
				,id: 'cliche-panel-upload-'+config.tv
			},{
				xtype: 'cliche-panel-cropper'
				,id: 'cliche-thumb-cropper-'+config.tv
				,autoHeight: true
			}]
			,setActiveItem: function(id){
				this.getLayout().setActiveItem(id);
			}
		}]
		,buttons :[{
			text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
		},{
			text: _('clichethumbnail.btn_select_image')
			,id: 'cliche-thumb-select-image-'+ config.tv
			,cls: 'green'
			,handler: this.onSelect
			,scope: this
			,hidden: true
		}]
	});
    MODx.window.ClicheThumbnailManager.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ClicheThumbnailManager,Ext.Window,{
	onSelect: function(btn, e){
		Ext.getCmp('cliche-main-'+this.tv).getThumb();
		this.hide();		
	}
	,setCurrentThumb: function(){
        var initial;
        var data = this.image
        if(data.x !== undefined){
            var initial = [ data.x, data.y, data.x2, data.y2 ];
        }
		Ext.getCmp('cliche-main-'+this.tv).updateThumbnail(data.id, initial);
	}
});
Ext.reg("cliche-thumbnail-manager", MODx.window.ClicheThumbnailManager);
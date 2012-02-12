Ext.ns('Cliche');
/**
 * @class Cliche.WindowThumbnailManager
 * @extends Ext.Window
 * @param {Object} config An object of configuration parameters
 * @xtype clichethumbnail-manager
 */
Cliche.WindowThumbnailManager = function(config) {
    config = config || {};	
    Ext.applyIf(config,{ 
		title: _('clichethumbnail.window_title')
		,width: 960
		,cls: 'cliche'
		,layout: 'form'
		,data: null
		,autoHeight: true
		,defaults: {
			authoHeight: true
		}
		,closeAction: 'hide'
		,items: [{
			xtype: 'modx-breadcrumbs-panel'
			,bodyCssClass: 'win breadcrumbs'
			,id: 'clichethumbnail-bd-'+config.tv
			,desc: _('clichethumbnail.breadcrumb_root_desc')
			,root : { 
				text : _('clichethumbnail.breadcrumb_root')
				,className: 'first'
				,root: true
				,pnl: 'clichethumbnail-main-'+config.tv
			}
		},{
			layout: 'card'
			,layoutOnCardChange: true
			,activeItem: 0
			,border: false
			,deferredRender: true
			,id: 'clichethumbnail-cards-'+config.tv
            ,bodyCssClass: ''
			,defaults:{
				url: MODx.ClicheConnectorUrl
				,border: false
                ,image: config.image
                ,tvConfig: config.tvConfig
                ,tv: config.tv
                ,uid: config.tv
                ,cardContainer: 'clichethumbnail-cards-'+config.tv
                ,breadcrumbs: 'clichethumbnail-bd-'+config.tv
				,mainCard: 'clichethumbnail-main-'+config.tv
                ,selectImageBtn: 'clichethumbnail-select-image-'+ config.tv
                ,albumViewCard: 'clichethumbnail-album-panel-'+config.tv
                ,uploadCard: 'clichethumbnail-upload-panel-'+config.tv
                ,cropperCard: 'clichethumbnail-cropper-'+config.tv
                ,previewPanel: config.previewPanel
			}
			,items: [{
				xtype: 'clichethumbnail-main-panel'
				,id: 'clichethumbnail-main-'+config.tv
				,autoHeight: true
			},{
				xtype: 'clichethumbnail-album-panel'
				,id: 'clichethumbnail-album-panel-'+config.tv
			},{
				xtype: 'clichethumbnail-upload-panel'
				,id: 'clichethumbnail-upload-panel-'+config.tv
			},{
				xtype: 'clichethumbnail-cropper-panel'
				,id: 'clichethumbnail-cropper-'+config.tv
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
			,id: 'clichethumbnail-select-image-'+ config.tv
			,cls: 'green'
			,handler: this.onSelect
			,scope: this
			,hidden: true
		}]
	});
    Cliche.WindowThumbnailManager.superclass.constructor.call(this,config);
};
Ext.extend(Cliche.WindowThumbnailManager,Ext.Window,{
	onSelect: function(btn, e){
		Ext.getCmp('clichethumbnail-main-'+this.tv).getThumb();
		this.hide();		
	}
	,setCurrentThumb: function(){
        var initial;
        var data = this.image
        if(data.x !== undefined){
            var initial = [ data.x, data.y, data.x2, data.y2 ];
        }
		Ext.getCmp('clichethumbnail-main-'+this.tv).updateThumbnail(data.id, initial);
	}
});
Ext.reg("clichethumbnail-manager", Cliche.WindowThumbnailManager);
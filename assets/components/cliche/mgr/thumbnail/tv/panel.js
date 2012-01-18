/**
 * The panel container for Cliche TV thumbnail
 * @class MODx.ClicheThumbnailTV
 * @extend MODx.TemplatePanel
 * @xtype cliche-thumbnail-tv
 */
MODx.ClicheThumbnailTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		startingMarkup: '<div class="cropped">{text}</div>'
		,startingText: _('clichethumbnail.main_default_text')
		,markup: '<div class="cropped">'
			+'<img src="{thumbnail}?t={timestamp}" alt="'+ _('clichethumbnail.main_your_preview') +'" class="thumb_pw"/>'
		+'</div>'
		,win: false
		,tv: null
		,tbar : [{
			xtype: 'button'
			,text: _('clichethumbnail.btn_browse')
            ,id: 'manage-thumb-btn-'+ config.tv
			,handler: this.showThumbnailManager
			,scope: this
		},{
            xtype: 'button'
            ,text: _('clichethumbnail.btn_remove_thumbnail')
            ,id: 'remove-thumb-btn-'+ config.tv
            ,handler: this.onRemoveThumbnail
            ,hidden: true
            ,scope: this
        }]
	});
	MODx.ClicheThumbnailTV.superclass.constructor.call(this,config);
	this.on('render', this.initData, this);
}
Ext.extend(MODx.ClicheThumbnailTV, MODx.TemplatePanel,{
	initData: function(){
        this.image = this.ownerCt.data;
        this.tvConfig = this.ownerCt.config;
		
		if(typeof(this.image) == "object" &&  typeof(this.image.thumbnail) !== "undefined"){
            Ext.getCmp('remove-thumb-btn-'+ this.tv).show();
            Ext.getCmp('manage-thumb-btn-'+ this.tv).setText(_('clichethumbnail.btn_replace_thumbnail'));
			this.updateDetail(this.image);
		}
	} // eo function initData
	
	,showThumbnailManager: function(btn,e){
		if(!this.win){
			this.win = new MODx.window.ClicheThumbnailManager({
				id: 'cliche-thumb-manager-window-'+ this.tv
                ,image: this.image
				,tvConfig: this.tvConfig
				,tv: this.tv
			});
		}
		this.win.show(btn.id);
		var pos = this.win.getPosition(true);
		this.win.setPosition(pos[0], 35);
		if(typeof(this.image) == "object" &&  typeof(this.image.thumbnail) !== "undefined"){
			this.win.setCurrentThumb();
		}
	} // eo function showThumbnailManager
	
	,isEmpty: function (obj) {
		for(var prop in obj) {
			if(obj.hasOwnProperty(prop))
			return false;
		}
		return true;
    } // eo function isEmpty

    ,onRemoveThumbnail: function(btn, e){
        Ext.getCmp('manage-thumb-btn-'+ this.tv).setText(_('clichethumbnail.btn_browse'));
        this.reset();
        btn.hide();
        var tv = Ext.select('#tv'+this.tv);
        tv.elements[0].value = Ext.encode({});
        Ext.getCmp('modx-panel-resource').markDirty();
    } // eo function onRemoveThumbnail

    ,onUpdateThumbnailPreview: function(){
        var btn = Ext.getCmp('remove-thumb-btn-'+ this.tv);
        if(btn.hidden){
            btn.show();
            Ext.getCmp('manage-thumb-btn-'+ this.tv).setText(_('clichethumbnail.btn_replace_thumbnail'));
        }
    } // eo function onUpdateThumbnailPreview
});	
Ext.reg('cliche-thumbnail-tv',MODx.ClicheThumbnailTV);
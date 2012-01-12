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
		,startingText: '<h4>Click the button above to add a thumbnail</h4><p>You\'ll see a preview of your thumbnail here once created.</p>'
		,markup: '<div class="cropped">'
			+'<img src="{thumbnail}?t={timestamp}" alt="Your thumbnail preview" class="thumb_pw"/>'
		+'</div>'
		,win: false
		,tv: null
		,tbar : [{
			xtype: 'button'
			,text: 'Manage your thumbnail'
            ,id: 'manage-thumb-btn-'+ config.tv
			,handler: this.showThumbnailManager
			,scope: this
		},{
            xtype: 'button'
            ,text: 'Remove thumbnail'
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
            Ext.getCmp('manage-thumb-btn-'+ this.tv).setText('Select another image');
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
		pos = this.win.getPosition(true);
		this.win.setPosition(pos[0], 35);
		if(typeof(this.image) == "object" &&  typeof(this.image.thumbnail) !== "undefined"){
			this.win.setCurrentThumb();
		}
	}
	
	,isEmpty: function (obj) {
		for(var prop in obj) {
			if(obj.hasOwnProperty(prop))
			return false;
		}
		return true;
    }

    ,onRemoveThumbnail: function(btn, e){
        Ext.getCmp('manage-thumb-btn-'+ this.tv).setText('Manage your thumbnail');
        this.reset();
        btn.hide();
        tv = Ext.select('#tv'+this.tv);
        tv.elements[0].value = Ext.encode({});
        Ext.getCmp('modx-panel-resource').markDirty();
    }

    ,onUpdateThumbnailPreview: function(){
        var btn = Ext.getCmp('remove-thumb-btn-'+ this.tv);
        if(btn.hidden){
            btn.show();
            Ext.getCmp('manage-thumb-btn-'+ this.tv).setText('Select another image');
        }
    }
});	
Ext.reg('cliche-thumbnail-tv',MODx.ClicheThumbnailTV);
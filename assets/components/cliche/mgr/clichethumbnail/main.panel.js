Ext.ns('MODx');
/**
 * @class MODx.ClicheThumbnailTV
 * @extend MODx.AbstractTemplatePanel
 * The panel container for Cliche TV thumbnail field
 */
MODx.ClicheThumbnailTV = Ext.extend(MODx.AbstractTemplatePanel, {
	bodyCssClass: 'cliche-pw-panel'
	,cls: 'scoped'
	,win: false
	,tvId: null
	,startingText:'<h4>Click the button above to add a thumbnail</h4><p>You\'ll see a preview of your thumbnail here once created.</p>'
	,startingMarkup: [
		'<div class="cropped">'
			,'{text}'
		,'</div>'
	]
	,tpl : [
		'<div class="cropped">'
			,'<img src="{thumbnail}?t={timestamp}" alt="Your thumbnail preview" class="thumb_pw"/>'
		,'</div>'
	]

	,buildUI: function(config){
		config.tbar = [{
			xtype: 'button'
			,text: 'Manage your thumbnail'
            ,id: 'manage-thumb-btn-'+ this.tvId
			,handler: this.showThumbnailManager
			,scope: this
		},{
            xtype: 'button'
            ,text: 'Remove thumbnail'
            ,id: 'remove-thumb-btn-'+ this.tvId
            ,handler: this.onRemoveThumbnail
            ,hidden: true
            ,scope: this
        }];
	}

	,showThumbnailManager: function(btn,e){
		id = this.ownerCt.tvId;
		resourceId = this.resourceId;
		if(!this.win){
			this.win = new MODx.ClicheThumbnailManager({
				id: 'thumb-mgr-window-'+id
				,tvId: id
                ,configTv: this.configTv
				,resourceId: resourceId
			});		
		}
		this.win.show(btn.id);
		pos = this.win.getPosition(true);
		this.win.setPosition(pos[0], 35);
		if(typeof(this.configTv) == "object" &&  typeof(this.configTv.image) !== "undefined"){
			this.win.setCurrentThumb(this.configTv);
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
        Ext.getCmp('manage-thumb-btn-'+ this.tvId).setText('Manage your thumbnail');
        this.reset();
        btn.hide();
        tv = Ext.select('#tv'+this.tvId);
        tv.elements[0].value = Ext.encode({});
        Ext.getCmp('modx-panel-resource').markDirty();
    }

    ,onUpdateThumbnailPreview: function(){
        btn = Ext.getCmp('remove-thumb-btn-'+ this.tvId);
        if(btn.hidden){
            btn.show();
            Ext.getCmp('manage-thumb-btn-'+ this.tvId).setText('Select another image');
        }
    }
	
	,initEvents: function(){
		MODx.ClicheThumbnailTV.superclass.initEvents.call(this);
        this.configTv = this.ownerCt.data;

		if(typeof(this.configTv) == "object" &&  typeof(this.configTv.phpthumb) !== "undefined"){
            Ext.getCmp('remove-thumb-btn-'+ this.tvId).show();
            Ext.getCmp('manage-thumb-btn-'+ this.tvId).setText('Select another image');
			this.updateDetail(this.configTv);
		}
	} // eo function initEvents
});
Ext.reg("cliche-thumbnail-tv", MODx.ClicheThumbnailTV);
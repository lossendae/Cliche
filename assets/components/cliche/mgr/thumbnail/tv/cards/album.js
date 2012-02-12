/**
 * The view panel for the default album type
 *
 * @class Cliche.ThumbnailAlbumView
 * @extends MODx.ClicheAlbumViewPanel
 * @param {Object} config An object of options.
 * @xtype clichethumbnail-album-view
 */
Cliche.ThumbnailAlbumView = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        fields: ['id','name','description','createdon','createdby','protected','album_id','image','thumbnail','phpthumb','metas','tv']
        ,baseParams: {
            action: 'clichethumbnail/thumb/getlist'
			,ctx: 'mgr'
			,limit: 8
			,start: 0
        }
		,loadingText : '<div class="empty-msg"><h4>'+_('clichethumbnail.album_loading')+'</h4></div>'
		,emptyText : '<div class="empty-msg"><h4>'+_('clichethumbnail.album_empty_msg')+'</h4></div>'
    });
    Cliche.ThumbnailAlbumView.superclass.constructor.call(this,config);
};
Ext.extend(Cliche.ThumbnailAlbumView,MODx.ClicheAlbumViewPanel,{
    showDetails : function(){
        var selNode = this.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
            if (data) { Ext.getCmp(this.ownerCt.ownerCt.itemDetailId).updateDetail(data); }
        }
    }
});
Ext.reg('clichethumbnail-album-view',Cliche.ThumbnailAlbumView);

/**
 * The album panel
 *
 * @class Cliche.ThumbnailAlbumPanel
 * @extends MODx.panel.ClicheAlbumPanel
 * @param {Object} config An object of options.
 * @xtype clichethumbnail-album-panel
 */
Cliche.ThumbnailAlbumPanel = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		issettv: false
		,itemDetailId: 'cliche-album-item-details-'+config.tv
		,tbar:[{
			xtype: 'button'
			,text: _('clichethumbnail.btn_back_to_main')
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp('clichethumbnail-main-'+config.uid).activate();
			}
		},{
			text: _('clichethumbnail.btn_add_photo')
			,cls: 'green'
			,iconCls: 'icon-add-white'
			,handler: this.onaddPhoto
			,scope: this
		}]
	});
	Cliche.ThumbnailAlbumPanel.superclass.constructor.call(this,config);
};
Ext.extend(Cliche.ThumbnailAlbumPanel,MODx.panel.ClicheAlbumPanel,{
	_loadView: function(){
		this.ident = 'cliche-album-ident-'+this.uid;
		this.view = MODx.load({
			id: 'cliche-album-view-'+this.uid
			,xtype: 'clichethumbnail-album-view'
			,uid: this.uid
			,containerScroll: true
			,ident: this.ident
			,border: false
		});
	}	
	
	,_descTpl: function(){
		return '<div class="details">'
			+'<tpl for=".">'
				+'<div class="selected">'
					+'<a href="{image}" title="Album {name} preview" alt="'+_('clichethumbnail.image_cover_alt_msg')+'" class="lightbox" />'
						+'<img src="{image}" alt="{name}" />'
					+'</a>'
					+'<h5>{name}</h5>'
					+'<button class="inline-button green" onclick="Ext.getCmp(\'clichethumbnail-main-{tv}\').updateThumbnail(\'{id}\'); return false;"/>'+_('clichethumbnail.btn_select_image')+'</button>'
				+'</div>'
				+'<div class="description">'
					+'<h4>'+_('clichethumbnail.image_desc_title')+'</h4>'
					+'{description:defaultValue("'+_('clichethumbnail.image_no_desc')+'")}'						
				+'</div>'
				+'<div class="infos">'
					+'<h4>'+_('clichethumbnail.image_informations_title')+'</h4>'
					+'<ul>'
						+'<li>'
							+'<span class="infoname">ID:</span>'
							+'<span class="infovalue">#{id}</span>'
						+'</li>'
						+'<li>'
							+'<span class="infoname">'+_('clichethumbnail.image_created_by')+':</span>'
							+'<span class="infovalue">{createdby}</span>'
						+'</li>'
						+'<li>'
							+'<span class="infoname">'+_('clichethumbnail.image_created_on')+':</span>'
							+'<span class="infovalue">{createdon}</span>'
						+'</li>'
					+'</ul>'
				+'</div>'
			+'</tpl>'
		+'</div>'
	}
			
	,activate: function(rec){
		if(!this.issettv){
			Ext.getCmp(this.view.id).store.setBaseParam('tv', this.tv);
			this.issettv = true;
		}
		this.view.run();
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
	}

	,updateBreadcrumbs: function(msg){
		var bd = {};
		if(msg != undefined){ bd.text = msg }
		bd.trail = [{
			text : _('clichethumbnail.breadcrumb_album')
		}];
		Ext.getCmp(this.breadcrumbs).updateDetail(bd);
	}
	
	,setRecord: function(data){
		this.album = data;
		this.updateBreadcrumbs();
	}
		
	,onaddPhoto: function(){
		Ext.getCmp(this.uploadCard).activate(this.album);
	}
});
Ext.reg('clichethumbnail-album-panel',Cliche.ThumbnailAlbumPanel);
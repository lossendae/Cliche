/**
 * The view panel for the default album type
 *
 * @class MODx.ClicheAlbumView
 * @extends MODx.DataView
 * @param {Object} config An object of options.
 * @xtype cliche-album-view
 */
MODx.ClicheAlbumView = function(config) {
    config = config || {};
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.ClicheConnectorUrl
        ,fields: ['id','name','description','createdon','createdby','protected','album_id','image','thumbnail','phpthumb','metas','tv']
        ,baseParams: {
            action: 'clichethumbnail/thumb/getlist'
			,ctx: 'mgr'
			,limit: 8
			,start: 0
        }
        ,tpl: this.templates.thumb
        ,prepareData: this.formatData.createDelegate(this)
		,overClass:'x-view-over'
		,selectedClass:'selected'
		,itemSelector: 'div.thumb-wrapper'
		,loadingText : '<div class="empty-msg"><h4>'+_('cliche.loading')+'</h4></div>'
		,emptyText : '<div class="empty-msg"><h4>'+_('cliche.items_empty_msg')+'</h4></div>'
    });
    MODx.ClicheAlbumView.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
    this.store.on('load',this.onStoreLoad,this);
};
Ext.extend(MODx.ClicheAlbumView,MODx.DataView,{
    templates: {}

    ,run: function(p) {
        var v = {};
        Ext.applyIf(v,this.store.baseParams);
        Ext.applyIf(v,p);
        this.store.load({
            params: v
			/* Fix layout after the store's loaded */
			,callback: function(rec, options, success){
				setTimeout(function(){
					Ext.getCmp('modx-content').doLayout();
				}, 500);
			}
        });
    }

    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
			//Show set as cover button if necessary
			var album = this.ownerCt.ownerCt.album;				
			data.is_cover = (data.id == album.cover_id) ? true : false;
			//Un peu alambiqué comme solution
            if (data) { Ext.getCmp(this.ownerCt.ownerCt.itemDetailId).updateDetail(data); }
        }
    }

    ,formatData: function(data) {
        data.shortName = Ext.util.Format.ellipsis(data.name, 12);
        this.lookup['cliche-album-item-thumb-'+data.id] = data;
        return data;
    }

    ,_initTemplates: function() {
		this.templates.thumb = new Ext.XTemplate('<tpl for=".">'
			+'<div class="thumb-wrapper" id="cliche-album-item-thumb-{id}">'
				+'<div class="thumb">'
					+'<img src="{thumbnail}" title="{name}" alt="{name}" />'
				+'</div>'
			+'</div>'
		+'</tpl>', {
			compiled: true
		});
		this.templates.album_desc = new Ext.XTemplate( '<tpl for=".">'+_('cliche.breadcrumbs_album_msg')+'</tpl>', {
			compiled: true
		});	
    }
	
	,onStoreLoad: function( ds, rec, options ){
		var album = ds.reader.jsonData.album;
		this.ownerCt.ownerCt.setRecord(album);
	}
});
Ext.reg('cliche-album-view',MODx.ClicheAlbumView);

/**
 * The album panel
 *
 * @class MODx.panel.ClicheAlbum
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-album-default
 */
MODx.panel.ClicheAlbum = function(config) {
    config = config || {};
	this.ident = 'cliche-album-default-'+Ext.id();
	this.view = MODx.load({
        id: 'cliche-album-view-'+Ext.id()
		,xtype: 'cliche-album-view'
		,containerScroll: true
		,ident: this.ident
		,border: false
    });

	Ext.applyIf(config,{
		bodyCssClass: 'body-wrapper'
		,cls: 'main-wrapper modx-template-detail'
		,bodyCssClass: 'body-wrapper'
		,layout: 'column'
		,border: false
		,autoHeight: true
		,border: false
		,autoHeight: true
		,issettv: false
		,itemDetailId: 'cliche-album-item-details-'+config.tv
		,items:[{
			items: this.view
			,border: false
			,bbar: new Ext.PagingToolbar({
				pageSize: 12
				,store: this.view.store
				,displayInfo: true
				,autoLoad: true
			})
			,columnWidth: 1
		},{
			xtype: 'modx-template-panel'
			,id: 'cliche-album-item-details-'+config.tv
			,cls: 'aside-details'
			,width: 250
			,startingText: _('cliche.album-empty-col-msg')
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<div class="selected">'
						+'<a href="{image}" title="Album {name} preview" alt="'+_('cliche.album_cover_alt_msg')+'" class="lightbox" />'
							+'<img src="{image}" alt="{name}" />'
						+'</a>'
						+'<h5>{name}</h5>'
						+'<button class="inline-button green" onclick="Ext.getCmp(\'cliche-main-{tv}\').updateThumbnail(\'{id}\'); return false;"/>'+_('cliche.thumb_select_btn')+'</button>'
					+'</div>'
					+'<div class="description">'
						+'<h4>'+_('cliche.desc_title')+'</h4>'
						+'{description:defaultValue("'+_('cliche.no_desc')+'")}'						
					+'</div>'
					+'<div class="infos">'
						+'<h4>'+_('cliche.informations_title')+'</h4>'
						+'<ul>'
							+'<li>'
								+'<span class="infoname">ID:</span>'
								+'<span class="infovalue">#{id}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('cliche.created_by')+':</span>'
								+'<span class="infovalue">{createdby}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('cliche.created_on')+':</span>'
								+'<span class="infovalue">{createdon}</span>'
							+'</li>'
						+'</ul>'
					+'</div>'
				+'</tpl>'
			+'</div>'
		}]
		,tbar:[{
			xtype: 'button'
			,text: _('cliche.back_to_album_list')
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp('cliche-main-'+config.tv).activate();
			}
		},'->',{
			text: _('cliche.add_photo')
			,cls: 'green'
			,iconCls: 'icon-add-white'
			,handler: this.onaddPhoto
			,scope: this
		}]
	});
	MODx.panel.ClicheAlbum.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ClicheAlbum,MODx.Panel,{
	activate: function(rec){
		if(!this.issettv){
			Ext.getCmp(this.view.id).store.setBaseParam('tv', this.tv);
			this.issettv = true;
		}
		this.view.run();
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
		this.updateBreadcrumbs('Choose an image from the album to use as thumbnail');
	}

	,updateBreadcrumbs: function(msg){
		var bd = { text: msg };
		bd.trail = [{
			text : 'Album browser'
		}];
		Ext.getCmp(this.breadcrumbs).updateDetail(bd);
	}
	
	,selectImage: function(){
		var selNode = this.view.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.view.lookup[selNode.id];
			if (data){ }
		}
	}
	
	,setRecord: function(data){
		this.album = data;
	}
	
	,onaddPhoto: function(){
		Ext.getCmp(this.uploadCard).activate(this.album);
	}
});
Ext.reg('cliche-thumb-album-view',MODx.panel.ClicheAlbum);
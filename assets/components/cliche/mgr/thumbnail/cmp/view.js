/**
 * The view panel for the default album type
 *
 * @class MODx.ClicheThumbnailAlbumView
 * @extends MODx.DataView
 * @param {Object} config An object of options.
 * @xtype clichethumbnail-album-view
 */
MODx.ClicheThumbnailAlbumView = function(config) {
    config = config || {};
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.ClicheConnectorUrl
        ,fields: ['id','name','description','createdon','createdby','protected','album_id','image','thumbnail','phpthumb','metas']
        ,baseParams: {
            action: 'album/getList'
			,ctx: 'mgr'
			,limit: 12
			,start: 0
        }
        ,tpl: this.templates.thumb
        ,prepareData: this.formatData.createDelegate(this)
		,overClass:'x-view-over'
		,selectedClass:'selected'
		,itemSelector: 'div.thumb-wrapper'
		,loadingText : '<div class="empty-msg"><h4>'+_('cliche.loading')+'</h4></div>'
		,emptyText : '<div class="empty-msg"><h4>'+_('cliche.album_item_empty_msg')+'</h4></div>'
    });
    MODx.ClicheThumbnailAlbumView.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
    this.store.on('load',this.onStoreLoad,this);
};
Ext.extend(MODx.ClicheThumbnailAlbumView,MODx.DataView,{
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
			var album = Ext.getCmp('cliche-album-clichethumbnail').album;			
			data.is_cover = (data.id == album.cover_id) ? true : false;
            if (data) { Ext.getCmp('clichethumbnail-album-item-details').updateDetail(data); }
        }
    }

    ,formatData: function(data) {
        this.lookup['cliche-album-item-thumb-'+data.id] = data;
        return data;
    }

    ,_initTemplates: function() {
		this.templates.thumb = new Ext.XTemplate('<tpl for=".">'
			+'<div class="thumb-wrapper" id="cliche-album-item-thumb-{id}">'
				+'<div class="thumb">'
					+'<img src="{thumbnail}" title="{name}" alt="{name}" />'
				+'</div>'
				+'<span class="image-name">{name}</span>'
			+'</div>'
		+'</tpl>'
		+'<div class="clear"></div>', {
			compiled: true
		});
		this.templates.album_desc = new Ext.XTemplate( '<tpl for=".">'+_('cliche.album_desc')+'</tpl>', {
			compiled: true
		});	
    }
	
	,onStoreLoad: function( ds, rec, options ){}
});
Ext.reg('clichethumbnail-album-view',MODx.ClicheThumbnailAlbumView);

/**
 * The package browser detail panel
 *
 * @class MODx.panel.ClicheAlbumThumbnail
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-album-clichethumbnail
 */
MODx.panel.ClicheAlbumThumbnail = function(config) {
    config = config || {};
	this.ident = 'cliche-album-clichethumbnail-'+Ext.id();
	this.view = MODx.load({
        id: 'clichethumbnail-album-view'
		,xtype: 'clichethumbnail-album-view'
		,containerScroll: true
		,ident: this.ident
		,border: false
		,plugins : new MODx.clicheSortableDataView()
    });

	Ext.applyIf(config,{
		id: 'cliche-album-clichethumbnail'
		,cls: 'main-wrapper modx-template-detail'
		,bodyCssClass: 'body-wrapper'
		,layout: 'column'
		,tbar: [{
			xtype: 'button'
			,text: _('cliche.btn_back_to_album_list')
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp('album-list').activate();
			}			
		// },{
            // text: _('cliche.btn_options')
			// ,iconCls:'icon-options'			
			// ,menu: {
				// cls: 'custom-menu'
				// ,items: [{
					// text: _('cliche.btn_update_album')
					// ,id:'update-album-clichethumbnail'
					// ,iconCls:'icon-edit'
					// ,handler: this.onUpdateAlbum
					// ,scope: this
				// },{
					// text: 'Save new order'
					// ,id: 'reorder-album-clichethumbnail'
					// ,handler: this.onReorderAlbum
					// ,scope: this
				// }]
			// }
		// },'-',{
			// xtype: 'trigger'
			// ,id: 'album-searchfield-clichethumbnail'
			// ,ctCls: 'customsearchfield'
			// ,emptyText: 'Search...'
			// ,onTriggerClick: function(){
				// this.reset();	
				// this.fireEvent('click');				
			// }
			// ,listeners: {
				// specialkey: function(field, e){
                    // if (e.getKey() == e.ENTER) {
						// this.view.getStore().setBaseParam('query',field.getValue());
						// this.view.getStore().load();
                    // }
                // }
				// ,click: function(trigger){
					// this.view.getStore().setBaseParam('query','');
					// this.view.getStore().load();
				// }
				// ,scope: this
			// }
		// },'-',{
			// text: _('cliche.btn_add_photo')
			// ,cls: 'green'
			// ,iconCls: 'icon-add-white'
			// ,handler: this.onaddPhoto
			// ,scope: this
		}]
		,border: false
		,autoHeight: true
		,border: false
		,autoHeight: true
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
			,id: 'clichethumbnail-album-item-details'
			,cls: 'aside-details'
			,width: 250
			,startingText: _('cliche.album_empty_col_msg')
			,markup: '<div class="details">'
				+'<tpl for=".">'
					+'<div class="selected">'
						+'<a href="{image}" title="Album {name} preview" alt="'+_('cliche.album_item_cover_alt_msg')+'" class="lightbox" />'
							+'<img src="{image}" alt="{name}" />'
						+'</a>'
						+'<h5>{name}</h5>'
						+'<ul class="splitbuttons">'
							+'<li class="inline-button edit"><button ext:qtip="'+_('cliche.btn_edit_image')+'" ext:trackMouse=true ext:anchorToTarget=false" onclick="Ext.getCmp(\'cliche-album-clichethumbnail\').editImage(\'{id}\'); return false;">'+_('cliche.btn_edit_image')+'</button></li>'
							+'<tpl if="!is_cover">'								
								+'<li class="inline-button set-as-cover"><button ext:qtip="'+_('cliche.btn_set_as_album_cover')+'" ext:trackMouse=true ext:anchorToTarget=false" onclick="Ext.getCmp(\'cliche-album-clichethumbnail\').setAsCover(\'{id}\'); return false;">'+_('cliche.btn_set_as_album_cover')+'</button></li>'
							+'</tpl>'
							+'<li class="inline-button delete"><button ext:qtip="'+_('cliche.btn_delete_image')+'" ext:trackMouse=true ext:anchorToTarget=false" onclick="Ext.getCmp(\'cliche-album-clichethumbnail\').deleteImage(\'{id}\'); return false;">'+_('cliche.btn_delete_image')+'</button></li>'
						+'</ul>'
					+'</div>'
					+'<div class="description">'
						+'<h4>'+_('cliche.album_item_desc_title')+'</h4>'
						+'{description:defaultValue("'+_('cliche.no_desc')+'")}'						
					+'</div>'
					+'<div class="infos">'
						+'<h4>'+_('cliche.album_item_informations_title')+'</h4>'
						+'<ul>'
							+'<li>'
								+'<span class="infoname">'+_('cliche.album_item_id')+':</span>'
								+'<span class="infovalue">#{id}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('cliche.album_item_created_by')+':</span>'
								+'<span class="infovalue">{createdby}</span>'
							+'</li>'
							+'<li>'
								+'<span class="infoname">'+_('cliche.album_item_created_on')+':</span>'
								+'<span class="infovalue">{createdon}</span>'
							+'</li>'
						+'</ul>'
					+'</div>'
				+'</tpl>'
			+'</div>'
		}]
	});
	MODx.panel.ClicheAlbumThumbnail.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ClicheAlbumThumbnail,MODx.Panel,{
	activate: function(rec){
		if(rec != undefined){
			this.album = rec;
		}		
		this.view.store.setBaseParam('album', this.album.id);
		this.view.run();
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		Ext.getCmp('clichethumbnail-album-item-details').reset();
		var msg = Ext.getCmp('clichethumbnail-album-view').templates.album_desc.apply(this.album);
		this.updateBreadcrumbs(msg);
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : this.album.name
		}];
		Ext.getCmp('cliche-breadcrumbs').updateDetail(bd);
	}
	
	,onaddPhoto: function(){
		Ext.getCmp('default-uploader').activate(this.album);
	}
	
	,editImage: function(){
		var selNode = this.view.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.view.lookup[selNode.id];
			//Show set as cover button if necessary
            if (data){ 
				if(!this.win){			
					this.win = new MODx.window.ClicheImageWindow();
				}
				this.win.show(this.id);	
				var pos = this.win.getPosition(true);
				this.win.setPosition(pos[0], 35);
				this.win.reset(this.id);
				this.win.load(data);
			}
        }
	}
	
	,deleteImage: function(id){
		MODx.msg.confirm({
			title: _('cliche.window_delete_image')
			,text: _('cliche.window_delete_image_msg')
			,url: MODx.ClicheConnectorUrl			   
			,params: {
				action: 'image/delete'
				,id: id
				,ctx: 'mgr'
			}
			,listeners: {
				'success':{fn:function(r) {
					this.activate(r.data);
				},scope:this}
			}
			,animEl: this.id
        });
	}
});
Ext.reg('cliche-album-clichethumbnail',MODx.panel.ClicheAlbumThumbnail);
/**
 * The view panel base class for a single album
 *
 * @class MODx.ClicheAlbumViewPanel
 * @extends MODx.DataView
 * @param {Object} config An object of options.
 * @xtype cliche-album-view
 */
MODx.ClicheAlbumViewPanel = function(config) {
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
    MODx.ClicheAlbumViewPanel.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
    this.store.on('load',this.onStoreLoad,this);
};
Ext.extend(MODx.ClicheAlbumViewPanel,MODx.DataView,{
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
			var album = Ext.getCmp('cliche-album-'+this.uid).album;			
			data.is_cover = (data.id == album.cover_id) ? true : false;
            if (data) { Ext.getCmp('cliche-album-item-details-'+this.uid).updateDetail(data); }
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
					+'<span class="img-loading-mask">&nbsp;</span>'
				+'</div>'
				+'<span class="image-name">{name}</span>'
			+'</div>'
		+'</tpl>'
		+'<div class="clear"></div>', {
			compiled: true
		});
    }
	
	,onStoreLoad: function( ds, rec, options ){	
		var owner = this.ownerCt.ownerCt;
		if(owner.isVisible()){
			this.beautify();
			var album = ds.reader.jsonData.album;
			if(album !== null){
				this.ownerCt.ownerCt.setRecord(album);
			}
		}				
	}
	
	,beautify: function(){
		var container = Ext.fly('cliche-album-view-'+this.uid);					
		var uid = this.uid;					
		if( container !== null ){
			if(container.hasClass('loaded')){
				container.removeClass('loaded');
			}
			var images = container.select('img');			
			var count = images.getCount();
			images.on('load', function(e){
				count--; 			
				if(count == 0){ 
					setTimeout(function(){
						Ext.fly('cliche-album-view-'+uid).addClass('loaded');
					}, 500);					
				}
				/* Hide the loading spinner */
				var loader = e.getTarget().parentElement.lastChild;
				Ext.get(loader).fadeOut();					
			});
			/* Set all thumb wrappers to the height of the collection's tallest item */
			var wrapper = container.query('.thumb-wrapper');
			var currentTallest = 0;
			Ext.each(wrapper, function(v){
				var current = Ext.fly(v);
				if (current.getHeight() > currentTallest) { currentTallest = current.getHeight(); }
			});
			Ext.each(wrapper, function(v){
				var itm = Ext.fly(v);
				itm.setHeight(currentTallest);
			});
		}		
	}
});
Ext.reg('cliche-album-view',MODx.ClicheAlbumViewPanel);

/**
 * The package browser detail panel
 *
 * @class MODx.panel.ClicheAlbumPanel
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-album-panel
 */
MODx.panel.ClicheAlbumPanel = function(config) {
    config = config || {};
	if(typeof(config.uid) == 'undefined'){ config.uid = 'default' }
	Ext.applyIf(config,{
		id: 'cliche-album-'+config.uid
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
		},'-','-',{
			xtype: 'trigger'
			,id: 'album-searchfield-'+config.uid
			,ctCls: 'customsearchfield'
			,emptyText: 'Search...'
			,onTriggerClick: function(){
				this.reset();	
				this.fireEvent('click');				
			}
			,listeners: {
				specialkey: function(field, e){
                    if (e.getKey() == e.ENTER) {
						this.view.getStore().setBaseParam('query',field.getValue());
						this.view.getStore().load();
                    }
                }
				,click: function(trigger){
					this.view.getStore().setBaseParam('query','');
					this.view.getStore().load();
				}
				,scope: this
			}
		},{
            text: _('cliche.btn_options')
			,iconCls:'icon-options'			
			,menu: {
				cls: 'custom-menu'
				,items: [{
					text: _('cliche.btn_update_album')
					,id:'update-album-'+config.uid
					,iconCls:'icon-edit'
					,handler: this.onUpdateAlbum
					,scope: this
				},{
					text: _('cliche.btn_delete_album')
					,id:'delete-album-'+config.uid
					,iconCls:'icon-delete-album'
					,handler: this.onDeleteAlbum
					,scope: this				
				},{
					text: 'Save new order'
					,id: 'reorder-album-'+config.uid
					,handler: this.onReorderAlbum
					,scope: this
				}]
			}
		},'-',{
			text: _('cliche.btn_add_photo')
			,cls: 'green'
			,iconCls: 'icon-add-white'
			,handler: this.onaddPhoto
			,scope: this
		}]
		,border: false
		,autoHeight: true
		,border: false
		,autoHeight: true
		,items: []
	});
	MODx.panel.ClicheAlbumPanel.superclass.constructor.call(this,config);
	this._loadView();
	this._init();
	this._initDescTpl();
};
Ext.extend(MODx.panel.ClicheAlbumPanel,MODx.Panel,{
	_init: function(){		
		this.add({
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
			,id: 'cliche-album-item-details-'+this.uid
			,cls: 'aside-details'
			,width: 230
			,startingText: _('cliche.album_empty_col_msg')
			,markup: this._descTpl()
		});			
	}
	
	,_initDescTpl: function(){
		this.albumDescTpl = new Ext.XTemplate( '<tpl for=".">'+_('cliche.album_desc')+'</tpl>', {
			compiled: true
		});
	}
	
	,_loadView: function(){
		this.ident = 'cliche-album-ident-'+this.uid;
		this.view = MODx.load({
			id: 'cliche-album-view-'+this.uid
			,xtype: 'cliche-album-view'
			,container: this.id
			,uid: this.uid
			,containerScroll: true
			,ident: this.ident
			,border: false
			,plugins : new MODx.clicheSortableDataView()
		});
	}	
	
	,_descTpl: function(){
		return '<div class="details">'
			+'<tpl for=".">'
				+'<div class="selected">'
					+'<a href="{image}" title="Album {name} preview" alt="'+_('cliche.album_item_cover_alt_msg')+'" class="lightbox" />'
						+'<img src="{image}" alt="{name}" />'
					+'</a>'
					+'<h5>{name}</h5>'
					+'<ul class="splitbuttons">'
						+'<li class="inline-button edit"><button ext:qtip="'+_('cliche.btn_edit_image')+'" ext:trackMouse=true ext:anchorToTarget=false" onclick="Ext.getCmp(\'cliche-album-default\').editImage(\'{id}\'); return false;">'+_('cliche.btn_edit_image')+'</button></li>'
						+'<tpl if="!is_cover">'								
							+'<li class="inline-button set-as-cover"><button ext:qtip="'+_('cliche.btn_set_as_album_cover')+'" ext:trackMouse=true ext:anchorToTarget=false" onclick="Ext.getCmp(\'cliche-album-default\').setAsCover(\'{id}\'); return false;">'+_('cliche.btn_set_as_album_cover')+'</button></li>'
						+'</tpl>'
						+'<li class="inline-button delete"><button ext:qtip="'+_('cliche.btn_delete_image')+'" ext:trackMouse=true ext:anchorToTarget=false" onclick="Ext.getCmp(\'cliche-album-default\').deleteImage(\'{id}\'); return false;">'+_('cliche.btn_delete_image')+'</button></li>'
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
		+'</div>';
	}
	
	,activate: function(rec){		
		if(rec !== undefined){
			this.album = rec;
		}		
		this.view.store.setBaseParam('album', this.album.id);
		this.view.run();
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		Ext.getCmp('cliche-album-item-details-'+this.uid).reset();
	}
	

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : this.album.name
		}];
		Ext.getCmp('cliche-breadcrumbs').updateDetail(bd);
	}	
		
	,setRecord: function(data){
		this.album = data;
		var msg = this.albumDescTpl.apply(this.album);
		this.updateBreadcrumbs(msg);
	}
	
	,onaddPhoto: function(){
		Ext.getCmp('cliche-uploader-'+this.uid).activate(this.album);
	}
	
	,onUpdateAlbum: function(btn, e){
		Ext.getCmp('cliche-main-panel').loadCreateUpdateWindow(_('cliche.window_update_album'), 'update', btn, this.id, this.album);	
	}
	
	,onDeleteAlbum: function(btn, e){
		MODx.msg.confirm({
			title: _('cliche.window_delete_album')
			,text: _('cliche.window_delete_album_msg')
			,url: MODx.ClicheConnectorUrl			   
			,params: {
				action: 'album/delete'
				,id: this.album.id
				,ctx: 'mgr'
			}
			,listeners: {
				'success':{fn:function(r) {
					Ext.getCmp('album-list').activate();
				},scope:this}
			}
			,animEl: btn.id
        });
	}
	
	,editImage: function(){
		var selNode = this.view.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.view.lookup[selNode.id];
			//Show set as cover button if necessary
            if (data){ 
				if(!this.win){			
					this.win = new MODx.window.ClicheImageEditWindow({ uid: this.uid });
				}
				this.win.show(this.id);	
				var pos = this.win.getPosition(true);
				this.win.setPosition(pos[0], 35);
				this.win.reset(this.id);
				this.win.load(data);
			}
        }
	}
	
	,setAsCover: function(id){
		MODx.msg.confirm({
			title: _('cliche.window_set_as_album_cover')
			,text: _('cliche.window_set_as_album_cover_msg')
			,url: MODx.ClicheConnectorUrl			   
			,params: {
				action: 'image/setascover'
				,id: id
				,album: this.album.id
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
	
	,onReorderAlbum: function(){
		var ids = this.view.getStore().collect('id');
		var start = this.view.getStore().lastOptions.params.start;
		var limit = this.view.getStore().lastOptions.params.limit;
		MODx.msg.confirm({
			title: 'Reorder album'
			,text: 'Are sure you want to reorder this album item\'s ?'
			,url: MODx.ClicheConnectorUrl			   
			,params: {
				action: 'album/reorder'
				,ctx: 'mgr'
				,ids: ids.join(',')
				,start: start
				,limit: limit
				,album: this.album.id
			}
			,listeners: {
				'success':{fn:function(r) {
					this.view.getStore().removeAll();
					this.activate(r.data);
				},scope:this}
			}
			,animEl: this.id
        });
	}
});
Ext.reg('cliche-album-panel',MODx.panel.ClicheAlbumPanel);
/**
 * The view panel for the albums list
 *
 * @class MODx.ClicheDefaultAlbumView
 * @extends MODx.DataView
 * @param {Object} config An object of options.
 * @xtype cliche-default-album-view
 */
MODx.ClicheDefaultAlbumView = function(config) {
    config = config || {};

    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.ClicheConnectorUrl
        ,fields: ['id','name','description','createdon','createdby','protected','album_id','image','thumbnail','phpthumb','metas']
        ,baseParams: {
            action: 'set/getList'
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
		,emptyText : '<div class="empty-msg"><h4>'+_('cliche.items_empty_msg')+'</h4></div>'
    });
    MODx.ClicheDefaultAlbumView.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
    this.store.on('load',this.onStoreLoad,this);
};
Ext.extend(MODx.ClicheDefaultAlbumView,MODx.DataView,{
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
            if (data) { Ext.getCmp('cliche-album-item-details').updateDetail(data); }
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
				+'<span class="name">{shortName}</span>'
			+'</div>'
		+'</tpl>', {
			compiled: true
		});
		this.templates.album_desc = new Ext.XTemplate( '<tpl for=".">'+_('cliche.breadcrumbs_album_msg')+'</tpl>', {
			compiled: true
		});	
    }
	
	,onStoreLoad: function( ds, rec, options ){}
});
Ext.reg('cliche-default-album-view',MODx.ClicheDefaultAlbumView);

/**
 * The package browser detail panel
 *
 * @class MODx.panel.ClicheAlbumDefault
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-album-default
 */
MODx.panel.ClicheAlbumDefault = function(config) {
    config = config || {};
	this.ident = 'cliche-album-default-'+Ext.id();
	this.view = MODx.load({
        id: 'cliche-default-album-view'
		,xtype: 'cliche-default-album-view'
		,containerScroll: true
		,ident: this.ident
		,border: false
    });

	Ext.applyIf(config,{
		id: 'cliche-album-default'
		,cls: 'main-wrapper modx-template-detail'
		,bodyCssClass: 'body-wrapper'
		,layout: 'column'
		,tbar: [{
			xtype: 'button'
			,text: 'Back to albums list' //@TODO lexicon
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp('album-list').activate();
			}			
		},'-',{
            text: 'Options'
			,iconCls:'icon-options'			
			,menu: {
				cls: 'custom-menu'
				,items: [{
					text: _('cliche.update_album')
					,id:'update-album'
					,iconCls:'icon-edit'
					,handler: this.onUpdateAlbum
					,scope: this
				},{
					text: _('cliche.delete_album')
					,id:'delete-album'
					,iconCls:'icon-delete-album'
					,handler: this.onDeleteAlbum
					,scope: this				
				}]
			}
		}]
		,border: false
		,autoHeight: true
		,items:[{
			items: this.view
			,border: false
			,bbar: new Ext.PagingToolbar({
				pageSize: 20
				,store: this.view.store
				,displayInfo: true
				,autoLoad: true
			})
		}]
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
			,id: 'cliche-album-item-details'
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
						+'<button class="inline-button" onclick="Ext.getCmp(\'modx-package-browser-view\').activate(\'{id}\'); return false;"/>'+_('cliche.edit_item')+'</button>'
					+'</div>'
					+'<div class="description">'
						+'<h4>'+_('cliche.desc_title')+'</h4>'
						+'{description:defaultValue("'+_('cliche.no_desc')+'")}'						
					+'</div>'
					+'<div class="infos">'
						+'<h4>'+_('cliche.informations_title')+'</h4>'
						+'<ul>'
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
	});
	MODx.panel.ClicheAlbumDefault.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ClicheAlbumDefault,MODx.Panel,{
	activate: function(rec){
		if(rec != undefined){
			this.album = rec;
			this.view.store.setBaseParam('album', rec.id);
		}
		this.view.run();
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		Ext.getCmp('cliche-album-item-details').reset();
		var msg = Ext.getCmp('cliche-default-album-view').templates.album_desc.apply(this.album);
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
	
	,onUpdateAlbum: function(btn, e){
		Ext.getCmp('cliche-main-panel').loadCreateUpdateWindow('Udpate Album', 'update', btn, this.id, this.album);	
	}
	
	,onDeleteAlbum: function(btn, e){
		MODx.msg.confirm({
			title: 'Remove album'
			,text: _('cliche.delete_album_msg')
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
});
Ext.reg('cliche-album-default',MODx.panel.ClicheAlbumDefault);
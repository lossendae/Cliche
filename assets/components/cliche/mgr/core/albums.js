/**
 * The view panel for the albums list
 *
 * @class MODx.ClicheAlbumsListView
 * @extends MODx.DataView
 * @param {Object} config An object of options.
 * @xtype cliche-albums-list-view
 */
MODx.ClicheAlbumsListView = function(config) {
    config = config || {};
    this._initTemplates();
    Ext.applyIf(config,{
        url: MODx.ClicheConnectorUrl
        ,fields: ['id','name','description','createdon','createdby','protected','cover_id','image','thumbnail','total','type']
        ,baseParams: {
            action: 'albums/getList'
			,ctx: 'mgr'
			,limit: 20
			,start: 0
        }
        ,tpl: this.templates.thumb
        ,prepareData: this.formatData.createDelegate(this)
		,overClass:'x-view-over'
		,selectedClass:'selected'
		,itemSelector: 'div.thumb-wrapper'
		,loadingText : '<div class="empty-msg"><h4>'+_('cliche.loading')+'</h4></div>'
		,emptyText : '<div class="empty-msg"><h4>'+_('cliche.album_list_empty_msg')+'</h4></div>'
    });
    MODx.ClicheAlbumsListView.superclass.constructor.call(this,config);
    this.on('selectionchange',this.onSelect,this,{buffer: 100});
	this.store.on('load',this.onStoreLoad,this);
};
Ext.extend(MODx.ClicheAlbumsListView,MODx.DataView,{
    templates: {}
    ,run: function(p) {
        var v = {};
        Ext.applyIf(v,this.store.baseParams);
        Ext.applyIf(v,p);
        this.store.load({
            params: v
			/* Fix layout after the store's been loaded */
			,callback: function(rec, options, success){
				setTimeout(function(){
					Ext.getCmp('modx-content').doLayout();
				}, 500);
			}
        });
    }

    ,sortBy: function(sel) {
        this.store.baseParams.sorter = sel.getValue();
        this.run();
        return true;
    }

    ,sortDir: function(sel) {
        this.store.baseParams.dir = sel.getValue();
        this.run();
    }

    ,onSelect : function(){
		var selNode = this.getSelectedNodes();
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
        }
		if(data !== undefined){
			Ext.getCmp('cliche-album-' + data.type).activate(data);
		}		
    }

    ,formatData: function(data) {
        this.lookup['album-list-thumb-'+data.id] = data;
        return data;
    }

    ,_initTemplates: function() {
		this.templates.thumb = new Ext.XTemplate('<tpl for=".">'
			+'<div class="thumb-wrapper thumb-{type}" id="album-list-thumb-{id}">'
				+'<span class="type-{type}">&nbsp;</span>'
				+'<div class="thumb">'
					+'<tpl if="cover_id == 0">'
						+'<span class="no-preview">'+_('cliche.no_preview')+'</span>'
					+'</tpl>'
					+'<tpl if="cover_id">'
						+'<tpl if="thumbnail">'
							+'<img src="{thumbnail}" title="{name}" alt="{name}" />'
						+'</tpl>'
						+'<tpl if="!thumbnail">'
							+'<span class="no-preview error"><strong>Error</strong>Image not found</span>'
						+'</tpl>'
					+'</tpl>'
					+'<span class="img-loading-mask">&nbsp;</span>'
				+'</div>'
				+'<span class="name">{name}</span>'
				+'<span class="total-pics">'+_('cliche.album_list_total_pics')+'</span>'
			+'</div>'
		+'</tpl>', {
			compiled: true
		});
    }
	
	,_loadStore: function(config) {
        this.store = new Ext.data.JsonStore({
            url: config.url
            ,baseParams: config.baseParams || { 
                action: 'getList'
                ,prependPath: config.prependPath || null
                ,prependUrl: config.prependUrl || null
                ,wctx: config.wctx || MODx.ctx
                ,dir: config.openTo || ''
                ,basePath: config.basePath || ''
                ,basePathRelative: config.basePathRelative || null
                ,baseUrl: config.baseUrl || ''
                ,baseUrlRelative: config.baseUrlRelative || null
            }
            ,root: config.root || 'results'
            ,fields: config.fields
            ,totalProperty: 'total'
			,listeners: {
                'load': {fn:function(){ /* do nothing */ }, scope:this, single:true}
            }
        });
        this.store.load();
    }
		
	,onStoreLoad: function( ds, rec, options ){		
		var container = Ext.fly('cliche-albums-list-view-'+this.uid);					
		var uid = this.uid;					
		if( container !== null ){
			if(container.hasClass('loaded')){
				container.removeClass('loaded');
			}
			var images = container.select('img');			
			var count = images.getCount();
			if(count == 0){ 
				container.addClass('loaded');					
			}
			images.on('load', function(e){
				count--; 			
				if(count == 0){ 
					setTimeout(function(){
						Ext.fly('cliche-albums-list-view-'+uid).addClass('loaded');
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
Ext.reg('cliche-albums-list-view',MODx.ClicheAlbumsListView);

/**
 * The package browser detail panel
 *
 * @class MODx.panel.ClicheAlbumsList
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-albums-list
 */
MODx.panel.ClicheAlbumsList = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		xtype: 'panel'
		,cls: 'main-wrapper'
		,bodyCssClass: 'body-wrapper'
		,layout: 'form'
		,tbar: [{
			xtype: 'button'
			,text: _('cliche.btn_add_album')
			,iconCls:'icon-add'
			,id: 'create-album-btn'
			,handler: this.loadWindow
			,scope: this
		}
		/* Not active in first bêta - Wait for the first TV */
		, '-', {
        // , '<span class="customsearchfield desc">Viewing</span>' ,{
			// text: 'Default'
			// ,id: 'cliche-filter-album-type'
            // ,param: 'type'
			// ,activeItem: 0
			// ,menu: {
				// plain: true
				// ,defaults: {
					// handler: this.onItemClick
					// ,scope: this
				// }
				// ,items: [{
					// text: 'Default'
                    // ,filter: 'default'
				// },{
					// text: 'Cliche Thumbnail TV'
                    // ,filter: 'clichethumbnail'
				// }]
			// }
        // }
		// , '	','Albums Sorted by : '
		// ,{
            // text: 'Creation date'
			// ,id: 'cliche-filter-album'
            // ,param: 'sortby'
			// ,activeItem: 0
			// ,menu: {
				// plain: true
				// ,defaults: {
					// handler: this.onSortByItemClick
					// ,scope: this
				// }
				// ,items: [{
					// text: 'Name'
                    // ,filter: 'name'
				// },{
					// text: 'Creation date'
                    // ,filter: 'createdon'
				// },{
					// text: 'Author'
                    // ,filter: 'createdby'
				// }]
			// }
		// },'-',{
			xtype: 'trigger'
			,id: 'albums-searchfield'
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
		}]
		,border: false
		,autoHeight: true
		,items:[]
	});
	MODx.panel.ClicheAlbumsList.superclass.constructor.call(this,config);
	this._loadView();
	this._init();
};
Ext.extend(MODx.panel.ClicheAlbumsList,MODx.Panel,{
	_init: function(){		
		this.add({
			items: this.view
			,border: false
			,bbar: new Ext.PagingToolbar({
				pageSize: 20
				,store: this.view.store
				,displayInfo: true
				,autoLoad: true
			})
		});			
	}
	
	,_loadView: function(){
		this.ident = 'cliche-albums-list-ident-'+this.uid;		
		this.view = MODx.load({
			id: 'cliche-albums-list-view-'+this.uid
			,xtype: 'cliche-albums-list-view'
			,containerScroll: true
			,ident: this.ident
			,border: false
		});
	}	
	
	,activate: function(){
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		this.updateBreadcrumbs(_('cliche.breadcrumb_album_list_desc'));
		this.view.run();
	}

	,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-breadcrumbs').reset(msg);
	}
	
	,loadWindow: function(btn){
		Ext.getCmp('cliche-main-panel').loadCreateUpdateWindow(_('cliche.window_create_a_new_album'), 'create', btn, 'album-list');	
	}
});
Ext.reg('cliche-albums-list', MODx.panel.ClicheAlbumsList);
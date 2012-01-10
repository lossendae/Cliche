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
		,emptyText : '<div class="empty-msg"><h4>'+_('cliche.album-list.empty_msg')+'</h4></div>'
    });
    MODx.ClicheAlbumsListView.superclass.constructor.call(this,config);
    this.on('selectionchange',this.onSelect,this,{buffer: 100});
};
Ext.extend(MODx.ClicheAlbumsListView,MODx.DataView,{
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
		/* @TODO : this should be supplied in separate column for better abstraction */
		if(data != undefined){
			Ext.getCmp('cliche-album-' + data.type).activate(data);
		}		
    }

    ,formatData: function(data) {
        data.shortName = Ext.util.Format.ellipsis(data.name, 16);
        this.lookup['album-list-thumb-'+data.id] = data;
        return data;
    }

    ,_initTemplates: function() {
		this.templates.thumb = new Ext.XTemplate('<tpl for=".">'
			+'<div class="thumb-wrapper" id="album-list-thumb-{id}">'
				+'<div class="thumb">'
					+'<tpl if="cover_id.length == 0">'
							+'<span class="no-preview">'+_('cliche.no_preview')+'</span>'
					+'</tpl>'
					+'<tpl if="cover_id">'
						+'<img src="{thumbnail}" title="{name}" alt="{name}" />'
					+'</tpl>'
				+'</div>'
				+'<span class="name">{shortName}</span>'
				+'<span class="total_pics">'+_('cliche.main_total_pics')+'</span>'
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
	this.ident = config.ident || 'cliche-al-'+Ext.id();
	this.view = MODx.load({
        id: 'cliche-albums-list-view'
		,xtype: 'cliche-albums-list-view'
		,containerScroll: true
		,ident: this.ident
		,border: false
    });

	Ext.applyIf(config,{
		xtype: 'panel'
		,cls: 'main-wrapper'
		,bodyCssClass: 'body-wrapper'
		,layout: 'form'
		,tbar: [{
			xtype: 'button'
			,text: _('cliche.add_album_btn')
			,iconCls:'icon-add'
			,id: 'create-album-btn'
			,handler: this.loadWindow
			,scope: this
		}
		/* Not active in first bêta - Wait for the first TV */
		// , '->'
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
                    // ,filter: 'TV'
				// }]
			// }
        // }
		// , '	','Albums sorted by : '
		// ,{
            // text: 'Title'
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
		// }
		]
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
	});
	MODx.panel.ClicheAlbumsList.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ClicheAlbumsList,MODx.Panel,{
	activate: function(cat){
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		this.updateBreadcrumbs(_('cliche.breadcrumb_album_list_desc'));
		this.view.run();
	}

	,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-breadcrumbs').reset(msg);
	}
	
	,loadWindow: function(btn){
		Ext.getCmp('cliche-main-panel').loadCreateUpdateWindow('Create a new Album', 'create', btn, 'album-list');	
	}
});
Ext.reg('cliche-albums-list', MODx.panel.ClicheAlbumsList);
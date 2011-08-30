/**
 * @class MODx.ClicheAlbumsList
 * The card panel containg the packages list and readme tpl
 */
MODx.ClicheAlbumsList = Ext.extend(MODx.AbstractDataView, {
	fields: ['id','name','description','createdon','createdby','protected','cover_id','image','thumbnail','total']
	,ident: 'albumident'
	,viewId : 'album-list-items'
	,limit: 20
	,baseParams: { 
		action: 'album/getList'
		,ctx: 'mgr'
		,limit: 20
		,start: 0
	}
	,loadingText: _('cliche.loading')
	,emptyText: _('cliche.album-list.empty_msg')
	,mainTpl :	[
		'<tpl for=".">'
			,'<div class="thumb-wrapper" id="modx-album-list-thumb-{id}">'
				,'<div class="thumb">'
					,'<tpl if="cover_id == 0">'
							,'<span class="no-preview">'+_('cliche.no_preview')+'</span>'
					,'</tpl>'
					,'<tpl if="cover_id != 0">'
						,'<img src="{thumbnail}" title="{name}" alt="{name}" />'
					,'</tpl>'
				,'</div>'
				,'<span class="name">{shortName}</span>'           
				,'<span class="total_pics">'+_('cliche.main_total_pics')+'</span>'            
			,'</div>'
		,'</tpl>'
	]
	
	,run: function() {
		var view = this.viewId || 'view-items';
		Ext.getCmp(view).store.load({
			callback: function(rec, options, success){				
				setTimeout(function(){ 
					Ext.getCmp('modx-content').doLayout();
				}, 500);
			}
		});
    }
	
	,formatData: function(data) {
		data.shortName = Ext.util.Format.ellipsis(data.name, 12);
        this.lookup['modx-album-list-thumb-'+data.id] = data;
        return data;
    }
	
	,buildUI: function(config){		
		config.ui = [{
			text: _('cliche.add_album_btn')
			,id:'add-album-btn'
			,iconCls:'icon-add'
			,handler: this.onCreate
			,scope: this
		// }, '->' 
		// , 'View albums : ' 
		// ,{
			// xtype: 'combo'		
			// ,store: new Ext.data.ArrayStore({
				// fields: ['d', 'v']
				// ,data : [[1, 'Public']
					// ,[2, 'Private']
					// ,[3, 'Thumbnails']
					// ,[4, 'Attached to a resource']
				// ]
			// })
			// ,displayField:'v'
			// ,valueField:'d'
			// ,typeAhead: true
			// ,mode: 'local'
			// ,forceSelection: true
			// ,triggerAction: 'all'
			// ,selectOnFocus:true
			// ,autoSelect:true
			// ,listWidth: 'auto'
			// ,width: 125
			// ,grow: true
			// ,listeners:{
				// select: function(combo, record, index){
					// listWidth = combo.innerList.dom.offsetWidth;
					// combo.setWidth( listWidth + 32 );
				// }
				// ,beforerender: function(combo){
					// combo.setValue(combo.store.getAt(0).data.d); // Ligne finale
				// }
				// ,scope: this
			// }
		// }
		// , '	','Sort by : ' 
		// ,{
			// xtype: 'combo'		
			// ,store: new Ext.data.ArrayStore({
				// fields: ['d', 'v']
				// ,data : [[1, 'Creation date']
					// ,[2, 'Album title']
					// ,[3, 'Total pictures']
				// ]
			// })
			// ,displayField:'v'
			// ,valueField:'d'
			// ,typeAhead: true
			// ,mode: 'local'
			// ,forceSelection: true
			// ,triggerAction: 'all'
			// ,selectOnFocus:true
			// ,autoSelect:true
			// ,listWidth: 'auto'
			// ,width: 125
			// ,grow: true
			// ,listeners:{
				// select: function(combo, record, index){
					// listWidth = combo.innerList.dom.offsetWidth;
					// combo.setWidth( listWidth + 32 );
				// }
				// ,beforerender: function(combo){
					// combo.setValue(combo.store.getAt(0).data.d); // Ligne finale
				// }
				// ,scope: this
			// }
		}]
	}
	
	,onCreate: function(btn, e){
		Ext.getCmp('cliche-albums-mgr-container').loadEditWindwow('create', btn);
	}
	
	,onSelect: function(){
		rec = Ext.getCmp('album-list-items').getSelectedRecords()[0];
		Ext.getCmp('cliche-album-view').activate(rec);
	}
	
	,activate: function(){		
		this.run();
		Ext.getCmp('cliche-albums-mgr-container').setActiveItem(0);	
		Ext.getCmp('cliche-albums-desc').reset();
	}
});
Ext.reg('cliche-albums-list', MODx.ClicheAlbumsList);
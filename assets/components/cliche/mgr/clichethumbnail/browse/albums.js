/**
 * @class MODx.ClicheOtherAlbumsList
 * @extend MODx.AbstractDataView
 * The card panel containing the packages list and readme tpl
 */
MODx.ClicheOtherAlbumsList = Ext.extend(MODx.AbstractDataView, {
	fields: ['id','name','description','createdon','createdby','protected','cover_id','image','thumbnail','total']
	,ident: 'albumident-'+ this.tvId
	,viewId : 'album-list-items-'+ this.tvId
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
		config.ui = undefined
	}
	
	,onSelect: function(){
		rec = Ext.getCmp('album-list-items').getSelectedRecords()[0];
		Ext.getCmp('cliche-album-view-'+ this.tvId).activate(rec);
	}
	
	,activate: function(){		
		this.run();
        this.updateBreadcrumbs('Upload a picture to use as thumbnail for your document');
		Ext.getCmp('cliche-thumb-cards-'+this.tvId).getLayout().setActiveItem(1);
	}
    
    ,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-thumb-bd-'+this.tvId).updateDetail({
			text: msg
			,trail: [{
				text : 'Your thumbnail'
				,cmp: 'cliche-main-'+this.tvId
                ,className: 'last'
				,isLink: true
			},{
				text: 'Albums browser'
                ,isLink: false
				,className: 'active'	
			}]
		});
	}
});
Ext.reg('cliche-other-albums-list', MODx.ClicheOtherAlbumsList);
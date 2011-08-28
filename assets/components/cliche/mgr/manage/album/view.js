/**
 * @class MODx.ClicheAlbumView
 * The card panel containg the packages list and readme tpl
 */
MODx.ClicheAlbumView = Ext.extend(MODx.AbstractDataViewWithColumn, {
	showDetailOnSelect: false
	,fields: ['id','name','description','createdon','createdby','protected','album_id','image','thumbnail','phpthumb','metas']
	,ident: 'albumviewident'
	,viewId: 'view-mainpanel'
	,colid: 'view-detailpanel'
	,limit: 20
	,baseParams: { 
		action: 'image/getList'
		,ctx: 'mgr'
		,limit: 20
		,start: 0
	}
	,detailsStartingText : _('cliche.items_empty_msg')
	,loadingText: _('loading')
	,emptyText: _('cliche.items_empty_msg')
	,mainTpl :	[
		'<tpl for=".">'
			,'<div class="thumb-wrapper" id="modx-album-view-thumb-{id}">'
				,'<div class="thumb">'
					,'<img src="{thumbnail}" title="{name}" alt="{name}" />'
				,'</div>'
				,'<span class="name">{shortName}</span>'                                       
			,'</div>'
		,'</tpl>'
	]
	
	,detailTpl : [ 
		'<div class="details">'
			,'<tpl for=".">'				
				,'<div class="selected">'	
					,'<tpl if="cover_id == 0">'
							,'<span class="no-preview">'+_('cliche.no_preview')+'</span>'
					,'</tpl>'
					,'<tpl if="cover_id != 0">'
						,'<a href="{image}" title="Album {name} preview" alt="'+_('cliche.album_cover_alt_msg')+'" class="lightbox" />'
							,'<img src="{thumbnail}" alt="{name}" />'
						,'<a/>'
					,'</tpl>'
					,'<h5>{name}</h5>'
				,'</div>'
				,'<div class="description">'				
					,'<h4>'+_('cliche.desc_title')+'</h4>'
					,'{description:defaultValue("'+_('cliche.no_desc')+'")}'
				,'</div>'				
				,'<div class="infos">'
					,'<h4>'+_('cliche.informations_title')+'</h4>'
					,'<ul>'
						,'<li>'	
							,'<span class="infoname">'+_('cliche.created_by')+':</span>'	
							,'<span class="infovalue">{createdby}</span>'	
						,'</li>'
						,'<li>'	
							,'<span class="infoname">'+_('cliche.created_on')+':</span>'	
							,'<span class="infovalue">{createdon}</span>'	
						,'</li>'	
						,'<li>'	
							,'<span class="infoname">'+_('cliche.view_total_pics')+':</span>'	
							,'<span class="infovalue">{total}</span>'	
						,'</li>'		
					,'</ul>'					
				,'</div>'					
			,'</tpl>'
		,'</div>'
	]
	
	,buildUI: function(config){		
		config.ui = [{
			text: _('cliche.add_images')
			,id:'add-pictures'
			,iconCls:'icon-add'
			,handler: this.onaddPhoto
			,scope: this
		},{
			text: _('cliche.update_album')
			,id:'update-album'
			,iconCls:'icon-add'
			,handler: this.onUpdateAlbum
			,scope: this
		},{
			text: _('cliche.delete_album')
			,id:'delete-album'
			,iconCls:'icon-delete'
			,handler: this.onDeleteAlbum
			,scope: this
		}]
	}
	
	,onaddPhoto: function(btn, e){
		/* @TODO - Pass the entire album meta informations */
		Ext.getCmp('cliche-panel-uploader').activate(this.album);
	}
	
	,onUpdateAlbum: function(btn, e){
		Ext.getCmp('cliche-albums-mgr-container').loadEditWindwow('update', btn, this.album);
	}
	
	,onDeleteAlbum: function(btn, e){
		MODx.msg.confirm({
			title: 'Remove album'
			,text: _('cliche.delete_album_msg')
			,cls: 'custom-window'
			,url: this.url			   
			,params: {
				action: 'album/delete'
				,id: this.album.id
				,ctx: 'mgr'
			}
			,listeners: {
				'success':{fn:function(r) {
					//r.msg to album list + this.album.name
					Ext.getCmp('cliche-albums-list').activate();
				},scope:this}
			}
			,animEl: btn.id
        });
	}
	
	,run: function() {
		view = this.viewId || 'view-items';
		Ext.getCmp(view).store.load();
    }
	
	,showDetails : function(data){
		var detailEl = Ext.getCmp(this.colid);			
		detailEl.updateDetail(data);
		Ext.getCmp('modx-content').doLayout();
    }
	
	,getSelected: function(){
		var el = Ext.getCmp(this.viewId);
		return el.getSelectedRecords()[0].id;
	}
	
	,formatData: function(data) {
        data.shortName = Ext.util.Format.ellipsis(data.name, 12);
        this.lookup['modx-album-view-thumb-'+data.id] = data;
        return data;
    }
	
	,reset: function(el){
		Ext.getCmp(el).reset();
	}
	
	,activate: function(rec){
		if(rec != undefined){
			Ext.getCmp(this.viewId).store.setBaseParam('album', rec.id);
			this.updateAlbumData(rec);
		} 
		this.run();
		
		/* Set panel as active */
		Ext.getCmp('cliche-albums-mgr-container').setActiveItem(1);			
		
		/* update album informations */
		Ext.getCmp(this.colid).updateDetail(this.album);
		
		/* update breadcrumbs */
		this.updateBreadcrumbs(_('cliche.breadcrumbs_album_msg') + this.album.name);
	}
		
	,updateAlbumData: function(rec){
		this.album = rec.data;
	}
	
	,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-albums-desc').updateDetail({
			text: msg
			,trail: [{
				text : _('cliche.breadcrumb_root')
				,cmp: 'cliche-albums-list'
				,isLink: true				
				,className: 'last'
			},{
				text: this.album.name
				,isLink: false
				,className: 'active'
			}]
		});
	}
	
	,onSelect: function(rec){
		rec.data.album_name = this.album.name;
		rec.data.album_id = this.album.id;
		rec.data.album_cover_id = this.album.cover_id;
		Ext.getCmp('cliche-picture-view').activate(rec);
	}
});
Ext.reg('cliche-album-view', MODx.ClicheAlbumView);
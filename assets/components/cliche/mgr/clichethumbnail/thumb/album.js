/**
 * @class MODx.ClicheThumbAlbumView
 * @extend MODx.AbstractDataViewWithColumn
 * The card panel containing the items available for use as thumbnail
 */
MODx.ClicheThumbAlbumView = Ext.extend(MODx.AbstractDataViewWithColumn, {
	showDetailOnSelect: false
	,fields: ['id','name','description','createdon','createdby','protected','album_id','image','thumbnail','phpthumb','metas','timestamp']
	,ident: 'thumb-album-ident-'+ this.tvId
	,viewId: 'thumb-album-panel-'+ this.tvId
	,colid: 'thumb-album-detailpanel-'+ this.tvId
	,colWidth: 275
    ,tv: false
	,limit: 8
	,baseParams: { 
		action: 'clichethumbnail/thumb/getlist'
		,ctx: 'mgr'
		,limit: 8
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
                    ,'<a href="{image}" title="Album {name} preview" alt="image previexw" class="lightbox" />'
                        ,'<img src="{thumbnail}" alt="{name}" />'
                    ,'<a/>'
                    ,'<h5>{name}</h5>'
                    ,'<a href="#" class="inline-button" onclick="Ext.getCmp(\'{container}\').selectItem(); return false;"/>'+_('download')+'</a>'
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
                        ,'<tpl for="metas">'
                            ,'<li>'
                                ,'<span class="infoname">{name}:</span>'
                                ,'<span class="infovalue">{value}</span>'
                            ,'</li>'
                        ,'</tpl>'
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
			,handler: function(btn,e){
				Ext.getCmp('cliche-thumb-uploader-'+this.tvId).activate(this.tvId);
			}
			,scope: this
		}]
	}
		
	,showDetails : function(data){
		var detailEl = Ext.getCmp(this.colid);
        /* Add container id */
        data.container = this.id;
		detailEl.updateDetail(data);
    }
	
	,getSelected: function(){
		var el = Ext.getCmp(this.viewId);
		return el.getSelectedRecords()[0].data;
	}
	
	,formatData: function(data) {
        data.shortName = Ext.util.Format.ellipsis(data.name, 12);
        this.lookup['modx-album-view-thumb-'+data.id] = data;
        return data;
    }
	
	,reset: function(el){
		Ext.getCmp(el).reset();
	}
		
	,run: function() {
		Ext.getCmp(this.viewId).store.load();
    }
	
	,activate: function(){
        if(!this.tv){
			Ext.getCmp(this.viewId).store.setBaseParam('tv', this.tvId);
			this.tv = true;
		}
        this.run();
        this.updateBreadcrumbs(_('cliche.breadcrumbs_album_msg'));
        Ext.getCmp(this.cardContainer).setActiveItem(this.id);
	}
	
	,updateBreadcrumbs: function(msg){
		Ext.getCmp(this.breadcrumbs).updateDetail({
			text: msg
			,trail: [{
				text : 'Your thumbnail'
				,cmp: this.mainCard
				,isLink: true				
				,className: 'last'
			},{
				text: 'Thumbnail album browser'
				,isLink: false
				,className: 'active'
			}]
		});
	}
	
	,onSelect: function(rec){
        this.showDetails(rec.data);
	}

    ,selectItem: function(){
        data = this.getSelected();
        Ext.getCmp(this.mainCard).activate(data);
    }
});
Ext.reg('cliche-thumb-album-view', MODx.ClicheThumbAlbumView);
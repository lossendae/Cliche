/**
 * @class MODx.ClichePictureView
 * The card panel containg the packages list and readme tpl
 */
MODx.ClichePictureView = Ext.extend(MODx.AbstractPanel, {
	border: false
	,cls: 'spaced-wrapper'
	,win: false
	,layout: 'column'
	,components:[{
		xtype: 'my-tpl-panel'
		,id: 'cliche-picture'
		,columnWidth: 1
		,startingMarkup: ['{text}']
		,startingText: 'loading...'
		,tpl: [
			'<tpl for=".">'
				,'<div class="centered" id="modx-album-view-thumb-{id}">'
					,'<div class="image-wrapper">'                                     
						,'<img src="{phpthumb}&amp;w={maxwidth}" title="{name}" alt="{name}" />'                                     
					,'</div>'                                     
				,'</div>'
			,'</tpl>'
		]
	},{
		xtype: 'my-tpl-panel'
		,id: 'cliche-picture-details'
		,width: 250
		,cls:'spaced-column'
		,bodyCssClass:'formatted-layout'
		,startingMarkup: ['{text}']
		,startingText: 'loading...'
		,tpl: [ 
			'<div class="details">'
				,'<tpl for=".">'				
					,'<div class="selected">'	
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
	}]
	
	,buildUI: function(config){		
		config.tbar = [{
			text: _('cliche.delete_image')
			,id:'delete-picture'
			,iconCls:'icon-delete'
			,handler: this.onDeletePicture
			,scope: this
		},{
			text: _('cliche.set_as_album_cover')
			,id:'set-as-cover'
			,iconCls:'icon-save'
			,handler: this.onSetAsCover
			,scope: this
		}]
	}
	
	,onDeletePicture: function(btn, e){
		MODx.msg.confirm({
			title: _('cliche.delete_image_title')
			,text: _('cliche.delete_image_msg')
			,cls: 'custom-window'
			,url: this.url			   
			,params: {
				action: 'image/delete'
				,id: this.picture.id
				,ctx: 'mgr'
			}
			,listeners: {
				'success':{fn:function(r) {
					//r.msg to album list + this.album.name
					Ext.getCmp('cliche-album-view').activate();
				},scope:this}
			}
			,animEl: btn.id
        });
	}
	
	,onSetAsCover: function(btn, e){
		Ext.Ajax.request({
			url : this.url			   
			,params: {
				action: 'image/setascover'
				,id: this.picture.id
				,album: this.picture.album_id
				,ctx: 'mgr'
			}
			,success: function(response, d) {
				result = Ext.util.JSON.decode(response.responseText);
				var pnl = this;				
				var name = this.picture.name
				pnl.updateBreadcrumbs(result.msg);
				setTimeout(function(){ 
					pnl.updateBreadcrumbs(_('cliche.breadcrumbs_item_msg') + name);
				}, 3000); 
				Ext.getCmp('set-as-cover').hide();
				/* Update album on parent panel */
				rec.data = result.data
				Ext.getCmp('cliche-album-view').updateAlbumData(rec);
			}
			,scope:this
			,animEl: btn.id
        });
	}
	
	,activate: function(rec){
		if(rec != undefined){
			this.picture = rec.data;
		} 
		/* Hide thre set as cover button */
		Ext.getCmp('set-as-cover').hide();
		
		/* Set panel active */
		Ext.getCmp('cliche-albums-mgr-container').setActiveItem(3);		
		
		/* Calculate pic preview maxwidth - genetrated by phpthumb */
		this.picture.maxwidth = Ext.getCmp('cliche-picture').el.dom.offsetWidth - 85;
		
		Ext.getCmp('cliche-picture').updateDetail(this.picture);
		Ext.getCmp('cliche-picture-details').updateDetail(this.picture);
		
		/* Show thre set as cover button if current pic is not the cover */
		if(this.picture.album_cover_id != this.picture.id){
			Ext.getCmp('set-as-cover').show();
		}
		
		/* Update breadcrumbs */
		this.updateBreadcrumbs(_('cliche.breadcrumbs_item_msg') + this.picture.name);
	}
	
	,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-albums-desc').updateDetail({
			text: msg
			,trail: [{
				text : _('cliche.breadcrumb_root')
				,cmp: 'cliche-albums-list'
				,isLink: true				
			},{
				text: this.picture.album_name
				,cmp: 'cliche-album-view'
				,isLink: true
				,className: 'last'
			},{
				text: this.picture.name
				,isLink: false
				,className: 'active'
			}]
		});
	}
});
Ext.reg('cliche-picture-view', MODx.ClichePictureView);
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
						,'<h4>Description</h4>'
						,'{description:defaultValue("'+_('cliche.no_desc')+'")}'
					,'</div>'				
					,'<div class="infos">'
						,'<h4>Informations</h4>'
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
		}]
	}
	
	,onDeletePicture: function(btn, e){
		MODx.msg.confirm({
			title: 'Remove picture'
			,text: 'Are you sure you want to remove this picture ?<br/> This is irreversible!'
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
	
	,activate: function(rec){
		if(rec != undefined){
			this.picture = rec.data;
		} 
		Ext.getCmp('cliche-albums-mgr-container').setActiveItem(3);		
		
		this.picture.maxwidth = Ext.getCmp('cliche-picture').el.dom.offsetWidth - 85;
		
		Ext.getCmp('cliche-picture').updateDetail(this.picture);
		Ext.getCmp('cliche-picture-details').updateDetail(this.picture);
		
		Ext.getCmp('cliche-albums-desc').updateDetail({
			text: 'This is the pic'
			,trail: [{
				text : 'Album list'
				,cmp: 'cliche-albums-list'
				,isLink: true				
				,className: 'last'
			},{
				text: this.picture.name
				,isLink: false
				,className: 'active'
			}]
		});
		Ext.getCmp('cliche-albums-desc').updateDetail({
			text: 'Viewing picture: '+ this.picture.name
			,trail: [{
				text : 'Album list'
				,cmp: 'cliche-albums-list'
				,isLink: true				
			},{
				text: this.picture.album
				// text: 'album'
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
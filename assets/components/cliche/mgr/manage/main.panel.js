Ext.ns('MODx');

/**
 * @class MODx.ClicheAlbums
 * @extend MODx.AbstractPanel
 * The panel container for Cliche manager page
 */
MODx.ClicheAlbums = Ext.extend(MODx.AbstractPanel, {
	id: 'my-albums'
	,components: [{
		xtype: 'panel'
		,bodyCssClass: 'main-wrapper'
		,layout: 'anchor'
		,items:[{		
			xtype: 'my-breadcrumbs'
			,id: 'cliche-albums-desc'
			,startingText: { 
				 text : 'List of all available albums'
				,trail : [{
					text : 'Album list'
				}]
			}				
		},{
			layout: 'card'
			,layoutOnCardChange: true
			,activeItem: 0
			,border: false
			,deferredRender: true
			,id: 'cliche-albums-mgr-container'
			,defaults:{
				url: MODx.ClicheConnectorUrl
			}
			,items: [{
				xtype: 'cliche-albums-list'
				,id: 'cliche-albums-list'
				,listeners:{
					render: function(){
						this.run();
					}
				}
			},{
				xtype: 'cliche-album-view'
				,id: 'cliche-album-view'
			},{
				xtype: 'cliche-panel-uploader'
				,id: 'cliche-panel-uploader'
			},{
				xtype: 'cliche-picture-view'
				,id: 'cliche-picture-view'
			}]
			,setActiveItem: function(i){
				this.getLayout().setActiveItem(i);
				Ext.getCmp('modx-content').doLayout();
			}
		}]
	}]
});
Ext.reg("cliche-albums", MODx.ClicheAlbums);

/**
 * @class MODx.ClicheMainPanel
 * @extend MODx.MainContainerTabPanel
 * The panel container for Cliche manager page
 */
MODx.ClicheMainPanel = Ext.extend(MODx.MainContainerTabPanel, {
	id: 'cliche-panel'
	,titleText : 'Cliche'
	,titleClass : 'tools'
	,cid: 'my-main-tabs'
	,components: [{
		title: 'Your Albums'
		,xtype: 'cliche-albums'
	}]
});
Ext.reg("cliche-main-panel", MODx.ClicheMainPanel);
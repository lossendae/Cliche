/**
 * @class MODx.MainContainerTabPanel
 * @extend MODx.AbstractPanel
 * The panel container for Cliche manager page
 */
MODx.MainContainerPanel = Ext.extend(Ext.Panel, {
	bodyCssClass: 'modx-panel'	
	,frame:false
	,plain:true
	,autoHeight: true
	,tpl: null
	,border: false
	
	/**
     * initComponent
     * @protected
     */
    ,initComponent : function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));		
		MODx.MainContainerPanel.superclass.initComponent.apply(this, arguments);
    }
	
	,buildConfig:function(config) {       
		this.buildItems(config);
	}
	
	,buildItems : function(config) {        
		config.pageAB = null;
		config.initAB = this.initAB || false;
		if(config.initAB){
			config.pageAB = new Ext.Toolbar({
				renderTo: "modAB"
				,id: 'modx-action-buttons'
				,items: this.actionbtn || []
			});
		}		
		config.components = this.components || [];
				
		config.items = [{
			xtype: 'my-tpl-panel'
			,id: 'container-title'
			,cls: 'page-header'
			,startingMarkup: this.titleTpl || '<h2 class="main-title {titleClass}">{titleText}</h2>'
			,listeners: {
				'render': function(c){
					data = {titleText: 'test',titleClass: 'test'}
					c.startingMarkup.overwrite(c.body, data);
				}
				,scope: this
			}
		},{
			xtype: 'panel'
			,id: this.cid || 'modx-panel-'+Ext.id()
			,bodyCssClass: 'main-wrapper'
			,layout: 'anchor'
			,items: config.components			
		}]
    }
	
	
});
Ext.reg("container-panel", MODx.MainContainerPanel);

/**
 * @class MODx.MainContainerTabPanel
 * @extend MODx.MainContainerPanel
 * The panel container for Cliche manager page
 */
MODx.MainContainerTabPanel = Ext.extend(MODx.MainContainerPanel, {
	buildItems : function(config) {        
		config.pageAB = null;
		config.initAB = this.initAB || false;
		if(config.initAB){
			config.pageAB = new Ext.Toolbar({
				renderTo: "modAB"
				,id: 'modx-action-buttons'
				,items: this.actionbtn || []
			});
		}		
		config.tabs = this.components || [];
				
		config.items = [{
			xtype: 'my-tpl-panel'
			,id: 'container-title'
			,cls: 'page-header'
			,startingMarkup: this.titleTpl || '<h2 class="main-title {titleClass}">{titleText}</h2>'
			,listeners: {
				'render': function(c){
					data = { titleText: this.titleText , titleClass: this.titleClass }
					c.startingMarkup.overwrite(c.body, data);
				}
				,scope: this
			}
		},{
			xtype: 'my-tabpanel'
			,id: this.cid || 'modx-tab-'+Ext.id()
			,cls: 'custom-tabpanel container'
			,defaults: {
				layout: 'form'
				,labelAlign: 'top'
				,anchor: '100%'
			}
			,components: config.tabs			
		}]
    }
});
Ext.reg("container-tab-panel", MODx.MainContainerTabPanel);
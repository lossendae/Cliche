Ext.ns('MODx');

/**
 * @class MODx.AbstractTabPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.AbstractTabPanel = Ext.extend(Ext.TabPanel,{
	 frame: false
	,border: false
	,plain:true
	,autoheight:true
	,defaults:{ autoHeight: true }
	,activeItem: 0
	,cls:'my-tabpanel'
	
	,initComponent:function(){
		var config = {};		
		this.buildConfig(config);
		
		Ext.apply(this, Ext.apply(this.initialConfig, config));
		
		MODx.AbstractTabPanel.superclass.initComponent.call(this, arguments);
	}
	
	,buildConfig:function(config) {
        this.buildItems(config);
        this.buildUI(config);
	} // eo function buildConfig
	
	,buildItems:function(config) {
        config.items = this.components || undefined;
    } // eo function buildItems
	
	,buildUI: function(config){
       config.buttons = undefined;
    }
	
	,addTab: function(title, id, data){
        this.add({
			title: title
			,xtype:'modx-test-wizard-tpl'
			,id: id +'-tab'
			,tpl: '{'+id+'}'
			,bodyCssClass: 'template-wrapper'
			,listeners: {
				afterrender: function() {
					this.updateDetail( data );
				}
			}
        });
    }
});
Ext.reg("modx-test-tab", MODx.AbstractTabPanel);
Ext.reg("my-tabpanel", MODx.AbstractTabPanel);
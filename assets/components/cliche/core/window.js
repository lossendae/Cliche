Ext.ns('MODx');

/**
 * @class MODx.AbstractWindow
 * A base class definition for Ext window component
 */
MODx.AbstractWindow = Ext.extend(Ext.Window, {
	cls: 'custom-window'
	,modal: true
	,animate: true
	,border: false
	,closeAction: 'hide'
	,autoHeight: true
	,layout: 'form'
	,defaults: {
		layout: 'form'
		,labelAlign: 'top'
		,anchor: '100%'
		,bodyCssClass:'main-wrapper'
		,border: false
	}
	,initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
		MODx.AbstractWindow.superclass.initComponent.call(this);
	}
	
	,buildConfig:function(config) {
		config.defaults = this.defaults || { border: false };
		// config.animate = true;
		// config.modal = this.modal || false;
		// config.border = this.border || false;
		// config.closeAction = 'hide';
		// config.autoHeight = this.autoHeight || true;
		config.bbarCfg  = this.bbarCfg  || { buttonAlign:'center' };
		config.cancelBtn = {
			text: this.cancelText || 'Cancel'
			,handler: this.onCancel
			,scope: this
		}
		// this.buildLayout(config);	
		this.buildItems(config);
		this.buildUI(config);		
		this.buildKeys(config);				
	}
	
	,buildUI: function(config){
		config.bbar = [config.cancelBtn];
    }
	
	,buildItems : function(config) {
        config.items = this.components || [];
    }
	
	,buildKeys : function(config) {
        config.keys = {
			key: 27
			,handler: this.onCancel
			,scope: this
		}
    }
	
	// ,buildLayout : function(config) {
        // config.layout = 'fit';
    // }
	
	,onCancel: function(b,e) {
		this.hide();
	}
	
	,listeners: {
		beforehide: function(win){
			if(typeof(this.onBeforeHideWindow) == "function"){
				this.onBeforeHideWindow(win);
			}
		}
	}
});
Ext.reg("modx-test-window", MODx.AbstractWindow);
Ext.reg("my-window", MODx.AbstractWindow);
Ext.ns('MODx');

/**
 * @class MODx.ConsoleAlternative
 * @require core/panel.js
 * A base definition for GridPanel to use in Manager
 */
MODx.ConsoleAlternative = Ext.extend(MODx.AbstractPanel, {
	mgr: null
	,task: null
	,height: 500
	,running: false
	,initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
		MODx.ConsoleAlternative.superclass.initComponent.call(this);
	}
	
	,buildConfig:function(config) {
		this.buildItems(config);
		this.buildTbar(config);
		this.buildUI(config);
		config.oldID = false;
	}
	
	,buildUI: function(config){
       config.buttons = undefined;
    }	
	
	,buildItems : function(config) {
        config.items =  [{
			xtype: 'displayfield'
			,id: 'console-content'
			,cls: 'console-content'
			,height: 220
		}];
    }
	
	,setRegister: function(register, topic, task) {
    	this.register = register;
        this.topic = topic;
		this.task = task;
    }
	
	,runPoll: function(id){
		if(this.oldID){
			var p = Ext.Direct.getProvider(this.oldID);	
			if(typeof p != 'undefined'){
				Ext.Direct.removeProvider(this.oldID);
			}	
		}
		
		this.provider = new Ext.direct.PollingProvider({
			type:'polling'
			,url: MODx.config.connectors_url+'system/index.php'
			,interval: 1000
			,id: record.data.name
			,baseParams: {
				action: 'console'
				,register: this.register || ''
				,topic: this.topic || ''
				,show_filename: this.show_filename || 0
				,format: this.format || 'html_log'
			}
		});			
		Ext.Direct.addProvider(this.provider);
	
		content = Ext.getCmp('console-content');
		content.reset();
		
		Ext.Direct.on('message', this.messageLogger,this);		
		this.oldID = id;
	}
	
	,messageLogger: function(e,v){
		if (e.data.search('COMPLETED') != -1) {
			this.provider.disconnect();
			this.fireEvent('complete');
		} else {	
			if (content && e.data != "") {					
				content.append(e.data);
				content.el.scroll('b', content.el.getHeight(), true);
			}
		}
		delete e;
	}
});
Ext.reg("modx-test-console", MODx.ConsoleAlternative);
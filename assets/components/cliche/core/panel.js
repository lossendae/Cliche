Ext.ns('MODx');

/**
 * @class MODx.AbstractPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.AbstractPanel = Ext.extend(Ext.Panel, {
	frame:false
	,plain:true
	,autoHeight: true
	,defaults: {
		layout: 'form'
		,labelAlign: 'top'
		,anchor: '100%'
	}

    /**
     * initComponent
     * @protected
     */
    ,initComponent : function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));		
		MODx.AbstractPanel.superclass.initComponent.apply(this, arguments);
    }
	
	,buildConfig:function(config) {
        this.buildItems(config);
		this.buildUI(config);
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
		config.items = this.components || undefined;
    }
	
	,buildUI: function(config){
       config.buttons = undefined;
    }
	
	,addActionButtons: function(){
		
	}
});
Ext.reg("modx-abstract-panel", MODx.AbstractPanel);
Ext.reg("my-abstract-panel", MODx.AbstractPanel);

/**
 * @class MODx.AbstractTemplatePanel
 * A typical Card panel Template definition
 */
MODx.AbstractTemplatePanel = Ext.extend(Ext.Panel, {
	frame:false
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
		MODx.AbstractTemplatePanel.superclass.initComponent.apply(this, arguments);
    }
	
	,buildConfig:function(config) {
        this.buildItems(config);
		this.buildTbar(config);
		this.buildBbar(config);
		this.buildUI(config);
		this.buildTpls(config);
	}
	
	,buildTpls:function(config){
		config.startingMarkup = this.addTpl(this.startingMarkup);
		if(this.tpl !== null ){
			config.tpl = this.addTpl(this.tpl);
		}
	}
	
	,addTpl: function(markup){
		return new Ext.XTemplate(markup, {
			compiled: true
		});
	}

	,buildItems : function(config) {
        config.items = undefined;
    }

	,buildTbar: function(config){
       config.tbar = undefined;
    }
	
	,buildBbar: function(config){
       config.buttons = undefined;
    }
	
	,buildUI: function(config){
       config.buttons = undefined;
    }
	
	,reset: function(data){		
		if(typeof data == "undefined"){
			data = { text: this.startingText };
		}
		this.body.hide();
		this.startingMarkup.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
	}
	
	,updateDetail: function(data) {		
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
	}
	
	,listeners: {
		'render': function(tp){
            if(typeof(this.startingText) == "object"){
                data = this.startingText;
            } else {
                data = { text: this.startingText }
            }
			this.startingMarkup.overwrite(this.body, data);
		}
	}
});
Ext.reg("modx-abstract-tpl-panel", MODx.AbstractTemplatePanel);
Ext.reg("my-abstract-tpl-panel", MODx.AbstractTemplatePanel);
Ext.reg("my-tpl-panel", MODx.AbstractTemplatePanel);

/**
 * @class MODx.AbstractWizardPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.BreadcrumbsTpl = Ext.extend(MODx.AbstractTemplatePanel, {
	cls: 'panel-desc'
	,startingMarkup: '<ul id="crumbs">'
						+'<tpl for="trail">'
							+'<li class="active">{text}</li>'
						+'</tpl>'
					+'</ul>'
					+'<tpl if="text != undefined">'
						+'<p>{text}</p>'
					+'</tpl>'
	,tpl: 	'<tpl if="typeof(trail) != &quot;undefined&quot;">'
				+'<ul id="crumbs">'
					+'<tpl for="trail">'
						+'<li{className:this.formatClass}>'
							+'<tpl if="isLink"><a href="#" class="controlBtn {cmp}"/>{text}</a></tpl>'  
							+'<tpl if="!isLink">{text}</tpl>'  
						+'</li>'
					+'</tpl>'				
				+'</ul>'
			+'</tpl>'
			+'<tpl if="typeof(text) != &quot;undefined&quot;">'
				+'<p>{text}</p>'
			+'</tpl>'
			
	,buildTpls:function(config){
		config.startingMarkup = this.addTpl(this.startingMarkup);			
		if(this.tpl !== null ){
			config.tpl = this.addTpl(this.tpl);
			config.tpl.formatClass = function(value, record) {				
				return (value != undefined) ? ' class="'+value+'"' : "";
			};						
		}
	}
	
	,listeners: {
		'render': function(tp){
			this.startingMarkup.overwrite(this.body, this.startingText);
			this.body.on('click', this.onClick, this);
		}
	}
	
	,onClick: function(e){
		var target = e.getTarget();
		elm = target.className.split(' ')[0];
		if(elm != "" && elm == 'controlBtn'){
			cmp = target.className.split(' ')[1];
			Ext.getCmp(cmp).activate();	
		}
	}
	
	,reset: function(data){	
		if(typeof data == "undefined"){
			data = this.startingText;
		}
		this.body.hide();
		this.startingMarkup.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
	}
});
Ext.reg("my-breadcrumbs", MODx.BreadcrumbsTpl);

/**
 * @class MODx.AbstractWizardPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.AbstractWizardPanel = Ext.extend(Ext.Panel, {
	maxHeight: 450
	,html: 'Wizard dummy content'
	,initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
				
		this.tpl = new Ext.Template(config.tplMarkup);
		MODx.AbstractWizardPanel.superclass.initComponent.call(this);
	}
	
	,buildConfig:function(config) {
        this.buildTpl(config);
        this.buildUI(config);
	}
		
	,buildTpl:function(config) {
		config.tplMarkup = ['<div class="text-wrapper pre-content">'+this.tpl+'</div>'];
	}
	
	,buildUI: function(config){
       config.buttons = undefined;
    }	
	
	,updateDetail: function(data) {
		this.tpl.overwrite(this.body, data);
	}

});
Ext.reg("modx-test-wizard-tpl", MODx.AbstractWizardPanel);
Ext.reg("my-wizard-panel", MODx.AbstractPanel);

/* FOR TESTING PURPOSE */

/**
 * @class MODx.AbstractTextFieldPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.AbstractTextFieldPanel = Ext.extend(Ext.Panel, {
	layout: 'form'
	,cls: 'variable-content'
	,defaults: {
		anchor: '100%'
	}
	,initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
		MODx.AbstractTextFieldPanel.superclass.initComponent.call(this);
		
		//Add custom description if necessary
		if( config.desc != undefined ){
			this.add({
				xtype: 'label'
				,forId: config.fieldName
				,text: config.desc
				,cls: 'desc-under'
			});
		}
	}
	
	,buildConfig:function(config) {
		config.field = this.field || undefined;
		config.label = this.label || undefined;
		config.fieldName = this.fieldName || undefined;
		config.desc = this.desc || undefined;
        this.buildItems(config);
	}
	
	,buildItems : function(config) {		
		if(config.label != undefined && config.fieldName != undefined){
			config.items = [{
				xtype: config.field || 'textfield'
				,fieldLabel: config.label
				,id: config.fieldName
				,name: config.fieldName
			}];
		} else {
			config.items = undefined;
		}        
    }	
});
Ext.reg("my-field", MODx.AbstractTextFieldPanel);


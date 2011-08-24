/**
 * @class MODx.AbstractFormPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.AbstractFormPanel = Ext.extend(Ext.form.FormPanel, {
	frame:false
	,plain:true
	,autoHeight: true
	,record : null
	,defaults: {
		layout: 'form'
		,labelAlign: 'top'
		,border: false
	}
    /**
     * initComponent
     * @protected
     */
    ,initComponent : function() {
		var config = {};		
		this.buildConfig(config);
		
		this.addEvents({
            /**
             * @event create
             * @param {FormPanel} this
             * @param {Object} values, the Form's values object
             */
            create : true
        });
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));		
		MODx.AbstractFormPanel.superclass.initComponent.apply(this, arguments);
    }
	
	,buildConfig:function(config) {
        this.buildItems(config);
		this.buildUI(config);
	}

	,buildItems : function(config) {
        config.items = this.components || undefined;
    }
	
	,buildUI: function(config){
       config.buttons = undefined;
    }
	
	/**
     * loadRecord
     * @param {Record} rec
     */
    ,loadRecord : function(rec) {
        this.record = rec;
        this.getForm().loadRecord(rec);
    }

    /**
     * onUpdate
     */
    ,onUpdate : function(btn, ev) {
        if (this.record == null) {
            return;
        }
        if (!this.getForm().isValid()) {
            return false;
        }
        this.getForm().updateRecord(this.record);
    }

    /**
     * onCreate
     */
    ,onCreate : function(btn, ev) {
        if (!this.getForm().isValid()) {
            return false;
        }
        this.fireEvent('create', this, this.getForm().getValues());
        this.getForm().reset();
    }

	/**
     * onReset
     */
    ,onReset : function(btn, ev) {
        this.getForm().reset();
    }
});
Ext.reg("modx-abstract-formpanel", MODx.AbstractFormPanel);
Ext.reg("my-abstract-formpanel", MODx.AbstractFormPanel);
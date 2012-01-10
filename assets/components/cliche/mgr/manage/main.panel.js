Ext.ns('MODx');

/**
 * Loads the main panel for Cliche cmp.
 * 
 * @class MODx.panel.cliche
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-cliche
 */
MODx.panel.cliche = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cliche-main-panel'
        ,cls: 'container'
        ,unstyled: true
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+ _('cliche.title') +'</h2>'
            ,border: false
            ,cls: 'tools modx-page-header'
        },{
			xtype: 'panel'
			,id: 'main-panel'
			,items:[{
				xtype: 'modx-breadcrumbs-panel'
				,id: 'cliche-breadcrumbs'
				,bdMarkup: '<tpl if="typeof(trail) != &quot;undefined&quot;">'
					+'<div class="crumb_wrapper"><ul class="crumbs">'
						+'<tpl for="trail">'		
							+'<li{[values.className != undefined ? \' class="\'+values.className+\'"\' : \'\' ]}>'		
								+'<tpl if="typeof pnl != \'undefined\'">'
									+'<button type="button" class="controlBtn {pnl}{[values.root ? \' root\' : \'\' ]}">{text}</button>'							
								+'</tpl>'
								+'<tpl if="typeof pnl == \'undefined\'"><span class="text{[values.root ? \' root\' : \'\' ]}">{text}</span></tpl>'										
							+'</li>'
						+'</tpl>'
					+'</ul></div>'
				+'</tpl>'
				+'<tpl if="typeof(text) != &quot;undefined&quot;">'
					+'<div class="panel-desc{[values.className != undefined ? \' \'+values.className+\'"\' : \'\' ]}">{text}</div>'
				+'</tpl>'
				,desc: _('cliche.breadcrumb_album_list_desc')
				,root : { 
					text : _('cliche.breadcrumb_root')
					,className: 'first'
					,root: true
					,pnl: 'album-list' 
				}
			},{
				layout:'card'
				,id:'card-container'
				,activeItem:0
				,border: false	
				,autoHeight: true			
				,defaults:{
					preventRender: true
					,autoHeight: true
				}
				,items: [{
					xtype: 'cliche-albums-list'
					,id: 'album-list'
				}]
				,listeners:{
					afterrender: this.addPanels
					,scope: this
				}
			}]
		}]
    });
    MODx.panel.cliche.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.cliche,MODx.Panel,{
	addPanels: function(){
		var p = getPanels();
		Ext.each(p, function(value){
			Ext.getCmp('card-container').add({ xtype: value });	
		});
	}
	
	,loadCreateUpdateWindow: function(title, action, btn, returnTo, data){
		if(!this.win){			
			this.win = new MODx.window.ClicheAlbumsWindow();
		}
		this.win.setTitle(title);
		this.win.show(btn.id);	
		this.win.reset(action, returnTo);		
		if(data != undefined){
			this.win.load(data);
		}
	}
});
Ext.reg('cliche-main-panel',MODx.panel.cliche);

/**
 * @class MODx.window.ClicheAlbumsWindow
 * @extends Ext.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-albums
 */
MODx.window.ClicheAlbumsWindow = function(config) {
    config = config || {};
	
    Ext.applyIf(config,{ 
		layout: 'form'
		,border: false		
		,width: 350
		,items:[{
			xtype: 'form'
			,id: 'create-update-form'
			,cls:'main-wrapper'
			,labelAlign: 'top'
			,unstyled: true 
			,defaults:{
				msgTarget: 'under'
				,anchor: '100%'
			}
			,items:[{
				fieldLabel: _('cliche.album_name_label')
				,name: 'name'
				,id: 'name'
				,xtype: 'textfield'			
				,allowBlank: false
			},{
				fieldLabel: _('cliche.album_desc_label')
				,name: 'description'
				,id: 'description'
				,xtype: 'textarea'
				,minHeight: 150
				,grow: true
			},{
				name: 'id'
				,id: 'id'
				,xtype: 'hidden'
			}]						
		}]
		,buttons :[{
			text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
		},{
			text: _('cliche.save_album_btn')
			,id: 'create-album-window-btn'
			,cls: 'green'
			,handler: this.save
			,scope: this
		}]
    });
    MODx.window.ClicheAlbumsWindow.superclass.constructor.call(this,config);
	
	this.formId = 'create-update-form';
};
Ext.extend(MODx.window.ClicheAlbumsWindow,Ext.Window,{
	save: function(b,t){	
		Ext.getCmp(this.formId).getForm().submit({
			waitMsg: _('cliche.saving_msg')
			,url     : MODx.ClicheConnectorUrl
			,params : {
				action: 'album/'+ this.saveAction
				,ctx: 'mgr'
			}
			,success: function( form, action ) {				
				response = action.result
				data = response.object
				msg = response.message
				if(response.success && this.returnTo != undefined){
					Ext.getCmp(this.returnTo).activate(data);										
				}				
				this.hide();
			}
			,failure: function( form, action ){
				response = action.result
				errors = response.object
				msg = response.message				
				//Show error messages under specified field
				for(var key in errors){
					if (errors.hasOwnProperty(key)) {
						fld = errors[key];
						f = form.findField(fld.name);
						if(f){ f.markInvalid(fld.msg) }
					}
				}
			}
			,scope: this
		});
	}
	
	,reset: function(action, returnTo){
		this.saveAction = action;
		this.returnTo = returnTo;
		Ext.getCmp(this.formId).getForm().reset();
	}
	
	,load: function(data){
		Ext.getCmp(this.formId).getForm().setValues(data);
	}
});
Ext.reg('modx-window-cu-albums', MODx.window.ClicheAlbumsWindow);
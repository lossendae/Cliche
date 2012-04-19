/**
 * The window allowing image edit
 * 
 * @class MODx.window.ClicheImageEditWindow
 * @extends Ext.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-cliche-image-edit
 */
MODx.window.ClicheImageEditWindow = function(config) {
    config = config || {};    
    if(typeof(config.uid) == 'undefined'){ config.uid = 'default' }
    Ext.applyIf(config,{ 
        layout: 'form'
        ,title: _('cliche.window_edit_image')
        ,border: false        
        ,width: 450
        ,items:[{            
            xtype: 'modx-template-panel'
            ,bodyCssClass: 'win-desc panel-desc'
            ,startingMarkup: '<tpl for="."><p>{text}</p></tpl>'
            ,startingText: _('cliche.window_edit_image_msg')
        },{
            xtype: 'form'
            ,id: 'edit-image-form-'+config.uid
            ,cls:'main-wrapper'
            ,labelAlign: 'top'
            ,unstyled: true 
            ,defaults:{
                msgTarget: 'under'
                ,anchor: '100%'
            }
            ,items:[{
                fieldLabel: _('cliche.field_image_name_label')
                ,name: 'name'
                ,id: 'image-name-'+config.uid
                ,xtype: 'textfield'            
                ,allowBlank: false
            },{
                fieldLabel: _('cliche.field_image_desc_label')
                ,name: 'description'
                ,id: 'image-description-'+config.uid
                ,xtype: 'textarea'
                ,minHeight: 150
                ,grow: true
            },{
                name: 'id'
                ,id: 'image-id-'+config.uid
                ,xtype: 'hidden'
            }]                        
        }]
        ,buttons :[{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: _('cliche.btn_save_image')
            ,id: 'edit-image-window-btn-'+config.uid
            ,cls: 'green'
            ,handler: this.save
            ,scope: this
        }]
    });
    MODx.window.ClicheImageEditWindow.superclass.constructor.call(this,config);    
    this.formId = 'edit-image-form-'+this.uid;
};
Ext.extend(MODx.window.ClicheImageEditWindow,Ext.Window,{
    save: function(b,t){    
        Ext.getCmp(this.formId).getForm().submit({
            waitMsg: _('cliche.saving_msg')
            ,url     : MODx.ClicheConnectorUrl
            ,params : {
                action: 'image/edit'
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
    
    ,reset: function(returnTo){
        this.returnTo = returnTo;
        Ext.getCmp(this.formId).getForm().reset();
    }
    
    ,load: function(data){
        Ext.getCmp(this.formId).getForm().setValues(data);
    }
});
Ext.reg('modx-window-cliche-image-edit', MODx.window.ClicheImageEditWindow);


/**
 * The base window allowing for album edit
 * 
 * @class MODx.window.ClicheAlbumEditWindow
 * @extends Ext.Window
 * @param {Object} config An object of configuration parameters
 * @xtype modx-window-cliche-album-edit
 */
MODx.window.ClicheAlbumEditWindow = function(config) {
    config = config || {};    
    if(typeof(config.uid) == 'undefined'){ config.uid = 'default' }
    Ext.applyIf(config,{ 
        layout: 'form'
        ,border: false        
        ,width: 350
        ,items:[{
            xtype: 'form'
            ,id: 'create-update-form-'+config.uid
            ,cls:'main-wrapper'
            ,labelAlign: 'top'
            ,unstyled: true 
            ,defaults:{
                msgTarget: 'under'
                ,anchor: '100%'
            }
            ,items:[{
                fieldLabel: _('cliche.field_album_name_label')
                ,name: 'name'
                ,id: 'album-name-'+config.uid
                ,xtype: 'textfield'            
                ,allowBlank: false
            },{
                fieldLabel: _('cliche.field_album_desc_label')
                ,name: 'description'
                ,id: 'album-description-'+config.uid
                ,xtype: 'textarea'
                ,minHeight: 150
                ,grow: true
            },{
                name: 'id'
                ,id: 'album-id-'+config.uid
                ,xtype: 'hidden'
            }]                        
        }]
        ,buttons :[{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: _('cliche.btn_save_album')
            ,id: 'create-album-window-btn-'+config.uid
            ,cls: 'green'
            ,handler: this.save
            ,scope: this
        }]
    });
    MODx.window.ClicheAlbumEditWindow.superclass.constructor.call(this,config);
    
    this.formId = 'create-update-form-'+this.uid;
};
Ext.extend(MODx.window.ClicheAlbumEditWindow,Ext.Window,{
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
Ext.reg('modx-window-cliche-album-edit', MODx.window.ClicheAlbumEditWindow);

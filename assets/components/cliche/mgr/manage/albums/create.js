/**
 * @class MODx.ClicheAlbumCreateOrUpdate
 * @extend MODx.AbstractWindow 
 * The window container for Cliche TV thumbnail manager
 */
MODx.ClicheAlbumCreateOrUpdate = Ext.extend(MODx.AbstractWindow , {
	title: 'Create a new Album'
	,components: [{
		xtype: 'modx-abstract-formpanel'
		,id: 'cliche-form-cu-album'
		,cls:'main-content'
		,layout: 'form'
		,labelAlign: 'top'
		,anchor: '100%'
		,border: false
		,defaults:{
			msgTarget: 'under'
			,anchor: '100%'
		}
		,components: [{
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
	
	,buildUI: function(config){
		config.buttons = [config.cancelBtn,{
			text: 'Save'
			,handler: this.save
			,scope: this
		}];
	}
	
	,save: function(b,t){	
		Ext.getCmp('cliche-form-cu-album').getForm().submit({
			waitMsg: 'Saving, please Wait...'
			,url     : MODx.ClicheConnectorUrl
			,params : {
				action: 'album/create'
				,ctx: 'mgr'
			}
			,success: function( form, action ) {				
				response = action.result
				data = response.object
				msg = response.message
				if(response.success){
					Ext.getCmp('cliche-albums-list').activate();					
				}				
				this.hide();
			}
			,failure: function( form, action ){
				response = action.result
				errors = response.object
				msg = response.message
				
				//Show error message binded to fields
				for(var key in errors){
					if (errors.hasOwnProperty(key)) {
						fld = errors[key];
						f = form.findField(fld.name);
						// console.log(fld.name);
						// console.log(f);
						if(f){
							f.markInvalid(fld.msg)
						}
					}
				}
				// console.log(msg);
			}
			,scope: this
		});
	}
});
Ext.reg("cliche-window- create-update", MODx.ClicheAlbumCreateOrUpdate);
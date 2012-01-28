/**
 * The base class for upload panel
 *
 * @class MODx.panel.ClicheUploadPanel
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-upload-panel
 */
MODx.panel.ClicheUploadPanel = function(config) {
	config = config || {};
	if(typeof(config.uid) == 'undefined'){ config.uid = 'default' }
	this._initTemplates();
	Ext.applyIf(config,{
		id: 'cliche-uploader-'+config.uid
		,cls: 'main-wrapper'
		,layout: 'form'
		,uploadListData: {}
		,tbar: [{
			xtype: 'button'
			,text: _('cliche.btn_back_to_album')
			,id: 'cliche-uploader-back-to-album-btn-'+config.uid
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp('cliche-album-'+config.uid).activate(this.album);
			}
			,scope: this
		},{
			xtype: 'button'
			,text: _('cliche.btn_browse')
			,id: 'cliche-uploader-browse-btn-'+config.uid
			,iconCls:'icon-add'
		},'-',{
			xtype: 'button'
			,text: _('cliche.btn_start_upload')
			,id: 'cliche-uploader-start-upload-btn-'+config.uid
			,iconCls:'icon-add-white'
			,handler: this.onStartUpload
			,cls: 'green'
			,scope: this
		}]
		,border: false
		,defaults: {
			unstyled: true
		}
		,autoHeight: true
		,items:[]
	});
	MODx.panel.ClicheUploadPanel.superclass.constructor.call(this,config);
	this._init();
};
Ext.extend(MODx.panel.ClicheUploadPanel,MODx.Panel,{
	_init: function(){
		this.add({
			xtype: 'modx-template-panel'
			,id: 'cliche-uploader-upload-list-'+this.uid
			,startingText: _('cliche.upload_desc')
			,startingMarkup: '<tpl for="."><div class="empty-msg">{text}</div></tpl>'
			,markup: this._uploadListTpl()
			//Override defaut updateDetail method implementation for adding callback
			,updateDetail: function(data) {	
				this.body.hide();
				this.tpl.overwrite(this.body, data);
				this.body.slideIn('r', {stopFx:true, duration:.2});
				setTimeout(function(){
					Ext.getCmp('modx-content').doLayout();
				}, 500);
				this.ownerCt.clientSideValidation();
			}
		});
	}
	
	,_uploadListTpl: function(){
		return '<p class="upload-ready-msg">'+_('cliche.upload_ready_msg')+'</p>'
			+'<ul class="upload-list">'
				+'<tpl for="files">'
					+'<li id="{id}">'	
						+'<div class="inner-content upload-content">'	
							+'<span class="upload-file">{name:ellipsis(60)}</span>'	
							+'<span class="upload-spinner hidden"></span>'	
							+'<span class="upload-percent hidden">0%</span>'	
							+'<span class="upload-size">{[values.size < 1024 ? values.size+" bytes" : (Math.round(((values.size*10) / 1024))/10)+" KB" ]}</span>'							
							+'<button class="upload-cancel" onclick="Ext.getCmp(\''+this.id+'\').removeFile(\'{id}\'); return false;">'+_('cliche.upload_cancel_msg')+'</button>'
							+'<span class="upload-success-hint">&nbsp;</span>'
						+'</div>'	
						+'<div class="inner-content upload-progress">&nbsp;</div>'		
					+'</li>'
				+'</tpl>'					
			+'</ul>';
	}
	
	,_initTemplates: function() {
		this.successTpl = new Ext.XTemplate( '<tpl for="."><div class="{className}"><tpl for="message">{thumbnail}<span>{message}</span></tpl></div></tpl>', {
			compiled: true
		});	
		this.errorTpl = new Ext.XTemplate( '<tpl for="."><div class="{className}">{message}</div></tpl>', {
			compiled: true
		});	
    }
	
	,containerWidth: null
	,uploader: null
	,_initUploader: function(){
		var params = {
			action: 'actions/upload'
			,album: this.album.id
			,ctx: 'mgr'
			,HTTP_MODAUTH:MODx.siteId
		};
		var extras = Ext.urlEncode(params);
		var connector = MODx.ClicheConnectorUrl + '?' + extras;
		
		

		this.uploader = new plupload.Uploader({
            url: connector
            ,runtimes: 'html5'
            ,browse_button: Ext.getCmp('cliche-uploader-browse-btn-'+this.uid).getEl().dom.id
            ,container: this.id
            ,drop_element: 'cliche-uploader-upload-list-'+this.uid
            ,multipart: false
        });
		
		var uploaderEvents = ['FilesAdded', 'FileUploaded', 'QueueChanged', 'UploadFile', 'UploadProgress', 'UploadComplete' ];
		Ext.each(uploaderEvents, function (v) { 
			var fn = 'on' + v;
			this.uploader.bind(v, this[fn], this); 
		},this);
		this.uploader.init();	
	}
	
	,activate: function(rec){
		Ext.getCmp('cliche-uploader-start-upload-btn-'+this.uid).disable();		
		this.album = rec;
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		this.updateBreadcrumbs(_('cliche.upload_items_for') + this.album.name +'</strong></p>');
		if(this.uploader !== null){
			this.resetUploader();
		} else {
			this._initUploader();
		}
		Ext.getCmp('cliche-uploader-upload-list-'+this.uid).reset();
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : this.album.name
			,pnl : 'cliche-album-'+this.uid
		},{
			text : _('cliche.breadcrumb_upload_images')
		}];
		Ext.getCmp('cliche-breadcrumbs').updateDetail(bd);
	}
	
	,deactivateBreadcrumbs: function(){
		Ext.getCmp('cliche-breadcrumbs').updateDetail({text: _('cliche.upload_in_progress'), className:'highlight'});
	}	
	
	,onFilesAdded: function(up, files){
		this.uploadListData.files = up.files;
		this.updateList = true;
	}
		
	,removeFile: function(id){
		this.updateList = true;
		var f = this.uploader.getFile(id);
		this.uploader.removeFile(f);		
	}
		
	,onQueueChanged: function(up){
		if(this.updateList){
			if(this.uploadListData.files.length > 0){
				var btn = Ext.getCmp('cliche-uploader-start-upload-btn-'+this.uid);
				Ext.getCmp('cliche-uploader-upload-list-'+this.uid).updateDetail(this.uploadListData);
				if(btn.disabled){ btn.enable() }	
			} else {
				Ext.getCmp('cliche-uploader-upload-list-'+this.uid).reset();
			}
			up.refresh();
		}	
	}
	
	,onUploadFile: function(uploader, file){
		Ext.select(this.getCurrent(file.id)).addClass('active');
		Ext.select(this.getCurrent(file.id) + ' .upload-progress').setWidth(0);
		this.containerWidth = Ext.select(this.getCurrent(file.id)).elements[0].offsetWidth - 4;
	}
	
	,onUploadProgress: function(uploader, file){
		if(this.containerWidth != null){
			var w = this.containerWidth * file.percent / 100;
			Ext.select(this.getCurrent(file.id) + ' .upload-progress').setWidth(w);
			Ext.select(this.getCurrent(file.id) + ' .upload-percent').update(file.percent + '%')
		}
	}
	
	,onUploadComplete: function(uploader, files){
		this.resetUploader();
		this.updateBreadcrumbs(_('cliche.upload_items_for') + this.album.name);
	}
	
	,onStartUpload: function(btn, e){
		this.deactivateBreadcrumbs();
		Ext.getCmp('cliche-uploader-start-upload-btn-'+this.uid).disable();
		Ext.getCmp('cliche-uploader-browse-btn-'+this.uid).hide();
		Ext.getCmp('cliche-uploader-back-to-album-btn-'+this.uid).disable();
		this.uploader.start();
	}
	
	,onFileUploaded: function(uploader, file, xhr){
		var r = Ext.util.JSON.decode( xhr.response );
		if(!r.success){	
			this.addItemErrorMessage(file.id, r.message);
		} else {
			this.addItemSuccessMessage(file.id, r);		
		}
		Ext.getCmp('modx-content').doLayout();
	}
	
	,getCurrent: function(id){
		return '.upload-list li#' + id;
	}
	
	,clientSideValidation: function(){
		var maxSize = (Cliche.uploadMaxFilesize > Cliche.postMaxSize) ? Cliche.uploadMaxFilesize : Cliche.postMaxSize ;
		var pnl = this;
		var files = this.uploader.files;
		var toRemove = [];
		Ext.each(files, function(file){
			var ext = file.name.split('.').pop();	
			var del = false;	
			// First, let's check if it's a valid extension
			if (!Cliche.allowedExtensions[ext]) {
				var del = pnl.addItemErrorMessage(file.id, _('cliche.upload_extensions_error') + Cliche.config['allowed_extension']);
				pnl.updateList = false;
				if(del){						
					toRemove.push(file);
				}				
			}
			// Is the file too large 
			if (file.size > maxSize && !del){
				var del = pnl.addItemErrorMessage(file.id, _('cliche.upload_file_too_large_error'));
				pnl.updateList = false;
				if(del){						
					toRemove.push(file);
				}
			}
		});
		// Delay file removing from the queue cause it's causing the uploader to crash
		Ext.each(toRemove, function(f){
			pnl.uploader.removeFile(f);
		})
	}
	
	,addItemErrorMessage: function(id, message){
		var desc = {
			'message' : message,
			'className' : 'what_happened'
		};
		var descTpl = this.errorTpl.apply(desc);
		var content = Ext.select('.upload-list li#' + id + ' .upload-content');
		content.createChild(descTpl);
		var item = Ext.select('.upload-list li#' + id);		
		item.removeClass('active').addClass('upload-fail');			
		return true;
	}
	
	,addItemSuccessMessage: function(id, desc){
		desc.className = 'pw';
		var descTpl = this.successTpl.apply(desc);
		var content = Ext.select('.upload-list li#' + id + ' .upload-content');
		content.createChild(descTpl);
		var item = Ext.select('.upload-list li#' + id);		
		item.setWidth(this.containerWidth);
		item.removeClass('active').addClass('upload-success');			
		return true;	
	}
	
	,resetUploader: function(){
		this.uploader.destroy();
		this.uploadListData.files = [];
		this._initUploader();
		Ext.getCmp('cliche-uploader-browse-btn-'+this.uid).show();
		Ext.getCmp('cliche-uploader-back-to-album-btn-'+this.uid).enable();
	}
});
Ext.reg('cliche-upload-panel',MODx.panel.ClicheUploadPanel);
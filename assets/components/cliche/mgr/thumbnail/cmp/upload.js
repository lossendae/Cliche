/**
 * The package browser detail panel
 *
 * @class MODx.panel.ClicheDefaultItemPanelUpload
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype cliche-item-default-upload-panel
 */
MODx.panel.ClicheDefaultItemPanelUpload = function(config) {
	config = config || {};
	this._initTemplates();
	Ext.applyIf(config,{
		id: 'thumbnail-uploader'
		,tbar: [{
			xtype: 'button'
			,text: _('cliche.btn_back_to_album')
			,id: 'thumbnail-back-to-album-btn'
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp('cliche-album-default').activate();
			}
		},{
			xtype: 'button'
			,text: _('cliche.btn_browse')
			,id: 'thumbnail-browse-btn'
			,iconCls:'icon-add'
		},'-',{
			xtype: 'button'
			,text: _('cliche.btn_start_upload')
			,id: 'thumbnail-start-upload-btn'
			,iconCls:'icon-add-white'
			,handler: this.onStartUpload
			,cls: 'green'
			,scope: this
		}]
		,items:[{
			xtype: 'modx-template-panel'
			,id: 'default-upload-list'
			,startingText: _('cliche.upload_desc')
			,startingMarkup: '<tpl for="."><div class="empty-msg">{text}</div></tpl>'
			,markup: [
				'<p class="upload-ready-msg">'+_('cliche.upload_ready_msg')+'</p>'
				,'<ul class="upload-list">'
					,'<tpl for="files">'
						,'<li id="{id}">'	
							,'<div class="inner-content upload-content">'	
								,'<span class="upload-file">{name:ellipsis(60)}</span>'	
								,'<span class="upload-spinner hidden"></span>'	
								,'<span class="upload-percent hidden">0%</span>'	
								,'<span class="upload-size">{[values.size < 1024 ? values.size+" bytes" : (Math.round(((values.size*10) / 1024))/10)+" KB" ]}</span>'							
								,'<button class="upload-cancel" onclick="Ext.getCmp(\'default-uploader\').removeFile(\'{id}\'); return false;">'+_('cliche.upload_cancel_msg')+'</button>'
								,'<span class="upload-success-hint">&nbsp;</span>'
							,'</div>'	
							,'<div class="inner-content upload-progress">&nbsp;</div>'		
						,'</li>'
					,'</tpl>'					
				,'</ul>'
			]
		}]
	});
	MODx.panel.ClicheDefaultItemPanelUpload.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ClicheDefaultItemPanelUpload,MODx.Panel,{
	activate: function(rec){
		Ext.getCmp('default-start-upload-btn').disable();		
		this.album = rec;
		Ext.getCmp('card-container').getLayout().setActiveItem(this.id);
		this.updateBreadcrumbs(_('cliche.upload_items_for') + this.album.name +'</strong></p>');
		if(this.uploader !== null){
			this.resetUploader();
		} else {
			this._initUploader();
		}
		Ext.getCmp('default-upload-list').reset();
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = { text: msg };
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : this.album.name
			,pnl : 'cliche-album-default'
		},{
			text : _('cliche.breadcrumb_upload_images')
		}];
		Ext.getCmp('cliche-breadcrumbs').updateDetail(bd);
	}
	
	,deactivateBreadcrumbs: function(){
		Ext.getCmp('cliche-breadcrumbs').updateDetail({text: _('cliche.upload_in_progress'), className:'highlight'});
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
			action: 'actions/default-upload'
			,album: this.album.id
			,ctx: 'mgr'
			,HTTP_MODAUTH:MODx.siteId
		};
		var extras = Ext.urlEncode(params);
		var connector = MODx.ClicheConnectorUrl + '?' + extras;

		this.uploader = new plupload.Uploader({
            url: connector
            ,runtimes: 'html5'
            ,browse_button: Ext.getCmp('default-browse-btn').getEl().dom.id
            ,container: 'default-uploader'
            ,drop_element: 'default-upload-list'
            ,multipart: false
        });
		
		var uploaderEvents = ['Init', 'FilesAdded', 'FilesRemoved', 'FileUploaded', 'QueueChanged', 'StateChanged', 'UploadFile', 'UploadProgress', 'Error', 'UploadComplete' ];
		Ext.each(uploaderEvents, function (v) { 
			var fn = 'on' + v;
			this.uploader.bind(v, this[fn], this); 
		},this);
		this.uploader.init();	
	}
	
	,onInit: function(uploader, data){}
	,onFilesAdded: function(up, files){
			this.uploadListData.files = up.files;
	}
	
	,onFilesRemoved: function(up, files){
		this.uploadListData.files = up.files;
	}
	
	,onFileUploaded: function(uploader, file, xhr){
		var r = Ext.util.JSON.decode( xhr.response );
		var current = Ext.select(this.getCurrent(file.id));
		var content = Ext.select(this.getCurrent(file.id) + ' .upload-content');
		if(!r.success){						
			current.removeClass('active').addClass('upload-fail');	
			r.className = 'what_happened';
			var s = this.errorTpl.apply(r);
			content.createChild(s);
		} else {
			current.setWidth(this.containerWidth);
			r.className = 'pw';
			var s = this.successTpl.apply(r);
			setTimeout(function(){
				current.removeClass('active').addClass('upload-success');							
				content.createChild(s);
				Ext.getCmp('modx-content').doLayout();
			}, 1000);			
		}
		Ext.getCmp('modx-content').doLayout();
	}
	
	,removeFile: function(id){
		var f = this.uploader.getFile(id);
		this.uploader.removeFile(f);
	}
		
	,onQueueChanged: function(up){
		if(this.uploadListData.files.length > 0){
			var btn = Ext.getCmp('default-start-upload-btn');
			Ext.getCmp('default-upload-list').updateDetail(this.uploadListData);
			if(btn.disabled){ btn.enable() }	
		}
		up.refresh();
	}
	
	,onStateChanged: function(uploader){}
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
	
	,onError: function(up, error){}	
	,onStartUpload: function(btn, e){
		this.deactivateBreadcrumbs();
		Ext.getCmp('default-start-upload-btn').disable();
		Ext.getCmp('default-browse-btn').hide();
		Ext.getCmp('default-back-to-album-btn').disable();
		this.uploader.start();
	}
	
	,getCurrent: function(id){
		return '.upload-list li#' + id;
	}
	
	,resetUploader: function(){
		this.uploader.destroy();
		this.uploadListData.files = [];
		this._initUploader();
		Ext.getCmp('default-browse-btn').show();
		Ext.getCmp('default-back-to-album-btn').enable();
	}
});
Ext.reg('cliche-item-default-upload-panel',MODx.panel.ClicheDefaultItemPanelUpload);
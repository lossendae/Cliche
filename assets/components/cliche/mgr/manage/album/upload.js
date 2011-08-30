Ext.ns('MODx');

/**
 * @class MODx.FileUploadList
 * A typical Card panel Template definition
 */
MODx.FileUploadList = Ext.extend(Ext.Panel, {
	frame:false
	,plain:true
	,autoHeight: true
	,border: false
	,startingText: _('cliche.upload_desc')
	,startingMarkup: ['<div class="empty-msg">{text}</div>']
	,tpl: [
		'<p class="upload-ready-msg">'+_('cliche.upload_ready_msg')+'</p>'
		,'<ul class="upload-list">'
			,'<tpl for="list">'
				,'<li id="item-{idx}">'	
					,'<div class="inner-content upload-content">'	
						,'<span class="upload-file">{name}</span>'	
						,'<span class="upload-spinner hidden"></span>'	
						,'<span class="upload-size">{size}</span>'	
						/* @TODO Not active yet */
						// ,'<a class="upload-cancel" href="#">'+_('cliche.upload_cancel_msg')+'</a>'	
						,'<span class="upload-failed-text">'+_('cliche.upload_fail_msg')+'</span>'
						,'<span class="upload-success-text">'+_('cliche.upload_success_msg')+'</span>'
					,'</div>'	
					,'<div class="inner-content upload-progress">&nbsp;</div>'		
				,'</li>'
			,'</tpl>'
		,'</ul>'
	]


    /**
     * initComponent
     * @protected
     */
    ,initComponent : function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));		
		MODx.FileUploadList.superclass.initComponent.apply(this, arguments);
    }
	
	,buildConfig:function(config) {
        this.buildItems(config);
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
	
	,buildUI: function(config){
       config.buttons = undefined;
    }
	
	,reset: function(data){		
		if(typeof data === "undefined"){
			data = { text: this.startingText };
		}
		this.body.hide();
		this.startingMarkup.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
	}
	
	,updateList: function(data) {		
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
	}
	
	,listeners: {
		'render': function(tp){
			this.reset();
		}
	}
});
Ext.reg("modx-fileupload-list", MODx.FileUploadList);

/**
 * @class MODx.ClichePanelUploader
 * @extend MODx.AbstractPanel
 * The upload panel container for Cliche uploader
 */
MODx.ClichePanelUploader = Ext.extend(MODx.AbstractPanel, {
	frame:false
	,plain:true
	,autoHeight: true
	,border: false
	
	//Init some required parameters
	,containerWidth: null
	,album: null
	,handler: null
	,uploadbtn: null
	,cls: 'spaced-wrapper'	
	,maxConnections: 1
	,current: 0
	,queue: 0
	
	/**
	 * Inherited factory method
	 */	
	,buildItems : function(config) {
		config.items = [{
			xtype: 'modx-fileupload-list'
			,id:'fileupload-list'
		},{
			xtype: 'form'
			,id: 'uploadform'
			,unstyled: true
			,html: ''
		}];
    } // eo function buildItems
	
	/**
	 * Inherited factory method
	 */	 
	,buildUI: function(config){
		config.tbar = [{
			xtype: 'multifileuploadfield'
			,id:'uploadformfield'
			,buttonText: _('cliche.browse')
			,buttonCfg:{
				iconCls: 'icon-browse'
			}
			,buttonOnly: true
			,allowMultiple: true
			,hidden: true
			,iconCls: 'browse'
			,listeners:{
				'fileselected': function(fb, v, input){
					files = fb.getFiles();
					Ext.getCmp('start-upload-btn').enable();					
					this.updateFileListFromMuti(files);
				}
				,scope: this
			}
		},{
			xtype: 'fileuploadfield'
			,id:'uploadfield'
			,buttonText: _('cliche.browse')
			,buttonCfg:{
				iconCls: 'icon-browse'
			}
			,buttonOnly: true
			,allowMultiple: true
			,hidden: true
			,listeners:{
				'fileselected': function(fb, v, input){
					files = fb.getFiles();
					Ext.getCmp('start-upload-btn').enable();					
					this.updateFileList(files)
				}
				,scope: this
			}
		},{
			xtype: 'button'
			,text: _('cliche.start_upload')
			,id: 'start-upload-btn'
			,iconCls: 'icon-upload'
			,disabled: true
			,handler: this.onStartUpload
			,scope: this
		}];
	} // eo function buildUI
	
	/**
	 * Set upload method handler
	 */	
	,initEvents: function(){
		MODx.ClichePanelUploader.superclass.initEvents.call(this);
		this.setHandler();
	} // eo function initEvents
	
	
	/**
	 * Set the upload method handler (xhr or iframe) for the instance regarding the browser capbilities.
	 */
	,setHandler: function(){
		if(this.isXhr()){
			this.handler = this.upload;
			this.uploadbtn = 'uploadfield';
		} else {
			this.handler = this.uploadForm;
			this.uploadbtn = 'uploadformfield'			
		}
		Ext.getCmp(this.uploadbtn).show();
	}
	
	/**
	 * This method check wether the browser support multiple file upload with xhr (using a single input file) or not.
	 * @return {Boolean}
	 */
	,isXhr: function(){
		var input = document.createElement('input');
		input.type = 'file';
		return('multiple' in input && typeof File != "undefined" 
			&& typeof (new XMLHttpRequest()).upload != "undefined");
	}	
		
	/**
	 * Get the files list to upload from the field. The field has to have a method getFiles as well.
	 */
	,getFiles: function(){
		return Ext.getCmp(this.uploadbtn).getFiles();
	}
	
	/**
	 * Set the upload file list for feedback (Iframe method).
	 * @param {Object} [file] The files to add to main feedback list
	 */
	,updateFileListFromMuti: function(files){
		items = [];
		for (i = 0; i < files.length; i++) {
			items.push(files[i]);
		}
		this.updateList({ total:files.length,  list: files });
	}
	
	/**
	 * Set the upload method handler (xhr or iframe) for the instance regarding the browser capbilities.
	 * @param {Number} [size] The raw filesize
	 */
	,formatSize: function(size){
		if(size < 1024) {
			return size + " bytes";
		} else {
			return (Math.round(((size*10) / 1024))/10) + " KB";
		}
	}
	
	/**
	 * Set the upload file list for feedback (xhr method).
	 * @param {Object} [file] The files to add to main feedback list
	 */
	,updateFileList: function(files){
		items = [];
		for (i = 0; i < files.length; i++) {
			file = files[i];
			items.push({ idx: i, name:file.name, size: this.formatSize(file.size) });
		}
		this.updateList({ total:files.length,  list: items });
	}
	
	/**
	 * Udpate the filelist element and resize the window height (only Mozilla play nicely with window autoheight).
	 * @param {Object} [data] The object containg the upload list to add to the content
	 */
	,updateList: function(data){
		currElSize = Ext.getCmp('fileupload-list').getHeight();
		//Add the content
		Ext.getCmp('fileupload-list').updateList(data);
	}
	
	/**
	 * This method and the following should be in a single methiod.
	 */
	,onStartUpload: function(btn, e){	
		//Prevent user to use breadcrumbs while uploading
		this.deactivateBreadcrumbs();
		//Set active item
		current = Ext.select('.upload-list li#item-'+this.current);
		current.addClass('active');
		//Set container width for progress bar
		this.containerWidth = current.elements[0].offsetWidth - 4;	
		Ext.select('.upload-list li#item-'+this.current+' .upload-progress').setWidth(0);
		this.queue = Ext.select('.upload-list li').elements.length - 1;
		//Pass file to upload handler
		files = this.getFiles();
		if(typeof(files) == "object"){
			this.handler(files[this.current], this.current);
		} else {
			console.log('error');
		}		
	}
	
	/**
	 * Get the next file to upload.
	 * @param {Number} [idx] The elements id that has been uploaded
	 */
	,getNextFile: function(idx){
		if(this.current <= this.queue){
			//Set next item active
			Ext.select('.upload-list li#item-'+this.current).addClass('active');
			Ext.select('.upload-list li#item-'+this.current+' .upload-progress').setWidth(0);
			//Pass next file to uploa handler
			files = this.getFiles();
			if(typeof(files) == "object"){
				this.handler(files[this.current], this.current);
			} else {
				console.log('Error');
			}	
		} else {					
			//Disable the upload button
			Ext.getCmp(this.uploadbtn).reset();
			Ext.getCmp('start-upload-btn').disable();
			this.current = 0;
			//Show success message
			this.updateBreadcrumbs(_('cliche.upload_successful'));
		}
	}
		
	/**
	 * Upload the given file using xhr method with support for progreebar.
	 * @param {Object} [file] The file to upload
	 * @param {Number} [idx] The elements id for feedback
	 */
	,upload:function(file, idx){
		var uploader = new Ext.ux.XHRUpload({
			url: this.url
			,file:file
			,params: {
				action:'image/upload'
				,album: this.album
				,ctx: 'mgr'
				,HTTP_MODAUTH:MODx.siteId
			}
			,listeners:{
				uploadprogress:function(event){
					if(this.containerWidth != null){
						percent = event.loaded * 100 / event.totalSize;
						w = this.containerWidth * percent / 100;
						Ext.select('.upload-list li#item-'+this.current+' .upload-progress').setWidth(w);
					}
				}
				,onComplete: function(file, xhr, e){
					Ext.select('.upload-list li#item-'+this.current+' .upload-progress').setWidth(this.containerWidth);					
					r = Ext.util.JSON.decode(xhr.responseText);
					current = Ext.select('.upload-list li#item-'+this.current);
					content = Ext.select('.upload-list li#item-'+this.current+' .upload-content');
					if(!r.success){						
						current.removeClass('active').addClass('upload-fail');						
						content.createChild('<div class="what_happened">'+ r.message +'</div>');
					} else {
						current.removeClass('active').addClass('upload-success');
						content.createChild('<div class="pw">'+ r.message +'</div>');
					}
					this.current = this.current + 1;					
					this.getNextFile(idx);	
					Ext.getCmp('modx-content').doLayout();
				}
				,scope: this
			}			
		});
		uploader.send();
	}
	
	/**
	 * Upload the given file using Ajax and Iframe for browser that don't support xhr upload (IE & Opera).
	 * @param {Object} [file] The file to upload
	 * @param {Number} [idx] The elements id for feedback
	 */
	,uploadForm:function(file, idx){
		input = Ext.getCmp(this.uploadbtn).getInput(0);
		Ext.getCmp('uploadform').getForm().getEl().update('').appendChild(input);
		Ext.Ajax.request({
			url: this.url
			,params: {
				action:'image/upload'
				,ctx: 'mgr'
				,album: this.album
			}			
			,form: Ext.getCmp('uploadform').getForm().getEl()
			,isUpload: true
			,success: function( result, request ) {				
				response = Ext.util.JSON.decode(result.responseText);
				if(response.success){
					this.current = this.current + 1;
					this.getNextFile(idx);
				}
			}
			,failure: function( result, request ){
				console.log(result);
			}
			,scope: this
		});
	}
	
	,activate: function(rec){
		/* @TODO - message d'erreur si l'id n'est pas fournie */
		Ext.getCmp('fileupload-list').reset();
		if(rec != undefined){
			this.album = rec.id;
			this.records = rec;
		} 	
		this.updateBreadcrumbs(_('cliche.upload_items_for') + this.records.name);
		Ext.getCmp('cliche-albums-mgr-container').setActiveItem(2);	
	}
	
	,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-albums-desc').updateDetail({
			text: msg
			,trail: [{
				text : _('cliche.breadcrumb_root')
				,cmp: 'cliche-albums-list'
				,isLink: true				
			},{
				text: this.records.name
				,cmp: 'cliche-album-view'
				,isLink: true
				,className: 'last'
			},{
				text: _('cliche.breadcrumbs_upload_pictures_msg')
				,isLink: false
				,className: 'active'
			}]
		});
	}
	
	,deactivateBreadcrumbs: function(){
		Ext.getCmp('cliche-albums-desc').updateDetail({text: _('cliche.upload_in_progress')});
	}
});
Ext.reg("cliche-panel-uploader", MODx.ClichePanelUploader);
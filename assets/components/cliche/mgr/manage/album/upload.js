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
		'<p>'+_('cliche.upload_ready_msg')+'</p>'
		,'<ul class="upload-list">'
			,'<tpl for="list">'
				,'<li id="item-{idx}">'	
					,'<span class="upload-file">{name}</span>'	
					,'<span class="upload-spinner hidden"></span>'	
					,'<span class="upload-size">{size}</span>'	
					,'<a class="upload-cancel" href="#">'+_('cliche.upload_cancel_msg')+'</a>'	
					,'<span class="upload-failed-text">'+_('cliche.upload_fail_msg')+'</span>'
					,'<span class="upload-success-text">'+_('cliche.upload_success_msg')+'</span>'
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
				iconCls: 'browse16'
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
				iconCls: 'browse16'
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
			,iconCls: 'up16'
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
		//And update the window size
		// newElSize = Ext.getCmp('fileupload-list').getHeight();
		// winSize = Ext.getCmp('win-uploader').getHeight();
		// Ext.getCmp('win-uploader').setHeight((winSize - currElSize) + newElSize);
	}
	
	/**
	 * This method and the following should be in a single methiod.
	 */
	,onStartUpload: function(btn, e){		
		Ext.select('.upload-list li#item-'+this.current).addClass('active');
		this.queue = Ext.select('.upload-list li').elements.length - 1;
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
		// curr = Ext.select('.upload-list li#item-'+idx);
		// curr.removeClass('active').addClass('upload-success');
		if(this.current <= this.queue){
			Ext.select('.upload-list li#item-'+this.current).addClass('active');
			files = this.getFiles();
			if(typeof(files) == "object"){
				this.handler(files[this.current], this.current);
			} else {
				console.log('Error');
			}	
		} else {					
			//Reset the upload field
			// Ext.getCmp('fileupload-list').reset();
			Ext.getCmp(this.uploadbtn).reset();
			this.current = 0;
			//Disable the upload button
			// Ext.getCmp('start-upload-btn').disable();
			//Show success message
			//@TODO - Add a time out success message or a message on return to album
			/* Go Back to album */
			/* TODO */
			// Ext.getCmp('cliche-album-view').activate();
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
					// console.log(event);
				}
				,onComplete: function(file, xhr, e){
					/* TODO */
					r = Ext.util.JSON.decode(xhr.responseText);
					// console.log(r);
					// curr = Ext.select('.upload-list li#item-'+idx);
					// curr.removeClass('active').addClass('upload-success');
					if(!r.success){						
						Ext.select('.upload-list li#item-'+this.current).removeClass('active');
						Ext.select('.upload-list li#item-'+this.current).addClass('upload-fail');
						Ext.select('.upload-list li#item-'+this.current).createChild('<div class="what_happened">'+ r.message +'</div>');
					} else {
						Ext.select('.upload-list li#item-'+this.current).addClass('upload-success');
						Ext.select('.upload-list li#item-'+this.current).createChild('<div class="pw">'+ r.message +'</div>');
					}
					this.current = this.current + 1;					
					this.getNextFile(idx);
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
		
		if(rec != undefined){
			this.album = rec.id;
			this.records = rec;
		} 	
		Ext.getCmp('cliche-albums-desc').updateDetail({
			text: 'Upload picture for the album '+ this.records.name
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
		Ext.getCmp('cliche-albums-mgr-container').setActiveItem(2);	
	}
});
Ext.reg("cliche-panel-uploader", MODx.ClichePanelUploader);
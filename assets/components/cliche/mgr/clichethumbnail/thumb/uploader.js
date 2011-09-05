Ext.ns('MODx');

/**
 * @class MODx.FileUploadList
 * @extend MODx.AbstractTemplatePanel
 * A typical Card panel Template definition
 */
MODx.FileUploadList = Ext.extend(MODx.AbstractTemplatePanel, {
	frame:false
	,plain:true
	,autoHeight: true
	,border: false
	,startingText: _('cliche.upload_desc')
	,startingMarkup: ['<div class="empty-msg">{text}</div>']
	,tpl: [
		'<ul class="upload-list">'
			,'<tpl for="list">'
				,'<li id="item-{idx}">'	
					,'<div class="inner-content upload-content">'	
						,'<span class="upload-file">{name}</span>'	
						,'<span class="upload-spinner hidden"></span>'	
						,'<span class="upload-size">{size}</span>'
                        ,'<a class="inline-button upload-button plain" onclick="Ext.getCmp(\'cliche-thumb-uploader-{tvId}\').onStartUpload(); return false;" href="#">Start upload</a>'
						,'<span class="upload-failed-text">'+_('cliche.upload_fail_msg')+'</span>'
						,'<span class="upload-success-text">'+_('cliche.upload_success_msg')+'</span>'
					,'</div>'	
					,'<div class="inner-content upload-progress">&nbsp;</div>'		
				,'</li>'
			,'</tpl>'
		,'</ul>'
	]

	,updateList: function(data) {		
		this.body.hide();
		this.tpl.overwrite(this.body, data);
		this.body.slideIn('r', {stopFx:true, duration:.2});
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
	,cls: ''
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
			,allowMultiple: false
			,hidden: true
			,iconCls: 'browse'
			,listeners:{
				'fileselected': function(fb, v, input){
					files = fb.getFiles();
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
			,allowMultiple: false
			,hidden: true
			,listeners:{
				'fileselected': function(fb, v, input){
					files = fb.getFiles();
					this.updateFileList(files)
				}
				,scope: this
			}
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
			items.push({ idx: i, name:file.name, size: this.formatSize(file.size), tvId: this.tvId });
		}
		this.updateList({ total:files.length,  list: items });
	}
	
	/**
	 * Udpate the filelist element and resize the window height (only Mozilla play nicely with window autoheight).
	 * @param {Object} [data] The object containg the upload list to add to the content
	 */
	,updateList: function(data){
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
			//Pass next file to upload handler
			files = this.getFiles();
			if(typeof(files) == "object"){
				this.handler(files[this.current], this.current);
			} else {
				console.log('Error');
			}	
		} else {					
			//Disable the upload button
			Ext.getCmp(this.uploadbtn).reset();
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
				action:'clichethumbnail/upload'
				,resourceId: this.configTv.resourceId
				,tv: this.tvId
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
						content.createChild('<div class="pw">'+ r.message.thumbnail +'<a class="inline-button plain" onclick="Ext.getCmp(\'cliche-thumb-uploader-'+this.tvId+'\').validatePicture(); return false;" href="#">Use this image</a></div>');
					}
					this.record = r.message
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
				action: 'clichethumbnail/upload'
				,ctx: 'mgr'
				,resourceId: this.configTv.resourceId
				,tv: this.tvId
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
	
	,activate: function(){
		/* @TODO - message d'erreur si l'id n'est pas fournie */
		Ext.getCmp('fileupload-list').reset();
		this.updateBreadcrumbs('Upload a picture to use as thumbnail for your document');
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
	}
	
	,updateBreadcrumbs: function(msg){
		Ext.getCmp('cliche-thumb-bd-'+this.tvId).updateDetail({
			text: msg
			,trail: [{
				text : 'Your thumbnail'
				,cmp: this.mainCard
				,isLink: true
			},{
				text: 'Thumbnail album browser'
                ,cmp: this.albumViewCard
                ,className: 'last'
				,isLink: true	
			},{
				text: 'Upload a new file'
				,isLink: false
				,className: 'active'
			}]
		});
	}
	
	,deactivateBreadcrumbs: function(){
		Ext.getCmp('cliche-original-'+this.tvId).updateDetail({text: _('cliche.upload_in_progress')});
	}
	
	,validatePicture: function(){
		Ext.getCmp('cliche-main-'+this.tvId).activate(this.record, false);
	}
});
Ext.reg("cliche-panel-uploader", MODx.ClichePanelUploader);
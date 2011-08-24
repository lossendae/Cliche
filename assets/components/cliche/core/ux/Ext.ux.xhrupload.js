Ext.ux.XHRUpload = function(config){
	Ext.apply(this, config, {
			method: 'POST'
			,fileNameHeader: 'X-File-Name'
			,filePostName:'fileName'
			,contentTypeHeader: 'application/octet-stream'
			,params:{}
			,sendMultiPartFormData:false
	});
	this.addEvents( //extend the xhr's progress events to here
			'loadstart'
			,'progress'
			,'abort'
			,'error'
			,'load'
			,'loadend'
			,'onComplete'
	);
	Ext.ux.XHRUpload.superclass.constructor.call(this);
};
Ext.extend(Ext.ux.XHRUpload, Ext.util.Observable,{
	send:function(config){
		Ext.apply(this, config);
		
		this.xhr = new XMLHttpRequest();	

		this.xhr.addEventListener('loadstart', this.relayXHREvent.createDelegate(this), false);
		this.xhr.addEventListener('progress', this.relayXHREvent.createDelegate(this), false);
		this.xhr.addEventListener('progressabort', this.relayXHREvent.createDelegate(this), false);
		this.xhr.addEventListener('error', this.relayXHREvent.createDelegate(this), false);
		this.xhr.addEventListener('load', this.relayXHREvent.createDelegate(this), false);
		this.xhr.addEventListener('loadend', this.relayXHREvent.createDelegate(this), false);
		this.xhr.addEventListener('readystatechange', this._checkState.createDelegate(this), false);
		
		this.xhr.upload.addEventListener('loadstart', this.relayUploadEvent.createDelegate(this), false);
		this.xhr.upload.addEventListener('progress', this.relayUploadEvent.createDelegate(this), false);
		this.xhr.upload.addEventListener('progressabort', this.relayUploadEvent.createDelegate(this), false);
		this.xhr.upload.addEventListener('error', this.relayUploadEvent.createDelegate(this), false);
		this.xhr.upload.addEventListener('load', this.relayUploadEvent.createDelegate(this), false);
		this.xhr.upload.addEventListener('loadend', this.relayUploadEvent.createDelegate(this), false);
		
		this.params.qqfile = this.file[this.filePostName];
		var extras = Ext.urlEncode(this.params);
		this.url = this.url + '?' + extras;

		this.xhr.open(this.method, this.url, true);
		this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		this.xhr.setRequestHeader(this.fileNameHeader, encodeURIComponent(this.file.name));
		this.xhr.setRequestHeader("Content-Type", this.contentTypeHeader);
		this.xhr.send(this.file);
		
		
	}
	,relayUploadEvent:function(event){
		this.fireEvent('upload'+event.type, event);
	}
	,relayXHREvent:function(event){
		this.fireEvent(event.type, event);
	}
	,_checkState:function(event){
		if(this.xhr.readyState == 4){
			this.fireEvent('onComplete', this.file, this.xhr, event);
		}
	}
});
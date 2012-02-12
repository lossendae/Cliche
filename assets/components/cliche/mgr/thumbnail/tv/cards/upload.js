/**
 * The package browser detail panel
 *
 * @class Cliche.ThumbnailUploadPanel
 * @extends MODx.Panel
 * @param {Object} config An object of options.
 * @xtype clichethumbnail-upload-panel
 */
Cliche.ThumbnailUploadPanel = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		tbar: [{
			xtype: 'button'
			,text: _('clichethumbnail.btn_back_to_album')
			,id: 'cliche-uploader-back-to-album-btn-'+config.tv
			,iconCls:'icon-back'
			,handler: function(){
				Ext.getCmp(config.albumViewCard).activate();
			}
		},{
			xtype: 'button'
			,text: _('clichethumbnail.btn_browse')
			,id: 'cliche-uploader-browse-btn-'+config.tv
			,iconCls:'icon-add'
		},{
			xtype: 'button'
			,text: _('clichethumbnail.btn_start_upload')
			,id: 'cliche-uploader-start-upload-btn-'+config.tv
			,iconCls:'icon-add-white'
			,handler: this.onStartUpload
			,cls: 'green'
			,scope: this
		}]
	});
	Cliche.ThumbnailUploadPanel.superclass.constructor.call(this,config);
};
Ext.extend(Cliche.ThumbnailUploadPanel,MODx.panel.ClicheUploadPanel,{
	_init: function(){
		this.add({
			xtype: 'modx-template-panel'
			,id: 'cliche-uploader-upload-list-'+this.uid
			,startingText: _('clichethumbnail.upload_desc')
			,startingMarkup: '<tpl for="."><div class="empty-msg">{text}</div></tpl>'
			,markup: this._uploadListTpl()
		});
	}
	
	,_uploadListTpl: function(){
		return '<p class="upload-ready-msg">'+_('clichethumbnail.upload_ready_msg')+'</p>'
			+'<ul class="upload-list">'
				+'<tpl for="files">'
					+'<li id="{id}">'	
						+'<div class="inner-content upload-content">'	
							+'<span class="upload-file">{name:ellipsis(60)}</span>'	
							+'<span class="upload-spinner hidden"></span>'	
							+'<span class="upload-percent hidden">0%</span>'	
							+'<span class="upload-size">{[values.size < 1024 ? values.size+" bytes" : (Math.round(((values.size*10) / 1024))/10)+" KB" ]}</span>'							
							+'<button class="upload-cancel" onclick="Ext.getCmp(\'clichethumbnail-upload-panel-'+this.uid+'\').removeFile(\'{id}\'); return false;">'+_('cliche.upload_cancel_msg')+'</button>'
							+'<span class="upload-success-hint">&nbsp;</span>'
						+'</div>'	
						+'<div class="inner-content upload-progress">&nbsp;</div>'		
					+'</li>'
				+'</tpl>'					
			+'</ul>';
	}
	
	,activate: function(rec){
		Ext.getCmp('cliche-uploader-start-upload-btn-'+this.tv).disable();		
		this.album = rec;
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
		this.updateBreadcrumbs();
		if(this.uploader !== null){
			this.resetUploader();
		} else {
			this._initUploader();
		}
		Ext.getCmp('cliche-uploader-upload-list-'+this.uid).reset();
	}

	,updateBreadcrumbs: function(msg, highlight){
		var bd = {};
		bd.text = msg;
        if(highlight){ bd.className = 'highlight'; }
		bd.trail = [{
			text : _('clichethumbnail.breadcrumb_album')
			,pnl : this.albumViewCard
		},{
			text : _('clichethumbnail.breadcrumb_upload')
		}];
		Ext.getCmp(this.breadcrumbs).updateDetail(bd);
	}
	
	,deactivateBreadcrumbs: function(){
		Ext.getCmp(this.breadcrumbs).updateDetail({text: _('clichethumbnail.upload_in_progress'), className:'highlight'});
	}
});
Ext.reg('clichethumbnail-upload-panel',Cliche.ThumbnailUploadPanel);
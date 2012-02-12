/**
 * The panel container for Cliche TV thumbnail
 * @class Cliche.ThumbnailCropperPanel
 * @extend MODx.Panel
 * @xtype clichethumbnail-cropper-panel
 */
Cliche.ThumbnailCropperPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
		coords: null
		,cls: 'main-wrapper modx-template-detail'
		,bodyCssClass: 'body-wrapper'
		,initial: false
		,layout: 'column'
		,border: false
		,items:[{
			xtype: 'modx-template-panel'
			,id: 'cliche-original-'+config.tv
			,startingText: _('clichethumbnail.cropper_empty_msg')
			,startingMarkup: '<div class="crop_tpl">'
				+'{text}'
				+'<hr class="sep_crop"/>'
			+'</div>'
			,markup: '<div class="crop_tpl">'
				+'<div id="panelholder-{tv}">'
					+'<img src="{image}?t={timestamp}" id="cropbox-{tv}" alt="Your thumbnail preview" class="thumb_pw"/>'
				+'</div>'
			+'</div>'
			,columnWidth: 1
		},{
			xtype: 'modx-template-panel'
			,id: 'cliche-preview-'+config.tv
			,startingText: _('clichethumbnail.cropper_empty_msg')
			,startingMarkup: '<div class="crop_tpl">'
				+'{text}'
				+'<hr class="sep_crop"/>'
			+'</div>'
			,markup: '<div class="crop_tpl">'
				+'<div class="crop_pw" style="width:{width}px;height:{height}px;overflow:hidden;">'
					+'<img src="{image}?t={timestamp}" id="preview-{tv}"/>'
				+'</div>'
			+'</div>'
			,width: 605
		}]
		,tbar: [{
			text: _('clichethumbnail.btn_crop_validate')
			,iconCls: 'icon-add'
			,handler: function(btn,e){
                this.coords.crop = true;
                this.coords.reload = true;
				Ext.getCmp(this.mainCard).updateThumbnail(this.image.id, this.coords);
			}
			,scope: this
		}]
	});
	Cliche.ThumbnailCropperPanel.superclass.constructor.call(this,config);
}
Ext.extend(Cliche.ThumbnailCropperPanel, MODx.Panel,{
	activate: function(record, initial){
        this.image = record;
        if(record !== undefined) this.updateCropper(record, initial);
        this.updateBreadcrumbs(_('clichethumbnail.breadcrumb_crop_desc'));
		Ext.getCmp(this.cardContainer).setActiveItem(this.id);
    }
	
	,updateBreadcrumbs: function(msg){
		var bd = { text: msg };
		bd.trail = [{
			text : _('clichethumbnail.breadcrumb_crop')
		}];
		Ext.getCmp(this.breadcrumbs).updateDetail(bd);
	}
	
	,initCropper: function(data, initial){
		var me = this;
		var params = {
			onChange: showPreview
			,onSelect: showPreview
			,boxWidth: 260
		}

        if(this.tvConfig.keep_aspect_ratio == 'true'){
            var aspectRatio = this.getAspectRatio(this.previewWidth, this.previewHeight);
            params.aspectRatio = aspectRatio.width / aspectRatio.height;
        }

		if(typeof(initial) == 'object'){ params.setSelect = initial; }
		setTimeout(function(){ jQuery('#cropbox-'+me.tv).Jcrop(params); }, 1000);

		function showPreview(coords){			
			var pw = $('#cropbox-'+me.tv).width();
			var ph = $('#cropbox-'+me.tv).height();

			if (parseInt(coords.w) > 0)
			{
				var rx = me.previewWidth / coords.w;
				var ry = me.previewHeight / coords.h;
				jQuery('#preview-'+me.tv).css({
					width: Math.round(rx * pw) + 'px',
					height: Math.round(ry * ph) + 'px',
					marginLeft: '-' + Math.round(rx * coords.x) + 'px',
					marginTop: '-' + Math.round(ry * coords.y) + 'px'
				});
			}
			me.coords = coords;
		}
	}

    ,getAspectRatio: function(width, height){
        var gcd = this.getGCD(width, height);
        return {"width": width/gcd, "height": height/gcd};
    }

    /* Greatest Common Divisor */
    ,getGCD: function(a, b){
        return (b == 0) ? a : this.getGCD(b, a%b);
    }

    ,scaleImage: function(width, height, maxWidth, maxHeight){
        var ratio = 0;
        var result = {};
        if(width > maxWidth){
            ratio = maxWidth / width;
            width = maxWidth;
            height = height * ratio;
        }
        if(height > maxHeight){
            ratio = maxHeight / height;
            width = width * ratio;
            height = maxHeight;
        }
        result.width = width;
        result.height = height;
        return result;
    }
	
	,updateCropper: function(record, initial){
        scaledPreview = this.scaleImage(this.tvConfig.thumbwidth, this.tvConfig.thumbheight, 585, 365);
        record.width = Math.round(scaledPreview.width);
        record.height = Math.round(scaledPreview.height);
        this.previewWidth = record.width;
        this.previewHeight = record.height;

    	Ext.getCmp('cliche-original-'+this.tv).updateDetail(record);
		Ext.getCmp('cliche-preview-'+this.tv).updateDetail(record);
		this.initCropper(record, initial);
    }

    /**
     * Overwrites obj1's values with obj2's and adds obj2's if non existent in obj1
     * @param obj1
     * @param obj2
     * @returns obj3 a new object based on obj1 and obj2
     */
    ,mergeOptions: function(obj1,obj2){
        var obj3 = {};
        for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
        for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
        return obj3;
    }
});
Ext.reg("clichethumbnail-cropper-panel", Cliche.ThumbnailCropperPanel);
MODx.ClicheThumbMainCard = Ext.extend(MODx.AbstractPanel, {
	coords: null
	,startingMarkup :[
		'<div class="crop_tpl">'
			,'{text}'
			,'<hr class="sep_crop"/>'
		,'</div>'
	]
	//Use buildItem constructor because of tvID
	,buildItems: function(config){
		config.items = [{
			layout: 'column'
			,border: false
			,items:[{
				xtype: 'my-tpl-panel'
				,id: 'cliche-original-'+this.tvId
				,startingText: '<h4>Thumbnail image source will be shown below</h4>'
				,startingMarkup: this.startingMarkup
				,tpl: [
					'<div class="crop_tpl">'
                        /* @TODO: replace all id based items to specific to tv ids */
						,'<div id="panelholder">'
							,'<img src="{image}?t={timestamp}" id="cropbox" alt="Your thumbnail preview" class="thumb_pw"/>'
						,'</div>'
					,'</div>'
				]
				,columnWidth: 1
			},{
				xtype: 'my-tpl-panel'
				,id: 'cliche-preview-'+this.tvId
				,startingText: '<h4>Thumbnail preview will appear once you have selected an image from the browser</h4>'
				,startingMarkup: this.startingMarkup
				,tpl: [
					'<div class="crop_tpl">'
						,'<div class="crop_pw" style="width:{width}px;height:{height}px;overflow:hidden;">'
							,'<img src="{image}?t={timestamp}" id="preview"/>'
						,'</div>'
					,'</div>'
				]
				,width: 605
			}]
		}]
	}
	
	,buildUI: function(config){		
		config.tbar = [{
			text: 'Browse album'
			,iconCls: 'icon-add'
			,handler: function(btn,e){
				Ext.getCmp('cliche-thumb-album-view-'+this.tvId).activate();
			}
			,scope: this
		},{
			text: 'Upload a picture'
			,iconCls: 'icon-upload'
			,handler: function(btn,e){
//                uploader
				Ext.getCmp('cliche-thumb-uploader-'+this.tvId).activate('cliche-thumb-uploader-'+this.tvId);
			}
			,scope: this
		}]
	}
		
	,initCropper: function(data, initial){
		var me = this;
		this.image = data.image;
		var params = {
			onChange: showPreview
			,onSelect: showPreview
			,boxWidth: 260
		}
		if(typeof(initial) == 'object'){ params.setSelect = initial; }
		setTimeout(function(){ jQuery('#cropbox').Jcrop(params); }, 1000);

		function showPreview(coords){
			var pw = $('#cropbox').width();
			var ph = $('#cropbox').height();

			if (parseInt(coords.w) > 0)
			{
				var rx = me.previewWidth / coords.w;
				var ry = me.previewHeight / coords.h;
				jQuery('#preview').css({
					width: Math.round(rx * pw) + 'px',
					height: Math.round(ry * ph) + 'px',
					marginLeft: '-' + Math.round(rx * coords.x) + 'px',
					marginTop: '-' + Math.round(ry * coords.y) + 'px'
				});
			}
			me.coords = coords;
		}
	}

    ,scaleImage: function(w, h, maxWidth, maxHeight){
        var ratio = 0;
        var result = {};
        var width = w
        var height = h
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



	,activate: function(record, initial){
        if(record !== undefined) this.updateCropper(record, initial);
        Ext.getCmp('cliche-thumb-bd-'+this.tvId).reset()
		Ext.getCmp('cliche-thumb-cards-'+this.tvId).getLayout().setActiveItem(0);	
    }

    /* @TODO DIRTY!! needs refactoring */
    ,updateCropper: function(record, initial){
        this.configTv.image_id = record.id;
        this.configTv.timestamp = record.timestamp;
        this.configTv.phpthumb = record.phpthumb;
        scaledPreview = this.scaleImage(this.configTv.twidth, this.configTv.theight, 585, 365);
        record.width = Math.round(scaledPreview.width);
        record.height = Math.round(scaledPreview.height);
        this.previewWidth = record.width;
        this.previewHeight = record.height;

    	Ext.getCmp('cliche-original-'+this.tvId).updateDetail(record);
		Ext.getCmp('cliche-preview-'+this.tvId).updateDetail(record);
		Ext.getCmp('cliche-main-'+this.tvId).initCropper(record, initial);
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

    ,getThumb: function(){
        this.coords.id = this.configTv.image_id;
        this.coords.width = this.configTv.twidth;
        this.coords.height = this.configTv.theight;

        /* Merge options for ajax request */
        params = this.mergeOptions({
            action: 'clichethumbnail/create_thumbnail'
            ,ctx: 'mgr'
            ,resourceId: this.configTv.resourceId
            ,tv: this.tvId
        }, this.coords);

        /* Get the generated thumbnail */
        Ext.Ajax.request({
            url: this.url
            ,params: params
            ,success: function( result, request ) {
                response = Ext.util.JSON.decode(result.responseText);
                if(response.success){
                    this.updateTv(response.image);
                }
            }
            ,failure: function( result, request ){
                console.log(result);
            }
            ,scope: this
        });
    }

    ,updateTv: function(data){
        this.mergeOptions(data, this.coords);
        Ext.getCmp('clichethumbnail-pw-'+this.tvId).updateDetail(data);
        Ext.getCmp('clichethumbnail-pw-'+this.tvId).onUpdateThumbnailPreview();
        tv = Ext.select('#tv'+this.tvId);
        tv.elements[0].value = Ext.encode(data);
        Ext.getCmp('modx-panel-resource').markDirty();
    }

});
Ext.reg("cliche-thumb-main-card", MODx.ClicheThumbMainCard);
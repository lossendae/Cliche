Ext.ns('Cliche');

/**
 * The panel container for Cliche TV thumbnail
 * @class Cliche.Thumbnail
 * @extend Ext.Panel
 * @xtype clichethumbnail
 */
Cliche.Thumbnail = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'form'
        ,id: 'clichethumbnail-panel-'+config.tv.id
        ,items:[{
            xtype: 'hidden'
            ,name: 'tv'+config.tv.id
            ,id: 'tv'+config.tv.id
            ,value: Ext.encode(config.tv.value)
        },{
            xtype : 'modx-template-panel'
            ,id: 'clichethumbnail-pw-'+config.tv.id
            ,cls: 'cliche-thumb-pw'
            ,startingMarkup: '<div class="cropped">{text}</div>'
            ,startingText: _('clichethumbnail.main_default_text')
            ,markup: '<div class="cropped">'
                +'<img src="{thumbnail}?t={timestamp}" alt="'+ _('clichethumbnail.main_your_preview') +'" class="thumb_pw"/>'
            +'</div>'
            ,listeners:{
                afterrender: this.init
                ,scope: this
            }
        }]    
        ,win: false
        ,tv: null
        ,tbar : [{
            xtype: 'button'
            ,text: _('clichethumbnail.btn_browse')
            ,id: 'manage-thumb-btn-'+config.tv.id
            ,handler: this.showThumbnailManager
            ,scope: this
        },{
            xtype: 'button'
            ,text: _('clichethumbnail.btn_remove_thumbnail')
            ,id: 'remove-thumb-btn-'+config.tv.id
            ,handler: this.onRemoveThumbnail
            ,hidden: true
            ,scope: this
        }]
    });
    Cliche.Thumbnail.superclass.constructor.call(this,config);
}
Ext.extend(Cliche.Thumbnail, Ext.Panel,{
    init: function(){    
        if(typeof(this.tv.value) == "string")
            this.tv.value = Ext.util.JSON.decode(this.tv.value);
        if(typeof(this.tv.value) == "object" &&  typeof(this.tv.value.thumbnail) !== "undefined"){
            Ext.getCmp('remove-thumb-btn-'+ this.tv.id).show();
            Ext.getCmp('manage-thumb-btn-'+ this.tv.id).setText(_('clichethumbnail.btn_replace_thumbnail'));
            Ext.getCmp('clichethumbnail-pw-'+this.tv.id).updateDetail(this.tv.value);
        }
    } // eo function init
    
    ,showThumbnailManager: function(btn,e){
        if(!this.win){
            this.win = new Cliche.WindowThumbnailManager({
                id: 'cliche-thumb-manager-window-'+ this.tv.id
                ,image: this.tv.value
                ,tvConfig: this.tv.output_properties
                ,tv: this.tv.id
                ,previewPanel: this.id
                ,listeners:{
                    show: function(w){
                        //Fix for webkit browsers
                        w.setHeight('auto');
                    }
                }
            });
        }
        this.win.show(btn.id);
        var pos = this.win.getPosition(true);
        this.win.setPosition(pos[0], 35);
        if(typeof(this.tv.value) == "object" &&  typeof(this.tv.value.thumbnail) !== "undefined"){
            this.win.setCurrentThumb();
        }
    } // eo function showThumbnailManager
    
    ,isEmpty: function (obj) {
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop))
            return false;
        }
        return true;
    } // eo function isEmpty
    
    ,onRemoveThumbnail: function(btn, e){
        Ext.getCmp('manage-thumb-btn-'+ this.tv.id).setText(_('clichethumbnail.btn_browse'));
        this.reset();
        btn.hide();
        var tv = Ext.select('#tv'+this.tv.id);
        tv.elements[0].value = Ext.encode({});
        if(typeof Ext.getCmp('modx-panel-resource').markDirty == "function"){
            Ext.getCmp('modx-panel-resource').markDirty();
        }  
    } // eo function onRemoveThumbnail
    
    ,onUpdateThumbnailPreview: function(image){
        Ext.getCmp('clichethumbnail-pw-'+this.tv.id).updateDetail(image);
        var btn = Ext.getCmp('remove-thumb-btn-'+ this.tv.id);
        if(btn.hidden){
            btn.show();
            Ext.getCmp('manage-thumb-btn-'+ this.tv.id).setText(_('clichethumbnail.btn_replace_thumbnail'));
        }
    } // eo function onUpdateThumbnailPreview
});    
Ext.reg('clichethumbnail',Cliche.Thumbnail);
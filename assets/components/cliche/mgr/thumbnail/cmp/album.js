/**
 * The package browser detail panel
 *
 * @class MODx.panel.ClicheAlbumPanelThumbnail
 * @extends MODx.panel.ClicheAlbumPanel
 * @param {Object} config An object of options.
 * @xtype cliche-album-clichethumbnail
 */
MODx.panel.ClicheAlbumPanelThumbnail = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cliche-album-'+config.uid
        ,cls: 'main-wrapper modx-template-detail'
        ,bodyCssClass: 'body-wrapper'
        ,layout: 'column'
        ,tbar: [{
            xtype: 'button'
            ,text: _('cliche.btn_back_to_album_list')
            ,iconCls:'icon-back'
            ,handler: function(){
                Ext.getCmp('album-list').activate();
            }            
        },'-',{
            text: _('cliche.btn_add_photo')
            ,cls: 'green'
            ,iconCls: 'icon-add-white'
            ,handler: this.onaddPhoto
            ,scope: this
        }]
        ,border: false
        ,autoHeight: true
        ,border: false
        ,autoHeight: true
    });
    MODx.panel.ClicheAlbumPanelThumbnail.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ClicheAlbumPanelThumbnail,MODx.panel.ClicheAlbumPanel,{
    _initDescTpl: function(){
        this.albumDescTpl = new Ext.XTemplate( '<tpl for=".">'+_('clichethumbnail.album_desc')+'</tpl>', {
            compiled: true
        });    
    }
});
Ext.reg('cliche-album-panel-thumbnail',MODx.panel.ClicheAlbumPanelThumbnail);
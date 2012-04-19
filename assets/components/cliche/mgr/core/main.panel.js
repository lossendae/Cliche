Ext.ns('MODx');
Ext.ns('Cliche');

/**
 * Loads the main panel for Cliche cmp.
 * 
 * @class MODx.panel.cliche
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-cliche
 */
MODx.panel.cliche = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cliche-main-panel'
        ,cls: 'container cliche'
        ,unstyled: true
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+ _('cliche.main_title') +'</h2>'
            ,border: false
            ,cls: 'tools modx-page-header'
        },{
            xtype: 'panel'
            ,id: 'main-panel'
            ,items:[{
                xtype: 'modx-breadcrumbs-panel'
                ,id: 'cliche-breadcrumbs'
                ,bdMarkup: '<tpl if="typeof(trail) != &quot;undefined&quot;">'
                    +'<div class="crumb_wrapper"><ul class="crumbs">'
                        +'<tpl for="trail">'        
                            +'<li{[values.className != undefined ? \' class="\'+values.className+\'"\' : \'\' ]}>'        
                                +'<tpl if="typeof pnl != \'undefined\'">'
                                    +'<button type="button" class="controlBtn {pnl}{[values.root ? \' root\' : \'\' ]}">{text}</button>'                            
                                +'</tpl>'
                                +'<tpl if="typeof pnl == \'undefined\'"><span class="text{[values.root ? \' root\' : \'\' ]}">{text}</span></tpl>'                                        
                            +'</li>'
                        +'</tpl>'
                    +'</ul></div>'
                +'</tpl>'
                +'<tpl if="typeof(text) != &quot;undefined&quot;">'
                    +'<div class="panel-desc{[values.className != undefined ? \' \'+values.className+\'"\' : \'\' ]}">{text}</div>'
                +'</tpl>'
                ,desc: _('cliche.breadcrumb_album_list_desc')
                ,root : { 
                    text : _('cliche.breadcrumb_root')
                    ,className: 'first'
                    ,root: true
                    ,pnl: 'album-list' 
                }
            },{
                layout:'card'
                ,id:'card-container'
                ,activeItem:0
                ,border: false    
                ,autoHeight: true            
                ,defaults:{
                    preventRender: true
                    ,autoHeight: true
                }
                ,items: [{
                    xtype: 'cliche-albums-list'
                    ,id: 'album-list'
                }]
                ,listeners:{
                    afterrender: this.loadPanels
                    ,scope: this
                }
            }]
        }]
    });
    MODx.panel.cliche.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.cliche,MODx.Panel,{
    loadPanels: function(){
        var p = Cliche.getPanels;
        Ext.each(p, function(value){
            Ext.getCmp('card-container').add(value);    
        });
    }
    
    ,loadCreateUpdateWindow: function(title, action, btn, returnTo, data){
        if(!this.win){            
            this.win = new MODx.window.ClicheAlbumEditWindow({ uid: this.uid });
        }
        this.win.setTitle(title);
        this.win.show(btn.id);    
        var pos = this.win.getPosition(true);
        this.win.setPosition(pos[0], 35);
        this.win.reset(action, returnTo);        
        if(data != undefined){
            this.win.load(data);
        }
    }
});
Ext.reg('cliche-main-panel',MODx.panel.cliche);

/**
 * @class MODx.clicheSortableDataView
 * @extends Ext.util.Observable
 * @author tof (http://blog.tof2k.com/) - Forum: http://www.sencha.com/forum/showthread.php?33857-Sortable-Plugin-for-DataView - Plugin : http://tof2k.com/ext/sortable/
 * @param {Object} config An object of configuration parameters
 * @xtype 'cliche-sortabledataview'
 */
MODx.clicheSortableDataView = function(config) {
    Ext.apply(this, config || {}, {
        dragCls : 'x-view-sortable-drag'
        ,viewDragCls : 'x-view-sortable-dragging'
    });
    MODx.clicheSortableDataView.superclass.constructor.call(this);
    
    this.addEvents({
      'drop' : true
    }); 
};
Ext.extend(MODx.clicheSortableDataView, Ext.util.Observable, {
    init : function(view) {
        window.sdv = this;
        this.view = view;
        view.on('render', this.onRender, this);
    }

    ,onRender : function() {
        
        var self = this
            ,v = this.view
            ,ds = v.store
            ,dd = new Ext.dd.DragDrop(v.el)
            ,dragCls = this.dragCls
            ,viewDragCls = this.viewDragCls;

        // onMouseDown : if found an element, record it for future startDrag
        dd.onMouseDown = function(e) {            
            var t, idx,record;
            this.dragData = null;
            try {
                t = e.getTarget(v.itemSelector);
                idx = v.indexOf(t);
                record = ds.getAt(idx);

                // Found a record to move
                if (t && record) {
                    this.dragData = {
                        origIdx : idx,
                        lastIdx : idx,
                        record  : record
                    };
                    return true;
                }
            } catch (ex) { this.dragData = null; }
            return false;
        };

        // startDrag: add dragCls to the element
        dd.startDrag = function(x, y) {
            if (!this.dragData) { return false; }
            Ext.fly(v.getNode(this.dragData.origIdx)).addClass(dragCls);
            v.el.addClass(viewDragCls);
        };

        // endDrag : remove dragCls and fire "drop" event
        dd.endDrag = function(e) {
            if (!this.dragData) { return true; }
            Ext.fly(v.getNode(this.dragData.lastIdx)).removeClass(dragCls);
            v.el.removeClass(viewDragCls);
            self.fireEvent('drop' 
                ,this.dragData.origIdx
                ,this.dragData.lastIdx 
                ,this.dragData.record
            );
            return true;
        };

        // onDrag : if correct position, move record
        dd.onDrag = function(e) {
            var t, idx, record,data = this.dragData;
            if (!data) { return false; }

            try {
                t = e.getTarget(v.itemSelector);
                idx = v.indexOf(t);
                record = ds.getAt(idx);

                if (idx === data.lastIdx) { return true; }

                // found new position : move record and re-add dragCls
                if (t && record) {
                    data.lastIdx = idx;
                    ds.remove(data.record);
                    ds.insert(idx, [data.record]);
                    Ext.fly(v.getNode(idx)).addClass(dragCls);
                    return true;
                }
            } catch (ex) { return false; }
            return false;
        };
        this.dd = dd;
    }
});
Ext.ns('MODx');

/**
 * @class MODx.MyGrid
 * A base definition for GridPanel to use in Manager
 */
MODx.MyGrid = Ext.extend(Ext.grid.GridPanel, {
	border: false
	,plain: true
	,initComponent : function() {
		var config = {};		
		this.buildConfig(config);		
		Ext.apply(this, Ext.apply(this.initialConfig, config));	
		MODx.MyGrid.superclass.initComponent.apply(this, arguments);
		
		this.on('click', this.onClick, this);
	}
	
	,buildConfig:function(config) {
		config.autoHeight = true;
		config.frame = false;
		config.stripeRows = false;
		config.loadMask = true;
		config.singleSelect = this.singleSelect || true;
		config.record = this.record || [];
		
        this.buildStore(config);
        this.buildColumns(config);
        this.viewCfg(config);
        this.buildBbar(config);
        this.buildUI(config);
		this.buildTpls(config);	
		
		config.cls = this.cls || 'modx-grid';
    } // eo function buildConfig
	
	,buildStore:function(config) {
		config.store = new Ext.data.Store({
			proxy: new Ext.data.HttpProxy({ url: this.url })
			,baseParams:{ ctx:'mgr' }
			,listeners: {
				exception : function(proxy, type, action, options, res, arg) {
					console.log('error');
				}
				,load: function(){
					Ext.getCmp('modx-content').doLayout();
				}
			}
			,reader: new Ext.data.JsonReader({
				 total: 'totalItems'
				,successProperty: 'success'
				,root: 'results'
				,messageProperty: 'message'
			}
			,Ext.data.Record.create( this.record ))
			,sortInfo: {field: this.sortBy, direction: 'ASC'}
			,remoteSort: true
		});		
		config.baseParams = this.baseParams || 'undefined';
		if(config.baseParams !== 'undefined'){
			config.store.baseParams = config.baseParams	
		}
	}
	
	,buildColumns:function(config) {
		config.columns = undefined
	} // eo function buildColumns
	
	,buildUI:function(config) {
        config.tbar = undefined;
    } // eo function buildTbar
 
    ,buildBbar:function(config) {
        config.bbar = new Ext.PagingToolbar({
			pageSize: this.limit
			,store: config.store
			,displayInfo: true
			,displayMsg: '{0} - {1} of {2}'
			,emptyMsg: this.emtpyMsg || 'No data'       
		});
    } // eo function buildBbar
	
	,buildTpls:function(config){}
	
	,addTpl: function(markup){
		return new Ext.XTemplate(markup, {
			compiled: true
		});
	}
	
	,viewCfg:function(config) {
		config.viewConfig = {
			scrollOffset: 0
			,forceFit: true
			,emptyText: this.emptyText || '<h4>No data to display</h4>'
			,enableRowBody:true
		}
		if( config.singleSelect ){
			config.selModel = new Ext.grid.RowSelectionModel({ singleSelect : true })
		}
	} // eo function viewCfg	
});
Ext.reg("my-grid", MODx.MyGrid);

/**
 * @class MODx.AbstractGrid
 * A base definition for GridPanel to use in Manager
 */
MODx.AbstractGrid = Ext.extend(Ext.grid.EditorGridPanel, {
	border: false
	,plain: true
	,initComponent : function() {
		var config = {};		
		this.buildConfig(config);		
		Ext.apply(this, Ext.apply(this.initialConfig, config));	
		MODx.AbstractGrid.superclass.initComponent.apply(this, arguments);
		
		this.on('click', this.onClick, this);
	}
	
	,buildConfig:function(config) {
		config.autoHeight = true;
		config.frame = false;
		config.stripeRows = false;
		config.loadMask = true;
		config.singleSelect = this.singleSelect || true;
		config.record = this.record || [];
		
        this.buildStore(config);
        this.buildColumns(config);
        this.viewCfg(config);
        this.buildBbar(config);
        this.buildUI(config);
		this.buildTpls(config);	
		
		if(this.expander){
			config.cls = 'modx-grid modx-grouping-grid';
		} else {
			config.cls = this.cls || 'modx-grid';
		}
    } // eo function buildConfig
	
	,buildStore:function(config) {
		if( this.writer ){
			config.store = new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({
					api: {
						 read : this.url+'?action=getList'
						,create : this.url+'?action=create'
						,update: this.url+'?action=updateFromGrid'
						,destroy: this.url+'?action=remove'
					}
				})
				,baseParams:{ ctx:'mgr' }
				,writer:new Ext.data.JsonWriter({
					encode: true,
					writeAllFields: true
				})
				,autoSave: true
				,listeners: {
					exception : function(proxy, type, action, options, res, arg) {						
						if(action == 'create'){
							r = Ext.util.JSON.decode(res.responseText);
							if(r.success){
								this.reload();
								MODx.msg.status({
									title: _('success')
									/* Add lexicon for successful saving */
									,message: _('save_successful')
									,dontHide: r.message != '' ? true : false
								});
							} else {
								console.log('error');
							}
						} else {
						
						}
					}
					,beforewrite: function(store, action, rs, options, arg){
						// Hack because the backend expect different datas since it was not a writer to begin with
						switch(action){
							case 'destroy':
								options.params.id = rs.data.id								
								break;
							case 'update':
								options.params.data = Ext.util.JSON.encode(rs.data);
								break;
							default:
								options.params = rs.data
								break;
						}						
					}
					,write : function( store, action, r, res, rs ){
						switch(action){
							case 'destroy':
								MODx.msg.status({
									title: _('success')
									/* Add lexicon for successful remove */
									,message: 'Data removed successfully'
								});
								break;
							case 'update':
								MODx.msg.status({
									title: _('success')
									/* Add lexicon for successful update */
									,message: 'Update successful'
								});
								break;
							default:
								break;
						}
						
					}
					,load: function(){
						Ext.getCmp('modx-content').doLayout();
					}
				}
				,reader: new Ext.data.JsonReader({
					 total: 'totalItems'
					,successProperty: 'success'
					,root: 'results'
					,messageProperty: 'message'
				}
				,Ext.data.Record.create( this.record ))
				,sortInfo: {field: this.sortBy, direction: 'ASC'}
				,remoteSort: true
			});	
		} else {
			config.store = new Ext.data.Store({
				proxy: new Ext.data.HttpProxy({ url: this.url })
				,baseParams:{ ctx:'mgr' }
				,listeners: {
					exception : function(proxy, type, action, options, res, arg) {
						console.log('error');
					}
					,load: function(){
						Ext.getCmp('modx-content').doLayout();
					}
				}
				,reader: new Ext.data.JsonReader({
					 total: 'totalItems'
					,successProperty: 'success'
					,root: 'results'
					,messageProperty: 'message'
				}
				,Ext.data.Record.create( this.record ))
				,sortInfo: {field: this.sortBy, direction: 'ASC'}
				,remoteSort: true
			});
		}
		config.baseParams = this.baseParams || 'undefined';
		if(config.baseParams !== 'undefined'){
			config.store.baseParams = config.baseParams	
		}
	} // eo function buildStore
	
	,buildColumns:function(config) {
		config.columns = undefined
	} // eo function buildColumns
	
	,buildUI:function(config) {
        config.tbar = undefined;
    } // eo function buildTbar
 
    ,buildBbar:function(config) {
        config.bbar = new Ext.PagingToolbar({
			pageSize: this.limit
			,store: config.store
			,displayInfo: true
			,displayMsg: '{0} - {1} of {2}'
			,emptyMsg: this.emtpyMsg || 'No data'       
		});
    } // eo function buildBbar
	
	,buildTpls:function(config){}
	
	,addTpl: function(markup){
		return new Ext.XTemplate(markup, {
			compiled: true
		});
	}
	
	,viewCfg:function(config) {
		config.viewConfig = {
			scrollOffset: 0
			,forceFit: true
			,emptyText: this.emptyText || '<h4>No data to display</h4>'
			,enableRowBody:true
		}
		if( config.singleSelect ){
			config.selModel = new Ext.grid.RowSelectionModel({ singleSelect : true })
		}
	} // eo function viewCfg
	
	,onSave : function(btn, ev) {
        this.store.save();
    } // eo function onSave
	
	,onAdd : function(btn, ev) {
        var u = new this.store.recordType(this.emptyNewRecords);
        this.stopEditing();
        this.store.insert(0, u);
        this.startEditing(0, 1);
    } // eo function onAdd
	
	,onDelete : function(btn, ev) {
        var index = this.getSelectionModel().getSelectedCell();
        if (!index) {
            return false;
        }
        var rec = this.store.getAt(index[0]);
        this.store.remove(rec);
    } // eo function onDelete
});
Ext.reg("modx-abstract-grid", MODx.AbstractGrid);
Ext.reg("my-abstract-grid", MODx.AbstractGrid);

/**
 * @class MODx.AbstractGroupingGrid
 * A base definition for GridPanel to use in Manager with grouping
 */
MODx.AbstractGroupingGrid = Ext.extend(MODx.AbstractGrid, {
	border: false
	,plain: true
	,bodyCssClass: 'modx-grouping-grid'
	,initComponent : function() {
		var config = {};		
		this.buildConfig(config);		
		Ext.apply(this, Ext.apply(this.initialConfig, config));	
		MODx.AbstractGroupingGrid.superclass.initComponent.apply(this, arguments);
	}
	
	,buildStore:function(config) {
		if( this.writer ){
			config.store = new Ext.data.GroupingStore({
				proxy: new Ext.data.HttpProxy({
					api: {
						 read : this.url+'?action=getList'
						,create : this.url+'?action=create'
						,update: this.url+'?action=updateFromGrid'
						,destroy: this.url+'?action=remove'
					}
				})
				,baseParams:{ ctx:'mgr' }
				,writer:new Ext.data.JsonWriter({
					encode: true,
					writeAllFields: true
				})
				,autoSave: true
				,listeners: {
					exception : function(proxy, type, action, options, res, arg) {						
						if(action == 'create'){
							r = Ext.util.JSON.decode(res.responseText);
							if(r.success){
								this.reload();
								MODx.msg.status({
									title: _('success')
									/* Add lexicon for successful saving */
									,message: _('save_successful')
									,dontHide: r.message != '' ? true : false
								});
							} else {
								console.log('error');
							}
						} else {
						
						}
					}
					,beforewrite: function(store, action, rs, options, arg){
						// Hack because the backend expect different datas since it was not a writer to begin with
						switch(action){
							case 'destroy':
								options.params.id = rs.data.id								
								break;
							case 'update':
								options.params.data = Ext.util.JSON.encode(rs.data);
								break;
							default:
								options.params = rs.data
								break;
						}						
					}
					,write : function( store, action, r, res, rs ){
						switch(action){
							case 'destroy':
								MODx.msg.status({
									title: _('success')
									/* Add lexicon for successful remove */
									,message: 'Data removed successfully'
								});
								break;
							case 'update':
								MODx.msg.status({
									title: _('success')
									/* Add lexicon for successful update */
									,message: 'Update successful'
								});
								break;
							default:
								break;
						}
						
					}
					,load: function(){
						Ext.getCmp('modx-content').doLayout();
					}
				}
				,reader: new Ext.data.JsonReader({
					 total: 'totalItems'
					,successProperty: 'success'
					,root: 'results'
					,messageProperty: 'message'
				}
				,Ext.data.Record.create( this.record ))
				,sortInfo: {field: this.sortBy, direction: 'ASC'}
				,remoteSort: true
				,groupField: this.groupBy
			});	
		} else {
			config.store = new Ext.data.GroupingStore({
				proxy: new Ext.data.HttpProxy({ url: this.url })
				,baseParams:{ ctx:'mgr' }
				,listeners: {
					exception : function(proxy, type, action, options, res, arg) {
						console.log('error');
					}
					,load: function(){
						Ext.getCmp('modx-content').doLayout();
					}
				}
				,reader: new Ext.data.JsonReader({
					 total: 'totalItems'
					,successProperty: 'success'
					,root: 'results'
					,messageProperty: 'message'
				}
				,Ext.data.Record.create( this.record ))
				,sortInfo: {field: this.sortBy, direction: 'ASC'}
				,remoteSort: true
				,groupField: this.groupBy
			});
		}
		config.baseParams = this.baseParams || undefined;
		if(config.baseParams != undefined){
			config.store.baseParams = config.baseParams	
		}
	} // eo function buildStore
	
	,viewCfg:function(config) {
		config.viewConfig = new Ext.grid.GroupingView({
			scrollOffset: 0
			,forceFit: true
			,emptyText: '<h4>No data to display</h4>'
			,groupTextTpl: '{gvalue} ({[values.rs.length]} {[values.rs.length > 1 ? "entries" : "entry"]})'
		})
		if( this.singleSelect ){
			config.selModel = new Ext.grid.RowSelectionModel({ singleSelect : true })
		}
	} // eo function viewCfg
});
Ext.reg("modx-abstract-grouping-grid", MODx.AbstractGroupingGrid);
Ext.reg("my-abstract-grouping-grid", MODx.AbstractGroupingGrid);
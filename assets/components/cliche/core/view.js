Ext.ns('MODx');

/**
 * Abstract class for Ext.DataView creation in MODx
 * 
 * @class MODx.AbstractDataView
 * @extends Ext.DataView
 * @constructor
 * @xtype modx-abstract-dataview
 */
MODx.AbstractDataView = Ext.extend(MODx.AbstractPanel, {
	lookup : {}	
	,bodyCssClass: 'data-view'
	,border: false
	,win: false
	,layout: 'form'
	,minHeight: 350
	,initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
		MODx.AbstractDataView.superclass.initComponent.call(this);
	}
	
	,buildConfig:function(config) {		
		this.buildStore(config);	
		this.buildUI(config);
		this.buildTpls(config);			
		config.viewId = this.viewId || 'view-items';
		config.view = new Ext.DataView({
			tpl: config.tpl
			,id: config.viewId
			,singleSelect: true
			,overClass:'x-view-over'
			,selectedClass:'selected'
			,itemSelector: 'div.thumb-wrapper'
			,loadingText : '<div class="empty-msg"><h4>Loading...</h4></div>'
			,emptyText : '<div class="empty-msg">' + this.emptyText + '</div>'|| '<div class="empty-msg"><h4>No data to display</h4></div>'
			,store: config.store
			,ident : this.ident || 'modx-view-'+Ext.id()		
			,listeners: {
				selectionchange: {
					fn: function(dv,nodes){
						if(typeof(this.onSelect) == "function"){
							this.onSelect();
						}
					}
				}
				,beforeselect: {
					fn: function(view){
						return view.store.getRange().length > 0;
					}
				}
				,scope:this
			}		
			,prepareData: this.formatData.createDelegate(this)
		});	
		this.buildItems(config);		
	}
	
	,buildUI: function(config){
		config.ui = null;
    }
	
	,buildTpls:function(config){
		config.tpl = this.addTpl(this.mainTpl);
	}
	
	,addTpl: function(markup){
		return new Ext.XTemplate(markup, {
			compiled: true
		});
	}
	
	,buildStore: function(config){
       config.store = new Ext.data.JsonStore({
			url: this.url
			,baseParams: this.baseParams || { 
                action: 'getList'
                ,prependPath: this.prependPath || null
                ,prependUrl: this.prependUrl || null
                ,wctx: this.wctx || MODx.ctx
                ,dir: this.openTo || ''
                ,basePath: this.basePath || ''
                ,basePathRelative: this.basePathRelative || null
                ,baseUrl: this.baseUrl || ''
                ,baseUrlRelative: this.baseUrlRelative || null
            }
            ,root: this.root || 'results'
			,remoteSort: this.remoteSort || true
			,sortInfo: {
				field: 'name',
				direction: 'DESC'
			}
            ,fields: this.fields
            ,totalProperty: 'total'
            ,listeners: {
                load: function(ds, rec, opts){
					Ext.getCmp('modx-content').doLayout();					
				}
				,exception: function(misc){
					// console.log(misc)
				}
				,single: true
				,scope:this
            }
        });;
    }
	
	,buildItems: function(config){
		config.items = [{
			id: this.mainId || 'main-view' + Ext.id()
			,items: config.view
			,bodyCssClass:'view-content'
			,cls:'view-panel'
			,tbar: config.ui || undefined
			,bbar: new Ext.PagingToolbar({
				pageSize: this.limit || 10
				,store: config.store
				,displayInfo: true
				,displayMsg: '{0} - {1} of {2}'
				,emptyMsg: this.emtpyMsg || 'No data to display'         
			})
		}];
    }

	,onLoadException: function(){
        this.getEl().update('<div class="empty-msg">'+_('data_err_load')+'</div>'); 
    }
});
Ext.reg("modx-abstract-dataview", MODx.AbstractDataView);

/**
 * Abstract class for Ext.DataView creation in MODx
 * 
 * @class MODx.AbstractDataViewWithColumn
 * @extends MODx.AbstractDataView
 * @constructor
 * @xtype modx-abstract-dataview-with-col
 */
MODx.AbstractDataViewWithColumn = Ext.extend(MODx.AbstractDataView, {
	layout: 'column'
	,bodyCssClass: 'data-view with-col'
	,showDetailOnSelect : true
	,initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
		MODx.AbstractDataViewWithColumn.superclass.initComponent.call(this);
	}
	
	,buildTpls:function(config){
		config.tpl = this.addTpl(this.mainTpl);
		config.detailTpl = this.detailTpl || null;
	}
	
	,buildConfig:function(config) {		
		this.buildStore(config);	
		this.buildUI(config);		
		this.buildTpls(config);	
		config.viewId = this.viewId || 'view-items';
		// config.showDetailOnSelect = this.showDetailOnSelect || true
		config.view = new Ext.DataView({
			tpl: config.tpl
			,id: config.viewId
			,singleSelect: true
			,overClass:'x-view-over'
			,selectedClass:'selected'
			,itemSelector: 'div.thumb-wrapper'
			,loadingText : '<div class="empty-msg"><h4>Loading...</h4></div>'
			,emptyText : '<div class="empty-msg">' + this.emptyText + '</div>'|| '<div class="empty-msg"><h4>No data to display</h4></div>'
			,store: config.store
			,ident : this.ident || 'modx-view-'+Ext.id()		
			,listeners: {
				selectionchange: function(dv,nodes){
					selected = dv.getSelectedRecords();
					if(selected.length > 0){
						if(this.showDetailOnSelect){
							this.showDetails(selected[0].data);
						}						
						if(typeof(this.onSelect) == "function"){
							this.onSelect(selected[0]);
						}
					} else {
						col = this.colid || 'detail-view';
						if(typeof(this.reset) == "function"){
							this.reset(col);
						} else {								
							Ext.getCmp(col).reset();
						}							
					}
				}
				,beforeselect: function(view){
					return view.store.getRange().length > 0;
				}
				,render: function(view){
					if(typeof(this.initialize) == "function"){
						this.initialize(view);
					}
				}
				,scope:this
			}		
			,prepareData: this.formatData.createDelegate(this)
		});	
		this.buildItems(config);		
	}
		
	,buildItems: function(config){
		config.items = [{
			columnWidth: 1
			,id: this.mainId || 'view-main' + Ext.id()
			,items: config.view
			,bodyCssClass:'view-content'
			,cls:'view-panel'
			,tbar: config.ui || undefined
			,bbar: new Ext.PagingToolbar({
				pageSize: this.limit || 10
				,store: config.store
				,displayInfo: true
				,displayMsg: '{0} - {1} of {2}'
				,emptyMsg: this.emtpyMsg || 'No data to display'         
			})
		},{
			width: 250
			,id: this.colid || 'view-detail' + Ext.id()
			,xtype: 'my-tpl-panel'
			,bodyCssClass:'formatted-layout'
			,cls:'spaced-column'
			,startingText : this.detailsStartingText || '<p>No item selected.</p>'
			,startingMarkup: '<tpl for=".">'
								+'<div class="centered">'
									+'{text}'
								+'</div>'
							+'</tpl>'
			,tpl: config.detailTpl
		}];
    }
});
Ext.reg("modx-abstract-dataview-with-col", MODx.AbstractDataViewWithColumn);
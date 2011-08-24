Ext.ns('MODx');

/**
 * @class MODx.AbstractPanel
 * A base definition for Ext Panel to use in Manager
 */
MODx.AbstractTreePanel = Ext.extend(Ext.tree.TreePanel, {
	initComponent: function() {
		var config = {};		
		this.buildConfig(config);
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
		MODx.AbstractTreePanel.superclass.initComponent.call(this);
		
		this.on('click',this.onNodeClick,this);
		this.on('beforeload',this.onBeforeLoad, this);
		this.on('expandnode',this.onLoad, this);
	}
	
	,initEvents: function(){
		MODx.AbstractTreePanel.superclass.initEvents.call(this);		
		this.getRootNode().expand();
	}
	
	,buildConfig: function(config){		
		config.useArrows = this.useArrows || true;
		config.singleExpand = this.singleExpand || true;		
		config.bodyCssClass = 'modx-tree';			
		config.root = this.root;
		config.rootVisible = this.rootVisible || false;
		this.buildLoader(config);
		this.buildTbar(config);
	}
	
	,buildLoader: function(config){		
		config.loaderConfig = {};
		config.loaderConfig.dataUrl = this.url;
		config.loaderConfig.baseParams = this.baseParams;
		config.loaderConfig.preloadChildren = this.preloadChildren || true;		
		config.loader = new Ext.tree.TreeLoader(config.loaderConfig);
	}
	
	,buildTbar: function(config){
       config.tbar = undefined;
    }
	
	,onNodeClick: function(n,e) {}
	,onBeforeLoad: function(tree, n, cb) {}
});
Ext.reg("modx-test-tree", MODx.AbstractTreePanel);
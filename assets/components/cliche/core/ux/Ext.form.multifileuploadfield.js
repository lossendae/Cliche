/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.ns('Ext.ux.form');

/**
 * @class Ext.ux.form.MultiFileUploadField
 * @extends Ext.form.TextField
 * Creates a file upload field.
 * @xtype multifileuploadfield
 */
Ext.ux.form.MultiFileUploadField = Ext.extend(Ext.form.TextField,  {
    /**
     * @cfg {String} buttonText The button text to display on the upload button (defaults to
     * 'Browse...').  Note that if you supply a value for {@link #buttonCfg}, the buttonCfg.text
     * value will be used instead if available.
     */
    buttonText: 'Browse...',
    /**
     * @cfg {Boolean} buttonOnly True to display the file upload field as a button with no visible
     * text field (defaults to false).  If true, all inherited TextField members will still be available.
     */
    buttonOnly: false,
    /**
     * @cfg {Boolean} multiple True Allow multiple file selection
     * (defaults to false).  If true, all inherited TextField members will still be available.
     */
    allowMultiple: false,
    /**
     * @cfg {Number} buttonOffset The number of pixels of space reserved between the button and the text field
     * (defaults to 3).  Note that this only applies if {@link #buttonOnly} = false.
     */
    buttonOffset: 3,
    /**
     * @cfg {Object} buttonCfg A standard {@link Ext.Button} config object.
     */

    // private
    readOnly: true,

    /**
     * @hide
     * @method autoSize
     */
    autoSize: Ext.emptyFn,

    // private
    initComponent: function(){
        Ext.ux.form.MultiFileUploadField.superclass.initComponent.call(this);

        this.addEvents(
            /**
             * @event fileselected
             * Fires when the underlying file input field's value has changed from the user
             * selecting a new file from the system file selection dialog.
             * @param {Ext.ux.form.MultiFileUploadField} this
             * @param {String} value The file value returned by the underlying file input field
             */
            'fileselected'
        );
    },

    // private
    onRender : function(ct, position){
        Ext.ux.form.MultiFileUploadField.superclass.onRender.call(this, ct, position);

        this.wrap = this.el.wrap({cls:'x-form-ajax-uploader x-form-field-wrap x-form-file-wrap x-form-'+this.getFileInputId()});
        this.el.addClass('x-form-file-text');
        this.el.dom.removeAttribute('name');
		this.queuedInput = [];
        
		this.createFileInput(0);
        var btnCfg = Ext.applyIf(this.buttonCfg || {}, {
            text: this.buttonText
        });
		this.buttonID = 'upload-btn-' + this.getFileInputId();
        this.button = new Ext.Button(Ext.apply(btnCfg, {
            renderTo: this.wrap,
			id: this.buttonID,
            cls: 'x-form-file-btn' + (btnCfg.iconCls ? ' x-btn-icon' : '')
        }));			

        if(this.buttonOnly){
            this.el.hide();
            this.wrap.setWidth(this.button.getEl().getWidth());
        }
        this.resizeEl = this.positionEl = this.wrap;
    },
        
    createFileInput : function(idx) {
		params = {
			id: this.getFileInputId() + '['+idx+']',
			name: this.getFileInputId(),
			cls: 'x-form-file',
			tag: 'input',
			type: 'file',
			size: 1
		};	
		if(typeof(Ext.getCmp(this.buttonID)) === "object"){
			this.fileInput = this.wrap.createChild(params, this.buttonID);
		} else {
			this.fileInput = this.wrap.createChild(params);
		}	
		this.bindListeners();
		this.idx = idx + 1;
    },	
	
    bindListeners: function(){
        this.fileInput.on({
            scope: this,
            mouseenter: function() {
                this.button.addClass(['x-btn-over','x-btn-focus']);
            },
            mouseleave: function(){
                this.button.removeClass(['x-btn-over','x-btn-focus','x-btn-click']);
            },
            mousedown: function(){
                this.button.addClass('x-btn-click');
            },
            mouseup: function(){
                this.button.removeClass(['x-btn-over','x-btn-focus','x-btn-click']);
            },
            change: function(){
				var v = this.fileInput.dom.value;
				var input = this.fileInput;             
                this.setValue(v);
                this.fireEvent('fileselected', this, v, input);   
				this.createFileInput(this.idx);
            }
        }); 
    },
	
	isMultiple: function(){
		var input = document.createElement('input');
		input.type = 'file';
		return('multiple' in input && typeof File !== "undefined" 
			&& typeof (new XMLHttpRequest()).upload !== "undefined" && this.allowMultiple);
	},
	
	getFiles: function(){
		inputs = Ext.select('.x-form-'+ this.getFileInputId() +' .x-form-file');		
		inputFiles = [];
		for (i = 0; i < inputs.elements.length; i++) {			
			el = inputs.elements[i];
			inputFiles.push({name:this.getName(el), size: '', idx: i});
		}
		return inputFiles;
	},
	
	getInput: function(idx){
		return Ext.select('.x-form-'+ this.getFileInputId() +' .x-form-file').elements[idx];
	},
	
	getName: function(input){
		return input.value.replace(/.*(\/|\\)/, "");
	},
    
    reset : function(){
        this.fileInput.remove();
        this.createFileInput();
        this.bindListeners();
        Ext.ux.form.MultiFileUploadField.superclass.reset.call(this);
    },

    // private
    getFileInputId: function(){
        return this.id + '-file';
    },

    // private
    onResize : function(w, h){
        Ext.ux.form.MultiFileUploadField.superclass.onResize.call(this, w, h);

        this.wrap.setWidth(w);
        if(!this.buttonOnly){
            w = this.wrap.getWidth() - this.button.getEl().getWidth() - this.buttonOffset;
            this.el.setWidth(w);
        }
    },

    // private
    onDestroy: function(){
        Ext.ux.form.MultiFileUploadField.superclass.onDestroy.call(this);
        Ext.destroy(this.fileInput, this.button, this.wrap);
    },
    
    onDisable: function(){
        Ext.ux.form.MultiFileUploadField.superclass.onDisable.call(this);
        this.doDisable(true);
    },
    
    onEnable: function(){
        Ext.ux.form.MultiFileUploadField.superclass.onEnable.call(this);
        this.doDisable(false);

    },
    
    // private
    doDisable: function(disabled){
        this.fileInput.dom.disabled = disabled;
        this.button.setDisabled(disabled);
    },


    // private
    preFocus : Ext.emptyFn,

    // private
    alignErrorIcon : function(){
        this.errorIcon.alignTo(this.wrap, 'tl-tr', [2, 0]);
    }

});

Ext.reg('multifileuploadfield', Ext.ux.form.MultiFileUploadField);
// backwards compat
Ext.form.MultiFileUploadField = Ext.ux.form.MultiFileUploadField;
/**
 * @package SM Page Builder
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright Copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.magentech.com
 */
"use strict";
// var $ = jQuery.noConflict();
var Pagebuilder = Pagebuilder || {};
Pagebuilder = Class.create();
Pagebuilder.prototype = {
	form: null,
	container: null,
	indexRow: 0,
	indexCol: 0,
	params: {},
	paramsChild: {},
	settings: {},
	settingsParams: "custom_css|custom_js|enable_wrapper|select_wrapper|wrapper_class|template_settings".split("|"),
	configParams: "custom_css|custom_js|enable_wrapper|select_wrapper|wrapper_class|template_settings".split("|"),
	/*
	 General editing classes---------------
	 */
	// Standard edit class, applied to active elements
	pdmEditClass: "pdm-editing",

	// Tool bar class which are inserted dynamically
	pdmToolClass: "pdm-tools",

	// Clearing class, used on most toolbars
	pdmClearClass: "clearfix",

	// Id row widget
	rowId: "pdm-layer-",

	// Buttons at the top of each row
	rowButtonsPrepend: [
		{
			title:"Move",
			element: "a",
			btnClass: "pdm-moveRow pull-left",
			iconClass: "fa fa-arrows "
		},
		{
			title:"New Row",
			element: "a",
			btnClass: "pdm-addRow pull-left",
			iconClass: "fa fa-bars"
		},
		{
			title:"New Column",
			element: "a",
			btnClass: "pdm-addColumn pull-left",
			iconClass: "fa fa-plus"
		},
		{
			title:"Remove row",
			element: "a",
			btnClass: "pull-right pdm-removeRow",
			iconClass: "fa fa-trash-o"
		},
		{
			title:"Duplicate",
			element: "a",
			btnClass: "pull-right pdm-duplicate",
			iconClass: "fa fa-files-o"
		},
		{
			title:"Row Settings",
			element: "a",
			btnClass: "pull-right pdm-rowSettings",
			iconClass: "fa fa-cog"
		},
		{
			title:"Edit Row Short Code",
			element: "a",
			btnClass: "pull-right pdm-editRowShortcode",
			iconClass: "fa fa-pencil-square-o"
		}
	],
	/*
	 Rows---------------
	 */
	// Generic row class. change to row--fluid for fluid width in Bootstrap
	rowClass:    "row",

	/*
	 Columns--------------
	 */
	// Column Class
	colClass: "column",

	// Generic desktop size layout class
	colDesktopClass: "col-lg-",

	// Generic desktop size layout class
	colLaptopClass: "col-md-",

	// Generic tablet size layout class
	colTabletClass: "col-sm-",

	// Generic phone size layout class
	colPhoneClass: "col-xs-",

	// Additional column class to add (foundation needs columns, bs3 doesn't)
	colAdditionalClass: "",

	// Id cols widget
	colId : 'pdm-col-',

	// Buttons to prepend to each column
	colButtonsPrepend: [
		{
			title:"Column Settings",
			element: "a",
			btnClass: "left pdm-colSettings",
			iconClass: "fa fa-pencil-square-o"
		},
		{
			title:"Add Nested Row",
			element: "a",
			btnClass: "left pdm-addRow",
			iconClass: "fa fa-plus-square"
		},
		{
			title:"Remove Column",
			element: "a",
			btnClass: "left pdm-removeCol",
			iconClass: "fa fa-trash-o"
		},
		{
			title:"Add Widget",
			element: "a",
			btnClass: "right pdm-addWidget",
			iconClass: "fa fa-cog"
		}
	],
	initialize: function(a){
		this.form = a;
		this.collectContainer();
	},
	collectContainer: function() {
		this.container = $("mypagebuilder");
	},
	save: function(a){
		if (this.form && this.form.validate()) {
			var b = this.form.validator.form;
			var c = b.action;
			var d = b.serialize(true);
			this.settings['custom_css'] = d.custom_css;
			this.settings['custom_js'] = d.custom_js;
			this.settings['wrapper_page'] = d.wrapper_page;
			this.settings['select_wrapper'] = d.select_wrapper;
			this.settings['wrapper_class'] = d.wrapper_class;
			if (a)
				d['back'] = 'edit';

			d.settings = JSON.stringify(this.settings);
			d.params = JSON.stringify(this.params);
			console.log(d.params);
			new Ajax.Request(c, {
				method: "post",
				parameters: d,
				onSuccess: function(b) {
					// if (a) window.location.href = b.responseText;
					// else if (0 === b.responseText.indexOf("http://")) window.location.href = b.responseText;
				}
			});
		}
	},
	getWidgetKey : function () {
		var d = new Date();
		return d.getTime();
	},
	loadWidgets : function (widgetUrl, objbuilder, callback, col) {
		var widgetUrl 	= widgetUrl != null ? widgetUrl : "";
		var objbuilder	= objbuilder != null ? objbuilder : "";
		var callback 	= callback != null ? callback : "";
		var col 		= col != null ? col : "";


		_PdmWidgetTools.openDialog(widgetUrl, objbuilder, callback, col);
	},
	addLayer: function (a) {
		var b = this.container.getDimensions();
		if (!b.width && !b.height) {
			setTimeout(function() {
				this.addLayer(a);
			}.bind(this), 500);
			return;
		}
		a.order = this.indexRow + 1;
		a.serial = a.serial ? a.serial : this.getWidgetKey();
		this.params[a.serial] = a;
		var d = this.renderLayerHtml(a);
		$('pdm-canvas').insertBefore(d, $('add-row-first'));
		if (a.col)
			this.addColumn(a.serial, a.col);

		this.indexRow++;
	},
	addColumn: function (b, e) {
		e.parent = b;
		e.order = this.indexCol+1;
		console.log(e);
		console.log(e.key);
		e.serial = e.serial ? e.serial : this.getWidgetKey() ? this.getWidgetKey() : this.indexCol+1;
		console.log(e.serial);
		e.size = 2;
		this.paramsChild[e.serial] = e;
		// console.log(this.paramsChild);
		this.params[e.parent]['col'] = this.paramsChild;
		var f = this.renderColumnHtml(e),
			id = this.rowId.concat(b),
			pdmTools = jQuery('.'+this.pdmToolClass+'', jQuery('#'+id+''))[0];
		pdmTools.after(f);
		this.indexCol++;
	},
	renderLayerHtml: function (a) {
		var b = new Element("div", {
			id: this.rowId+a.serial,
			class: this.rowClass+ ' '+this.pdmEditClass+ ' ui-sortable',
			row: a.serial
		});

		b.insert(this.toolFactory(this.rowButtonsPrepend));
		return b;
	},
	renderColumnHtml: function (e) {
		var b = new Element("div", {
			id: this.colId+e.serial,
			class: this.colClass + ' ' + this.colDesktopClass + e.size + ' ' + this.colLaptopClass + e.size + ' ' +
			this.colTabletClass + e.size + ' ' + this.colPhoneClass + e.size + ' ' + this.pdmEditClass + ' ' + this.colAdditionalClass
		});

		b.insert(this.toolFactory(this.colButtonsPrepend));
		return b;
	},
	/**
	 * Returns an editing div with appropriate btns as passed in
	 * @method toolFactory
	 * @param {array} btns - Array of buttons (see options)
	 * @return MemberExpression
	 */
	toolFactory: function(btns){
		var c = new Element("div", {
			class: this.pdmToolClass+ ' ' +this.pdmClearClass
		});
		var tools = c.insert(this.buttonFactory(btns));
		return tools;
	},
	/**
	 * Returns html string of buttons
	 * @method buttonFactory
	 * @param {array} btns - Array of button configurations (see options)
	 * @return CallExpression
	 */
	buttonFactory: function(btns){
		var buttons=[];
		jQuery.each(btns, function(i, val){
			val.btnLabel = (typeof val.btnLabel === 'undefined')? '' : val.btnLabel;
			val.title = (typeof val.title === 'undefined')? '' : val.title;
			buttons.push("<" + val.element +" title='" + val.title + "' class='" + val.btnClass + "'><span class='"+val.iconClass+"'></span>&nbsp;" + val.btnLabel + "</" + val.element + "> ");
		});
		return buttons.join("");
	}
};

(function($) {
	/**
	 * PB_Layout Plugin
	 */
	$.fn.PB_Layout = function (opts, datajson) {
		var builder = new Pagebuilder();

		function init(a) {
			builder.addLayer(a);
		}

		/**
		 * add suggest row using to click to this for adding new real row
		 */
		function addSuggestRow(){
			var divAddRow 		= document.createElement('div');
			var innerDiv 		= document.createElement('a');
			var innerA 			= document.createElement('span');
			divAddRow.setAttribute("id", "add-row-first");
			divAddRow.setAttribute("class", "add-row-first");
			innerDiv.setAttribute("class", "pdm-addRow pull-center");
			innerDiv.setAttribute("title", "New Row");
			innerA.setAttribute("class", "fa fa-bars");
			innerDiv.append(innerA);
			innerDiv.insert("&nbsp;");
			divAddRow.append(innerDiv);
			$('#pdm-canvas').append(divAddRow);
		}

		/**
		 * initialize every element
		 */
		this.each(function() {
			var json = JSON.parse(datajson);
			for(var j in json)
				init(json[j]);

			addSuggestRow();
		});

		return this;
	};
})(jQuery);
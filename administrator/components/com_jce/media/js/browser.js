/* jce - 2.9.10 | 2021-07-08 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2021 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function($){$.WFBrowserWidget={options:{element:null,plugin:{plugin:"browser",root:"",site:"",help:function(e){window.parent;$.Dialog.iframe("Help","index.php?option=com_jce&view=help&tmpl=component&section=editor&category=browser",{width:768,height:520})}},manager:{upload:{insert:!1},expandable:!1}},init:function(options){var self=this,win=window.parent,doc=win.document;if($.extend(!0,this.options,options),$('<input type="hidden" id="src" value="" />').appendTo(document.body),$.Plugin.init(this.options.plugin),$("button#insert, button#cancel").hide(),this.options.element){$("button#insert").show().click(function(e){self.insert(),self.close(),e.preventDefault()}),$("button#cancel").show().click(function(e){self.close(),e.preventDefault()});var src=doc.getElementById(this.options.element).value||"";$("#src").val(src)}WFFileBrowser.init($("#src"),$.extend(this.options.manager,{}))},insert:function(){if(this.options.element){var src=WFFileBrowser.getSelectedItems(0);window.parent.document.getElementById(this.options.element).value=$(src).data("url")||""}},close:function(){var win=window.parent;return"undefined"!=typeof win.$jce?win.$jce.closeDialog("#"+this.options.element+"_browser"):"undefined"!=typeof win.SqueezeBox?win.SqueezeBox.close():void 0}}}(jQuery);var tinyMCE={addI18n:function(p,o){return jQuery.Plugin.addI18n(p,o)}};
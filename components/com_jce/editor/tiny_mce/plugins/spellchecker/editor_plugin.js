/* jce - 2.9.10 | 2021-07-08 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2021 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){function uid(){var i,guid=(new Date).getTime().toString(32);for(i=0;i<5;i++)guid+=Math.floor(65535*Math.random()).toString(32);return"wf_"+guid+(counter++).toString(32)}var each=tinymce.each,DOM=tinymce.DOM,counter=0;tinymce.create("tinymce.plugins.SpellcheckerPlugin",{init:function(ed,url){var self=this;self.url=url,self.editor=ed,self.native_spellchecker="browser"==ed.getParam("spellchecker_engine","browser"),self.native_spellchecker&&ed.getParam("spellchecker_suggestions",!0)&&ed.onContextMenu.addToTop(function(ed,e){if(self.active)return!1}),ed.addCommand("mceSpellCheck",function(){if(self.native_spellchecker){var body=ed.getBody();return body.spellcheck=self.active=!self.active,void ed.focus()}self.active?self._done():(ed.setProgressState(1),self._sendRPC("checkWords",[self.selectedLang,self._getWords()],function(r){r.length>0?(self.active=1,self._markWords(r),ed.setProgressState(0),ed.nodeChanged()):(ed.setProgressState(0),ed.getParam("spellchecker_report_no_misspellings",!0)&&ed.windowManager.alert("spellchecker.no_mpell"))}))}),ed.getParam("spellchecker_suggestions",!0)&&(ed.onClick.add(self._showMenu,self),ed.onContextMenu.add(self._showMenu,self)),ed.onNodeChange.add(function(ed,cm){cm.setActive("spellchecker",!!self.active)}),self.native_spellchecker||(ed.onPreInit.add(function(){ed.settings.content_css!==!1&&ed.dom.loadCSS(url+"/css/content.css")}),ed.onBeforeGetContent.add(function(){self.active&&self._removeWords()}),ed.onNodeChange.add(function(ed,cm){cm.setActive("spellchecker",!!self.active)}),ed.onSetContent.add(function(){self._done()}),ed.onBeforeGetContent.add(function(){self._done()}),ed.onBeforeExecCommand.add(function(ed,cmd){"mceFullScreen"==cmd&&self._done()})),self.languages={},each(ed.getParam("spellchecker_languages","+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv","hash"),function(v,k){0===k.indexOf("+")&&(k=k.substring(1),self.selectedLang=v),self.languages[k]=v}),ed.onInit.add(function(){if(self.native_spellchecker&&ed.getParam("spellchecker_browser_state",0)){var body=ed.getBody();body.spellcheck=self.active=!self.active}})},createControl:function(n,cm){var c,self=this;self.editor;if("spellchecker"==n)return self.native_spellchecker?c=cm.createButton(n,{title:"spellchecker.desc",cmd:"mceSpellCheck",scope:self}):(c=cm.createSplitButton(n,{title:"spellchecker.desc",cmd:"mceSpellCheck",scope:self}),c.onRenderMenu.add(function(c,m){m.add({title:"spellchecker.langs",class:"mceMenuItemTitle"}).setDisabled(1),self.menuItems={},each(self.languages,function(v,k){var mi,o={};o.onclick=function(){v!=self.selectedLang&&(self._updateMenu(mi),self.selectedLang=v)},o.title=k,mi=m.add(o),mi.setSelected(v==self.selectedLang),self.menuItems[v]=mi,v==self.selectedLang&&(self.selectedItem=mi)})}),c)},setLanguage:function(lang){var self=this;if(lang!=self.selectedLang){if(0===tinymce.grep(self.languages,function(v){return v===lang}).length)throw"Unknown language: "+lang;self.selectedLang=lang,self.menuItems&&self._updateMenu(self.menuItems[lang]),self.active&&self._done()}},_updateMenu:function(mi){mi.setSelected(1),this.selectedItem.setSelected(0),this.selectedItem=mi},_walk:function(n,f){var w,d=this.editor.getDoc();if(d.createTreeWalker)for(w=d.createTreeWalker(n,NodeFilter.SHOW_TEXT,null,!1);null!=(n=w.nextNode());)f.call(this,n);else tinymce.walk(n,f,"childNodes")},_getSeparators:function(){var i,re="",str=this.editor.getParam("spellchecker_word_separator_chars",'\\s!"#$%&()*+,-./:;<=>?@[]^_{|}ß©´Æ±∂∑∏ªºΩæø◊˜§”“');for(i=0;i<str.length;i++)re+="\\"+str.charAt(i);return re},_getWords:function(){var ed=this.editor,wl=[],tx="",lo={},rawWords=[];return this._walk(ed.getBody(),function(n){3==n.nodeType&&(tx+=n.nodeValue+" ")}),ed.getParam("spellchecker_word_pattern")?rawWords=tx.match("("+ed.getParam("spellchecker_word_pattern")+")","gi"):(tx=tx.replace(new RegExp("([0-9]|["+this._getSeparators()+"])","g")," "),tx=tinymce.trim(tx.replace(/(\s+)/g," ")),rawWords=tx.split(" ")),each(rawWords,function(v){lo[v]||(wl.push(v),lo[v]=1)}),wl},_removeWords:function(w){var ed=this.editor,dom=ed.dom,se=ed.selection,r=se.getRng(!0);each(dom.select("span").reverse(),function(n){n&&(dom.hasClass(n,"mce-item-hiddenspellword")||dom.hasClass(n,"mce-item-hidden"))&&(w&&dom.decode(n.innerHTML)!=w||dom.remove(n,1))}),se.setRng(r)},_markWords:function(wl){var ed=this.editor,dom=ed.dom,doc=ed.getDoc(),se=ed.selection,r=se.getRng(!0),nl=[],w=wl.join("|"),re=this._getSeparators(),rx=new RegExp("(^|["+re+"])("+w+")(?=["+re+"]|$)","g");this._walk(ed.getBody(),function(n){3==n.nodeType&&nl.push(n)}),each(nl,function(n){var node,elem,txt,pos,v=n.nodeValue;if(rx.lastIndex=0,rx.test(v)){if(v=dom.encode(v),elem=dom.create("span",{class:"mce-item-hidden"}),tinymce.isIE){for(v=v.replace(rx,"$1<mcespell>$2</mcespell>");(pos=v.indexOf("<mcespell>"))!=-1;)txt=v.substring(0,pos),txt.length&&(node=doc.createTextNode(dom.decode(txt)),elem.appendChild(node)),v=v.substring(pos+10),pos=v.indexOf("</mcespell>"),txt=v.substring(0,pos),v=v.substring(pos+11),elem.appendChild(dom.create("span",{class:"mce-item-hiddenspellword"},txt));v.length&&(node=doc.createTextNode(dom.decode(v)),elem.appendChild(node))}else elem.innerHTML=v.replace(rx,'$1<span class="mce-item-hiddenspellword">$2</span>');dom.replace(elem,n)}}),se.setRng(r)},_showMenu:function(ed,e){var p1,self=this,ed=self.editor,m=self._menu,dom=ed.dom,vp=dom.getViewPort(ed.getWin()),wordSpan=e.target;return e=0,m||(m=ed.controlManager.createDropMenu("spellcheckermenu",{keyboard_focus:!0}),self._menu=m),dom.hasClass(wordSpan,"mce-item-hiddenspellword")?(m.removeAll(),m.add({title:"spellchecker.wait",class:"mceMenuItemTitle"}).setDisabled(1),self._sendRPC("getSuggestions",[self.selectedLang,dom.decode(wordSpan.innerHTML)],function(r){var ignoreRpc;m.removeAll(),r.length>0?(m.add({title:"spellchecker.sug",class:"mceMenuItemTitle"}).setDisabled(1),each(r,function(v){m.add({title:v,onclick:function(){dom.replace(ed.getDoc().createTextNode(v),wordSpan),self._checkDone()}})})):m.add({title:"spellchecker.no_sug",class:"mceMenuItemTitle"}).setDisabled(1),ed.getParam("show_ignore_words",!0)&&(m.addSeparator(),ignoreRpc=self.editor.getParam("spellchecker_enable_ignore_rpc",""),m.add({title:"spellchecker.ignore_word",onclick:function(){var word=wordSpan.innerHTML;dom.remove(wordSpan,1),self._checkDone(),ignoreRpc&&(ed.setProgressState(1),self._sendRPC("ignoreWord",[self.selectedLang,word],function(r){ed.setProgressState(0)}))}}),m.add({title:"spellchecker.ignore_words",onclick:function(){var word=wordSpan.innerHTML;self._removeWords(dom.decode(word)),self._checkDone(),ignoreRpc&&(ed.setProgressState(1),self._sendRPC("ignoreWords",[self.selectedLang,word],function(r){ed.setProgressState(0)}))}})),self.editor.getParam("spellchecker_enable_learn_rpc")&&m.add({title:"spellchecker.learn_word",onclick:function(){var word=wordSpan.innerHTML;dom.remove(wordSpan,1),self._checkDone(),ed.setProgressState(1),self._sendRPC("learnWord",[self.selectedLang,word],function(r){ed.setProgressState(0)})}}),m.update()}),p1=DOM.getPos(ed.getContentAreaContainer()),m.settings.offset_x=p1.x,m.settings.offset_y=p1.y,ed.selection.select(wordSpan),p1=dom.getPos(wordSpan),m.showMenu(p1.x,p1.y+wordSpan.offsetHeight-vp.y),tinymce.dom.Event.cancel(e)):void m.hideMenu()},_checkDone:function(){var o,self=this,ed=self.editor,dom=ed.dom;each(dom.select("span"),function(n){if(n&&dom.hasClass(n,"mce-item-hiddenspellword"))return o=!0,!1}),o||self._done()},_done:function(){var self=this,la=self.active;self.active&&(self._removeWords(),self._menu&&self._menu.hideMenu(),la&&self.editor.nodeChanged(),self.active=!1)},_sendRPC:function(m,p,cb){var self=this,ed=self.editor,args={id:uid(),method:m,params:p};tinymce.util.XHR.send({url:ed.getParam("site_url")+"index.php?option=com_jce&task=plugin.rpc&plugin=spellchecker&"+ed.settings.query,data:"json="+JSON.stringify(args),content_type:"application/x-www-form-urlencoded",success:function(o){var c;try{c=JSON.parse(o)}catch(e){c={error:"JSON Parse error"}}if(!c||c.error){ed.setProgressState(0);var e=c.error||"JSON Parse error";ed.windowManager.alert(e.errstr||"Error response: "+e)}else cb.call(self,c.result||"")},error:function(x){ed.setProgressState(0),ed.windowManager.alert("Error response: "+x)}})}}),tinymce.PluginManager.add("spellchecker",tinymce.plugins.SpellcheckerPlugin)}();
/*
 * Copyright (c) 2008, www.jc001.cn! All rights reserved.
 *
 * web         : http://www.jc001.cn
 * author      : stcer (ab12cxyh@163.com)
 * version     : 0.1.0 (2008.7.22)
 */
var MAjax = MAjax || {
	
	async : true,
	debug : false,
	status : true,
	
	debugCntner : null,
	debugCntnerId : '_myAjaxDebugInfo',
	loaderCntner : null,
	loaderCntnerId : '_myAjaxLoader',
	
	getXmlHttpRequest : function(){
		if (window.XMLHttpRequest) {
			return new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
	},
	
	send : function(url, callback){
		var _self = this;
		var xmlReq = this.getXmlHttpRequest();
		if(url.indexOf('?') == -1){;
			url += '?'
		}else{
			url += '&'
		}
		url += 'isAjax=1';
		
		xmlReq.onreadystatechange = function(){
			if(xmlReq.readyState == 4 && (xmlReq.status == 200 || xmlReq.status === 0)){
				_self.complated(xmlReq.responseText);
				if(typeof(callback) === 'function'){
					callback(xmlReq.responseText);
				}
				if(_self.debug){
					_self.printInfo(xmlReq.responseText);
				}
			}else if(xmlReq.readyState === 1){
				_self.start();
				if(_self.debug){
					_self.printInfo(url, 'param');
				}
			}
		}
		xmlReq.open("GET", url, this.async);
		xmlReq.send(null);
	},
	
	start : function(){
		if(!this.status){
			return false;	
		}
		this._initLoader();
		
		this.loaderCntner.className = 'ajaxStart'
		this.loaderCntner.innerHTML = 'processing...';	
		this.loaderCntner.style.display = 'block';
		this.loaderCntner.style.top = document.documentElement.scrollTop + 10 + 'px';
	},
	
	complated : function(info){
		if(!this.status){
			return false;	
		}
		this._initLoader();
		this.loaderCntner.className = 'ajaxComplated'
		this.loaderCntner.innerHTML = info ? info : 'complate';	
		//this.loaderCntner.innerHTML = 'complate';
		setTimeout('MAjax._hideLoader()', 2000);
	},
	
	_initLoader : function(){
		if(!this.loaderCntner){
			var id = this.loaderCntnerId;
			if (!(this.loaderCntner = document.getElementById(id))){
				div = document.createElement("div");
				div.id = id;
				div.style.position = "absolute";
				div.style.top = 10 + "px";
				div.style.right = 10 + "px";
				
				div.style.border = "1px solid #f00";
				div.style.backgroundColor = "#eef";
			
				document.body.appendChild(div);
				this.loaderCntner = div;
			}
		}	
	},
	
	_hideLoader : function(){
		if(this.loaderCntner){
			this.loaderCntner.style.display = 'none';
		}
	},
	
	printInfo : function(info, type){
		if(!this.debugCntner){
			var id = this.debugCntnerId;
			if (!(this.debugCntner = document.getElementById(id))){
				div = document.createElement("div");
				div.id = id;
				div.style.position = "absolute";
				div.style.width = "98%";
				div.style.border = "1px solid #f00";
				div.style.backgroundColor = "#eef";

				div.innerHTML = '<a href="###">close</a>'
					+ '<div></div>'
					+ '<hr style="height:1px;border:1px solid red;">'
					+ '<div></div>';
				
				document.body.appendChild(div);
				closeHandel = div.getElementsByTagName("a");
				closeHandel[0].onclick = function(){ div.style.display = 'none'; }
				this.debugCntner = div;
			}
		}
		
		this.debugCntner.style.display = 'block';
		this.debugCntner.style.top = document.documentElement.clientHeight + document.documentElement.scrollTop - 40 + "px";
		var sdivs = this.debugCntner.getElementsByTagName("div");
		if(type === "param"){
			sdivs[0].innerHTML = info;
		}else{
			sdivs[1].innerHTML = info;
		}
	}
}
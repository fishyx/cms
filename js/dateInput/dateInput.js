/*
 * Copyright (c) 2008, www.jc001.cn! All rights reserved.
 *
 * web         : http://www.jc001.cn
 * author      : stcer (ab12cxyh@163.com)
 * version     : 0.1.0 (2008.3.22)
 */
var Class = Class || {
  create: function() {
    return function() {
      this.initialize.apply(this, arguments);
    }
  }
}

Function.prototype.bind = Function.prototype.bind || function() {
  var __method = this, args = new Array();
  for(var __i = 0; __i < arguments.length; __i++){
	 args.push(arguments[__i]);
  }
  var object = args.shift();
  return function() {
	return __method.apply(object, args);
  }
}

// Class FValidate
var DateInput = Class.create();
DateInput.prototype = {
	yearRange : [1980, 2020],
	weekDays : ['日','一','二','三','四','五','六'],
	monthDays : [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
	monthNames : ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
	dateFormat : /^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/,
	
	initialize : function(name, defDate){
		this.name = name;
		this.date = new Date(),
		this.setDate(defDate);
		this.initHtml();
		return this;
	},
	
	setDate : function(date){
		var r, y, m, d;
		if(date && (r = date.match(this.dateFormat))){
			y = parseInt(r[1].replace(/^0/, '')); 
			m = parseInt(r[2].replace(/^0/, '')); 
			d = parseInt(r[3].replace(/^0/, ''));
			if(m > 0){
				m = m - 1;
			}
			this.date.setFullYear(y); 
			this.date.setMonth(m); 
			this.date.setDate(d);
		}
	},
	
	initHtml : function(){
		var inputId = 'Calendar_' + this.name;
		if(!document.getElementById(inputId)){
			var value, html;
			html = '<div id="' + inputId + '" class="dateInputWrap">';
			html += '<input name="'  + this.name + '" size="10" value=""/>';
			html += '<a href="###">&nbsp;&nbsp;</a>';
			html += '</div>';
			document.write(html);
			
			var inputWrap = document.getElementById(inputId);
			var showCalendar = inputWrap.getElementsByTagName("a")[0];
			showCalendar.onclick = this.show.bind(this, showCalendar); // set handle
			
			var inputElement = inputWrap.getElementsByTagName("input")[0];
			inputElement.onblur = this.inputBlur.bind(this); // set handle
		}
		
		var panelId = "dateInputPanel";
		if(!document.getElementById(panelId)){
			html = '<table class="month_year"><tr>';
			html += '<td><a href="###" id="CpreYear">&nbsp;</a></td>';
			html += '<td id="CalendarYear"></td>';
			html += '<td><a href="###" id="CnextYear">&nbsp;</a></td>';
			html += '<td>&nbsp;</td>';
			html += '<td><a href="###" id="CpreMonth">&nbsp;</a></td>';
			html += '<td id="CalendarMonth"></td>';
			html += '<td><a href="###" id="CnextMonth">&nbsp;</a></td>';
			html += '</tr></table>';
			
			html += '<table class="weekdays">';
			html += '<thead><tr>';
			for(var i = 0; i < this.weekDays.length; i++)
				html +=	'<td>' + this.weekDays[i] + '</td>';
			html += '</tr></thead>';
			html += '</table>';
			
			html += '<div id="CalendarDays"></div>';
			html += '<div id="CalendarClose"><a href="###">close</a></div>';
			html = '<div id="' + panelId + '">' + html + '</div>';
			document.write(html);
			// set style
			with(document.getElementById(panelId).style){
				position = 'absolute';
				top = '-100';
				left = '-200';
				display = 'none';
			}
			// set handle
			document.getElementById('CalendarClose').onclick = this.hide.bind(this);
		}
		
		with(document){
			this.panelElement = getElementById(panelId);
			this.yearElement = getElementById("CalendarYear");
			this.monthElement = getElementById("CalendarMonth");
			this.daysElement = getElementById("CalendarDays");
			this.inputElement = getElementById(inputId).getElementsByTagName("input")[0];
		}
		
		this.setInputValue();
	},
	
	hide : function(){
		this.panelElement.style.display = 'none'	
	},
	
	show : function(showCalendar){
		var pos = this.getCoordinate(showCalendar);
		pos.x = pos.x + showCalendar.offsetWidth + 4;
		with(this.panelElement.style){
			display = ''
			try{
				top = pos.y + 'px';
				left = pos.x + 'px';
			}catch(e){
				top = pos.y;
				left = pos.x;
			}
		}
		with(document){
			getElementById('CpreMonth').onclick = this.preMonth.bind(this);
			getElementById('CnextMonth').onclick = this.nextMonth.bind(this);
			getElementById('CpreYear').onclick = this.preYear.bind(this);
			getElementById('CnextYear').onclick = this.nextYear.bind(this);	
		}
		this.refresh();
	},
	
	buildDays : function(){
		var emptyNums = new Date(this.date.getYear(), this.date.getMonth(), 1).getDay();
		var j = 1, html = '';
		if(emptyNums > 0){
			html = '<td colspan="' + emptyNums + '">';
			j += emptyNums;
		}
		
		var d = this.date.getDate();
		var days = this.getMonthDays();
		for(var i = 1; i <= days ; i++){
			html += '<td><a href="###"' + (i == d ? ' class="sel"' : '') + '>' +  i ;
			html += '</a></td>\n';
			if((j++ % 7) == 0){
				html += '</tr><tr>'
			}
		}
		html = '<table class="days"><tr>' + html + '</tr></table>';
		
		this.daysElement.innerHTML = html;
		daylinks = this.daysElement.getElementsByTagName("a");
		for(var i = 0; i < daylinks.length; i++){
			daylinks[i].onclick = this.changeDay.bind(this, daylinks[i].innerHTML);
		}
	},
	
	buildYear : function(){
		var html = '';
		var year = this.date.getFullYear();
		for(i = this.yearRange[0]; i < this.yearRange[1]; i++){
			html += '<option '+ (i == year ? 'selected' : '') + ' value="' + i + '">' ;
			html += i;
			html += '</option>'
		}
		html = '<select id="CalendarYearSelect">' + html + '</select>';
		this.yearElement.innerHTML = html;
		var oSelect = document.getElementById("CalendarYearSelect");
		oSelect.onchange = this.changeYear.bind(this, oSelect);
	},
	
	buildMonth : function(){
		var html = '';
		var month = this.date.getMonth();
		for(var i = 0; i < this.monthNames.length; i++){
			html += '<option '+ (i == month ? 'selected' : '') + ' value="' + i + '">' ;
			html += this.monthNames[i];
			html += '</option>';
		}
		html = '<select id="CalendarMonthSelect">' + html + '</select>';
		this.monthElement.innerHTML = html;
		var oSelect = document.getElementById("CalendarMonthSelect");
		oSelect.onchange = this.changeMonth.bind(this, oSelect);
	},
	
	setInputValue : function(){
		var value;
		value = this.date.getFullYear() + "-";
		value += (this.date.getMonth() + 1) + "-";
		value += this.date.getDate();
		this.inputElement.value = value;
	},
	
	inputBlur : function(){
		this.setDate(this.inputElement.value);
		this.setInputValue();
	},
	
	refresh : function(){
		this.buildDays();
		this.buildYear();
		this.buildMonth();
		this.setInputValue();
	},
	
	changeDay : function(day){
		this.date.setDate(parseInt(day));
		this.setInputValue();
		this.hide();
	},
	
	changeMonth : function(el){
		var value = parseInt(el.value);
		this.date.setMonth(value);
		this.buildDays();
		this.setInputValue();
	},
	
	changeYear : function(el){
		var value = parseInt(el.value);
		this.date.setFullYear(value);
		this.buildDays();
		this.setInputValue();
	},
	
	
	nextMonth : function(){
		this.date.setMonth(this.date.getMonth() + 1);
		this.refresh();
	},
	
	preMonth : function(){
		this.date.setMonth(this.date.getMonth() - 1);
		this.refresh();
	},
	
	nextYear : function(){
		this.date.setFullYear(this.date.getFullYear() + 1);
		this.refresh();
	},
	
	preYear : function(){
		this.date.setFullYear(this.date.getFullYear() - 1);
		this.refresh();
	},
	
	getMonthDays : function(){
		var m = this.date.getMonth();
		if(m == 1){
			var y = this.date.getFullYear();
			if((y % 400) == 0 || ((y % 4 == 0) && ((y % 100) != 0))){
				return 29;	
			}
		}
		return this.monthDays[m];
	},
	
	getCoordinate : function(element){
		var pos = { "x" : 0, "y" : 0 };
		pos.x = document.body.offsetLeft;
		pos.y = document.body.offsetTop;
		do {
			pos.x += element.offsetLeft;
			pos.y += element.offsetTop;
			element = element.offsetParent;
		}while (element.tagName.toUpperCase() != 'BODY')
		return pos;
	}
}
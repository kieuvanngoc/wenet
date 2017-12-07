/*
 boxmobi prototype;
 Level 2;
*/ 
var core = {
    user: null,
    baseURI: null,
  _store : {
	ajax: {},
	data: {},
	method: {},
	variable: {}
  },
  _all_popup: {},
  _show_popup : {},
  /*------ begin active popup -----*/
  _active_popup : function(popup_id, title, content, option) {
	if (core.is_exists(core._all_popup[popup_id])) {
	  var popup = core.get_ele(popup_id);
	  jQuery(popup).remove();
	}
	var config = {
	  background_image : 'style/images/button/close.png',
	  auto_hide: 0,
	  position : 'default', //default, center-center, top-left, top-center, top-right, bottom-left, bottom-center, bottom-right
	  pos_type : 'absolute',
	  type: 'show-hide', //overlay, one-time, show-hide
	  overlay: {
		'background-color' : '#000',
		'opacity' : '0.8'
	  },
	  background:{
		'background-color' : '#fff'
	  },
	  border: {
		//'background-color' : '#bebebe',
		//'padding' : '5px'
	  },
	  title : {
		'background-color' : '#0093D0',
		'color' : '#fff',
		'status': 1,
		'display' : 'block'
	  },
	  content : {
		'width' : '585px',
		'height': 'auto',
		'padding' : '20px',
		'display' : 'block'
	  },
	  before: function(){},
	  release: function(){},
	  onclose: function(){}
	};
	if (core.is_exists(option)) { //load config;
	  for(var o in option) {
		if(!Object.prototype[o] && core.is_exists(option[o])) {
		  if (core.is_func(option[o])) {
			  config[o] = option[o];
		  } else if (core.is_obj(option[o])) {
			for (var i in option[o]) {
			  var sub_opt = option[o];
			  if (!Object.prototype[i] && core.is_exists(sub_opt[i])) {
				config[o][i] = sub_opt[i];
			  }
			}
		  } else {
			config[o] = option[o];
		  }
		}
	  }
	}
	core._all_popup[popup_id] = config.type;
	//get site dimension;
	var windowHeight = jQuery(window).height();
	var windowWidth = jQuery(window).width();
	var pageHeight = jQuery(document).height() ;
	var pageWidth = jQuery(document).width();
	//create overlay popup;
	if (config.type == 'overlay') {
	  var oPopup = jQuery('<div id=' + popup_id + '> </div>')
	  .css({
		'background-color' : config.overlay['background-color'],
		'opacity': config.overlay['opacity'],
		'position' : config.pos_type,
		'top' : '0px',
		'left' : '0px',
		'z-index' : '332',
		'width' : '100%'
	  }).height(pageHeight).appendTo('body');
	} else {
	  //detect close button type;
	  var close_button, close_button_hover;
	  if (config.title.status == 1) {
		close_button = 'popup-close-button pcb-blue-normal';
		close_button_hover = 'popup-close-button pcb-blue-hover';
	  } else if (config.title.status == -1){
		close_button = 'popup-close-button pcb-red-normal';
		close_button_hover = 'popup-close-button pcb-red-hover';
	  } else {
		close_button = 'popup-close-button pcb-orange-normal';
		close_button_hover = 'popup-close-button pcb-orange-hover';
	  }
	  var oButton = jQuery('<div></div>')
	  .addClass(close_button)
	  .mouseover(function(){
		this.className = close_button_hover;
	  }).mouseout(function(){
		this.className = close_button;
	  }).click(function(){
		core._hide_popup(popup_id);
	  });

	  var sTitle = jQuery(title);
	  var oTitle = jQuery('<div class="classic-popup-title"></div>')
	  .css({
		'color' : config.title['color'],
		'background-color' : config.title['background-color'],
        'position': 'relative'
	  }).append(oButton).append(sTitle);

	  var oContent = jQuery('<div id="popup-container" style="color: black"></div>')
	  .css({
		'font-size' : core.is_exists(config.content['font-size']) ? config.content['font-size'] : '14px',
		'height' : config.content['height'],
		'display' : config.content['display'],
		'width' : '100%',
		'float' : 'left',
		'background' : core.is_exists(config.background['background-color'])?config.background['background-color']:'#FFF'
	  });

	  var content_popup_id = null;
	  var content_popup_state = null;
	  if (core.is_str(content)) {
		oContent.html(content);
	  } else if (core.is_ele(content)) {
		//store state content visibility;
		content_popup_id = content.id;
		content_popup_state = content.style.display;
		oContent.append(content);
		content.style.display = "block";
	  }

	  var blockContent = jQuery('<div style="background-color: '+config.background['background-color']+'"></div>');

	  var oPopup = jQuery('<div id=' + popup_id + ' class="popbg"></div>')
	  .css({
		'position' : config.pos_type,
		'padding' : config.border['padding'],
		'opacity' : '0.4',
		'z-index' : '333',
		'width' : config.content['width']
	  }).append(blockContent.append(oTitle).append(oContent)).appendTo('body').fadeTo("slow", 1);

	  //store state of content popup;
	  if (content_popup_id) {
		core.get_ele(popup_id).content_popup = {
		  id : content_popup_id,
		  state : content_popup_state
		};
	  }

	  config.before(oPopup);
	  //display popup;
	  switch (config.position) {
		case 'top-left': oPopup.css({'top': 0,'left':0}); break;
		case 'top-center': oPopup.css({'top': 0,'left':(pageWidth - oPopup.width()) / 2}); break;
		case 'top-right': oPopup.css({'top': 0,'right' : 0}); break;
		case 'center-center': oPopup.css({'top':  (windowHeight - oPopup.height()) / 2,'left' : (pageWidth - oPopup.width()) / 2}); break;
		case 'bottom-left': oPopup.css({'bottom': 0,'left' : 0}); break;
		case 'bottom-center': oPopup.css({'bottom': 0, 'left' : (pageWidth - oPopup.width()) / 2}); break;
		case 'bottom-right':  oPopup.css({'bottom': 0,'right' : 0}); break;
		case 'default': oPopup.css({'top': core.get_top_page() + 92,'left' : (pageWidth - oPopup.width()) / 2}); break;
	  }// end of else;
	}

	//auto hide;
	if (config.auto_hide) {
	  setTimeout(function() {
		oPopup.fadeTo('show', 0, function() {
		  if (config.type != 'show-hide') {
			jQuery(this).remove();
		  } else  {
			jQuery(this).hide();
		  }
		});
	  },
	  config.auto_hide);
	}
	core.get_ele(popup_id).onclose = config.onclose;
	config.release(oPopup);
	return oPopup;
  },
/*----- end active popup ------*/
  _hide_popup: function(id) {
	var popup = core.get_ele(id);
	if (core.is_ele(popup)) {
	  //remove overlay popup if it exists;
	  core.hide_popup(popup.overlay_popup);
	  //restore state visibility;
	  if (core.is_exists(popup.content_popup)) {
		var content_popup = core.get_ele(popup.content_popup.id);
		content_popup.style.display = popup.content_popup.state;
	  }
	  //remove chaos popup;
	  if (core._all_popup[id] == 'one-time' || core._all_popup[id] == 'overlay') {
		core._all_popup[id] = null;
		delete core._all_popup[id];
		popup.parentNode.removeChild(popup);
	  } else {
		popup.style.display = "none";
	  }
	  var onclose = popup.onclose;
	  if (core.is_func(onclose)) {
		onclose();
	  } else if (core.is_str(onclose)) {
		eval(onclose);
	  }
	}
  }
};

//check every thing;
core.is_arr = function(arr) { return (arr != null && arr.constructor == Array) };

core.is_str = function(str) { return (str && (/string/).test(typeof str)) };

core.is_func = function(func) { return (func != null && func.constructor == Function) };

core.is_num = function(num) { return (num != null && num.constructor == Number) };

core.is_obj = function(obj) { return (obj != null && obj instanceof Object) };

core.is_ele = function(ele) { return (ele && ele.tagName && ele.nodeType == 1) };

core.is_exists = function(obj) { return (obj != null && obj != undefined && obj != "undefined") };

core.is_json = function(){};

core.is_blank = function(str) { return (core.util_trim(str) == "") };

core.is_phone = function(num) {
  //return (/^(0120|0121|0122|0123|0124|0125|0126|0127|0128|0129|0163|0164|0165|0166|0167|0168|0169|0188|0199|090|091|092|093|094|095|096|097|098|099)(\d{7})$/i).test(num);
  return (/^(01([0-9]{2})|09[0-9])(\d{7})$/i).test(num);
};

core.is_email = function(str) {return (/^[a-z-_0-9\.]+@[a-z-_=>0-9\.]+\.[a-z]{2,3}$/i).test(core.util_trim(str))};

core.is_username = function(value){ return (value.match(/^[0-9]/) == null) && (value.search(/^[0-9_a-zA-Z]*$/) > -1); }

core.is_link = function(str){ return (/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/).test(core.util_trim(str)) };

core.is_image = function(imagePath){
  var fileType = imagePath.substring(imagePath.lastIndexOf("."),imagePath.length).toLowerCase();
  return (fileType == ".gif") || (fileType == ".jpg") || (fileType == ".png") || (fileType == ".jpeg");
};

core.is_ff  = function(){ return (/Firefox/).test(navigator.userAgent) };

core.is_ie  = function() { return (/MSIE/).test(navigator.userAgent) };

core.is_ie6 = function() { return (/MSIE 6/).test(navigator.userAgent) };

core.is_ie7 = function() { return (/MSIE 7/).test(navigator.userAgent) };

core.is_ie8 = function() { return (/MSIE 8/).test(navigator.userAgent) };

core.is_chrome = function(){ return (/Chrome/).test(navigator.userAgent) };

core.is_opera = function() { return (/Opera/).test(navigator.userAgent) };

core.is_safari = function(){ return (/Safari/).test(navigator.userAgent) };

//working with ajax;
core.ajax_get = function(){};
core.ajax_post = function(){};

core.ajax_popup = function(url, method, param, callback, option) {
  if (!core.is_exists(url)) return;
  var data = '',
  opt = {
	loading: (core.is_obj(option) && core.is_func(option.loading)) ? option.loading : core.show_loading,
	error: (core.is_obj(option) && core.is_func(option.error)) ? option.error : core.hide_loading
  };
  if(core.is_obj(param)) {
	for (var key in param) {
	  if (Object.prototype[key]) continue;
	  data += '&' + key + '=' + param[key];
	}
  } else if (core.is_str(param)) {
	data = '&' + param;
  }
  var old_ajax = core._store.ajax[url];
  if (core.is_exists(old_ajax) && old_ajax === data) {
	return;
  } else {
	core._store.ajax[url] = data;
  }
  data += '&rand=' + Math.random();
  jQuery.ajax({
	beforeSend: opt.loading,
	url : core.baseURI + 'ajax.php?' + url,
	type: method ? method : 'POST',
	data: data,
	dataType: 'json',
	success: function(xhr) {
	  core._store.ajax[url] = null;
	  delete core._store.ajax[url];
	  core.hide_loading();
	  if (xhr && core.is_exists(xhr.intReturn)) {
		switch(xhr.intReturn) {
		  case -1: core.show_popup_message(xhr.msg, "Th�ng b�o l?i!", -1); break;
		  case 0:  core.show_popup_message(xhr.msg, "C?nh b�o", 0); break;
		  case 1:  core.show_popup_message(xhr.msg, "Th�ng b�o", 1);break;
		}
	  }
	  if (core.is_exists(xhr.script)) {
		eval(xhr.script);
	  }
	  if(core.is_exists(callback)) {
		callback(xhr);
	  }
	},
	error: function(xhr) {
	  core._store.ajax[url] = null;
	  delete core._store.ajax[url];
	  opt.error();
	  //core.show_popup_message("L?i k?t n?i m?ng", "Th�ng b�o l?i!", -1);
	}
  });
};

core.ajax_tab = function(){};

core.show_loading = function (txt){
  txt = core.is_str(txt) ? txt : '�ang t?i d? li?u...';
  jQuery('.float_loading').remove();
  jQuery('body').append('<div class="float_loading">'+txt+'</div>');
  jQuery('.float_loading').fadeTo("fast",0.9);
  core.update_position();
  jQuery(window).scroll(core.updatePosition);
};

core.update_position = function(){
  if (core.is_ie()) {
	jQuery('.mine_float_loading').css('top', document.documentElement['scrollTop']);
  }
};

core.hide_loading = function() {
  jQuery('.float_loading').fadeTo("slow",0,function(){jQuery(this).remove();});
};

//working with popup;
core.show_popup =  function(popup_id, title, content, option) {
  core.hide_all_popup();
  core._active_popup(popup_id, title, content, option);
};

core.hide_popup = function(id) {core._hide_popup(id)};

core.show_next_popup = function(popup_id, title, content, option) {
  core._active_popup(popup_id, title, content, option);
};

core.hide_all_popup = function() {
  for(var i in core._all_popup) {
	if (Object.prototype[i]) continue;
	core._hide_popup(i);
  }
};

//hide all popup when press esc;
jQuery(document).keydown(
  function(event) {
	if (event.keyCode == 27) {
	  core.hide_all_popup();
	}
  }
);

core.show_overlay_popup = function(popup_id, title, content, option) {
  core.hide_all_popup();
  core._active_popup('overlay-popup','','',{
	type: 'overlay',
	overlay: core.is_exists(option) ? option.overlay : null
  });
  core._active_popup(popup_id, title, content, option);
  //store to remove;
  core.get_ele(popup_id).overlay_popup = 'overlay-popup';
  //update height;
  core.get_ele('overlay-popup').style.height =  jQuery(document).height() + 92 + 'px';
};

core.hide_overlay_popup = function(id) {
  window.parent.$("#payFrame").hide("slow");
  window.parent.$("#overlay-popup").css("opacity","0");
  core.hide_popup(id);
  core.hide_popup('overlay-popup');
};


core.show_popup_message = function(message, title, type, width, height) {
  var bg_color;
  if (type == -1) {
	bg_color = '#ba0000';
  } else if (type == 0) {
	bg_color = '#ec6f00';
  } else {
	bg_color = '#034b8a';
  }

  var id_overlay = core.get_uuid();
  core._active_popup(id_overlay, "", "", {
	type: "overlay",
	auto_hide: 3000,
	overlay : {
	  'opacity' : 0.3,
	  'background-color' : '#fff'
	}
  });

  var id_popup = core.get_uuid();
  core._active_popup(id_popup, title, message, {
	type: 'one-time',
	auto_hide: 3000,
	title: {
	  'background-color' : bg_color,
	  'status' : type
	},
	content: {
	  'width' : width ? width : '300px',
	  'height' : height ? height : 'auto'
	}
  });
  //store to remove;
  core.get_ele(id_popup).overlay_popup = id_overlay;
  //update height;
  core.get_ele(id_overlay).style.height =  jQuery(document).height() + 'px';
};

core.show_access_notify = function() {
  core.show_overlay_popup(
	"popup_access_notify",
	"Th�ng b�o",
	core.get_ele("access_notify"),
	{
	  title: {
		'background-color' : 'red',
		'status' : -1
	  },
	  content: {width: '400px'}
	}
  );
};

core.confirm = function(message, callback, callback_data) {
	//halm: update data for callback function :D
	core.show_next_popup(
	  "popup_confirm",
	  "X�c nh?n",
	  core.join
	  ('<div style="font-weight: bold; margin: 0 0 10px;">' + message + '</div>')
	  ('<div align="center"><input type="button" value="�?ng �" onclick="core.confirm_ok()" />&nbsp;&nbsp;&nbsp;')
	  ('<input type="button" value="H?y b?" onclick="core.hide_popup(\'popup_confirm\')" /></div>')(),
	  {content: {width: "300px"}}
	);
	core._store.method["popup_confirm"] = callback;
	core._store.method["popup_confirm_data"] = callback_data;
};

core.confirm_ok = function(){
  core._store.method["popup_confirm"](core._store.method["popup_confirm_data"]);
  core.hide_popup("popup_confirm");
  core._store.method["popup_confirm"] = null;
  core._store.method["popup_confirm_data"] = null;
  delete core._store.method["popup_confirm"];
  delete core._store.method["popup_confirm_data"];
};

//Working with something;
core.util_trim = function(str) {return (/string/).test(typeof str) ? str.replace(/^\s+|\s+$/g, "") : ""};

core.util_random = function(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a };

core.get_ele = function(id) { return document.getElementById(id) };

core.get_uuid = function() { return (new Date().getTime() + Math.random().toString().substring(2))};

core.get_top_page = function() {
  if (core.is_exists(window.pageYOffset)) {
	return window.pageYOffset;
  }
  if (core.is_exists(document.compatMode) && document.compatMode != 'BackCompat') {
	return document.documentElement.scrollTop;
  }
  if (core.is_exists(document.body)) {
	scrollPos = document.body.scrollTop;
  }
  return 0;
};

core.get_form = function(form_id) {
  var form = core.get_ele(form_id);

  if (!core.is_ele(form)) return '';

  var arr = [], inputs = form.getElementsByTagName("input");

  for (var i = 0; i < inputs.length; i ++) {
	var item = inputs[i];
	if (item.type != 'button') {
	  arr.push(item.name + "=" + encodeURIComponent(item.value));
	}
  }

  var selects = form.getElementsByTagName("select");

  for (var i = 0; i < selects.length; i ++) {
	var item = selects[i],
	key = item.name,
	value = item.options[item.selectedIndex].value;
	arr.push(key + "=" + encodeURIComponent(value));
  }

  var textareas = form.getElementsByTagName("textarea");

  for (var i = 0; i < textareas.length; i ++) {
	var item = textareas[i];
	arr.push(item.name + "=" + encodeURIComponent(item.value));
  }

  return arr.join("&");
};

/*
  Level 3
*/

//halm: fade image to hide loading
core.fadeImageLoading = function(obj, speed, width, height) {
  speed = speed ? speed : 400;
  jQuery(obj).fadeTo(speed,1,function(){
	if(width){
	  jQuery(obj).parent().css({width:'auto'});
	}
	if(height){
	  jQuery(obj).parent().css({height:'auto'});
	}
  });
};
	
// using to fix with for image;	
core.fix_width_element = function(obj, limit) {
  var width = jQuery(obj).width(),
  height = jQuery(obj).height(),
  max_width = limit || 468;
  if (width > max_width) {
	var ratio = (height / width );
	var new_width = max_width;
	var new_height = (new_width * ratio);
	jQuery(obj).height(new_height).width(new_width);
  }
};

//redirect to url
core.redirect = function(url){window.location = url};

//show form error
core.raiseError = function(id, msg, focus, cl, icon){
  if(focus){jQuery(id).focus()}
  if(cl == undefined || cl == null || cl==''){
    jQuery(id).addClass('error');
  }else{
	jQuery(id).removeClass('error');
  }
  var p = jQuery(id).parent();
  jQuery('.showErr',p).remove();
  if(icon){
	jQuery('.showErrIconFalse',p).remove();
	jQuery('.showErrIconTrue',p).remove();
  }
  p.append((icon?'<span class="showErrIcon'+(cl?'True':'False')+'"></span>':'')+'<span class="pLeft5 showErr"><font color="'+(cl?'green':'red')+'">'+msg+'</font></span>');
};

//close form error
core.closeErr = function (id, icon){
  jQuery(id).removeClass('error');
  var p = jQuery(id).parent();
  jQuery('.showErr',p).remove();
  if(icon){
	jQuery('.showErrIconFalse',p).addClass('showErrIconTrue').removeClass('showErrIconFalse');
  }
};

core.styleInputTxT = function(){
  jQuery(":text,:password").focus(function(){jQuery(this).addClass('active') });
  jQuery(":text,:password").blur(function() {jQuery(this).removeClass('active')});
};

/* function core connect */
String.prototype.E = function() {return core.get_ele(this)};

core.join = function(str) {
  var store = [str];
  return function extend(other) {
    if (other != null && 'string' == typeof other ) {
      store.push(other);
      return extend;
    }
    return store.join('');
  }
};

core.nextNumber = (function(){
  var i = 0;
  return function() {return ++i}
}());

core.showInputInline = function(obj, value){
  if(jQuery('#inline_input', obj).html() == null){
	obj.innerHTML = core.join('<input type="text" value="'+value.replace(/(<([^>]+)>)/ig,"")+'" id="inline_input" onblur="core.closeInputInline(this)" />')('<div class="hidden">'+obj.innerHTML+'</div>')();
	jQuery('#inline_input', obj).select().focus();
  }
};

core.closeInputInline = function(obj){
  var parent = jQuery(obj).parent(), txt = jQuery('.hidden', parent).html();
  parent.html(txt);
};

core.numberOnly = function(myfield, e){
  var key,keychar;
  if (window.event){key = window.event.keyCode}
  else if (e){key = e.which}
  else{return true}
  keychar = String.fromCharCode(key);
  if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27)){return true}
  else if (("0123456789").indexOf(keychar) > -1){return true}
  return false;
};

core.fix_png = function(id) {
  if (navigator.appVersion.match(/MSIE [0-6]\./)) {
	jQuery(id).each(function () {
	  var background_image = jQuery(this).css("backgroundImage");
	  if (background_image != 'none') {
		if (background_image.substring(4, 5) == '"') {
		  var img_src = background_image.substring(5, background_image.length - 2)
		} else {
		  var img_src = background_image.substring(4, background_image.length - 1)
		}
		jQuery(this).css({
		  'backgroundColor': 'transparent',
		  'backgroundImage': 'none',
		  'filter': "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='" + img_src + "')"
		})
	  }
	})
  }
};

core.create3DText = function(id, deep, mainColor, overColor, left){
  deep = deep ? deep : 1;
  var html = jQuery(id).html(),
  w = jQuery(id).width()+deep,
  h = jQuery(id).height()+deep,
  u = core.get_uuid(),
  c = '#txt3D'+u+' .txt2DMain a{color:'+mainColor+'}#txt3D'+u+' .txt2DOverlay a{color:'+overColor+'}';
  
  html = core.join
  ('<div class="txt3D" id="txt3D'+u+'" style="position:relative;z-index:0;width:'+w+'px;height:'+h+'px;">')
	('<div class="txt2DMain" style="position:absolute;z-index:3;top:0;left:'+(left?0:'1px')+';width:'+w+'px;height:'+h+'px;color:'+mainColor+'">'+html+'</div>')
	('<div class="txt2DOverlay" style="position:absolute;z-index:1;top:'+deep+'px;left:'+(left?deep+'px':0)+';width:'+w+'px;height:'+h+'px;color:'+overColor+'">'+html+'</div>')
  ('</div>')();
  core.bindCSS(c);
  jQuery(id).html(html);
};

core.bindCSS = function (a) {
  var c = document.createElement("style");
  c.type = "text/css";
  document.getElementsByTagName("head")[0].appendChild(c);
  if (c.styleSheet) c.styleSheet.cssText = a;
  else c.appendChild(document.createTextNode(a))
};

core.goTopStart = function(){
  jQuery('body').append('<a href="javascript:void(0)" onclick="jQuery(\'html,body\').animate({scrollTop: 0},1000);" class="go_top" style="display:none"></a>');
  jQuery(window).scroll(function(){
	var top = 0;
	if(document.documentElement && document.documentElement.clientHeight){
	  top = document.documentElement.scrollTop;
	}else if(document.body){
	  top = document.body.scrollTop;
	}
	if(top > 0){
	  if (core.is_ie6() || core.is_ie7()) {
		top = top + jQuery(window).height() - 30;
		jQuery('.go_top').css('top', top);
	  }
	  jQuery('.go_top').show();
	}else{
	  jQuery('.go_top').hide()
	}
  });
};

core.deleteCache = function(cacheKey){
  core.ajax_popup("act=admin&code=delcache",'POST',{cacheKey:cacheKey},
	function(j){
		if(j.err == 0){
			core.cart.theme.systemAlert(
			  core.join
				('<div style="font-size:14px;margin-top:5px">Xo� cache th�nh c�ng</div>')()
				,'H? th?ng'
			);
		}
	});
};

core.error = {
  set:function(id, msg, width, cl){
	msg = msg ? msg : '';
	width = width ? width : 430;
	var html = core.join
	('<div class="my_msg" style="width: '+width+'px; color:red; margin: 5px auto 15px; padding:10px; background:rgb(255, 249, 215); border: 1px solid rgb(226, 200, 34); text-align: center; font-size: 15px;">')
	  (msg)
	('</div>')();
	if(cl){
	  jQuery('#cError', jQuery(cl)).html(html);
	}else{
	  jQuery('#cError').html(html);
	}
	jQuery(id).addClass('error').focus();
  },
  close:function(id, cl){
	if(cl){
	  jQuery('#cError', jQuery(cl)).html('');
	}else{
	  jQuery('#cError').html('');
	}
	jQuery(id).removeClass('error');
  }
};

core.cookie = {
    COOKIE_ID: 'core',
	set: function(name, value, expires, path, domain, secure) {
		expires instanceof Date ? expires = expires.toGMTString() : typeof(expires) == 'number' && (expires = (new Date( + (new Date) + expires * 1e3)).toGMTString());
		var r = [core.cookie.COOKIE_ID+'_'+name + "=" + escape(value)], s, i;
		if(domain == undefined && document.URL.search(/pay.soha.vn/i) > 0){
		  domain = '.pay.soha.vn';
		}
		if(path == undefined){
		  path = '/';
		}
		for (i in s = {
			expires: expires,
			path: path,
			domain: domain
		}){
			s[i] && r.push(i + "=" + s[i])
		}
		return secure && r.push("secure"), document.cookie = r.join(";"), true
	},
	get: function(c_name) {
		if (document.cookie.length > 0) {
			c_name = core.cookie.COOKIE_ID+'_'+c_name;
			c_start = document.cookie.indexOf(c_name + "=");
			if (c_start != -1) {
				c_start = c_start + c_name.length + 1;
				c_end = document.cookie.indexOf(";", c_start);
				if (c_end == -1) c_end = document.cookie.length;
				return unescape(document.cookie.substring(c_start, c_end))
			}
		}
		return ""
	}  
};
TIME_NOW = 0;
core.serverTimeCounter = function(){
  TIME_NOW++;
  var d = new Date(TIME_NOW*1000),
  hour= d.getHours(),
  min = d.getMinutes(),
  sec = 0,
  iSecond = false,
  objectTime = core.get_ele('serverTimeCounter');
  if(objectTime){
	if(iSecond){sec=d.getSeconds();}
	objectTime.innerHTML = (hour>9?'':'0')+(hour>0?hour:0)+':'+(min>9?'':'0')+(min>0?min:0)+(iSecond?(':'+(sec>9?'':'0')+(sec>0?sec:0)):'');
  }
  setTimeout(core.serverTimeCounter, 1000);
};
//start timer
core.serverTimeCounter();

core.hideProduct = function(id, is_home){
  var count = 0, count2 = 0;
  if(core.get_ele('blockSold'+id)){
	jQuery('#blockSold'+id).hide();
	if(jQuery('#blockSold'+id).hasClass('first')){
	  jQuery('.blockSoldItemBorder:first').removeClass('blockSoldItemBorder').removeClass('mTop15');
	}
	jQuery('.blockSoldItem').each(function(){count++});
	if(count <= 1){
	  jQuery('.blockSold').parent().parent().parent().hide();
	}
  }else{
	if(is_home){
	  jQuery('.blockSoldItem').each(function(){
		if(jQuery(this).hasClass('blockSoldHide3')){
		  jQuery(this).hide();
		  count++;
		}else{
		  count2++;
		}
	  });
	  if(count2 == 0){
		jQuery('.blockSold').parent().parent().parent().hide();
	  }
	}
	if(count <= 0){
	  jQuery('.blockSoldItemLast').hide();
	}
  }
};
core.enter = function(id, cb){
  if(cb){
	jQuery(id).keydown(
	  function(event) {
		if (event.keyCode == 13) cb();
	  }
	);
  }
};
core.numberFormat = function( number, decimals, dec_point, thousands_sep ){
  var n = number, prec = decimals;
  n = !isFinite(+n) ? 0 : +n;
  prec = !isFinite(+prec) ? 0 : Math.abs(prec);
  var sep = (typeof thousands_sep == "undefined") ? '.' : thousands_sep;
  var dec = (typeof dec_point == "undefined") ? ',' : dec_point;
  var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
  var abs = Math.abs(n).toFixed(prec);
  var _, i;
  if (abs >= 1000) {
	  _ = abs.split(/\D/);
	  i = _[0].length % 3 || 3;
	  _[0] = s.slice(0,i + (n < 0)) +
			_[0].slice(i).replace(/(\d{3})/g, sep+'$1');
	  s = _.join(dec);
  } else {
	  s = s.replace(',', dec);
  }
  return s;
};

core.mixMoney = function(obj){
	var strvalue;
	if (eval(obj))
	    strvalue = eval(obj).value;
	else
	    strvalue = obj; 
	var num;
    num = strvalue.toString().replace(/\$|\./g,'');

    if(isNaN(num))
    num = "";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    num = Math.floor(num/100).toString();
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+'.'+
    num.substring(num.length-(4*i+3));
    eval(obj).value = (((sign)?'':'-') + num);
};

core.selectAllText = function(obj){
  obj.focus();
  obj.select();
};
core.popupSite = function(id, title, content, close, opt){
  close = close ? 'core.hide_overlay_popup(\''+close+'\');' : '';
  var style = '';
  if(opt){
	  style = 'margin:0 auto;';
	  if(core.is_exists(opt.width)){
		  style += 'width:'+opt.width+'px;';
	  }
	  if(core.is_exists(opt.height)){
		  style += 'height:'+opt.height+'px;';
	  }
	  style = ' style="'+style+'"';
  }
  return core.join
  ('<div class="classic-popup"'+style+'>')
	  ('<div class="classic-popup-top"><div class="right"><div class="bg"></div></div></div>')
	  ('<div class="classic-popup-main">')
		  ('<div class="classic-popup-title">')
			  ('<div class="fl">'+title+'</div>')
			  ('<a href="javascript:void(0)" class="classic-popup-close" title="��ng" onclick="core.hide_overlay_popup(\''+id+'\');'+close+'">x</a>')
			  ('<div class="c"></div>')
		  ('</div>')
		  ('<div class="classic-popup-content">'+content+'</div>')
	  ('</div>')
	  ('<div class="classic-popup-bottom"><div class="right"><div class="bg"></div></div></div>')
  ('</div>')();
};
core.echo = function(v){
  jQuery('body').append(prettyPrint(v));
};
core.hover = {
  c_clicked: '#fff',
  over:function(obj, color){obj.style.backgroundColor = color},
  out:function(obj){
	if(jQuery(obj).hasClass('tr_clicked')){
	  obj.style.backgroundColor = core.hover.c_clicked;
	}else{
	  obj.style.backgroundColor = '';
	}
  }
};

core.setUserInfo = function(user){
    core.user = user;
}
core.setBaseURI = function(baseURI){
    core.baseURI = baseURI;
}

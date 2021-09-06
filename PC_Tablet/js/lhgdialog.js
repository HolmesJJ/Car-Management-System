;(function( $, window, undefined ){

var _ie6 = window.ActiveXObject && !window.XMLHttpRequest,
	_fn = function(){},
	_count = 0,
	_rurl = /^url:/,
	_singleton,
	onKeyDown,
	document = window.document,
	expando = 'JDG' + (+new Date),

dialogTpl =
'<table class="ui_border">' +
	'<tbody>' +
		'<tr>' +
			'<td class="ui_lt"></td>' +
			'<td class="ui_t"></td>' +
			'<td class="ui_rt"></td>' +
		'</tr>' +
		'<tr>' +
			'<td class="ui_l"></td>' +
			'<td class="ui_c">' +
				'<div class="ui_inner">' +
				'<table class="ui_dialog">' +
					'<tbody>' +
						'<tr>' +
							'<td colspan="2">' +
								'<div class="ui_title_bar">' +
									'<div class="ui_title" unselectable="on"></div>' +
									'<div class="ui_title_buttons">' +
										'<a class="ui_min" href="javascript:void(0);" title="\u6700\u5C0F\u5316"><b class="ui_min_b"></b></a>' +
										'<a class="ui_max" href="javascript:void(0);" title="\u6700\u5927\u5316"><b class="ui_max_b"></b></a>' +
										'<a class="ui_res" href="javascript:void(0);" title="\u8FD8\u539F"><b class="ui_res_b"></b><b class="ui_res_t"></b></a>' +
										'<a class="ui_close" href="javascript:void(0);" title="\u5173\u95ED(esc\u952E)">\xd7</a>' +
									'</div>' +
								'</div>' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td class="ui_icon"></td>' +
							'<td class="ui_main">' +
								'<div class="ui_content"></div>' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td colspan="2">' +
								'<div class="ui_buttons"></div>' +
							'</td>' +
						'</tr>' +
					'</tbody>' +
				'</table>' +
				'</div>' +
			'</td>' +
			'<td class="ui_r"></td>' +
		'</tr>' +
		'<tr>' +
			'<td class="ui_lb"></td>' +
			'<td class="ui_b"></td>' +
			'<td class="ui_rb"></td>' +
		'</tr>' +
	'</tbody>' +
'</table>',
	
/*!
 * _path 获取组件核心文件lhgdialog.js所在的绝对路径
 * _args 获取lhgdialog.js文件后的url参数组，如：lhgdialog.js?self=true&skin=aero中的?后面的内容
 */
_args, _path = (function( script, i, me )
{
    var l = script.length;
	
	for( ; i < l; i++ )
	{
		me = !!document.querySelector ?
		    script[i].src : script[i].getAttribute('src',4);
		
		if( me.substr(me.lastIndexOf('/')).indexOf('lhgdialog') !== -1 )
		    break;
	}
	
	me = me.split('?'); _args = me[1];
	
	return me[0].substr( 0, me[0].lastIndexOf('/') + 1 );
})(document.getElementsByTagName('script'),0),        

/*!
 * 获取url参数值函数
 * @param  {String}
 * @return {String||null}
 * @demo lhgdialog.js?skin=aero | _getArgs('skin') => 'aero'
 */
_getArgs = function( name )
{
    if( _args )
	{
	    var p = _args.split('&'), i = 0, l = p.length, a;
		for( ; i < l; i++ )
		{
		    a = p[i].split('=');
			if( name === a[0] ) return a[1];
		}
	}
	return null;
},

/*! 取皮肤样式名，默认为 default */
_skin = _getArgs('skin') || 'idialog',

/*! 获取 lhgdialog 可跨级调用的最高层的 window 对象和 document 对象 */
_doc, _top = (function(w)
{
	try{
	    _doc = w['top'].document;  // 跨域|无权限
		_doc.getElementsByTagName; // chrome 浏览器本地安全限制
	}catch(e){
	    _doc = w.document; return w;
	};
	
	// 如果指定参数self为true则不跨框架弹出，或为框架集则无法显示第三方元素
	if( _getArgs('self') === 'true' ||
	    _doc.getElementsByTagName('frameset').length > 0 )
	{
	    _doc = w.document; return w;
	}
	
	return w['top'];
})(window),

_root = _doc.documentElement, _doctype = _doc.compatMode === 'BackCompat';

_$doc = $(_doc), _$top = $(_top), _$html = $(_doc.getElementsByTagName('html')[0]);

/*! 开启IE6 CSS背景图片缓存 */
try{
	_doc.execCommand( 'BackgroundImageCache', false, true );
}catch(e){};

/*! 在最顶层页面添加样式文件 */
(function(style){
	if(!style)
	{
	    var head = _doc.getElementsByTagName('head')[0],
		    link = _doc.createElement('link');
			
		//link.href = _path + 'skins/' + _skin + '.css';
	    link.rel = 'stylesheet';
		link.id = 'lhgdialoglink';
		head.insertBefore(link, head.firstChild);
	}
})(_doc.getElementById('lhgdialoglink'));

/*!
 * IE6下Fixed无抖动静止定位
 * 如果你的页面的html元素设定了背景图片请把设置背景图片的css写到body元素上
 * 如果你不需要组件静止定位（也就是随屏滚动）或此段代码影响了你的页面布局可将此段代码删除
 */
_ie6 && (function(bg){
	if( _$html.css(bg) !== 'fixed' )
	{
		_$html.css({
			zoom: 1,// 避免偶尔出现body背景图片异常的情况
			backgroundImage: 'url(about:blank)',
			backgroundAttachment: 'fixed'
		});
	}
})('backgroundAttachment');

/*!----------------------------------以下为lhgdialog核心代码部分----------------------------------*/

var lhgdialog = function( config )
{
	config = config || {};
	
	var api, setting = lhgdialog.setting;
		
	// 合并默认配置
	for( var i in setting )
	{
		if( config[i] === undefined ) config[i] = setting[i];
	}
	
	config.id = config.id || expando + _count;
	
	// 如果定义了id参数则返回存在此id的窗口对象
	api = lhgdialog.list[config.id];
	if(api) return api.zindex().focus();
	
	// 按钮队列
	config.button = config.button || [];
	
	config.ok &&
	config.button.push({
	    id: 'ok',
		name: config.okVal,
		callback: config.ok,
		focus: config.focus
	});
	
	config.cancel &&
	config.button.push({
	    id: 'cancel',
		name: config.cancelVal,
		callback: config.cancel
	});
	
	// zIndex全局配置
	lhgdialog.setting.zIndex = config.zIndex;
	
	_count++;
	
	return lhgdialog.list[config.id] = _singleton ?
	    _singleton._init(config) : new lhgdialog.fn._init( config );
};

lhgdialog.fn = lhgdialog.prototype =
{
    constructor: lhgdialog,
	
	_init: function( config )
	{
	    var that = this, DOM,
		    content = config.content,
			isIfr = _rurl.test(content);
			
		that.opener = window;
		that.config = config;
		
		that.DOM = DOM = that.DOM || that._getDOM();
		that.closed = false;
		that.data = config.data;
		
		// 假如提示性图标为真默认不显示最小化和最大化按钮
		if( config.icon && !isIfr )
		{
		    config.min = false;
			config.max = false;
			
			DOM.icon[0].style.display = '';
			DOM.icon[0].innerHTML = '<img src="' + config.path + 'skins/icons/' + config.icon + '" class="ui_icon_bg"/>';
		}
		else
		    DOM.icon[0].style.display = 'none';

		DOM.wrap.addClass( config.skin ); // 多皮肤共存
		DOM.rb[0].style.cursor = config.resize ? 'se-resize' : 'auto';
		DOM.title[0].style.cursor = config.drag ? 'move' : 'auto';
		DOM.max[0].style.display = config.max ? 'inline-block' : 'none';
		DOM.min[0].style.display = config.min ? 'inline-block' : 'none';
		DOM.close[0].style.display = config.cancel === false ? 'none' : 'inline-block'; //当cancel参数为false时隐藏关闭按钮
		DOM.content[0].style.padding = config.padding;
		
		that.button.apply( that, config.button );
		
		that.title( config.title )
		.content( content, true, isIfr )
		.size( config.width, config.height )
		.position( config.left, config.top )
		.time( config.time )
		[config.show?'show':'hide'](true).zindex();
		
		config.focus && that.focus();
		config.lock && that.lock();
		that._ie6PngFix()._addEvent();
		
		_singleton = null;
		
		// 假如加载的是单独页面的内容页config.init函数会在内容页加载完成后执行，这里就不执行了
		if( !isIfr && config.init )
		    config.init.call( that, window );
		
		return that;
	},
};

lhgdialog.fn._init.prototype = lhgdialog.fn;

/*! 此对象用来存储获得焦点的窗口对象实例 */
lhgdialog.focus = null;

/*! 存储窗口实例的对象列表 */
lhgdialog.list = {};

/*!
 * 全局快捷键
 * 由于跨框架时事件是绑定到最顶层页面，所以当当前页面卸载时必须要除移此事件
 * 所以必须unbind此事件绑定的函数，所以这里要给绑定的事件定义个函数
 * 这样在当前页面卸载时就可以移此事件绑定的相应函数，不而不影响顶层页面此事件绑定的其它函数
 */
onKeyDown = function(event)
{
	var target = event.target,
		api = lhgdialog.focus,
		keyCode = event.keyCode;

	if( !api || !api.config.esc || api.config.cancel === false ) return;
		
	keyCode === 27 && api._click(api.config.cancelVal);
};

_$doc.bind('keydown',onKeyDown);

/*!
 * 框架页面卸载前关闭所有穿越的对话框
 * 同时移除拖动层和遮罩层
 */
_top != window && $(window).bind('unload',function()
{
    var list = lhgdialog.list;
	for( var i in list )
	{
	    if(list[i])
		    list[i].close();
	}
	_singleton && _singleton.DOM.wrap.remove();
	
	_$doc.unbind('keydown',onKeyDown);
	
	$('#ldg_lockmask',_doc)[0] && $('#ldg_lockmask',_doc).remove();
	$('#ldg_dragmask',_doc)[0] && $('#ldg_dragmask',_doc).remove();
});

/*! lhgdialog 的全局默认配置 */
lhgdialog.setting =
{
    content: '<div class="ui_loading"><span>loading...</span></div>',
	title: '\u89C6\u7A97 ',     // 标题,默认'视窗'
	button: null,	     		// 自定义按钮
	ok: null,					// 确定按钮回调函数
	cancel: null,				// 取消按钮回调函数
	init: null,					// 对话框初始化后执行的函数
	close: null,				// 对话框关闭前执行的函数
	okVal: '\u786E\u5B9A',		// 确定按钮文本,默认'确定'
	cancelVal: '\u53D6\u6D88',	// 取消按钮文本,默认'取消'
	skin: '',					// 多皮肤共存预留接口
	esc: true,					// 是否支持Esc键关闭
	show: true,					// 初始化后是否显示对话框
	width: 'auto',				// 内容宽度
	height: 'auto',				// 内容高度
	icon: null,					// 消息图标名称
	path: _path,                // lhgdialog路径
	lock: true,				// 是否锁屏
	focus: true,                // 窗口是否自动获取焦点
	parent: null,               // 打开子窗口的父窗口对象，主要用于多层锁屏窗口
	padding: '10px',		    // 内容与边界填充距离
	fixed: false,				// 是否静止定位
	left: '50%',				// X轴坐标
	top: '38.2%',				// Y轴坐标
	max: false,                  // 是否显示最大化按钮
	min: false,                  // 是否显示最小化按钮
	zIndex: 1976,				// 对话框叠加高度值(重要：此值不能超过浏览器最大限制)
	resize: true,				// 是否允许用户调节尺寸
	drag: true, 				// 是否允许用户拖动位置
	cache: true,                // 是否缓存窗口内容页
	data: null,                 // 传递各种数据
	extendDrag: false           // 增加lhgdialog拖拽体验
};

/*!
 *------------------------------------------------
 * 对话框模块-拖拽支持（可选外置模块）
 *------------------------------------------------
 */
var _use, _isSetCapture = 'setCapture' in _root,
	_isLosecapture = 'onlosecapture' in _root;

lhgdialog.dragEvent =
{
    onstart: _fn,
	start: function(event)
	{
	    var that = lhgdialog.dragEvent;
		
		_$doc
		.bind( 'mousemove', that.move )
		.bind( 'mouseup', that.end );
		
		that._sClientX = event.clientX;
		that._sClientY = event.clientY;
		that.onstart( event.clientX, event.clientY );
		
		return false;
	},
	
	onmove: _fn,
	move: function(event)
	{
	    var that = lhgdialog.dragEvent;
		
		that.onmove(
		    event.clientX - that._sClientX,
			event.clientY - that._sClientY
		);
		
		return false;
	},
	
	onend: _fn,
	end: function(event)
	{
	    var that = lhgdialog.dragEvent;
		
		_$doc
		.unbind('mousemove', that.move)
		.unbind('mouseup', that.end);
		
		that.onend(  event.clientX, event.clientY );
		return false;
	}
};

_use = function(event)
{
	var limit, startWidth, startHeight, startLeft, startTop, isResize,
		api = lhgdialog.focus,
		config = api.config,
		DOM = api.DOM,
		wrap = DOM.wrap[0],
		title = DOM.title,
		main = DOM.main[0],
		_dragEvent = lhgdialog.dragEvent,
	
	// 清除文本选择
	clsSelect = 'getSelection' in _top ?
	function(){
		_top.getSelection().removeAllRanges();
	}:function(){
		try{_doc.selection.empty();}catch(e){};
	};
	
	// 对话框准备拖动
	_dragEvent.onstart = function( x, y )
	{
		if( isResize )
		{
			startWidth = main.offsetWidth;
			startHeight = main.offsetHeight;
		}
		else
		{
			startLeft = wrap.offsetLeft;
			startTop = wrap.offsetTop;
		};
		
		_$doc.bind( 'dblclick', _dragEvent.end );
		
		!_ie6 && _isLosecapture
		? title.bind('losecapture',_dragEvent.end )
		: _$top.bind('blur',_dragEvent.end);
		
		_isSetCapture && title[0].setCapture();
		
		DOM.border.addClass('ui_state_drag');
		api.focus();
	};
	
	// 对话框拖动进行中
	_dragEvent.onmove = function( x, y )
	{
		if( isResize )
		{
			var wrapStyle = wrap.style,
				style = main.style,
				width = x + startWidth,
				height = y + startHeight;
			
			wrapStyle.width = 'auto';
			config.width = style.width = Math.max(0,width) + 'px';
			wrapStyle.width = wrap.offsetWidth + 'px';
			
			config.height = style.height = Math.max(0,height) + 'px';
			//api._ie6SelectFix();
		    // 使用loading层置顶窗口时窗口大小改变相应loading层大小也得改变
			api._load && $(api._load).css({width:style.width, height:style.height});
		}
		else
		{
			var style = wrap.style,
				left = x + startLeft,
				top = y + startTop;

			config.left = Math.max( limit.minX, Math.min(limit.maxX,left) );
			config.top = Math.max( limit.minY, Math.min(limit.maxY,top) );
			style.left = config.left + 'px';
			style.top = config.top + 'px';
		}
			
		clsSelect();
	};
	
	// 对话框拖动结束
	_dragEvent.onend = function( x, y )
	{
		_$doc.unbind('dblclick',_dragEvent.end);
		
		!_ie6 && _isLosecapture
		? title.unbind('losecapture',_dragEvent.end)
		: _$top.unbind('blur',_dragEvent.end);
		
		_isSetCapture && title[0].releaseCapture();
		
		_ie6 && api._autoPositionType();
		
		DOM.border.removeClass('ui_state_drag');
	};
	
	isResize = event.target === DOM.rb[0] ? true : false;
	
	limit =	(function(fixed)
	{
		var	ow = wrap.offsetWidth,
			// 向下拖动时不能将标题栏拖出可视区域
			oh = title[0].offsetHeight || 20,
			ww = _$top.width(),
			wh = _$top.height(),
			dl = fixed ? 0 : _$top.scrollLeft(),
			dt = fixed ? 0 : _$top.scrollTop();
		    // 坐标最大值限制(在可视区域内)	
		    maxX = ww - ow + dl;
		    maxY = wh - oh + dt;
		
		return {
			minX: dl,
			minY: dt,
			maxX: maxX,
			maxY: maxY
		};
	})(wrap.style.position === 'fixed');
	
	_dragEvent.start(event);
};

/*! 
 * 页面DOM加载完成执行的代码
 */
$(function(){
	// 触发浏览器预先缓存背景图片
	setTimeout(function()
	{
	    if(_count) return;
		lhgdialog({left:'-9999em',time:9,fixed:false,lock:false,focus:false});
	},150);
	
	// 增强lhgdialog拖拽体验（可选外置模块，如不需要可删除）
	// 防止鼠标落入iframe导致不流畅，对超大对话框拖动优化
	lhgdialog.setting.extendDrag &&
	(function(dragEvent){
	    var mask = _doc.createElement('div'),
		    style = mask.style,
			positionType = _ie6 ? 'absolute' : 'fixed';
		mask.id = 'ldg_dragmask';
		
		style.cssText = 'display:none;position:' + positionType + ';left:0;top:0;width:100%;height:100%;'
		+ 'cursor:move;filter:alpha(opacity=0);opacity:0;background:#FFF;pointer-events:none;';
		
		_doc.body.appendChild(mask);
		
		dragEvent._start = dragEvent.start;
		dragEvent._end = dragEvent.end;
		
		dragEvent.start = function()
		{
			var api = lhgdialog.focus,
				main = api.DOM.main[0],
				iframe = api.iframe;
			
			dragEvent._start.apply(this, arguments);
			style.display = 'block';
			style.zIndex = lhgdialog.setting.zIndex + 3;
			
			if(positionType === 'absolute')
			{
				style.width = _$top.width() + 'px';
				style.height = _$top.height() + 'px';
				style.left = _$doc.scrollLeft() + 'px';
				style.top = _$doc.scrollTop() + 'px';
			};
			
			if( iframe && main.offsetWidth * main.offsetHeight > 307200 )
				main.style.visibility = 'hidden';
		};
		
		dragEvent.end = function()
		{
			var api = lhgdialog.focus;
			dragEvent._end.apply(this, arguments);
			style.display = 'none';
			if(api) api.DOM.main[0].style.visibility = 'visible';
		};
	})(lhgdialog.dragEvent);
});

/*! 使用jQ方式调用窗口 */
$.fn.dialog = function()
{
	var config = arguments;
	this.bind('click',function(){ lhgdialog.apply(this,config); return false; });
	return this;
};
		
window.lhgdialog = $.dialog = lhgdialog;

})( this.jQuery || this.lhgcore, this );

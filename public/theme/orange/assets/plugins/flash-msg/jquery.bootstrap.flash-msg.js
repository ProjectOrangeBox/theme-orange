var orange = (orange) || {};
var messages = (messages) || [];
var plugins = (plugins) || [];

/*
	# add a notice
	$.noticeAdd({text:'abc',type:'success|danger|warning|info',stay:true|false,stayTime:0(milliseconds)});

	# animate a removal
	$.noticeRemove($('#something'));

	# remove all of the notices on the screen
	$.noticeRemoveAll();

	# msg stored until the next page reload
	$.noticeReloadAdd(({text:'abc',type:'success|danger|warning|info',stay:true|false,stayTime:0(milliseconds)});
*/

/* how long flash msgs stay on screen */
plugins.flash_msg = {};
plugins.flash_msg.pause = 3000;

/*
modified to do a bootstrap growl notice
*/
(function(jQuery){
	jQuery.extend({
		noticeAdd: function(options){
			var defaults = {
				inEffect: 			  {opacity: 'show'},	/* in effect */
				inEffectDuration: 600,				        /* in effect duration in milliseconds */
				stayTime: 			  3000,				        /* time in milliseconds before the item has to disappear */
				text: 				    '',					        /* content of the item */
				stay: 				    false,				      /* should the notice item stay or not? */
				type: 				    'info' 			        /* could also be error, success, info */
			}
			
			/* does it look like they send in seconds? */
			if (options.stayTime < 15) {
				options.stayTime = options.stayTime * 1000;
			}

			var options, noticeWrapAll, noticeItemOuter, noticeItemInner, noticeItemClose;

			options = jQuery.extend({}, defaults, options);
			
			noticeWrapAll	= (!jQuery('.notice-wrap').length) ? jQuery('<div></div>').addClass('notice-wrap').appendTo('body') : jQuery('.notice-wrap');
			noticeItemOuter	= jQuery('<div></div>').addClass('notice-item-wrapper');
			noticeItemInner	= jQuery('<div></div>').hide().addClass('notice-item alert alert-' + options.type).attr('data-dismiss','alert').appendTo(noticeWrapAll).html(options.text).animate(options.inEffect, options.inEffectDuration).wrap(noticeItemOuter);
			noticeItemClose	= jQuery('<div></div>').addClass('close').prependTo(noticeItemInner).html('&times;').click(function(e) {
				e.stopPropagation();
				jQuery.noticeRemove(noticeItemInner)
			});

			if (navigator.userAgent.match(/MSIE 6/i)) {
				noticeWrapAll.css({top: document.documentElement.scrollTop});
			}

			if (!options.stay) {
				setTimeout(function() {
					jQuery.noticeRemove(noticeItemInner);
				}, options.stayTime);
			}
		},

		noticeRemove: function(obj) {
			obj.animate({opacity: '0'}, 600, function() {
				obj.parent().animate({height: '0px'}, 300, function() {
					obj.parent().remove();
				});
			});
		},

		noticeRemoveAll: function() {
			$('.notice-item-wrapper').each(function(idx){
				$(this).remove();
			});
		},

		noticeReloadAdd: function(options) {
			var defaults = {
				stayTime: 3000, 	/* time in miliseconds before the item has to disappear */
				text: '',					/* content of the item */
				stay: false,			/* should the notice item stay or not? */
				type: 'info'			/* could also be success, danger, warning, info */
			}

			options = jQuery.extend({}, defaults, options);

			if (options.type == 'danger') {
				options.stay = true;
			}

			$.jStorage.set('flash_msg',options);
		}

	});

})(jQuery);

orange.flash_msg = function(text,type,redirect) {
	/*
	{"text":"Oh Darn!","stay":true,"type":"danger"}
	success, info, warning, danger
	green, blue, yellow, red
	*/

	var map = {
		red: 'danger',
		yellow: 'warning',
		blue: 'info',
		green: 'success',
		danger: 'danger',
		warning: 'warning',
		info: 'info',
		success: 'success'
	};

	sticky = (type == 'danger' || type == 'warning') ? true : false;

	var msg = {};

	msg.text = text;
	msg.type = map[type];
	msg.sticky = sticky;

	/* if redirect is true then they want to show it now! */
	if (redirect === false) {
	  $.noticeAdd(msg);
	} else {
		/* otherwise store it for the next page refresh */
		$.jStorage.set('flash_msg',msg);

		/* if redirect isn't undefined then they must have included a redirect url */
		if (redirect !== undefined) {
			orange.redirect(redirect);
		}
	}
}

orange.get_length = function(object,only) {
	if (only) {
		if (object.hasOwnProperty(only)) {
			return object[only].length > 0;
		}
		
		return 0;
	}
		
	/* all */
	var total = 0;
	
	for (var prop in object) {
		if (object.hasOwnProperty(prop)) {
			total = total + object[prop].length;
		}
	}
	
	return total;
}

orange.get_errors = function(object,lineending,only) {
	/* only */
	if (only) {
		if (object.hasOwnProperty(only)) {
			if (object[only].length > 0) {
				return object[only].join(lineending);
			}
		}
		
		return '';
	}
	
	/* all */
	var groups = [];

	for (var prop in object) {
		if (object.hasOwnProperty(prop)) {
			if (object[prop].length > 0) {
				groups.push(object[prop].join(lineending));
			}
		}
	}

	return groups.join(lineending);
}

/* any message in cold storage? */
var flash_msg = $.jStorage.get('flash_msg',null);

if (flash_msg) {
	/* get it out and show it */
	$.noticeAdd(flash_msg);

	/* remove the key */
	$.jStorage.deleteKey('flash_msg');
}

/* any in message attached to the javascript variable message? */
if (messages) {
	console.log(orange.get_length(messages));
	console.log(orange.get_errors(messages,'<br>'));
	
	if (orange.get_length(messages)) {
		$.noticeAdd({text: orange.get_errors(messages,'<br>')});
	}
}


// jQuery Alert Dialogs Plugin
//
// Version 1.0
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 29 December 2008
//
// Visit http://abeautifulsite.net/notebook/87 for more information
//
// Usage:
//		jAlert( message, [title, callback] )
//		jConfirm( message, [title, callback] )
//		jPrompt( message, [value, title, callback] )
// 
// History:
//
//		1.00 - Released (29 December 2008)
//
// License:
// 
//		This plugin is licensed under the GNU General Public License: http://www.gnu.org/licenses/gpl.html
//
(function($) {
	
	$.alerts = {
		
		// These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time
		
		verticalOffset: 0,                // vertical offset of the dialog from center screen, in pixels
		horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels/
		repositionOnResize: true,           // re-centers the dialog on window resize
		overlayOpacity: .7,                // transparency level of overlay
		overlayColor: '#000',               // base color of overlay
		draggable: true,                    // make the dialogs draggable (requires UI Draggables plugin)
		dialogClass: null,                  // if specified, this class will be applied to all dialogs
		
		// Public methods
		show: function(message,  callback) {
			$.alerts._show(message, null, function() {
				if( callback ) callback();
			});
		},
		
		// Private methods
		
		_show: function( msg, value, callback) {
			
			$.alerts._hide();
			$.alerts._overlay('show');
			
			$("BODY").append(
			  '<div id="popup_container">' +
			      '<div class="block" style="border: 1px solid #c1c1c1;border-radius: 7px;">' +
					  '<div id="popup_message" style="min-width: 200px;"></div>' +
					  '<div style="width: 100%; text-align: right; margin-top: 15px;"><button id="popup_ok">Подтвердить</button> <button id="popup_cancel">Отмена</button></div>' +
				  '</div>'+
			  '</div>');
			
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
			
			$("#popup_container").css({
				position: pos,
				zIndex: 99999,
				padding: 0,
				margin: 0
			});
			
			$("#popup_message").text(msg);
			$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
			
			$("#popup_container").css({
				minWidth: $("#popup_container").outerWidth(),
				maxWidth: $("#popup_container").outerWidth()
			});
			
			$.alerts._reposition();
			$.alerts._maintainPosition(true);

			$("#popup_ok").click( function() {
				$.alerts._hide();
				if( callback ) callback();
			});
			$("#popup_cancel").click( function() {
				$.alerts._hide();
			});
			$("#popup_ok").focus();
			$("#popup_ok, #popup_cancel").keypress( function(e) {
				if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
				if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
			});
		},
		
		_hide: function() {
			$("#popup_container").fadeOut('fast', function ()  {
					$.alerts._overlay('hide');
					$("#popup_container").remove();
			});
			$.alerts._maintainPosition(false);
		},
		
		_overlay: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						display: 'none',
						zIndex: 99998,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
					$("#popup_overlay").fadeIn('normal', function ()  {
						$("#popup_container").fadeIn('fast');
					});
				break;
				case 'hide':
					$("#popup_overlay").fadeOut('normal', function ()  {
						$("#popup_overlay").remove();
					});
				break;
			}
		},
		
		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},
		
		_maintainPosition: function(status) {
			if( $.alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', function() {
							$.alerts._reposition();
						});
					break;
					case false:
						$(window).unbind('resize');
					break;
				}
			}
		}
		
	}
	
	// Shortuct functions
	
	jConfirm = function(message, callback) {
		$.alerts.show(message, callback);
	};

})(jQuery);
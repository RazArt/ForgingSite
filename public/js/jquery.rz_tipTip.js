(function( $ ){

	var className = 'jquery-rz-popup';
	var visible = false;
	
		show_popup = function( options )
		{
			var settings = {
				title: 'Ошибка',
				text: 'Привет, я всплывающее окно',
				ok_button: null;
			};
			
			settings = jQuery.extend( settings, options || {} );
			
			if( $('#' + className).length <= 0 ) {
				var popup = jQuery( '<div class="' + className + '"></div>' );
				var popup_background = jQuery( '<div class="' + className + '-background"></div>' );
				if ( settings.title !== null ) {
					var popup_title = jQuery( '<div class="' + className + '-title"></div>' );
				}
				var popup_content = jQuery( '<div class="' + className + '-content"></div>' );
				
				var popup_buttons_block = jQuery( '<div class="' + className + '-buttons_block"></div>' );
				var popup_button_ok = jQuery( '<input type="submit" value="Ок"></div>' );
				
				
				$("body").append(popup);
				$("body").append(popup_background);
			} else {
				var popup = $('.' + className);
				var popup_background = $('.' + className+'-background');
				var popup = $('.' + className + ' content');
				if ( settings.title !== null ) {
					var popup_title = jQuery( '<div class="' + className + '-title"></div>' );
				}
			}
			
			if ( settings.title !== null ) {
				popup.append(popup_title);
				popup_title.html(settings.title);
			}
			
			popup.append(popup_content);
			popup.append(popup_buttons_block);
			
			popup_content.html(settings.text);
			popup_buttons_block.append(popup_button_ok);
			
			$('body').css('overflow', 'hidden');
			
			popup_background.show();
			
			popup.css({
				left: ($(window).width() - popup.width()) / 2,
				top: ($(window).height() - popup.height()) / 2
				});
				
			popup.show();
			
			popup_background.animate({
				opacity: 1
			}, 300);
			
			popup.animate({
				opacity: 1
			}, 300);
			
			popup_background.bind('click', hide_popup);
		};
		
		hide_popup = function()
		{
			var popup = $('.' + className);
			var popup_background = $('.' + className+'-background');
			
			popup.css('display', 'none');
			popup_background.css('display', 'none');
			
			$('body').css('overflow', 'auto');
		};
})( jQuery );
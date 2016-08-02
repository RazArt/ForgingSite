(function($) {

	$.fn.spasticNav = function(options) {
	
		options = $.extend({
			overlap : 15,
			speed : 500,
			reset : 200,
			easing : 'easeOutExpo'
		}, options);
	
		return this.each(function() {
		
		 	var nav = $(this),
		 		currentPageItem = $('#selected', nav),
		 		blob,
		 		reset;
		 		
		 	$('<li id="navigation_blob"></li>').css({
		 		width : currentPageItem.outerWidth(),
		 		height : currentPageItem.outerHeight() + options.overlap,
		 		left : currentPageItem.position().left,
		 		top : currentPageItem.position().top - options.overlap / 2
		 	}).appendTo(this);
		 	
		 	blob = $('#navigation_blob', nav);
					 	
			$('li:not(#navigation_blob)', nav).hover(function() {
				clearTimeout(reset);
				blob.animate(
					{
						left : $(this).position().left,
						width : $(this).width()
					},
					{
						duration : options.speed,
						easing : options.easing,
						queue : false
					}
				);
			}, function() {
				reset = setTimeout(function() {
					blob.animate({
						width : currentPageItem.outerWidth(),
						left : currentPageItem.position().left
					}, options.speed)
					
				}, options.reset);
			});
		 
		
		}); // end each
	
	};

})(jQuery);
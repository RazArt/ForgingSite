jQuery.fn.selectbox = function(options){
	var settings = {
		className: 'select',
		loadText: 'Selectbox',
		EmptyBoxText: 'Selectbox Empty',
		useLoadText: false,
		TooltipePos: 'right',
		animationSpeed: "normal",
		listboxMaxSize: 100,
		replaceInvisible: false,
		onChange: null
	};
	
	settings = jQuery.extend(settings, options || {});
	
	var listOpen = false;
	
	var commonClass = 'jquery-custom-selectboxes-replaced';
	
	var showList = function(listObj) {
		var selectbox = listObj.parents('.' + settings.className + '').find('.' + settings.className + '-header');
		selectbox.addClass(settings.className + '-header-active');
		listOpen = true;
		listObj.slideDown(settings.animationSpeed);
		jQuery(document).bind('click', onBlurList);
		return listObj;
		
	}
	var hideList = function(listObj) {
		var selectbox = listObj.parents('.' + settings.className + '').find('.' + settings.className + '-header');
		listOpen = false;
		listObj.slideUp(settings.animationSpeed, function ()  {
			selectbox.removeClass(settings.className + '-header-active');
		});
		jQuery(document).unbind('click', onBlurList);
		return listObj;
	}
	
	var onBlurList = function(e) {
		var trgt = e.target;
		var currentListElements = jQuery('.' + settings.className + '-list:visible').parent().find('*').andSelf();
		if(jQuery.inArray(trgt, currentListElements)<0 && listOpen) {
			hideList( jQuery('.' + commonClass + '-list') );
		}
		return false;
	}
	
	this.each(function() {
		var _this = jQuery(this);
		
		var replacement = jQuery(
			'<div class="' + settings.className + ' ' + commonClass + '">' +
				'<div class="' + settings.className + '-header" />' +
				'<div class="' + settings.className + '-list ' + commonClass + '-list" />' +
				'<input type="hidden" name="" />' +
			'</div>'
		);
		
		if (jQuery('option', this).length > 0){
			jQuery('option', this).each(function(i, val){
				var listElement =  jQuery('<div class="' + settings.className + '-listelement value-'+$(val).val()+' item-'+i+'" value="' + $(val).val() + '">' + $(val).html() + '</div>');
				if($(val).filter(":disabled").length > 0) {
					listElement.addClass(settings.className + '-listelement-disabled');
				}
				else
				{
					listElement.click(function(){
						var thisListElement = jQuery(this);
						var thisReplacment = thisListElement.parents('.'+settings.className);
						thisReplacment
							.find('.' + settings.className + '-header')
							.text(thisListElement.text());
						thisReplacment
							.find('input').val(thisListElement.attr("value"));
						hideList(thisListElement.parent());
						var thisSublist = thisReplacment.find('.' + settings.className + '-list');
						if (settings.onChange)
						{
							settings.onChange.call('', [thisListElement.attr("value")]);
						}
						if(thisSublist.filter(":visible").length > 0) {
							hideList(thisSublist);
						}else{
							showList(thisSublist);
						}
					}).bind('mouseenter',function(){
						jQuery(this).addClass(settings.className + '-listelement-active');
					}).bind('mouseleave',function(){
						jQuery(this).removeClass(settings.className + '-listelement-active');
					});
				}
				jQuery('.' + settings.className + '-list', replacement).append(listElement);
				
				if (settings.useLoadText == true){
					jQuery('.'+settings.className + '-header', replacement).text(settings.loadText);
				}
				else
				{
					if($(val).filter(':selected').length > 0) {
						jQuery('.'+settings.className + '-header', replacement).text($(val).text());
						jQuery('input', replacement).val($(val).attr("value"));
					}
				}
			});
			
			replacement.find('.' + settings.className + '-header').click(function(){
				var thisHeader = jQuery(this);
				var thisList = jQuery(this).siblings('.' + settings.className + '-list');
				var otherLists = jQuery('.' + settings.className + '-list')
					.not(thisHeader.siblings('.' + settings.className + '-list'));
				hideList(otherLists);
				if(thisList.filter(":visible").length > 0) {
					hideList(thisList);
				}else{
					showList(thisList);
				}
			}).bind('mouseenter',function(){
				jQuery(this).addClass(settings.className + '-header-active');
			}).bind('mouseleave',function(){
				if(listOpen == false) {
					jQuery(this).removeClass(settings.className + '-header-active');
				}
			});
		}
		else
		{
			jQuery('.'+settings.className + '-header', replacement).text(settings.EmptyBoxText);
		}
		
		replacement.css('width', _this.width());
		
		/*if(_this.attr('title').length > 0) {
			replacement.attr('title', _this.attr('title'));
			replacement.tipTip({maxWidth: "auto", edgeOffset: 10, defaultPosition: settings.TooltipePos});
		}*/
		
		replacement.find('.' + settings.className + '-list').css('width', _this.width() - 2);
		
		replacement.find('input').attr('name', _this.attr('id'));
		
		_this.hide().replaceWith(replacement);
	});
}
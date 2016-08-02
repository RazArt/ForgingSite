function confirm(text, link) {
	jConfirm(text, function() {
		location.replace(link);
	});
}

function setLocation(curLoc)
{
	if (curLoc != undefined) 
	{
		history.pushState(null, null, curLoc);
		return true;
	}
}

function AJAXLoad(route, postData)  
{
	if (route != undefined) 
	{
		try
		{
			var answer = jQuery.parseJSON($.ajax({
				url: route,
				cache: false,
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded',
				dataType: "json",
				data: postData,
				async:false,
				error: function () {
						return false;
					}
				}).responseText);
            
			if (answer['type'] == 'html') {
				return answer['data'];
			} else if (answer['type'] == 'error'){
				show_msg(answer['data']);
			} else if (answer['type'] == 'msg'){
				show_msg(answer['data']);
			} else if (answer['type'] == 'data'){
				return answer['data'];
			}
		} catch(e) {}
	}
	
	return false;
}

function loadController(route, postdata)  
{
	answer = AJAXLoad(route);
    
	if (answer !== false)
	{
        if (route !== false)
		{
			setLocation(route);
		} else {
            return;
        }
        
		$('#controllerBlock').html(answer);
        
		/*if ($('div#controller_title').html()) {
			$('div#main_controller_block_title').html($('div#controller_title').html());
		} else {
			$('div#main_controller_block_title').html(404);
		}*/
		
		return true;
	}
	
	return false;
}

function loginCheck()  
{
	answer = AJAXLoad(webPath + 'account/check/', $('#login_form').serialize());

	if (answer['check'] == true)
	{
		document.location.href = webPath;
		
		return true;
	} else {
        loadController(webPath + 'account');
    }
}

function logout()  
{
	answer = AJAXLoad(webPath + 'login/logout/');

	if (answer['logout'] == true)
	{
		document.location.href = webPath;
		
		return true;
	}
}

function show_error(error)  
{
	/*if($("#tiptip_holder").length <= 0){
		var tiptip_holder = $('<div id="tiptip_holder" style="max-width:900;"></div>');
		var tiptip_content = $('<div id="tiptip_content"></div>');
		var tiptip_arrow = $('<div id="tiptip_arrow"></div>');
		$("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow));
	} else {
		var tiptip_holder = $("#tiptip_holder");
		var tiptip_content = $("#tiptip_content");
		var tiptip_arrow = $("#tiptip_arrow");
	}*/
	/*$( "#dialog-message" ).dialog({
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});*/
	alert(error);
	
	return;
}

function show_msg(msg)  
{
	alert(msg);
	
	return;
}

function implode(glue, pieces)
{
	return ((pieces instanceof Array) ? pieces.join (glue) : pieces );
}
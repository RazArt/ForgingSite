function setLocation(curLoc)
{
	if (curLoc != undefined) 
	{
		history.pushState(null, null, curLoc);
		return true;
	}
}

function loadPage(route, user_post_data)  
{
	if (route != undefined) 
	{
		post_data = 'ajax_mode=1';
		
		if (user_post_data != null) 
		{
			post_data += '&'+user_post_data;
		}
		
		try{
			json_answer = jQuery.parseJSON($.ajax({
				url: route,
				cache: false,
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded',
				data: post_data,
				dataType: "json",
				async:false,
			}).responseText);
		
			if (json_answer != undefined) 
			{
				return json_answer;
			}
		} catch(e) {}
		
		return false;
	}
	
	return false;
}

function loadController(route, user_post_data)  
{
	showURL = site_path+route;
	
	route = site_path+route;
	
	post_data = 'tpl=1';
	
	if (user_post_data != null) 
	{
		post_data += '&'+user_post_data;
	}
		
	$html = loadPage(route, post_data);
	
	if ($html != false)
	{
		if (typeof($html['server_vars']) === 'undefined')
		{
			$html['server_vars'] = '';
		}
		
		if (typeof($html['tpl_body']) !== 'undefined')
		{
			if ($html['server_vars']['err_code'] != '1') 
			{
				$('#main_site_block').html('');
				
				$.template('controller_tmplt', $html.tpl_body);
				
				delete $html.tpl_body;
				
				$.tmpl('controller_tmplt', $html).appendTo('#main_site_block');
				
				setLocation(showURL);
				
				return true;
			}
		}
	}
	
	$('#main_site_block').html(errors_str[0]);
	
	return false;
}

function implode(glue, pieces)
{
	return ((pieces instanceof Array) ? pieces.join (glue) : pieces );
}
var orig_width = image_width;
var orig_height = image_height;
var zoom = 'off';
var auto_zoom = 'on'
var zoom_percentage = 100;
		
$(document).keydown(function(e){
    if (e.keyCode == 37 && enable_hotkeys == 1) 
    { 
       if (prev_image != '') { document.location = prev_image; }
       return false;
    }
    if (e.keyCode == 38 && enable_up_hotkey == 1) 
    { 
       document.location = up_folder;
       return false;
    }
    if (e.keyCode == 39 && enable_hotkeys == 1) 
    { 
       if (next_image != '') { document.location = next_image; }
       return false;
    }
});

function go_next_image()
{
    if (current_file != (files.length-1))
    {
    	current_file++;
        
        var image_div = document.getElementById('image');
    
        temp_image = new Image();
        temp_image.onload = function() 
        {
            image_width = files[current_file][2];
        	image_height = files[current_file][3];
            resize();
            
            image_div.src = files[current_file][0];
            
            show_title();
        }
        temp_image.src = files[current_file][0];
    }
}

function go_prev_image()
{
    if (current_file != 0)
    {
    	current_file--;
        
        var image_div = document.getElementById('image');
    
        temp_image = new Image();
        temp_image.onload = function() 
        {
            image_width = files[current_file][2];
        	image_height = files[current_file][3];
            resize();
            
            image_div.src = files[current_file][0];
            
            show_title();
        }
        temp_image.src = files[current_file][0];
    }
}

function show_title()
{
	var str = page_title_format;
    
    page_title = files[current_file][1];
    
    str = str.replace('[S]', site_name);
    str = str.replace('[P]', page_title);
    
    document.title = str;
    document.getElementById('page-title').innerHTML = str;
}

function toggle_resize()
{
	if (auto_zoom == 'off')
	{
		auto_zoom = 'on';
		resize();
        resize();
	} else {
    	document.getElementById('image').style.width = image_width + 'px';
		auto_zoom = 'off';
		resize();
        resize();
	}
}

function getX(element)
{
	var output = 0;
	
	element = document.getElementById(element)
	while(element != null)
	{
		output += element.offsetLeft;
		element = element.offsetParent;
	}
	
	return output;
}

function getY(element)
{
	var output = 0;
	
	element = document.getElementById(element)
	while(element != null)
	{
		output += element.offsetTop;
		element = element.offsetParent;
	}
	
	return output;
}

function resize()
{			
	if (auto_zoom == 'on')
	{
		curr_width = document.getElementById('image').width;
		
		if (document.documentElement.clientWidth < image_width)
		{
			curr_width = document.documentElement.clientWidth;
		} else {
			curr_width = image_width;
		}
	
		document.getElementById('image').style.width = curr_width + 'px';
	}
	
	imageX = getX('image');
	imageWidth = document.getElementById('image').width;
	imageHeight = document.getElementById('image').height;
}

window.onload = function() { resize(); resize(); }
window.onresize = function() { resize(); resize(); }
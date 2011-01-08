<?php
/*
PHP Picture Index 0.9.4
------------------------
Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://code.google.com/p/phppi/
Licence: GNU General Public License v3                   		 
http://www.gnu.org/licenses/
*/

$version = '0.9.4';

/* Settings */

$settings['gzip_compression'] = true; //Enable gzip compression of html where possible.

$settings['enable_settings.php'] = true; //Allows for folder specific settings. Create a settings.php file in the folder and add your settings for that folder. Template supplied in 'extras' folder.
$settings['use_settings.php_as_global'] = false; //Use if you'd like the settings.php file in the same folder as this index.php to act as the global settings.

//Use GD v2 (if installed), otherwise uses thumbnails from thumbs folder inside each
//directory (if available), uses the same filename as the full size image.
$settings['thumbnail_gd'] = true;
$settings['thumbnail_gd_cache'] = true;
$settings['thumbnail_gd_expire'] = 60*60*24*2; //Seconds till expire (seconds, minutes, hours, days) (2 days)

//Note make sure if you're not using GD that you set thumbnail_size to match your thumbnails (e.g. if set to 150, either width or height of each thumbnail must be 150px)
$settings['thumbnail_size'] = 150; //Size in pixels, thumbnails will not go past this size either horizontally or vertically
$settings['folder_size'] = 150; //Size in pixels, thumbnails will not go past this size either horizontally or vertically

$settings['thumbnail_file_type'] = 'png'; //Filetype of thumbnails to use if GD is turned off

$settings['thumbnail_text_size'] = 14; //Font size in pixels
$settings['thumbnail_text_font'] = 'Arial, Helvetica, sans-serif'; //Font for image and folder name text, write in CSS "font-family" format

$settings['thumbnail_filename'] = true;
$settings['thumbnail_directoryname'] = true;

$settings['folder_thumbnail_file'] = '';
$settings['folder_thumbnail_type'] = 'none';
//Accepts the following:
//none - no thumbnail.
//lazycache - random thumbnail from cache folder or thumbs folder if GDv2 is turned off. Only shows existing cache/thumbs files, updated or not. Can take longer to load the page.
//dyncache - random thumbnail based on images in folder (requires GDv2 enabled, will revert to thumbs folder if GDv2 isn't enabled). Updates cache if needed. Can take much longer to load the page depending on how many thumbnails need creating.
//static - uses the image inside the folder with the filename set using the $settings['folder_thumbnail_file'] variable. Remember to add the image to the blacklist below otherwise it will appear when browsing.
//globalstatic - uses the image specified by $settings['folder_thumbnail_file'], image must be in the same folder as this index.php file. Remember to add the image to the blacklist below otherwise it will appear when browsing.

$settings['slimbox_support'] = false; //Requires jquery.js (production) and slimbox2.js (plus it's css folder contents) in the folder specified by $settings['slimbox_folder'].
$settings['slimbox_folder'] = 'slimbox/'; //Include / at the end if not blank. Remember to add the folder to the blacklist or files to the blacklist if the root folder is used.

$settings['visual_background_color'] = '#000000';
$settings['visual_text_color'] = '#ffffff';
$settings['visual_zoom_bar_color'] = '#222222'; //Zoom bar background color
$settings['visual_bar_size'] = '2'; //Size in pixels, use 0 for none
$settings['visual_bar_color'] = '#404040';
$settings['visual_thumb_border'] = true; //Use a border, true or false
$settings['visual_thumb_border_color'] = '#404040';
$settings['visual_folder_color'] = '#222222';
$settings['visual_folder_border'] = true; //Use a border, true or false
$settings['visual_folder_border_color'] = '#404040';

$settings['page_override_header'] = ''; //Location of custom header (leave blank to use default), e.g. header.htm or header.php
$settings['page_override_footer'] = ''; //Location of custom footer (leave blank to use default), e.g. footer.htm or footer.php

$settings['file_types'] = array('jpg', 'jpeg', 'png'); //Only these filetypes will be shown
$settings['file_blacklist'] = array('index.php', 'settings.php'); //Do not show these files
$settings['dir_blacklist'] = array('.', '..', 'cache', 'thumbs', 'slimbox'); //Do not show these folders

/* ONLY CHANGE PHP CODE BELOW IF NEEDED */
/* HTML and CSS at bottom of page, change if needed */

/* Start timer */

$temp_time = microtime();
$temp_time = explode(" ", $temp_time);
$temp_time = $temp_time[1] + $temp_time[0];
$start_time = $temp_time;

/* Settings.php */

if ($settings['use_settings.php_as_global'] == true)
{
	if (file_exists('settings.php'))
	{
		include('settings.php');
	}
}

if ($settings['enable_settings.php'] == true)
{
	setvariables();
	
	if ($var['dir_req'] == '')
	{
		$location = 'settings.php';
	} else {
		if ($_GET['thumb'])
		{
			$location = substr(get_path_info($var['dir_req'], 'dir_path'), 6) . '/settings.php';
		} else {
			$location = $var['dir_req'] . '/settings.php';
		}
	}
	
	if (file_exists($location))
	{
		include($location);
	}
}

/* Gzip Compression */

if ($settings['gzip_compression'] == true)
{
	ini_set('zlib.output_compression', 'On');
	ini_set('zlib.output_compression', 6);
}

/* Disable GD setting if it isn't installed */

if (!extension_loaded('gd')) 
{
	$settings['thumbnail_gd'] = false;
}

/* Functions */

$var = array();

function setvariables()
{
	global $var;
	
	$var['dir_local'] = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); //			/var/www/pictures
	$var['dir_req'] = clean_path($_SERVER['QUERY_STRING']); //						photo/landscape
	$var['dir_req_parent'] = dirname($var['dir_req']); //							photo
	$var['dir_parent'] = get_path_info($_SERVER['SCRIPT_NAME'], 'dir_path'); //		/pictures
	$var['dir_cache'] = $var['dir_req'] . '/cache';//								photo/landscape/cache
	
	if ($var['dir_req_parent'] == '.')
	{
		$var['dir_req_parent'] = '';
	}
}

function clean_path($path)
{
	//Replace %20 in url to a space
	$path = str_replace('%20', ' ', $path);
	
	if (substr($path, 0, 1) == '/')
	{
		$path = substr($path, 1);
	}
	
	if (substr($path, -1, 1) == '/')
	{
		$path = substr($path, 0, strlen($path) - 1);
	}
	
	return $path;
}

function get_path_info($path, $info)
{
	$temp = pathinfo($path);
	
	if ($info == 'dir_path')
	{
		return $temp['dirname'];
	} else if ($info == 'full_file_name') {
		return $temp['basename'];
	} else if ($info == 'file_ext') {
		return $temp['extension'];
	} else if ($info == 'file_name') {
		return $temp['filename'];
	}
}

function get_margin_size($file, $mode = 'dynamic')
{
	//Returns margin/padding size in pixels, used to make sure thumbnails are vertically centered
	global $var;
	global $settings;
	
	$image_size = '';
	
	if (file_exists($file) and $mode == 'file')
	{
		//Use file mode if obtaining image size from thumbnail itself
		list($width, $height) = getimagesize($file);
	} else if ($mode == 'dynamic') {
		//Use dynamic mode if obtaining image size by working out the thumbnail size from the full image
		list($image_size[0], $image_size[1]) = getimagesize($file);
		
		$size = get_resized_size($image_size[0], $image_size[1]);
		$height = $size[1];
	}
	
	if ($height < $settings['thumbnail_size'])
	{
		$margin = floor(($settings['thumbnail_size'] - $height) / 2);
	} else {
		$margin = 0;
	}
	
	return $margin;
}

function get_resized_size($width, $height, $return = 2)
{
	//Returns width, height or an array of width and height for the thumbnail size of a full sized image
	global $settings;
	
	if ($width > $height)
	{
		$new_width = $settings['thumbnail_size'];
		$new_height = $height * ($settings['thumbnail_size'] / $width);
	} else if ($width < $height) {
		$new_width = $width * ($settings['thumbnail_size'] / $height);
		$new_height = $settings['thumbnail_size'];
	} else if ($width == $height) {
		$new_width = $settings['thumbnail_size'];
		$new_height = $settings['thumbnail_size'];
	}
	
	if ($return == 0)
	{
		//Return width
		return floor($new_width);
	} else if ($return == 1) {
		//Return height
		return floor($new_height);
	} else if ($return == 2) {
		//Return array with width and height
		return array(floor($new_width), floor($new_height));
	}
}

function get_random_image($directory)
{
	global $settings;
	
	$local_path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
	
	if (is_dir($local_path . '/' . $directory))
	{
		if ($dh = opendir($local_path . '/' . $directory))
		{
			while (($file = readdir($dh)) !== false) 
			{
				if (filetype($local_path . '/' . $directory . '/' . $file) == 'file' and in_array($file, $settings['file_blacklist']) == false and in_array(get_path_info($file, 'file_ext'), $settings['file_types']) == true) {
					//Is file
					$found_files[] = array(
						$local_path . '/' . $directory . '/' . $file,
						$file
					);
					
					shuffle($found_files);
				}
			}
			closedir($dh);
		}
	}
	
	if ($found_files[0][1] != '')
	{
		return ($found_files[0][1]);
	} else {
		return false;
	}
}

function create_thumbnail($filename)
{
	//Creates thumbnail, either dynamically or for cache depending on settings
	global $settings;
	
	$filename = realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/' . $filename;
	$cache_folder = get_path_info($filename, 'dir_path') . '/cache/';
	
	$create_cache_file = false;
	
	if ($settings['thumbnail_gd_cache'] == true)
	{
		if (!file_exists($cache_folder))
		{
			//Create cache folder if possible
			mkdir($cache_folder, 0755);
			chmod($cache_folder, 0755);
			if (!file_exists($cache_folder))
			{
				$create_cache_file = false;
			} else {
				$create_cache_file = true;
			}
		} else {
			$create_cache_file = true;
		}
	}
	
	if (get_path_info($filename, 'file_ext') == 'jpg' or get_path_info($filename, 'file_ext') == 'jpeg')
	{
		$image = imagecreatefromjpeg($filename);
	} else if (get_path_info($filename, 'file_ext') == 'png') {
		$image = imagecreatefrompng($filename);
	}
	
	$width = imagesx($image);
	$height = imagesy($image);
	
	$new_size = get_resized_size($width, $height);
	
	$new_image = ImageCreateTrueColor($new_size[0], $new_size[1]);
	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_size[0], $new_size[1], $width, $height);

	if ($create_cache_file == false)
	{
		header('Content-type: image/png');
		header('Pragma: public');
		header('Cache-Control: maxage=' . $settings['thumbnail_gd_expire']);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $settings['thumbnail_gd_expire']) . ' GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		
		imagepng($new_image);
	} else if ($create_cache_file == true) {
		imagepng($new_image, $cache_folder . get_path_info($filename, 'file_name') . '.png');
		
		header('Content-type: image/png');
		header('Pragma: public');
		header('Cache-Control: maxage=' . $settings['thumbnail_gd_expire']);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $settings['thumbnail_gd_expire']) . ' GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		
		readfile($cache_folder . get_path_info($filename, 'file_name') . '.png');
	}		
	
	imagedestroy($new_image);
}

/* Initialise */

if (isset($_GET['thumb']))
{
	//If requesting thumbnail
	create_thumbnail($_GET['thumb']);
} elseif (isset($_GET['file'])) {
	//If requesting full view	
	$local_path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
	$req_path = get_path_info($_GET['file'], 'dir_path');
	
	if (is_dir($local_path . '/' . $req_path . '/'))
	{
		if ($dh = opendir($local_path . '/' . $req_path . '/'))
		{
			while (($file = readdir($dh)) !== false) 
			{
				if (filetype($local_path . '/' . $req_path . '/' . $file) == 'file' and in_array($file, $settings['file_blacklist']) == false and in_array(get_path_info($file, 'file_ext'), $settings['file_types']) == true) {
					//Is file
					$found_files[] = array(
						$local_path . '/' . $req_path . '/' . $file,
						$file
					);
					
					sort($found_files);
				}
			}
			closedir($dh);
		}
	}
	
	//Determines previous and next image if they exist
	for($i = 0; $i < count($found_files); $i++)
	{
		if ($found_files[$i][1] == get_path_info($_GET['file'], 'full_file_name'))
		{
			if ($i > 0)
			{
				$previous_id = $i - 1;
			}
			if ($i < (count($found_files) - 1))
			{
				$next_id = $i + 1;
			}
			
			break;
		}
	}
	
	//Output for full view
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <!-- 
    PHP Picture Index <?php print $version . "\n"; ?>
    ------------------------
    Created by: Brendan Ryan (http://www.pixelizm.com/)
    Site: http://code.google.com/p/phppi/
    Licence: GNU General Public License v3                   		 
    http://www.gnu.org/licenses/                
    -->

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable = no; maximum-scale=1.0;" />
    <title><?php echo get_path_info($_GET['file'], 'full_file_name'); ?></title>
    <script type="text/javascript">
		<?php list($orig_size[0], $orig_size[1]) = getimagesize($_GET['file']); ?>
		var orig_width = <?php echo $orig_size[0]; ?>;
		var orig_height = <?php echo $orig_size[1]; ?>;
		
		var zoom = 'off';
		var auto_zoom = 'on';
		var zoom_percentage = 100;
		
		function show_zoom()
		{
			if (zoom == 'off')
			{
				document.getElementById('zoom').style.display = 'block';
				zoom = 'on';
			} else {
				document.getElementById('zoom').style.display = 'none';
				zoom = 'off';
			}
		}
		
		function zoom_out()
		{
			if (auto_zoom == 'on')
			{
				zoom_auto();
			}
			
			if (zoom_percentage > 25)
			{
				zoom_percentage = zoom_percentage - 25;
			} else {
				zoom_percentage = 25;
			}
			
			document.getElementById('image').style.width = ((orig_width / 100) * zoom_percentage) + 'px';
			
			resize();
		}
		
		function zoom_in()
		{
			if (auto_zoom == 'on')
			{
				zoom_auto();
			}
			
			zoom_percentage = zoom_percentage + 25;
				
			document.getElementById('image').style.width = ((orig_width / 100) * zoom_percentage) + 'px';
			
			resize();
		}
		
		function zoom_auto()
		{
			if (auto_zoom == 'off')
			{
				document.getElementById('zoom_auto').innerHTML = 'A:ON';
				auto_zoom = 'on';
				resize();
			} else {
				document.getElementById('zoom_auto').innerHTML = 'A:OFF';
				document.getElementById('image').style.width = orig_width + 'px';
				auto_zoom = 'off';
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
				
				if (document.documentElement.clientWidth < orig_width)
				{
					curr_width = document.documentElement.clientWidth;
				} else {
					curr_width = orig_width;
				}
			
				document.getElementById('image').style.width = curr_width + 'px';
			}
			
			imageX = getX('image');
			imageWidth = document.getElementById('image').width;
			imageHeight = document.getElementById('image').height;
			<?php if (isset($previous_id)) { ?>
			document.getElementById('previous').style.display = 'block';
			document.getElementById('previous').style.left = imageX + 'px';
			document.getElementById('previous').style.top = imageHeight + 'px';
			<?php } ?>
			<?php if (isset($next_id)) { ?>
			document.getElementById('next').style.display = 'block';
			document.getElementById('next').style.left = ((imageX + imageWidth) - 80) + 'px';
			document.getElementById('next').style.top = imageHeight + 'px';
			<?php } ?>
			document.getElementById('home').style.display = 'block';
			document.getElementById('home').style.left = ((imageX + (imageWidth / 2)) - 40) + 'px';
			document.getElementById('home').style.top = imageHeight + 'px';
		}
	</script>
    <style type="text/css">
		body
		{
			margin: 0px;
			padding: 0px;
			-webkit-text-size-adjust: none;
			background-color: <?php echo $settings['visual_background_color']; ?>;
			text-align: center;
			padding-bottom: 73px;
		}
		
		img
		{
			border: 0px;
			padding: 0px;
			margin: 0px;
		}
		
		a
		{
			text-decoration: none;
			color: <?php echo $settings['visual_text_color']; ?>;
		}
		
		#zoom
		{
			position: absolute;
			background-color: <?php echo $settings['visual_zoom_bar_color']; ?>;
			display: none;
			top: 10px;
			left: 10px;
		}
		
		.button_zoom
		{
			float: right;
			padding: 15px 30px 15px 30px;
			color: <?php echo $settings['visual_text_color']; ?>;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 20px;
			display: block;
		}
		
		#home
		{
			display: none;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 36px;
			color: <?php echo $settings['visual_text_color']; ?>;
			padding: 15px;
			width: 50px;
			position: absolute;
		}
		
		#previous
		{
			display: none;
			color: <?php echo $settings['visual_text_color']; ?>;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 36px;
			text-align: center;
			padding: 15px;
			width: 50px;
			position: absolute;
		}
		
		#next
		{
			display: none;
			color: <?php echo $settings['visual_text_color']; ?>;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 36px;
			text-align: center;
			padding: 15px;
			width: 50px;
			position: absolute;
		}
	</style>
</head>
<body onload="resize();" onresize="resize();">
	<div id="zoom"><a id="zoom_auto" class="button_zoom" href="javascript: zoom_auto();">A:ON</a><a class="button_zoom" href="javascript: zoom_in();">+</a><a class="button_zoom" href="javascript: zoom_out();">-</a></div>
	<img id="image" onclick="show_zoom();" src="<?php echo $_GET['file']; ?>" />
    <a id="home" href="?<?php echo $req_path; ?>">&#8226;</a>
    <?php if (isset($previous_id)) { ?><a id="previous" href="?file=<?php echo $req_path . '/' . $found_files[$previous_id][1]; ?>">&lt;&lt;</a><?php } ?>
    <?php if (isset($next_id)) { ?><a id="next" href="?file=<?php echo $req_path . '/' . $found_files[$next_id][1]; ?>">&gt;&gt;</a><?php } ?>
</body>
</html>
	<?php
} else {
	//If displaying folder/file view
	setvariables();
		
	if (is_dir($var['dir_local'] . '/' . $var['dir_req']))
	{
		if ($dh = opendir($var['dir_local'] . '/' . $var['dir_req']))
		{
			while (($file = readdir($dh)) !== false) 
			{
				if (filetype($var['dir_local'] . '/' . $var['dir_req'] . '/' . $file) == 'dir' and in_array($file, $settings['dir_blacklist']) == false)
				{
					//Is directory
					$found_directories[] = array(
						$var['dir_local'] . '/' . $var['dir_req'] . '/' . $file,
						$file
					);
					
					sort($found_directories);
				} else if (filetype($var['dir_local'] . '/' . $var['dir_req'] . '/' . $file) == 'file' and in_array($file, $settings['file_blacklist']) == false and in_array(get_path_info($file, 'file_ext'), $settings['file_types']) == true) {
					//Is file
					$found_files[] = array(
						$var['dir_local'] . '/' . $var['dir_req'] . '/' . $file,
						$file
					);
					
					sort($found_files);
				}
			}
			closedir($dh);
		} else {
			$error = 'Cannot open specified directory';
		}
	} else {
		$error = 'No directory found';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<!-- 
	PHP Picture Index <?php print $version . "\n"; ?>
	------------------------
	Created by: Brendan Ryan (http://www.pixelizm.com/)
	Site: http://code.google.com/p/phppi/
	Licence: GNU General Public License v3                   		 
	http://www.gnu.org/licenses/                
	-->

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable = no; maximum-scale=1.0;" />
	<title><?php if (!$error) { echo 'Index of /' . $var['dir_req']; } else { echo 'Error'; } ?></title>
	<style type="text/css">
		body
		{
			margin: 0px;
			padding: 0px;
			-webkit-text-size-adjust: none;
			background-color: <?php echo $settings['visual_background_color']; ?>;
		}
		
		img
		{
			border: 0px;
			padding: 0px;
			margin: 0px;
		}
		
		a
		{
			color: <?php echo $settings['visual_text_color']; ?>;
		}
		
		hr
		{
			border: 0;
			color: <?php echo $settings['visual_bar_color']; ?>;
			background-color: <?php echo $settings['visual_bar_color']; ?>;
			height: <?php echo $settings['visual_bar_size']; ?>px;
			text-align: left;
			margin: 20px;
		}
		
		.error
		{
			border: 2px solid #C00000;
			background-color: #F99999;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			text-align: left;
			padding: 10px;
			margin: 20px;
		}
		
		#page-container
		{
			padding: 10px 0px 0px 10px;
		}
		
		.page-title
		{
			padding: 20px 20px 0px 20px;
			font-family: Arial, Helvetica, sans-serif;
			color: <?php echo $settings['visual_text_color']; ?>;
			font-size: 32px;
		}
		
		.page-parent
		{
			padding: 10px 20px 0px 20px;
			font-family: Arial, Helvetica, sans-serif;
			color: <?php echo $settings['visual_text_color']; ?>;
			font-size: 14px;
		}
		
		.page-parent a
		{
			color: <?php echo $settings['visual_text_color']; ?>;
			text-decoration: none;
		}
		
		.page-parent a:hover
		{
			color: <?php echo $settings['visual_text_color']; ?>;
			text-decoration: none;
		}
		
		.page-footer
		{
			padding: 0px 20px 20px 20px;
			font-family: Arial, Helvetica, sans-serif;
			color: <?php echo $settings['visual_text_color']; ?>;
			font-size: 10px;
		}
		
		.thumbnail-container
		{
			float: left; 
			margin: 0px 10px 10px 0px;
			padding: 10px;
		}
		
		.thumbnail-container .directory
		{
			background-color: <?php echo $settings['visual_folder_color']; ?>;
			width: <?php echo $settings['folder_size']; ?>px;
			height: <?php echo $settings['folder_size']; ?>px;
			<?php if ($settings['visual_folder_border'] == true) { echo 'border: 1px solid ' . $settings['visual_folder_border_color'] . ';'; } ?>
			display: block;
			background-position: center;
			background-repeat: no-repeat;
		}
		
		.thumbnail-container .file
		{
			width: <?php echo $settings['thumbnail_size']; ?>px;
			text-align: center;
		}
		
		.thumbnail-container .file img
		{
			<?php if ($settings['visual_thumb_border'] == true) { echo 'border: 1px solid ' . $settings['visual_thumb_border_color'] . ';'; } ?>
		}
		
		.thumbnail-container .title
		{
			width: <?php echo $settings['thumbnail_size']; ?>px;
			margin: 10px 0px 10px 0px;
			text-align: center;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
			
			font-family: <?php echo $settings['thumbnail_text_font']; ?>;
			font-size: <?php echo $settings['thumbnail_text_size']; ?>px;
			color: <?php echo $settings['visual_text_color']; ?>;
			
			text-decoration: none;
			
			display: block;
		}
	</style>
    <?php
	if ($settings['slimbox_support'] == true)
	{
	?>
    <script type="text/javascript" src="<?php echo $settings['slimbox_folder']; ?>jquery.js"></script>
	<script type="text/javascript" src="<?php echo $settings['slimbox_folder']; ?>slimbox2.js"></script>
    <link rel="stylesheet" href="<?php echo $settings['slimbox_folder']; ?>slimbox2.css" type="text/css" media="screen" />
	<?
	}
	?>
</head>
<body>
	<?php
	//Check if errors
	if ($error)
	{
		echo '<div class="error">' . $error . '</div>';
		echo "\n</body>";
		echo "\n</html>";
		exit();
	}
	
	//Check if custom header is used
	if ($settings['page_override_header'] == '')
	{
	?>
	<div class="page-title">Index of <?php echo '/' . $var['dir_req']; ?></div>
	<?php
	if ($var['dir_req'] != '')
	{
	?>
	<div class="page-parent"><a href="?<?php echo $var['dir_req_parent']; ?>">&laquo; Parent Directory</a></div>
	<?
	}
	?>
	<hr />
	<?php
	} else {
		if (!include($settings['page_override_header']))
		{
			echo '<div class="error">Unable to load header, check specified url</div>';
		}
	}
	//End check
	
	if (count($found_directories) == 0 and count($found_files) == 0)
	{
		echo '<div class="error">No directories or images found</div>';
	} else {
		if ($var['dir_req'] != '')
		{
			$request = $var['dir_req'] . '/';
		} else {
			$request = '';
		}
		
		$thumbnail_container_size = 0;
		
		if ($settings['thumbnail_directoryname'] == true) { $thumbnail_container_size = (20 + $settings['thumbnail_text_size']); }
		$thumbnail_container_size = $thumbnail_container_size + $settings['thumbnail_size'];
		
		echo '<div id="page-container">';
		echo "\n\t";
		
		if (is_array($found_directories) and count($found_directories) > 0)
		{
			foreach ($found_directories as $directory)
			{	
				echo '<div class="thumbnail-container" style="height: ' . $thumbnail_container_size . 'px;">';
				
				if ($settings['folder_thumbnail_type'] == 'none')
				{
					//no thumbnail
					echo '<a class="directory" href="?' . $request . $directory[1] .'" title="' . $directory[1] .'"></a>';
				} else if ($settings['folder_thumbnail_type'] == 'lazycache') {
					//lazycache thumbnail
					if ($settings['thumbnail_gd'] == true)
					{
						$file = get_random_image($request . $directory[1] . '/cache/');
						$img_url = $request . $directory[1] . '/cache/' . $file;
					} else {
						$file = get_random_image($request . $directory[1] . '/thumbs/');
						$img_url = $request . $directory[1] . '/thumbs/' . $file;
					}
					
					if ($file == '')
					{
						$background = '';
					} else {
						$background = 'style="background-image: url(\'' . $img_url . '\');" ';
					}
					
					echo '<a class="directory" ' . $background . 'href="?' . $request . $directory[1] .'" title="' . $directory[1] .'"></a>';
				} else if ($settings['folder_thumbnail_type'] == 'dyncache') {
					//dyncache thumbnail
					$file = get_random_image($request . $directory[1]);
					
					if ($settings['thumbnail_gd'] == true)
					{		
						if ($settings['thumbnail_gd_cache'] == true)
						{
							$use_cache = false;
							
							if (!file_exists($request . $directory[1] . '/cache/' . get_path_info($file, 'file_name') . '.png'))
							{
								//Cached image does not exist, create if possible
								$use_cache = false;
							} else {
								//Cached image exists, check if correct image size
								list($thumb_width, $thumb_height) = getimagesize($request . $directory[1] . '/cache/' . get_path_info($file, 'file_name') . '.png');
								list($img_width, $img_height) = getimagesize($request . $directory[1] . '/' . $file);
								
								$thumb_size = get_resized_size($img_width, $img_height);
								
								if ($thumb_size[0] != $thumb_width and $thumb_size[1] != $thumb_height)
								{
									//Cached image does not match the current thumbnail size settings, create new thumbnail
									$use_cache = false;
								} else {
									//Cached image does not need updating, use cached thumbnail
									$use_cache = true;
								}
							}
							
							if ($use_cache == true)
							{
								$img_url = $request . $directory[1] . '/cache/' . get_path_info($file, 'file_name') . '.png';
							} else {
								$img_url = '?thumb=' . $request . $directory[1] . '/' . $file;
							}
						} else {
							$img_url = '?thumb=' . $request . $directory[1] . '/' . $file;
						}
					} else {
						$img_url = $request . $directory[1] . '/thumbs/' . get_path_info($file, 'file_name') . '.' . $settings['thumbnail_file_type'];
					}
					
					if ($file == '')
					{
						$background = '';
					} else {
						$background = 'style="background-image: url(\'' . $img_url . '\');" ';
					}
					
					echo '<a class="directory" ' . $background . 'href="?' . $request . $directory[1] .'" title="' . $directory[1] .'"></a>';
				} else if ($settings['folder_thumbnail_type'] == 'static') {
					//static thumbnail
					if ($settings['folder_thumbnail_file'] != '')
					{
						if (file_exists($request . $directory[1] . '/' .$settings['folder_thumbnail_file']))
						{
							$background = 'style="background-image: url(\'' . $request . $directory[1] . '/' . $settings['folder_thumbnail_file'] . '\');" ';
						} else {
							$background = '';
						}
					} else {
						$background = '';
					}
					
					echo '<a class="directory" ' . $background . 'href="?' . $request . $directory[1] .'" title="' . $directory[1] .'"></a>';
				} else if ($settings['folder_thumbnail_type'] == 'globalstatic') {
					//globalstatic thumbnail
					if ($settings['folder_thumbnail_file'] != '')
					{
						if (file_exists($settings['folder_thumbnail_file']))
						{
							$background = 'style="background-image: url(\'' . $settings['folder_thumbnail_file'] . '\');" ';
						} else {
							$background = '';
						}
					} else {
						$background = '';
					}
					
					echo '<a class="directory" ' . $background . 'href="?' . $request . $directory[1] .'" title="' . $directory[1] .'"></a>';
				}
				
				if ($settings['thumbnail_directoryname'] == true)
				{
					echo '<a class="title" href="?' . $request . $directory[1] .'" title="' . $directory[1] .'"><b>' . $directory[1] . '</b></a>';
				}
				
				echo '</div>';
				echo "\n\t";
			}
		}
		
		$x = 0;
		
		if (is_array($found_files) and count($found_files) > 0)
		{
			foreach ($found_files as $file)
			{		
				$thumbnail_container_size = 0;
			
				if ($settings['thumbnail_filename'] == true) { $thumbnail_container_size = (20 + $settings['thumbnail_text_size']); }
				if ($settings['thumbnail_gd'] == false)
				{
					list($thumb_width, $thumb_height) = getimagesize($request . 'thumbs/' . get_path_info($file[1], 'file_name') . '.' . $settings['thumbnail_file_type']);
					$thumbnail_container_size = $thumbnail_container_size + $thumb_height;
				} else {
					$thumbnail_container_size = $thumbnail_container_size + $settings['thumbnail_size'];
				}
			
				echo '<div class="thumbnail-container" style="height: ' . $thumbnail_container_size . 'px;">';
				
				if ($settings['thumbnail_gd'] == true)
				{		
					if ($settings['thumbnail_gd_cache'] == true)
					{
						$use_cache = false;
						
						if (!file_exists($var['dir_cache'] . '/' . get_path_info($file[1], 'file_name') . '.png'))
						{
							//Cached image does not exist, create if possible
							$use_cache = false;
						} else {
							//Cached image exists, check if correct image size
							list($thumb_width, $thumb_height) = getimagesize($var['dir_cache'] . '/' . get_path_info($file[1], 'file_name') . '.png');
							list($img_width, $img_height) = getimagesize($request . $file[1]);
							
							$thumb_size = get_resized_size($img_width, $img_height);
							
							if ($thumb_size[0] != $thumb_width and $thumb_size[1] != $thumb_height)
							{
								//Cached image does not match the current thumbnail size settings, create new thumbnail
								$use_cache = false;
							} else {
								//Cached image does not need updating, use cached thumbnail
								$use_cache = true;
							}
						}
						
						if ($use_cache == true)
						{
							$img_url = $var['dir_cache'] . '/' . get_path_info($file[1], 'file_name') . '.png';
							$margin = get_margin_size($var['dir_cache'] . '/' . get_path_info($file[1], 'file_name') . '.png', 'file');
						} else {
							$img_url = '?thumb=' . $request . $file[1];
							$margin = get_margin_size($request . $file[1], 'dynamic');
						}
					} else {
						$img_url = '?thumb=' . $request . $file[1];
						$margin = get_margin_size($request . $file[1], 'dynamic');
					}
				} else {
					$img_url = $request . 'thumbs/' . get_path_info($file[1], 'file_name') . '.' . $settings['thumbnail_file_type'];
					$margin = get_margin_size($request . 'thumbs/' . get_path_info($file[1], 'file_name') . '.' . $settings['thumbnail_file_type'], 'file');
				}
				
				//Get thumbnail size
				if ($use_cache)
				{
					$img_size = getimagesize($var['dir_cache'] . '/' . get_path_info($file[1], 'file_name') . '.png');
				} else {
					$img_size = getimagesize($request . $file[1]);
					$img_size = get_resized_size($img_size[0], $img_size[1]);
				}
				
				if ($settings['slimbox_support'] == true)
				{
					$link = '<a href="' . $request . $file[1] . '" title="' . $file[1] .'" rel="lightbox-group">';
				} else {
					$link = '<a href="?file=' . $request . $file[1] . '" title="' . $file[1] .'">';
				}
				
				echo '<div class="file" style="padding: ' . $margin . 'px 0px ' . $margin . 'px 0px;">' . $link . '<img style="width: ' . $img_size[0] . 'px; height: ' . $img_size[1] . 'px;" src="' . $img_url .'" alt="" /></a></div>';
				
				if ($settings['thumbnail_filename'] == true)
				{
					echo '<a class="title" href="' . $request . $file[1] . '" title="' . $file[1] .'">' . $file[1] . '</a>';
				}
				
				echo '</div>';
				echo "\n\t";
				
				$x++;
			}
		}
		
		echo '<div style="clear: both;"></div>';
		echo "\n\t";
		echo '</div>';
		echo "\n";
	}
	
	/* End timer */
	
	$temp_time = microtime();
	$temp_time = explode(" ", $temp_time);
	$temp_time = $temp_time[1] + $temp_time[0];
	$end_time = $temp_time;
	$load_time = ($end_time - $start_time);
	
	//Check if custom footer is used
	if ($settings['page_override_footer'] == '')
	{
	?>
	<hr />
	<div class="page-footer">Generated by <a href="http://code.pixelizm.com/" target="_blank">PHP Picture Index <?php echo $version; ?></a> in <?php echo number_format($load_time, 7); ?> seconds.</div>
	<?php
	} else {
		if (!include($settings['page_override_footer']))
		{
			echo '<div class="error">Unable to load footer, check specified url</div>';
		}
	}
	//End check
	?>
</body>
</html>
<?php
}
?>
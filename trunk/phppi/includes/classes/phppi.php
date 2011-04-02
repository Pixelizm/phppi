<?php
class PHPPI
{
	var $settings;
	var $vars;
	
	function startTimer()
	{
		$temp_time = microtime();
		$temp_time = explode(" ", $temp_time);
		$temp_time = $temp_time[1] + $temp_time[0];
		$this->vars['start_time'] = $temp_time;
	}
	
	function endTimer()
	{
		$temp_time = microtime();
		$temp_time = explode(" ", $temp_time);
		$temp_time = $temp_time[1] + $temp_time[0];
		$this->vars['end_time'] = $temp_time;
		$this->vars['total_time'] = ($this->vars['end_time'] - $this->vars['start_time']);
	}
	
	function setThemeMode()
	{
		require('phppi/includes/classes/browser.php');
		$browser = new Browser();
		
		switch ($browser->getBrowser())
		{
			case Browser::BROWSER_ANDROID:
				$this->vars['theme_mode'] = 'mobile';
				break;
			case Browser::BROWSER_IPHONE:
				$this->vars['theme_mode'] = 'mobile';
				break;
			case Browser::BROWSER_IPOD:
				$this->vars['theme_mode'] = 'mobile';
				break;
			default:
				$this->vars['theme_mode'] = 'standard';
				break;
		}
	}
	
	function loadSettings($theme = false)
    {
    	//Set theme to true if you want to retrieve theme settings
		
		if ($theme == true)
		{
			if (!is_file('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/settings.php'))
			{
				return false;
			} else {
				require('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/settings.php');
				return true;
			}
		} else {
			if (!is_file('phppi/settings.php'))
			{
				return false;
			} else {
				require('phppi/settings.php');
				return true;
			}
		}
    }
	
	function loadVars()
	{
		$this->vars['dir_local'] = realpath(dirname($_SERVER['SCRIPT_FILENAME'])); //			/var/www/pictures
		$this->vars['dir_req'] = $this->cleanPath($_SERVER['QUERY_STRING']); //					photo/landscape
		$this->vars['dir_req_parent'] = dirname($this->vars['dir_req']); //						photo
		$this->vars['dir_parent'] = $this->pathInfo($_SERVER['SCRIPT_NAME'], 'dir_path'); //	/pictures
		$this->vars['dir_cache'] = $this->vars['dir_req'] . '/cache';//							photo/landscape/cache
		
		if ($this->vars['dir_req_parent'] == '.')
		{
			$this->vars['dir_req_parent'] = '';
		}
	}
	
	function loadLists()
	{
		$temp_file = file_get_contents('phppi/file_blacklist.txt');
		$this->vars['file_blacklist'] = explode("\n", $temp_file);
		
		$temp_folder = file_get_contents('phppi/folder_blacklist.txt');
		$this->vars['folder_blacklist'] = explode("\n", $temp_folder);
		
		$temp_type = file_get_contents('phppi/file_types.txt');
		$this->vars['file_types'] = explode("\n", $temp_type);
	}
	
	function checkList($item, $list)
	{		
		foreach($list as $list_item)
		{
			if ($list_item == $item)
			{
				return true;
			}
		}
		
		return false;
	}
	
	function cleanPath($path)
	{
		$path = str_replace('%20', ' ', $path);
		$path = str_replace('.', '', $path);
	
		if (substr($path, 0, 1) == '/')
		{
			$path = substr($path, 1);
		}
		
		if (substr($path, -1, 1) == '/')
		{
			$path = substr($path, 0, -1);
		}
		
		return $path;
	}
	
	function pathInfo($path, $info)
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
	
	function readXMLCache($path)
	{
		$cache_folder = $path . 'cache/';
		
		if (is_file($cache_folder . 'cache.xml'))
		{
			$xml = new SimpleXMLElement(file_get_contents($cache_folder . 'cache.xml'));
			
			$x = 0;
			
			if (isset($xml->directories))
			{
				foreach($xml->directories->dir as $dirs)
				{
					$this->vars['folder_list'][$x]['full_path'] = (string)$dirs->path;
					$this->vars['folder_list'][$x]['dir'] = (string)$dirs->dirname;
					
					$x++;
				}
			}
			
			$x = 0;
			
			if (isset($xml->files))
			{
				foreach($xml->files->file as $files)
				{
					$this->vars['file_list'][$x]['full_path'] = (string)$files->path;
					$this->vars['file_list'][$x]['file'] = (string)$files->filename;
					$this->vars['file_list'][$x]['data'][0] = (integer)$files->data->width;
					$this->vars['file_list'][$x]['data'][1] = (integer)$files->data->height;
					$this->vars['file_list'][$x]['data'][2] = (integer)$files->data->imagetype;
					$this->vars['file_list'][$x]['data'][3] = (string)$files->data->sizetext;
					if (isset($files->data->bits)) { $this->vars['file_list'][$x]['data']['bits'] = (integer)$files->data->bits; }
					if (isset($files->data->channels)) { $this->vars['file_list'][$x]['data']['channels'] = (integer)$files->data->channels; }
					if (isset($files->data->mime)) { $this->vars['file_list'][$x]['data']['mime'] = (string)$files->data->mime; }
					
					$x++;
				}
			}
	
			return true;
		} else {
			return false;
		}
	}
	
	function writeXMLCache($path)
	{
		$cache_folder = $path . 'cache/';
		
		if (!is_dir($cache_folder))
		{
			//Create cache folder if possible
			if (mkdir($cache_folder, 0755))
			{
				chmod($cache_folder, 0755);
				$cache_exists = true;
			} else {
				$cache_exists = false;
			}
		} else {
			$cache_exists = true;
		}
		
		if ($cache_exists == true)
		{
			$xmlstr = "<?xml version='1.0' ?>\n<cache></cache>";
			$xml = new SimpleXMLElement($xmlstr);
			
			if (isset($this->vars['folder_list']))
			{
				$xml_dir = $xml->addChild('directories');
				
				foreach($this->vars['folder_list'] as $dirs)
				{
					$xml_dirs_data = $xml_dir->addChild('dir');
					$xml_dirs_data->addChild('path', $dirs['full_path']);
					$xml_dirs_data->addChild('dirname', $dirs['dir']);
				}
			}
			
			if (isset($this->vars['file_list']))
			{
				$xml_files = $xml->addChild('files');
				
				foreach($this->vars['file_list'] as $files)
				{
					$xml_files_data = $xml_files->addChild('file');
					$xml_files_data->addChild('path', $files['full_path']);
					$xml_files_data->addChild('filename', $files['file']);
					
					$xml_data = $xml_files_data->addChild('data');
					$xml_data->addChild('width', $files['data'][0]);
					$xml_data->addChild('height', $files['data'][1]);
					$xml_data->addChild('imagetype', $files['data'][2]);
					$xml_data->addChild('sizetext', $files['data'][3]);
					if (isset($files['data']['bits'])) { $xml_data->addChild('bits', $files['data']['bits']); }
					if (isset($files['data']['channels'])) { $xml_data->addChild('channels', $files['data']['channels']); }
					if (isset($files['data']['mime'])) { $xml_data->addChild('mime', $files['data']['mime']); }
				}
			}
			
			$xml->asXML($cache_folder . 'cache.xml');
			return true;
		} else {
			return false;
		}
	}
	
	function fileList($path)
	{
		if ($this->settings['advanced']['use_file_cache'] == true and is_file($path . 'cache/cache.xml') and ((time() - filemtime($path . 'cache/cache.xml')) < $this->settings['advanced']['expire_file_cache']) and $this->readXMLCache($path) == true)
		{
			return true;
		} else {
			if (is_dir($path))
			{
				if ($dh = opendir($path))
				{
					while (($file = readdir($dh)) !== false) 
					{
						if (filetype($path . $file) == 'dir' and $this->checkList($file, $this->vars['folder_blacklist']) == false)
						{
							$found_directories[] = array(
								'full_path'=>$path . $file,
								'dir'=>$file
							);
							
							sort($found_directories);
						} else if (filetype($path . $file) == 'file' and $this->checkList($file, $this->vars['file_blacklist']) == false and $this->checkList($this->pathInfo($file, 'file_ext'), $this->vars['file_types']) == true) {
							$found_files[] = array(
								'full_path'=>$path . $file,
								'file'=>$file,
								'data'=>getimagesize($path . $file)
							);
							
							sort($found_files);
						}
					}
					closedir($dh);
					
					if (isset($found_files)) { $this->vars['file_list'] = $found_files; }
					if (isset($found_directories)) { $this->vars['folder_list'] = $found_directories; }
					
					if ($this->settings['advanced']['use_file_cache'] == true)
					{
						$this->writeXMLCache($path);
					}
					
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}
	
	function prevFolderExists()
	{
		if ($this->vars['dir_req'] != '')
		{
			return true;
		} else {
			return false;
		}
	}
	
	function prevImageExists($ignore_javascript = false)
	{
		//Set ignore_javascript to true if you want accurate results, otherwise if use_javascript_navigation is set to true this will always return true
		
		if (isset($this->vars['previous_image_id']))
		{
			return true;
		} else if ($ignore_javascript == false and $this->settings['advanced']['use_javascript_navigation'] == true) {
			return true;
		} else {
			return false;
		}
	}
	
	function nextImageExists($ignore_javascript = false)
	{
		//Set ignore_javascript to true if you want accurate results, otherwise if use_javascript_navigation is set to true this will always return true
		
		if (isset($this->vars['next_image_id']))
		{
			return true;
		} else if ($ignore_javascript == false and $this->settings['advanced']['use_javascript_navigation'] == true) {
			return true;
		} else {
			return false;
		}
	}
	
	function noticeExists()
	{
		if ($this->settings['general']['site_notice'] != '')
		{
			return true;
		} else {
			return false;
		}
	}
	
	function outputSettingsArray()
	{
		echo '<pre>';
		print_r($this->settings);
		echo '</pre>';
	}
	
	function outputVarsArray()
	{
		echo '<pre>';
		print_r($this->vars);
		echo '</pre>';
	}
	
	function showError($format = 0)
	{
		//0 = Output error
		//1 = Return error as string	

		if ($format == 0)
		{
			echo $this->vars['error'];
		} else if ($format == 1) {
			return $this->vars['error'];
		}
	}
	
	function showNotice($format = 0)
	{
		//0 = Output notice
		//1 = Return notice as string	

		if ($format == 0)
		{
			echo $this->settings['general']['site_notice'];
		} else if ($format == 1) {
			return $this->settings['general']['site_notice'];
		}
	}
	
	function showImage($format = 2)
	{
		//0 = Output url
		//1 = Return url as string
		//2 = Output full img tag
		//3 = Return full img tag as string
		
		if ($format == 0)
		{
			echo $_GET['file'];
		} else if ($format == 1) {
			return $_GET['file'];
		} else if ($format == 2) {
			$toggle = ($this->settings['advanced']['use_resize_toggle'] == true) ? ' onclick="toggle_resize();"' : '';
			echo '<img' . $toggle . ' id="image" src="' . $_GET['file'] . '" alt="' . $_GET['file'] . '" />';
		} else if ($format == 3) {
			$toggle = ($this->settings['advanced']['use_resize_toggle'] == true) ? ' onclick="toggle_resize();"' : '';
			return '<img' . $toggle . ' id="image" src="' . $_GET['file'] . '" alt="' . $_GET['file'] . '" />';
		}
	}
	
	function showFooter($format = 0)
	{
		//0 = Output footer
		//1 = Return footer as string	
		
		if (is_file('phppi/footer.txt'))
		{
			$footer = file_get_contents('phppi/footer.txt');
			
			$this->endTimer();
			
			$search = array(
				'[site_name]',
				'[current_item]',
				'[version]',
				'[load_time]'
				);
			$replace = array(
				$this->settings['general']['site_name'],
				$this->vars['page_title'],
				$this->vars['version'],
				number_format($this->vars['total_time'], 7)
				);
				
			if ($format == 0)
			{
				echo str_replace($search, $replace, $footer);
			} else if ($format == 1) {
				return str_replace($search, $replace, $footer);
			}
		}
	}
	
	function showGallery()
	{
		if ($this->vars['dir_req'] != '')
		{
			$request = $this->vars['dir_req'] . '/';
		} else {
			$request = '';
		}
		
		if (isset($this->vars['folder_list']))
		{
			foreach ($this->vars['folder_list'] as $dir)
			{	
				$output = '';
				$output .= '<a class="thumbnail-container" style="width: ' . $this->settings['general']['folder_size'] . 'px;" href="?' . $request . $dir['dir'] .'" title="' . $dir['dir'] . '">';
				$output .= '<span class="directory" style="width: ' . $this->settings['general']['folder_size'] . 'px; height: ' . $this->settings['general']['folder_size'] . 'px;"></span>';
				
				if (isset($this->settings['hacks']['folder_title_padding']))
				{
					$title_width = $this->settings['general']['folder_size'] - ($this->settings['hacks']['folder_title_padding'] * 2);
				} else {
					$title_width = $this->settings['general']['folder_size'];
				}
				
				$output .= '<span class="title" style="width: ' . $title_width . 'px;"><span class="title-text">' . $dir['dir'] . '</span></span>';
				$output .= '</a>';
				$output .= "\n";
				
				echo $output;
			}
		}
		
		if (isset($this->vars['file_list']))
		{
			foreach ($this->vars['file_list'] as $file)
			{	
				$output = '';
				
				if ($this->settings['advanced']['use_slimbox'] == true)
				{
					$output .= '<a class="thumbnail-container" style="width: ' . $this->settings['general']['thumb_size'] . 'px;" href="' . $request . $file['file'] . '" title="' . $file['file'] . '" rel="lightbox-group">';
				} else {
					$output .= '<a class="thumbnail-container" style="width: ' . $this->settings['general']['thumb_size'] . 'px;" href="?file=' . $request . $file['file'] . '" title="' . $file['file'] . '">';
				}
				
				$file_ext = $this->pathInfo($file['file'], 'file_ext');
				if ($file_ext == 'jpeg' or $file_ext == 'jpg') { $file_ext = 'jpg';	}
				
				if ($this->settings['advanced']['use_gd'] == true)
				{		
					if ($this->settings['advanced']['use_gd_cache'] == true)
					{
						$use_cache = false;
						
						if (!is_file($this->vars['dir_cache'] . '/' . $this->pathInfo($file['file'], 'file_name') . '.' . $file_ext))
						{
							//Cached image does not exist, create if possible
							$use_cache = false;
						} else {
							//Cached image exists, check if correct image size
							list($thumb_width, $thumb_height) = getimagesize($this->vars['dir_cache'] . '/' . $this->pathInfo($file['file'], 'file_name') . '.' . $file_ext);
							
							$thumb_size = $this->resizedSize($file['data'][0], $file['data'][1]);
							
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
							$img_url = $this->vars['dir_cache'] . '/' . $this->pathInfo($file['file'], 'file_name') . '.' . $file_ext;
							$margin = $this->marginSize($file['data'][0], $file['data'][1]);
						} else {
							$img_url = '?thumb=' . $request . $file['file'];
							$margin = $this->marginSize($file['data'][0], $file['data'][1]);
						}
					} else {
						$img_url = '?thumb=' . $request . $file['file'];
						$margin = $this->marginSize($file['data'][0], $file['data'][1]);
					}
				} else {
					$img_url = $request . 'thumbs/' . $this->pathInfo($file['file'], 'file_name') . '.' . $this->settings['general']['thumb_file_ext'];
					$img_thumb_size = getimagesize($img_url);
					$margin = $this->marginSize($img_thumb_size[0], $img_thumb_size[1]);
				}
				
				//Get thumbnail size
				if ($use_cache)
				{
					$img_size = getimagesize($this->vars['dir_cache'] . '/' . $this->pathInfo($file['file'], 'file_name') . '.' . $file_ext);
				} else {
					$img_size = $this->resizedSize($file['data'][0], $file['data'][1]);
				}
				
				$output .= '<span class="file" style="width: ' . $this->settings['general']['thumb_size'] . 'px; height: ' . ($this->settings['general']['thumb_size'] - ($margin * 2)) . 'px; padding: ' . $margin . 'px 0px ' . $margin . 'px 0px;"><img style="width: ' . $img_size[0] . 'px; height: ' . $img_size[1] . 'px;" src="' . $img_url .'" alt="" /></span>';
				
				if (isset($this->settings['hacks']['thumb_title_padding']))
				{
					$title_width = $this->settings['general']['thumb_size'] - ($this->settings['hacks']['thumb_title_padding'] * 2);
				} else {
					$title_width = $this->settings['general']['thumb_size'];
				}
				
				$output .= '<span class="title" style="width: ' . $title_width . 'px;"><span class="title-text">' . $file['file'] . '</span></span>';
				$output .= '</a>';
				$output .= "\n";
				
				echo $output;
			}
		}
		
		echo "<div style=\"clear: both;\"></div>\n";
	}
	
	function showThumbnail($filename)
	{
		//Creates thumbnail, either dynamically or for cache depending on settings		
		$filename = realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/' . $filename;
		$cache_folder = $this->pathInfo($filename, 'dir_path') . '/cache/';
		
		$create_cache_file = false;
		
		if ($this->settings['advanced']['use_gd'] == true)
		{
			if (!is_dir($cache_folder))
			{
				//Create cache folder if possible
				mkdir($cache_folder, 0755);
				chmod($cache_folder, 0755);
				if (!is_file($cache_folder))
				{
					$create_cache_file = false;
				} else {
					$create_cache_file = true;
				}
			} else {
				$create_cache_file = true;
			}
		}
		
		$file_ext = $this->pathInfo($filename, 'file_ext');
		
		if ($file_ext == 'jpg' or $file_ext == 'jpeg')
		{
			$image = imagecreatefromjpeg($filename);
			$format = 'jpeg';
		} else if ($file_ext == 'png') {
			$image = imagecreatefrompng($filename);
			$format = 'png';
		} else if ($file_ext == 'gif') {
			$image = imagecreatefromgif($filename);
			$format = 'gif';
		}
		
		$width = imagesx($image);
		$height = imagesy($image);
		
		$new_size = $this->resizedSize($width, $height);
		
		$new_image = ImageCreateTrueColor($new_size[0], $new_size[1]);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_size[0], $new_size[1], $width, $height);
	
		if ($create_cache_file == false)
		{
			header('Pragma: public');
			header('Cache-Control: maxage=' . $this->settings['advanced']['gd_cache_expire']);
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $this->settings['advanced']['gd_cache_expire']) . ' GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			
			if ($format == 'jpeg')
			{
				header('Content-type: image/jpeg');
				imagejpeg($new_image, null, $this->settings['advanced']['jpeg_quality']);
			} else if ($format == 'png') {
				header('Content-type: image/png');
				imagepng($new_image);
			} else if ($format == 'gif') {
				header('Content-type: image/gif');
				imagegif($new_image);
			}
		} else if ($create_cache_file == true) {
			header('Pragma: public');
			header('Cache-Control: maxage=' . $this->settings['advanced']['gd_cache_expire']);
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $this->settings['advanced']['gd_cache_expire']) . ' GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			
			if ($format == 'jpeg')
			{
				header('Content-type: image/jpeg');
				imagejpeg($new_image, $cache_folder . $this->pathInfo($filename, 'file_name') . '.jpg', $this->settings['advanced']['jpeg_quality']);
				imagejpeg($new_image);
			} else if ($format == 'png') {
				header('Content-type: image/png');
				imagepng($new_image, $cache_folder . $this->pathInfo($filename, 'file_name') . '.png');
				imagepng($new_image);
			} else if ($format == 'gif') {
				header('Content-type: image/gif');
				imagegif($new_image, $cache_folder . $this->pathInfo($filename, 'file_name') . '.gif');
				imagegif($new_image);
			}
		}		
		
		imagedestroy($new_image);	
	}
	
	function showPrevFolderURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string	
		if ($format == 0)
		{
			echo '?' . $this->vars['dir_req_parent'];
		} else if ($format == 1) {
			return '?' . $this->vars['dir_req_parent'];
		}
	}
	
	function showPrevImageURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string	
		if ($format == 0)
		{
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				echo 'javascript: go_prev_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['previous_image_id']]['file'])
				{
					echo '?file=' . $this->pathInfo($_GET['file'], 'dir_path') . '/' . $this->vars['file_list'][$this->vars['previous_image_id']]['file'];
				} else {
					echo '';
				}
			}
		} else if ($format == 1) {
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				return 'javascript: go_prev_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['previous_image_id']]['file'])
				{
					return '?file=' . $this->pathInfo($_GET['file'], 'dir_path') . '/' . $this->vars['file_list'][$this->vars['previous_image_id']]['file'];
				} else {
					return '';
				}
			}
		}
	}
	
	function showNextImageURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string	
		if ($format == 0)
		{
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				echo 'javascript: go_next_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['next_image_id']]['file'])
				{
					echo '?file=' . $this->pathInfo($_GET['file'], 'dir_path') . '/' . $this->vars['file_list'][$this->vars['next_image_id']]['file'];
				} else {
					echo '';
				}
			}
		} else if ($format == 1) {
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				return 'javascript: go_next_image();';
			} else {
				if ($this->vars['file_list'][$this->vars['next_image_id']]['file'])
				{
					return '?file=' . $this->pathInfo($_GET['file'], 'dir_path') . '/' . $this->vars['file_list'][$this->vars['next_image_id']]['file'];
				} else {
					return '';
				}
			}
		}
	}
	
	function showUpFolderURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string	
		if ($format == 0)
		{
			echo '?' . $this->pathInfo($_GET['file'], 'dir_path');
		} else if ($format == 1) {
			return '?' . $this->pathInfo($_GET['file'], 'dir_path');
		}
	}
	
	function showThemeURL($format = 0)
	{
		//0 = Output url
		//1 = Return url as string	
		if ($format == 0)
		{
			echo 'phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/';
		} else if ($format == 1) {
			return 'phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/';
		}
	}
	
	function showTitle($format = 0)
	{
		//0 = Output url
		//1 = Return url as string	
		$search = array(
			'[S]',
			'[P]'
			);
		$replace = array(
			$this->settings['general']['site_name'],
			$this->vars['page_title']
			);
		
		if ($format == 0)
		{
			echo str_replace($search, $replace, $this->settings['general']['page_title_format']);
		} else if ($format == 1) {
			return str_replace($search, $replace, $this->settings['general']['page_title_format']);
		}
	}
	
	function showPage()
	{
		require($this->showThemeURL(1) . 'pages/' . $this->vars['page_requested'] . '.php');
	}
	
	function resizedSize($width, $height, $return = 2)
	{
		//Returns width, height or an array of width and height for the thumbnail size of a full sized image		
		if ($width > $height)
		{
			$new_width = $this->settings['general']['thumb_size'];
			$new_height = $height * ($this->settings['general']['thumb_size'] / $width);
		} else if ($width < $height) {
			$new_width = $width * ($this->settings['general']['thumb_size'] / $height);
			$new_height = $this->settings['general']['thumb_size'];
		} else if ($width == $height) {
			$new_width = $this->settings['general']['thumb_size'];
			$new_height = $this->settings['general']['thumb_size'];
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
	
	function marginSize($width, $height)
	{
		//Returns margin/padding size in pixels, used to make sure thumbnails are vertically centered
		$size = $this->resizedSize($width, $height);
		$height = $size[1];
		
		if ($height < $this->settings['general']['thumb_size'])
		{
			$margin = floor(($this->settings['general']['thumb_size'] - $height) / 2);
		} else {
			$margin = 0;
		}
		
		return $margin;
	}
	
	function insertHeadInfo()
	{
		echo "
<!-- 
PHP Picture Index " . $this->vars['version'] . "

Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://phppi.pixelizm.com/
Licence: GNU General Public License v3                   		 
http://www.gnu.org/licenses/                
-->\n\n";
		
		echo "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; user-scalable = no; maximum-scale=1.0;\" />\n";
		
		echo "<script type=\"text/javascript\" src=\"phppi/scripts/jquery/jquery.js\"></script>\n";
		
		if (isset($_GET['file']) and !isset($this->vars['error']))
		{
			echo "<script type=\"text/javascript\">\n";
			echo "var image_width = " . $this->vars['file_list'][$this->vars['current_image_id']]['data'][0] . ";\n";
			echo "var image_height = " . $this->vars['file_list'][$this->vars['current_image_id']]['data'][1] . ";\n";
			echo "var up_folder = '" . $this->showUpFolderURL(1) . "';\n";
			echo "var prev_image = '" . $this->showPrevImageURL(1) . "';\n";
			echo "var next_image = '" . $this->showNextImageURL(1) . "';\n";
			
			if ($this->settings['general']['enable_hotkeys']) { $enable_hotkeys = 1; } else { $enable_hotkeys = 0; }
			if ($this->settings['general']['enable_up_hotkey']) { $enable_up_hotkey = 1; } else { $enable_up_hotkey = 0; }
			
			echo "var enable_hotkeys = " . $enable_hotkeys . ";\n";
			echo "var enable_up_hotkey = " . $enable_up_hotkey . ";\n";
			
			if ($this->settings['advanced']['use_javascript_navigation'] == true)
			{
				echo "var site_name = '" . $this->settings['general']['site_name'] . "';\n";
				echo "var page_title = '" . $this->vars['page_title'] . "';\n";
				echo "var page_title_format = '" . $this->settings['general']['page_title_format'] . "';\n";
				echo "var current_file = " . $this->vars['current_image_id'] . ";\n\n";
				echo "var files = new Array();\n";
				
				$x = 0;
				$dir = $this->pathInfo($_GET['file'], 'dir_path');
				
				foreach($this->vars['file_list'] as $file)
				{
					echo "files[" . $x . "] = Array('" . $dir . "/" . $file['file'] . "', '" . $file['file'] . "', " . $file['data'][0] . ", " . $file['data'][1] . ");\n";
					$x++;
				}
			}
			
			echo "</script>\n";				
			echo "<script type=\"text/javascript\" src=\"phppi/scripts/phppi_js.php\"></script>\n";
		}
		
		if ($this->settings['advanced']['use_slimbox'] == true)
		{
			echo "<script type=\"text/javascript\" src=\"phppi/scripts/slimbox/slimbox2.js\"></script>\n";
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"phppi/scripts/slimbox/slimbox2.css\" />\n";
		}
		
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"phppi/css/global.css\" />\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->showThemeURL(1) . "style.css\" />\n";
	}
	
	function initialize()
	{		
		//Debug Mode		
		if ($this->settings['advanced']['debug_mode'] == true)
		{
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		
		//GZIP Compression
		ini_set('zlib.output_compression', $this->settings['advanced']['use_gzip_compression']);
		ini_set('zlib.output_compression_level', $this->settings['advanced']['gzip_compression_level']);
		
		//Theme Mode
		$this->setThemeMode();
		
		if ($this->settings['advanced']['allow_mobile_theme'] == true)
		{
			if (!is_file('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/template.php'))
			{
				$this->vars['theme_mode'] = 'standard';
			}
		} else {
			$this->vars['theme_mode'] = 'standard';
		}
		
		//Theme Specific Settings
		if ($this->settings['advanced']['allow_theme_settings'] == true)
		{
			$this->loadSettings(true);
		}
		
		//Load Variables
		$this->loadVars();
		
		//Load Blacklists/Whitelists
		$this->loadLists();
		
		//Display Content
		if (isset($_GET['thumb'])) 
		{
			//Show thumbnail only
			$this->showThumbnail($_GET['thumb']);
		} else if (isset($_GET['file'])) {
			//Show full image view
			$req_path = $this->pathInfo($_GET['file'], 'dir_path');
			
			if (!$this->fileList($this->vars['dir_local'] . '/' . $req_path . '/'))
			{
				$this->vars['error'] = 'Folder doesn\'t exist';
				$this->vars['page_title'] = 'Error';
				$this->vars['page_requested'] = 'error';
			} else if (!is_file($_GET['file'])) {
				$this->vars['error'] = 'File doesn\'t exist';
				$this->vars['page_title'] = 'Error';
				$this->vars['page_requested'] = 'error';
			} else {
				for($i = 0; $i < count($this->vars['file_list']); $i++)
				{
					if ($this->vars['file_list'][$i]['file'] == $this->pathInfo($_GET['file'], 'full_file_name'))
					{
						$this->vars['current_image_id'] = $i;
						
						if ($i > 0)
						{
							$this->vars['previous_image_id'] = $i - 1;
						}
						if ($i < (count($this->vars['file_list']) - 1))
						{
							$this->vars['next_image_id'] = $i + 1;
						}
						
						break;
					}
				}
				
				$this->vars['page_title'] = $this->pathInfo($_GET['file'], 'full_file_name');
				$this->vars['page_requested'] = 'image';
			}
			
			require('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/template.php');
		} else {
			//Show folder view	
			if ($this->vars['dir_req'] == '')
			{
				$dir_req = '';
			} else {
				$dir_req = $this->vars['dir_req'] . '/';
			}
			
			if (!$this->fileList($this->vars['dir_local'] . '/' . $dir_req))
			{
				$this->vars['error'] = 'Folder doesn\'t exist';
				$this->vars['page_title'] = 'Error';
				$this->vars['page_requested'] = 'error';
			} else {
				$this->vars['page_title'] = '/' . $this->vars['dir_req'];
				$this->vars['page_requested'] = 'folder';
			}
			
			require('phppi/themes/' . $this->settings['general']['theme'] . '/' . $this->vars['theme_mode'] . '/template.php');
		}
	}
}
?>
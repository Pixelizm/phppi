<?php
/* General */

$this->settings['general']['site_name'] = 'My Gallery';
$this->settings['general']['site_notice'] = ''; //Display a notice on all pages (depends on theme)(html is allowed), leave blank to disable.
$this->settings['general']['page_title_format'] = '[S] - [P]'; //Title format ([S] = Site name, [P] = Page title).
$this->settings['general']['theme'] = 'aero'; //Folder name of theme to use.

$this->settings['general']['thumb_size'] = 125; //Thumbnail will not exceed this value in either width or height (pixels).
$this->settings['general']['folder_size'] = 125; //Folder will not exceed this value in either width or height (pixels).
$this->settings['general']['thumb_file_ext'] = 'png'; //File extention of non GD thumbnails.

$this->settings['general']['enable_hotkeys'] = true; //Left goes to the previous image, Right goes to the next image while in full image view.
$this->settings['general']['enable_up_hotkey'] = false; //Up returns to folder/file view from full image view.

/* Advanced */

$this->settings['advanced']['debug_mode'] = false; //Enable if having issues with PHPPI so you can report the exact error you are getting.

$this->settings['advanced']['allow_mobile_theme'] = true; //Enables mobile version if supported by theme.
$this->settings['advanced']['allow_theme_settings'] = true; //Allow theme settings to override your own.

$this->settings['advanced']['use_gzip_compression'] = 'on'; //Enable gzip compression of html where possible ('on' or 'off')
$this->settings['advanced']['gzip_compression_level'] = 1; //0 to 9 (9 being most compression).

$this->settings['advanced']['use_gd'] = true; //Enable GD thumbnail creation (dynamic thumbnails).
$this->settings['advanced']['use_gd_cache'] = true; //Cache thumbnails so they aren't recreated on every visit.
$this->settings['advanced']['jpeg_quality'] = 75; //Jpeg thumbnail quality
$this->settings['advanced']['gd_cache_expire'] = 60*60*24*2; //Seconds till expire (seconds, minutes, hours, days) (2 days)
$this->settings['advanced']['use_file_cache'] = true; //Cache list of files to improve speed.
$this->settings['advanced']['expire_file_cache'] = 60*60*24*1; //Seconds till expire (seconds, minutes, hours, days) (1 day)

$this->settings['advanced']['use_javascript_navigation'] = false; //Use javascript for changing between images in full view mode, doesn't reload the page for each image.
$this->settings['advanced']['use_resize_toggle'] = false; //Set to true if you want users to be able to turn off automatic resizing by clicking the full image.
$this->settings['advanced']['use_slimbox'] = false; //Use slimbox (lightbox alternative) for viewing full images instead of built in viewer.
?>
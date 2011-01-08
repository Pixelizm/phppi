<?php
$settings['gzip_compression'] = true;

$settings['enable_settings.php'] = true;
$settings['use_settings.php_as_global'] = false;

$settings['thumbnail_gd'] = true;
$settings['thumbnail_gd_cache'] = true;
$settings['thumbnail_gd_expire'] = 60*60*24*2;

$settings['thumbnail_size'] = 150;
$settings['folder_size'] = 150;

$settings['thumbnail_file_type'] = 'png';

$settings['thumbnail_text_size'] = 14;
$settings['thumbnail_text_font'] = 'Arial, Helvetica, sans-serif';

$settings['thumbnail_filename'] = true;
$settings['thumbnail_directoryname'] = true;

$settings['folder_thumbnail_file'] = '';
$settings['folder_thumbnail_type'] = 'none';

$settings['slimbox_support'] = false;
$settings['slimbox_folder'] = 'slimbox/';

$settings['visual_background_color'] = '#000000';
$settings['visual_text_color'] = '#ffffff';
$settings['visual_zoom_bar_color'] = '#222222';
$settings['visual_bar_size'] = '2';
$settings['visual_bar_color'] = '#404040';
$settings['visual_thumb_border'] = true;
$settings['visual_thumb_border_color'] = '#404040';
$settings['visual_folder_color'] = '#222222';
$settings['visual_folder_border'] = true;
$settings['visual_folder_border_color'] = '#404040';

$settings['page_override_header'] = '';
$settings['page_override_footer'] = '';

$settings['file_types'] = array('jpg', 'jpeg', 'png');
$settings['file_blacklist'] = array('index.php', 'settings.php');
$settings['dir_blacklist'] = array('.', '..', 'cache', 'thumbs', 'slimbox');
?>
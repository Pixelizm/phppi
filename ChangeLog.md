## 1.3.0b (1st July, 2013) ##

**New Features:**

  * Complete rewrite to support Smarty template engine, allows for easier theme creation as well as speed improvements.
  * Classic theme, useful starting point when creating a theme.
  * Cache folder can be stored anywhere (doesn't have to be relevant to the install folder).
  * Blacklists moved to settings file instead of individual text files.
  * Thumbnails now expire on the server, previously they only expired on the client's cache.
  * Basic EXIF support, data provided by the PHP function "exif\_read\_data" can now be accessed by theme creators. Default theme currently does not use EXIF data.
  * Implemented Supersized (http://buildinternet.com/project/supersized/) for Slideshow feature.
  * Should now work with Windows (Tested with WAMP).

**Fixes:**

  * General speed improvements.
  * Single quotations now allowed in file/folder names. Example: Family's Photos is now acceptable.
  * Other minor bugs.

**Settings (See settings.php for more information on each setting):**

Added:

  * folder\_blacklist
  * file\_blacklist
  * file\_types
  * smarty\_cache
  * smarty\_expire
  * smarty\_theme\_debug
  * smarty\_cache\_folder
  * smarty\_compile\_folder
  * folder\_cover\_filename
  * enable\_slideshow
  * slideshow\_background\_color
  * slideshow\_sizing
  * slideshow\_slide\_interval
  * slideshow\_transition\_speed
  * slideshow\_transition\_effect
  * slideshow\_random
  * enable\_exif\_support
  * version
  * gd\_client\_cache\_expire
  * gd\_server\_cache\_expire
  * file\_cache\_expire

Depreciated (Some settings may return in future versions):

  * nav\_menu\_style
  * thumbnail\_theme
  * thumb\_use\_jpeg\_for\_png
  * image\_show\_title\_on\_hover
  * folder\_show\_title\_on\_hover
  * disable\_popup\_image\_viewer\_for\_mobile
  * show\_thumbs\_under\_viewer
  * popup\_thumb\_size
  * enable\_mousewheel
  * enable\_click\_next
  * use\_javascript\_navigation
  * debug\_show\_settings
  * access\_log
  * access\_log\_no\_thumbnail
  * log\_timezone
  * allow\_mobile\_theme

## 1.2.0 (3rd December, 2012) ##

**New Features:**

  * Admin section and setup process for new/upgrade installs. Admin section currently only allows to check for updates and changing settings.
  * Breadcrumb style navigation bar.
  * Gallery no longer needs to reside in the PHPPI folder.
  * Thumbnail themes, you can now change the style of the thumbnails irregardless of the chosen gallery theme.

**Fixes:**

  * New php memory limit setting, increasing this value allows large photos to be shown and thumbnails to be dynamically created.
  * Improved sections of coding that needed updating.
  * Upgraded jQuery to 1.8.3.
  * Other minor fixes.

**Settings (See setup/admin section for more information on each setting):**

Added:

  * gallery\_folder
  * thumb\_use\_jpeg\_for\_png
  * debug\_show\_vars
  * debug\_show\_settings
  * nav\_menu\_style
  * thumbnail\_theme
  * admin\_password
  * php\_memory

Depreciated:

  * debug\_show\_all
  * page\_title\_format
  * allow\_theme\_settings
  * use\_css\_animations

## 1.1.1 (21st August, 2012) ##

**New Features:**

  * Access log. Outputs ip address, time and action of anyone accessing phppi.

**Fixes:**

  * Creates cache.xml file if expired instead of every page load.
  * No longer shows "Folder not found" instead of updating the cache when it expired.

**Settings (See settings.php for more information on each setting):**

Added:

  * ['advanced']['access\_log']
  * ['advanced']['access\_log\_no\_thumbnail']
  * ['advanced']['phppi\_log'] (Feature to be added in next release)
  * ['advanced']['log\_timezone']

## 1.1.0 (25th April, 2012) ##

**New Features:**

  * Redesigned thumbnails, more modern look with css animations. Also shows image thumbnails from within folders.
  * New theme called Pix, designed around the new thumbnail look.
  * Added fancybox support in replacement of slimbox.
  * Users can change thumbnail sizes on the fly (if enabled in settings).
  * Added global cache folder instead of having a cache folder inside every gallery folder.
  * Included Sample Photos.

**Fixes:**

  * Now supports cryillic file and folder names.
  * Full view image now resizes correctly regardless of surrounding padding/margins.
  * Overhauled PHPPI's javascript to use jQuery.
  * Changed to HTML5 instead of XHTML.
  * Security fixes.
  * Other minor fixes.

**Settings (See settings.php for more information on each setting):**

Added:

  * ['general']['page\_title\_show\_full\_path']
  * ['general']['thumb\_size']['small']
  * ['general']['thumb\_size']['medium']
  * ['general']['thumb\_size']['large']
  * ['general']['thumb\_size\_default']
  * ['general']['enable\_thumb\_size\_change']
  * ['general']['thumb\_folder\_show\_thumbs']
  * ['general']['thumb\_folder\_shuffle']
  * ['general']['thumb\_folder\_use\_cache\_only']
  * ['general']['use\_css\_animations']
  * ['general']['image\_show\_title\_on\_hover']
  * ['general']['folder\_show\_title\_on\_hover']
  * ['general']['use\_popup\_image\_viewer']
  * ['general']['disable\_popup\_image\_viewer\_for\_mobile']
  * ['general']['show\_thumbs\_under\_viewer']
  * ['general']['popup\_thumb\_size']
  * ['general']['enable\_mousewheel']
  * ['general']['nextprev\_image\_animation']
  * ['general']['open\_image\_animation']
  * ['general']['close\_image\_animation']
  * ['general']['enable\_click\_next']
  * ['advanced']['cyrillic\_support']
  * ['advanced']['cache\_folder']
  * ['advanced']['thumbs\_folder']

Depreciated:

  * ['general']['thumb\_size']
  * ['general']['folder\_size']
  * ['advanced']['use\_resize\_toggle']
  * ['advanced']['use\_slimbox']

## 1.0.2 (16th October, 2011) ##

  * Fixed uppercase file extension support, now it should not matter if file extensions are in uppercase.
  * Changed blacklist files to be delimited by commas instead of new lines.
  * Added debug\_show\_all setting. See settings.php for info.

## 1.0.1 (8th August, 2011) ##

  * Fixed broken jpeg support. Thumbnails of broken jpegs should now work.

## 1.0.0 RC (2nd April, 2011) ##

  * Theme support, PHPPI has been redesigned to allow easy creation of themes by use of ready made functions to insert content created by PHPPI.
  * Improved mobile support by allowing theme designers to have a seperate mobile version of their theme.
  * New default theme, Aero (based on the Windows Vista/7 theme).
  * Speed improvements, caching of file information has been included. If enabled PHPPI will create an xml file containing file information for that folder. From testing, a folder that would take 10 seconds to display now only takes 0.5 seconds once cached.
  * PHPPI is no longer a one file installation, this change was made to make PHPPI easier to work on as well as allow new features like themes. You can still use 0.9.5 if you want the one file installation but be aware that there will be no future updates to that version.
  * Javascript navigation by using arrow keys on your keyboard.
  * Javascript image loading so your web browser doesn't have to reload the page everytime you go to the next or previous images (disabled by default). Currently depending on the size of the image it may appear that you have not clicked next or previous but the image will display after it has loaded. This will be fixed in a future version where a loading animation will be displayed.
  * Seperate settings file so future updates will not interfere with you settings (unless changes need to be made but you will be made aware in the change log).
  * GIF files now supported, thumbnails created by GD now also use the same file type as the original file.
  * Site notice, you can now display a message on every page. This depends on the theme.
  * Security fixes.

## 0.9.5 (1st February, 2011) ##

  * Fixed 2 short php tags, should've been <?php.

## 0.9.4 (20th December, 2010) ##

  * Added GZIP compression support (for HTML).
  * Added Slimbox 2 support.
  * Added global and folder specific settings. You can now add a settings.php file to any folder and any $settings variable you use will overwrite the global $settings variable. With this you can now have different color schemes for each folder, or use it as a way to separate the settings from the index.php to make it easier when upgrading. Note that some settings may clash. Changing the thumbnail size does work but if the parent folder is set to a smaller or larger thumbnail size it will not accommodate, dynamic thumbnails for folders will also not update. This feature is meant more for visual changes.

## 0.9.3 (18th December, 2010) ##

  * Added check for whether files or folders are found and if not the process of displaying files or folders is not performed.

## 0.9.2 (16th December, 2010) ##

  * Fixed issue where the previous, next and home buttons would show before the image was loaded.
  * Added the ability to have thumbnails on folders. The following are now possible:
    * None: No thumbnail.
    * Lazy Cache: Random thumbnail from cache folder or thumbs folder but will not update thumbnails if needed.
    * Dynamic Cache: Random thumbnail from cache folder or thumbs folder, will update the thumbnail if GDv2 is enabled and it needs updating.
    * Static: Uses a specified image inside each folder.
    * Global Static: Uses a specified image inside the same folder as the index.php file (applies the image to all folders).
  * Changed folder text to be bolded to make it stand out more.
  * Added check for GD, if not installed $settings['thumbnail\_gd'] will be set to false.

## 0.9.1 (3rd December, 2010) ##

  * Added visual\_zoom\_bar\_color to settings which had been left out from the previous version.
  * Added link to http://code.google.com/p/phppi/ in footer.

## 0.9.0 (3rd December, 2010) ##

  * New full image view.
    * Auto zoom feature.
    * Manual zoom in and out controls.
    * Next and Previous buttons.
    * Return to gallery button.
  * Support for Mobile Safari (iPhone, iPad, iPod) improved.
  * Support for Android added.
  * New default color scheme.
  * Added visual\_background\_color to settings so you can change the background color.

## 0.8.0 (26th September, 2010) ##

  * Initial release.
  * GD v2.0 support for thumbnail creation.
  * Caching of thumbnails, either on server or client side.
  * Folder and sub-folder browsing.
  * Header and footer support.
  * JPEG and PNG support.
  * Simple Mobile Safari viewing support (iPhone, iPad, iPod).
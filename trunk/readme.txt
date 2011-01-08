
PHP Picture Index
------------------------

Created by: Brendan Ryan (http://www.pixelizm.com/)
Site: http://code.google.com/p/phppi/
Licence: GNU General Public License v3                   		 
http://www.gnu.org/licenses/

Requirements
------------------------

- GD version 2.0 (if you want it to dynamically create your thumbnails).
- GD (if used) must support JPEG and PNG.
- Tested with PHP 5.3.2, may work with earlier versions but I would say at least version 5.

Installation Notes
------------------------

PHP Picture Index

- (Optional) Edit index.php and make changes to the settings as needed or edit the code if desired.
- Upload index.php into a folder of your choice (PHPPI will be able to browse the folder it's in as well as sub-folders).
- Make sure that folder is setup to default to index.php if no file is specified.
- When you visit that folder through your web browser you will be presented with PHP Picture Index (as long as you meet the requirements).
- For GD caching of thumbnails you may need to chmod the image folder in question to 0777 temporarily, once you visit that folder and it 
  creates a cache folder then you can return the image folder to it's previous chmod setting. If you have already viewed that folder you 
  may need to hard refresh the page (usually CTRL + F5) for the browser to retry the image and therefore allowing it to create the cached 
  thumbnail.
- If you find a bug please let me know through the issues section of http://code.google.com/p/phppi/

Slimbox 2

- Make sure $settings['slimbox_support'] is set to true in the settings.
- Upload the slimbox folder provided in this zip file.
- Update $settings['slimbox_folder'] if necessary to point to the correct location of the Slimbox files.
- Note the settings default to having Slimbox disabled and the folder for Slimbox is located in the same folder as the index.php file.

Extras
-------------------------

settings.php: 
This is a template of all the settings, edit this file and upload to individual folders if you want to have specific settings
for each folder. You can add or remove any setting in the file, it will fill in the other settings with those set in the global settings.

Upgrading Note
-------------------------

Since PHPPI is designed to be a one file solution you will need to make note of what your current settings are before overwriting the file
unless you have your settings stored in a settings.php file in the same folder as the index.php file and have $settings['use_settings.php_as_global']
set to true.

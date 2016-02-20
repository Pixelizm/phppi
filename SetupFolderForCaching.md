## Introduction ##

This document should explain how to get caching to work with PHPPI. Depending on how your server is setup you may run into the issue where a cache folder is not created when you visit a folder using PHPPI (and of course caching is enabled in the settings).

Note: This document was created with Linux servers in mind. Wherever Apache is mentioned take that as whatever web server software you're using.

Disclaimer: By following this guide you will not hold Brendan Ryan responsible for any damages to your files/server. This guide has been written as best as I possibly can but there may be situations where your server is setup differently and may react differently to what is explained below. If you have any concerns or are not confident with doing what is written below then it's recommended you contact your web host instead.

## How To ##

For caching to work PHPPI must be able to create a cache folder inside the folder that contains your pictures (this may include multiple folders. For example you have a Flowers, Nature and Urban folder each containing pictures, each folder will have it's own cache folder). For this to work the permissions of the folder containing your pictures must give write access to Apache.

Some ftp clients allow you to view the properties of a folder and see what permissions are set and what the user/owner and group are. In most cases Apache is setup as the "www-data" user/group. If you don't see "www-data" as your user/owner or group then you may have to double check with your web host as to what your install of Apache is running as.

To fix the permissions you may be able to do so using just FTP but this depends on how your server is setup. For FTP to work you would need the user/owner and or group of the folder containing your pictures already setup as your Apache user/group (www-data). If they're not then you are going to need to use SSH or contact your web host for assistance. Provided below are guides for both FTP and SSH.

## FTP ##

Make sure you can change permissions using your FTP client before proceeding. Also look into how to do so using any guides provided by your FTP client, in most cases it's a matter of viewing the properties of the folder in your client.

Ok, so check the user/owner and group of your folder, if user/owner is set to your Apache user/group then you will need to give write permissions to owner. If group is set to your Apache user/group then you will need to give write permissions to group. You don't need to keep these permissions, once PHPPI has created the cache folder you can revert them to what they were.

You should now be able to get caching to work.

## SSH ##

This section requires some Linux knowledge, if you get stuck search Google for guides or contact your web host.

For this section we will assume the following:

  * Web site is run from - /var/www/mysite.com/public\_html/
  * Folder containing pictures - /var/www/mysite.com/public\_html/Flowers/

Log into your server via SSH.

  * Go to the parent folder of the folder that contains your pictures.
`cd /var/www/mysite.com/public_html/`

  * Now change the owner of the folder to your ftp username.
`chown myftpusername Flowers`

  * Change the group to your Apache group (www-data).
`chgrp myapachegroup Flowers`

  * Change the permissions to 0775.
`chmod 775 Flowers`

Repeat the above steps for other folders.

PHPPI should now be able to create the cache folder and caching should work.

## Help I Can't Delete <Insert Folder Here> ##

In some situations you may find you're unable to delete one of your picture folders (or cache folders) via FTP or through other methods. This will happen if your user account doesn't have write permissions on that folder. You can only remedy this through SSH or by contacting your web host. In future versions of PHPPI I will be providing ways to do this without having to use SSH.

In the meanwhile if you can login using SSH (as root user) then perform the following:

  * Go to the parent folder of the folder you want to remove.
`cd /var/www/mysite.com/public_html/`

  * Now change the owner of the folder and contents to your ftp username.
`chown -R myftpusername Flowers`

  * Change the permissions to 0775.
`chmod -R 775 Flowers`

If your root login is disabled for SSH you could try the "su" or "sudo" commands (search Google for info on these commands if needed) and then run the commands above.

You should now be able to remove the folder using FTP.

## Conclusion ##

Hopefully this has helped you getting cache to work. If not feel free to post below so that I can improve this guide.
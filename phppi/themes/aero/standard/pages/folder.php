<div id="page-title"><?php $this->showTitle(); ?></div>
<div class="page-bar">
	<?php if ($this->prevFolderExists()) { echo '<a class="previous-link" href="' . $this->showPrevFolderURL(1) . '"><img src="' . $this->showThemeURL(1) . 'images/previous.png" alt="Previous Folder" /></a>'; } ?>
    <div style="clear: both;"></div>
</div>
<?php if ($this->noticeExists()) { echo '<div id="page-notice">' . $this->showNotice(1) . '</div>'; } ?>
<div id="page-container">
<?php $this->showGallery(); ?>
</div>
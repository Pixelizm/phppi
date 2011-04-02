<div id="page-title"><?php $this->showTitle(); ?></div>
<div class="page-bar">
	<?php if ($this->prevFolderExists()) { echo '<a class="previous-link" href="' . $this->showPrevFolderURL(1) . '">Previous</a>'; } ?>
    <div style="clear: both;"></div>
</div>
<?php if ($this->noticeExists()) { echo '<div id="page-notice">' . $this->showNotice(1) . '</div>'; } ?>
<div id="page-container">
<?php $this->showGallery(); ?>
</div>
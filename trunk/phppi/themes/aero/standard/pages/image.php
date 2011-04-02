<div id="page-title"><?php $this->showTitle(); ?></div>
<div class="page-bar">
	<?php if ($this->prevImageExists()) { echo '<a class="previous-link" href="' . $this->showPrevImageURL(1) . '"><img src="' . $this->showThemeURL(1) . 'images/previous.png" alt="Previous Image" /></a>'; } ?>
    <a class="home-link" href="<?php $this->showUpFolderURL(); ?>"><img src="<?php $this->showThemeURL(); ?>images/up.png" alt="Previous Folder" /></a>
    <?php if ($this->nextImageExists()) { echo '<a class="next-link"  href="' . $this->showNextImageURL(1) . '"><img src="' . $this->showThemeURL(1) . 'images/next.png" alt="Next Image" /></a>'; } ?>
    <div style="clear: both;"></div>
</div>
<?php if ($this->noticeExists()) { echo '<div id="page-notice">' . $this->showNotice(1) . '</div>'; } ?>
<div id="page-image-container">
	<?php $this->showImage(); ?>
</div>
<div class="page-bar">
	<?php if ($this->prevImageExists()) { echo '<a class="previous-link" href="' . $this->showPrevImageURL(1) . '"><img src="' . $this->showThemeURL(1) . 'images/previous.png" alt="Previous Image" /></a>'; } ?>
    <a class="home-link" href="<?php $this->showUpFolderURL(); ?>"><img src="<?php $this->showThemeURL(); ?>images/up.png" alt="Previous Folder" /></a>
    <?php if ($this->nextImageExists()) { echo '<a class="next-link"  href="' . $this->showNextImageURL(1) . '"><img src="' . $this->showThemeURL(1) . 'images/next.png" alt="Next Image" /></a>'; } ?>
    <div style="clear: both;"></div>
</div>
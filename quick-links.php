<div class="row">
	<div class="col-sm-12">
		<div class="bd-b pd-b quick-links">
			<span class="bld" style="color: rgba(255,255,255,.7);">Quick Links:</span>
			&nbsp;&nbsp;
			
			<?php if(CURRENT_PAGE_NAME == 'learn-quran' || CURRENT_PAGE_NAME == 'icons') { ?>
			
			<font color="white">|</font>
			<a onclick="window.history.back();">Back</a>
			<font color="white">|</font>
			<a class="<?php echo (CURRENT_PAGE_NAME == 'index') ? 'active' : '' ?> color-primary" href="<?php echo SITE_DOMAIN_NAME ?>">Home</a>
			<?php } ?>

			<font color="white">|</font>
			<a class="<?php echo (CURRENT_PAGE_NAME == 'about') ? 'active' : '' ?> color-info" href="<?php echo 
			SITE_DOMAIN_NAME ?>/about<?= $_SESSION['DOT_PHP'] ?>">About Us</a>
			
			<font color="white">|</font>
			<a class="<?php echo (CURRENT_PAGE_NAME == 'contact') ? 'active' : '' ?> color-primary" href="<?php echo SITE_DOMAIN_NAME ?>/contact<?= $_SESSION['DOT_PHP'] ?>">Contact Us</a>
			<font color="white">|</font>
			<a class="<?php echo (CURRENT_PAGE_NAME == 'quran') ? 'active' : '' ?> color-warning" href="<?php echo SITE_DOMAIN_NAME ?>/quran<?= $_SESSION['DOT_PHP'] ?>">Quran</a>
			<font color="white">|</font>
			<a class="<?php echo (CURRENT_PAGE_NAME == 'learn-quran') ? 'active' : '' ?> color-success" href="<?php echo SITE_DOMAIN_NAME ?>/learn-quran<?= $_SESSION['DOT_PHP'] ?>">Learn Quran</a>
			<font color="white">|</font>
			<a class="<?php echo (CURRENT_PAGE_NAME == 'schools') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/schools<?= $_SESSION['DOT_PHP'] ?>" style="color: #f8e4fc;">Schools</a>
		</div>
	</div>
</div>
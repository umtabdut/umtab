<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php
		if(CURRENT_PAGE_NAME == 'user' && $link_error == false){ include_once 'includes/css/u-profile.css.php';}
		if(in_array(CURRENT_PAGE_NAME, ['quran', 'watch', 'audios', 'listen', 'edit-watch']))
		{ //include_once 'includes/css/video-player.css.php'; ?>
	
			<link rel="stylesheet" type="text/css" href="<?= SITE_DOMAIN_NAME ?>/includes/css/css.media-player.css">

	<?php }	?>

	<title><?php echo isset($title) ? $title .' ::' : 'Welcome to '; echo ucfirst(SITE_NAME); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="images/jgp" href="<?= SITE_DOMAIN_NAME ?>/wap-content/images/logo/logo.ico">
	

<?php
	include_once 'includes/css/css.php';

 if($user->is_admin()){ ?>	<link rel="stylesheet" type="text/css" href="<?= SITE_DOMAIN_NAME ?>/includes/css/admin/css.main.css"> <?php } ?>

<?php if(CURRENT_PAGE_NAME=='schools' || CURRENT_PAGE_NAME=='user'){ ?>	<link rel="stylesheet" type="text/css" href="<?= SITE_DOMAIN_NAME ?>/includes/css/css.schools.css"> <?php } ?>

	<!-- 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	
	<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/jquery.js"></script>
	<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/clock.js"></script> 
	-->

	<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/jquery-3.4.0-jquery.min.js"></script>
	<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/main.js"></script>
	<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/functions.main.js"></script>
</head>

<body>
	<div class="wrapper" id="wrapper">
		
		<div class="alert-status"><!-- alert-status --></div>
		<script> const alertStatus = document.querySelector('.alert-status'); </script>

		<?php
		if(CURRENT_PAGE_NAME == 'signup' || CURRENT_PAGE_NAME == 'login' || CURRENT_PAGE_NAME == 'page-not-found' ||CURRENT_PAGE_NAME == 'icons' ){
			?>
			<!-- 
			-->
			<header style="z-index: 3">
				<!-- <img src="<?php echo SITE_DOMAIN_NAME; ?>/wap-content/images/site/header.jpg"> -->
				<a href="<?= SITE_DOMAIN_NAME ?>" title="Back to homepage">
					<img id="logo" src="<?php echo SITE_DOMAIN_NAME; ?>/wap-content/images/logo/logo.gif">
				</a>
				<span id="clockTxt"></span>
			</header>
			<!--
			<style>
				.nav-top-wrapper{
					/* last added from w3schools */		
					top: 4em;
				}
			</style>
			-->
			<?php
		}
		else
		{
			//loader image
			?>
			<!--
			<style>
				.nav-top-wrapper{
					/* last added from w3schools */		
					top: 0;
				}
			</style>
		-->

			<a id="Top"></a>
			<a style="position: fixed; top: 92%; left: 87%; background: #000; padding: 4px; border-radius: 30%; opacity: .8; color: #fff;" href="#Top" id="totop">To top</a>
			
			<!-- <div id="loader" style="background: rgba(255,255,255,1); padding: 3em; height: 100vh; position: fixed; top: 0; right: 0; left: 0; z-index: 9999999; display: flex; justify-content: center; align-items: center; overflow: hidden;">
				<div>
					<div class="text-center" style="color: rgba(0,0,0,.5); font-size: 1.2em; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">Loading...</div>
					<img src="<?php echo SITE_DOMAIN_NAME; ?>/wap-content/images/site/loader/spinner-5.gif" width="250px" height="auto" style="opacity: 1; border-radius: 50%;">
				</div>
			</div>
			<style>
				#loader::-webkit-scrollbar{
					width: 0;
				}
				#loader::-webkit-scrollbar-thumb{
					background: transparent;
				}
			</style> -->
			<?php
		}
		/*
			print_r($allowed_up_file['pic']['type']);
			<audio src="http://sunniconnect.com/audio//uploads/tracks/2070932263_587265135_750760012.mp3"  autoplay="autoplay"></audio>
		*/

			?>

			<style>
				.nav-top-wrapper{
					/* last added from w3schools */		
					top: 0;
				}
			</style>
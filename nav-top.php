<?php
$rrrrrrr = 10; //initially 1

	if( (CURRENT_PAGE_NAME != 'login' && CURRENT_PAGE_NAME != 'signup' && CURRENT_PAGE_NAME != 'user' && !(CURRENT_PAGE_NAME == 'groups' && count($_gets) > $rrrrrrr) ) )
	{
		?>
		<div class="nav-top-wrapper">

			<div class="nav-top">
				<a class="<?php echo (CURRENT_PAGE_NAME == 'index') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>" title="Homepage">
					<img id="logo" src="<?php echo SITE_DOMAIN_NAME; ?>/wap-content/images/logo/logo.gif" alt="Logo">
				</a>
				<nav>
					<ul>
						<!-- <li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'index') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/">Home</a>
						</li>
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'articles' || CURRENT_PAGE_NAME == 'article') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/articles.php">Articles</a>
						</li> -->
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'audios') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/audios<?= $_SESSION['DOT_PHP'] ?>">Audios</a>
						</li>
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'videos') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/videos<?= $_SESSION['DOT_PHP'] ?>">Videos</a>
						</li>
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'images') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/images<?= $_SESSION['DOT_PHP'] ?>">Images</a>
						</li>
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'documents') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/documents<?= $_SESSION['DOT_PHP'] ?>">Documents</a>
						</li>
						
						<!--<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'downloads') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/downloads">Downloads</a>
						</li>-->
						<?php if(logged_in()){ ?>

						<li id="profile">
							<a class="<?php echo (CURRENT_PAGE_NAME == 'user') ? 'active' : '' ?>" href="<?php echo $user->getLink() ?>">Profile</a>
						</li>
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'friends') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/friends<?= $_SESSION['DOT_PHP'] ?>">Friends <span id="newFriend"></span></a>
						</li>

						<?php if($user->friendsnum > 0){ 
							//make sure that user has at least one friend ?>
							<li id="chat">
								<a class="<?php echo (CURRENT_PAGE_NAME == 'chat') ? 'active' : '' ?>" href="<?php echo 
								SITE_DOMAIN_NAME ?>/chat<?= $_SESSION['DOT_PHP'] ?>">Chats <span id="countMessage"></span>
									<span id="newMessage"></span>
								</a>
							</li>
						<?php } ?>

						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'groups') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/groups<?= $_SESSION['DOT_PHP'] ?>">Groups <span id="newGroup"></span></a>
						</li>
						<li>
							<a class="<?php echo (CURRENT_PAGE_NAME == 'notifications') ? 'active' : '' ?>" href="<?php echo SITE_DOMAIN_NAME ?>/notifications<?= $_SESSION['DOT_PHP'] ?>">Notifications</a>
						</li>
						<?php } ?>
					</ul>
				</nav>
				<div>
					<?php echo nav_user_icon($user); ?>
				</div>
			</div>

		</div>
		
		<?php if (!in_array(CURRENT_PAGE_NAME, ['schools', 'chat', 'friends', 'groups', 'notifications', 'settings'])) { ?> 
		<div class="row pd bd bd-rad bg-white-6">
			<div class="max-width">
				<div style="display: flex; justify-content: space-between; align-items: center;">

						<?php if(CURRENT_PAGE_NAME != 'read_file' && CURRENT_PAGE_NAME != 'take' ) { //to_search(); ?>
					<div style="display: flex; gap: 1em;">
						<?php if(logged_in()) { ?>
							<div class="mg-r">	
									<button class="btn <?= (!ALLOW_USERS_UPLOAD && !IS_ADMIN) ? 'btn-primary' : 'btn-default' ?>" onclick="modal_display('uploadForm')">Upload</button>

									<button class="btn <?= (!ALLOW_USERS_POST && !IS_ADMIN) ? 'btn-primary' : 'btn-default' ?>" onclick="modal_display('postForm')">Post</button>
							</div>
						<?php } ?>

							<div class="row">
								<?php echo display_form('search'); ?>
							</div>
					</div>
						<?php } ?>
					
					<!--
					<div id="nav_user_icon">
						<?php echo nav_user_icon($user); ?>
					</div>
					-->
				</div>
			</div>
		</div>
		<?php } ?>

<?php } ?>

<div class="modal" id="uploadForm" style="visibility: hidden;">
	<div class="modal-container modal-form">
		<span onclick="modal_display('uploadForm')">&times;</span>
		<?php echo display_form('upload') ?>
	</div>
</div>

<div class="modal" id="postForm" style="visibility: hidden;">
	<div class="modal-container modal-form">
		<span onclick="modal_display('postForm')">&times;</span>
		<?php echo display_form('post') ?>
	</div>
</div>
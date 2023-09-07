<footer class="footer-main">
	<div class="max-width-1000">

		<div class="row">
			<?php include 'includes/quick-links.php'; ?>
			<div class="col-sm-4">
				<div class="pd">
					<h1>About</h1>
					<ul class="about-links"><?php
						$limitAbout = 5;
						$q = "SELECT au_id FROM about_us LIMIT 0, $limitAbout;";
						$sql = $conn->query($q);
						while ($aboutRows = $sql->fetch_assoc()) {
							$about = new About(); 
							$about->setId($aboutRows['au_id']);
							$aboutTitles[] = $about->getTitle();
							$aboutLinks[] = $about->getLink();
						}

						for ($i=0; $i < count($aboutTitles); $i++) { 
							?>
							<li><a href="<?= $aboutLinks[$i] ?>"><?= $aboutTitles[$i] ?></a></li>
							<?php
						}
						if($sql->num_rows > $limitAbout){
							echo '<p>
							<a href="'. SITE_DOMAIN_NAME .'/about">Go to About Us page</a>
							</p>';
						}
					?></ul>
					
					<?php
						echo CURRENT_PAGE_NAME == 'index' ? '' :
						'<p class="mg-t bld">
							<a href="'. SITE_DOMAIN_NAME .'">Go to Homepage</a>
						</p>';
					?>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="pd">
					<h1>Contact</h1>
					<ul class="about-links" >
						<p class="bld">Our telephone lines:</p>
						<li>
							<a class="tel" href="tel:+2348131168825">+234 (0)8131168825</a>									
						</li>
						<li>
							<a class="tel" href="tel:+2349031519421">+234 (0)9031519421</a>									
						</li>
						<p class="bld">Email addresses:</p>
						<li>
							<a class="tel" href="mailto:umartahirbako@gmail.com">umartahirbako@gmail.com</a>
						</li>
						<li>
							<a class="tel" href="mailto:umartahirbako@yahoo.com">umartahirbako@yahoo.com</a>
						</li>
					</ul>								
				</div>
			</div>
			
			<div class="col-sm-4">
				<div class="pd">
					<?php
					if(!logged_in()){
						echo '<div class="row pd-b">
							<p class="color-info text-center">
								<a href="'. SITE_DOMAIN_NAME .'/login.php?fr='. CURRENT_PAGE .'">Login</a>
								OR
								<a href="'. SITE_DOMAIN_NAME .'/signup.php?fr='. CURRENT_PAGE .'">Register</a>
							<p>
						</div>';
						echo display_form('subscribe');
					}
					else
					{
						if($user->postnum > 0 OR $user->audnum > 0 OR $user->vidnum > 0 OR $user->picnum > 0 OR $user->docnum > 0)
						{
							echo '<h1>My Quick Links</h1>';
							
						}
						echo '<font style="color: white; font-weight: 600 !important;">'. preg_replace('/'.$user->fullname() .'/', 'My Profile', $user->toProfile(50,50)) .'</font>';

						//echo $user->toProfile();
						echo '
						<div class="mg-l pd-l">
							<ul class="mg-l about-links">';
							echo $user->postnum < 1 ? '' : 
									'<li>
										<a href="'. $user->getLink() .'&tab=posts">Posts</a>
									</li>'.	
								( $user->audnum < 1 ? '' : 
									'<li>
										<a href="'. $user->getLink() .'&tab=audios">Audios</a>
									</li>'
								).	
								( $user->vidnum < 1 ? '' : 
									'<li>
										<a href="'. $user->getLink() .'&tab=videos">Videos</a>
									</li>'
								).	
								( $user->picnum < 1 ? '' : 
									'<li>
										<a href="'. $user->getLink() .'&tab=pictures">Pictures</a>
									</li>'
								).	
								( $user->docnum < 1 ? '' : 
									'<li>
										<a href="'. $user->getLink() .'&tab=documents">Documents</a>
									</li>'
								);
								echo '<div class="mg-t pd-t bd-t">
									<p>
										<a href="'. SITE_DOMAIN_NAME .'/notifications'.$_SESSION['DOT_PHP'].'">Notifications '. ($user->notificationsnum() > 0 ? '('. $user->notificationsnum() .')' : '') .'</a>
									</p>
									<p><a class="color" href="'. SITE_DOMAIN_NAME .'/mydashboard'.$_SESSION['DOT_PHP'].'">My Dashboard</a></p>';
									if(logged_in() && $_SESSION['is_admin']){
										echo '<p>
											<a href="'.SITE_DOMAIN_NAME .'/dashboard'.$_SESSION['DOT_PHP'].'">Dashboard</a>
										</p>';
									}									
									echo '<p><a class="color-" href="'. SITE_DOMAIN_NAME .'/settings'.$_SESSION['DOT_PHP'].'">Settings</a></p>
									<p><a class="color-warning" href="'. SITE_DOMAIN_NAME .'/logout'.$_SESSION['DOT_PHP'].'?fr='. urlencode(CURRENT_PAGE) .'">Logout</a></p>
								</div>
							</ul>
						</div>';
					}
					?>
				</div>
			</div>
		</div>

		<div class="row-">
			<div class="text-center" style="margin-top: 2rem; color: #fff; padding: 1em 1em 2rem 1em; border-top: #222 solid 2px; opacity: .7">			
				&copy; copyright <?= ucfirst(SITE_NAME) .' 2020 '. ( date('Y') > 2020 ? ' - '. date('Y') : '') ?>
				
			</div>
		</div>

	</div>
</footer>
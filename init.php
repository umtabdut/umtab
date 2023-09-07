<?php 
//	\	|
session_start();
// error_reporting(0);

//include 'includes/variables/variables.define.php';
include_once 'includes/core/database/conn.php';
include_once 'includes/variables/variables.define.php';

$_SESSION['CURRENT_TIMESTAMP'] = CURRENT_TIMESTAMP;
$_SESSION['SITE_DOMAIN_NAME'] = SITE_DOMAIN_NAME;
$_SESSION['CURRENT_PAGE'] = CURRENT_PAGE;
$_SESSION['CURRENT_PAGE_NAME'] = CURRENT_PAGE_NAME;
$_SESSION['CHARS'] = CHARS;

// SITE ICONS
$_SESSION['ICON']['settings'] = 'wap-content/images/site/icons/settings.png';
$_SESSION['ICON']['mydashboard'] = 'wap-content/images/site/icons/mydashboard.png';
$_SESSION['ICON']['dashboard'] = 'wap-content/images/site/icons/dashboard.png';
$_SESSION['ICON']['schools'] = 'wap-content/images/site/icons/schools.png';
$_SESSION['ICON']['schools-active'] = 'wap-content/images/site/icons/schools-active.png';
$_SESSION['ICON']['schools-removed'] = 'wap-content/images/site/icons/schools-removed.png';
$_SESSION['ICON']['schools-request'] = 'wap-content/images/site/icons/schools-request.png';
$_SESSION['ICON']['notifications'] = 'wap-content/images/site/icons/notifications.png';
$_SESSION['ICON']['friends'] = 'wap-content/images/site/icons/friends.png';
$_SESSION['ICON']['user'] = 'wap-content/images/site/icons/user.png';
$_SESSION['ICON']['chat'] = 'wap-content/images/site/icons/chat.png';
$_SESSION['ICON']['groups'] = 'wap-content/images/site/icons/groups.png';
$_SESSION['ICON']['students'] = 'wap-content/images/site/icons/students.png';
$_SESSION['ICON']['logout'] = 'wap-content/images/site/icons/logout.png';
$_SESSION['ICON']['switchOn'] = 'wap-content/images/site/icons/switch-on.png';
$_SESSION['ICON']['switchOff'] = 'wap-content/images/site/icons/switch-off.png';

//URL FILE EXTENSIONS
$_SESSION['DOT_HTML'] = DOT_HTML;
$_SESSION['DOT_PHP'] = DOT_PHP;

$sqlTotal = $conn->query("SELECT gr_key FROM groups;");
$_SESSION['total_groups'] = $sqlTotal->num_rows;
$_SESSION['groups_limit'] = 20;
$_SESSION['key_length']['groups'] = 6;

//RANDOM KEY LENGTH
if($_SESSION['total_groups'] > 1000){
	$_SESSION['key_length']['groups'] = 7;
}
//print_r($_SESSION); die;

$msgF = true;
$msg='';
//$next=CURRENT_PAGE;
include_once 'includes/functions/index.php';

if(logged_in()){
	$sql=$conn->query("SELECT count(gr_key) FROM groups WHERE gr_u_id='".$_SESSION['user_id']."';");
	$row=$sql->fetch_assoc();
	$_SESSION['user_total_groups'] = $row['count(gr_key)'];
	unset($sql);
}

// ECHO pre_r($_POST);

include_once 'includes/index.php';
include_once 'includes/classes/index.php';

include_once 'includes/core/database/queries.php';
//defined variable from settings table
foreach($settingsRows as $settingsRow)
{
	foreach($settingsRow as $col => $value)
	{
		define(strtoupper($col), $value);
		$_SESSION['SETTINGS'][strtoupper($col)] = $value;
	}
}

include_once 'process.php';

//USER (Logged in user)
$user = new User;
//$user->setId( logged_in() ? $_SESSION['user_id'] : '');
$user->selectAll=true; $user->select(logged_in() ? $_SESSION['user_id'] : '');
$admin = new Admin; $admin->setId( logged_in() ? $_SESSION['user_id'] : ''); $admin->check();
$is_admin = false;
$is_admin = $admin->is_admin();
$_SESSION['master'] = $admin->master();
// echo '<pre>'; var_dump($user); die;

//FORMS
$form = new Forms;
$notification = new Notification;
$notification->setConn($conn); //is required for the Class to work properly

//SHARE
$share = new Share;

//var_dump($notification); die;
//var_dump($user); die;

// $sqlSetting = $conn -> query("SELECT s_item, s_item_value FROM settings;");

// while($row = $sqlSetting -> fetch_assoc())
// {
	// $settingsRows[$row['s_item']] = $row['s_item_value'];
// }

//SET BOOL VALUES OF THE SITE BEHAVIOUS
$actUser = isset($settingsRows['active']) ? $settingsRows['active'] : 0;
$approve['post'] = isset($settingsRows['approve_post']) ? $settingsRows['approve_post'] : 0;
$approve['download'] = isset($settingsRows['approve_dl']) ? $settingsRows['approve_dl'] : 0;
$upload['download'] = isset($settingsRows['up_dl']) ? $settingsRows['up_dl'] : 0;
$upload['post'] = isset($settingsRows['up_post']) ? $settingsRows['up_post'] : 0;
$download['download'] = isset($settingsRows['down_dl']) ? $settingsRows['down_dl'] : 0;

$_SESSION['HIDE_FROM']['POST'] = array();
$_SESSION['HIDE']['POST'] = array();
$_SESSION['HIDE_FROM']['DOWNLOADS'] = array();
$_SESSION['HIDE']['DOWNLOADS'] = array();
if(logged_in()){
	$admin = new Admin; $admin->setId($_SESSION['user_id']); $admin->check();
	if($admin->is_admin())
	{
		$actUser = $approve['post'] = $approve['download'] = $upload['post'] = $upload['download'] = 1;
	}

	$q = "SELECT post_r_id, post_r_u_id_2 FROM posts_remove WHERE post_r_u_id='".$_SESSION['user_id']."';";
	$sql = $conn->query($q);
	while ($rows = $sql->fetch_assoc()) {
		array_push($_SESSION['HIDE']['POST'], $rows['post_r_id']);
		if($rows['post_r_u_id_2'] > 0){
			array_push($_SESSION['HIDE_FROM']['POST'], $rows['post_r_u_id_2']);
		}
	}

	$q = "SELECT post_r_id, post_r_u_id_2 FROM posts_remove WHERE post_r_type IN ('audio', 'video', 'image', 'document') && post_r_u_id='".$_SESSION['user_id']."';";
	$sql = $conn->query($q);
	while ($rows = $sql->fetch_assoc()) {
		array_push($_SESSION['HIDE']['DOWNLOADS'], $rows['post_r_id']);
		if($rows['post_r_u_id_2'] > 0){
			array_push($_SESSION['HIDE_FROM']['DOWNLOADS'], $rows['post_r_u_id_2']);
		}
	}
}
// echo pre_r($_SESSION['HIDE']['POST']);
// echo pre_r($_SESSION['HIDE_FROM']['POST']);

$_SESSION['logged_in'] = logged_in();
//PROCESSORS
to_search();
process_form($conn);

// echo '<pre>'; print_r($_); echo '</pre>';
//echo '<pre>'; print_r($_SESSION); echo '</pre>';
//echo first_launched() ? 'OK':'NONE';
	
	//print_r($dbtables);
	if(!logged_in())
	{
		if(!isset($_COOKIE['v']))
		{
			$uKey = generateKey($dbtables[0],24);
			$expire = date('U')+date('U');
			setcookie('v', $uKey, $expire);
			$c = "INSERT INTO visitors (v_user, v_date) VALUES ('$uKey', '". CURRENT_TIMESTAMP ."')";
			$sql = $conn -> query($c);
		}
	} 
	//categories
	$DbCat = new Category;
	$dbCat = $DbCat->getCategories();
	$dbCatTxt = $DbCat->getCategoriesLnk();
	
	//print_r($dbCatTxt);

	//scholars
	$dbScholars = new Scholars();
	$allowed_up_file = array (
	'aud' => array (
		'type' => array ('mp3', 'm4a', 'ogg', 'amr', 'wav', 'wma'),
	),
	'vid' => array (
		'type' => array ('3g2', '3gp', 'm4v', 'mp4', 'avi', 'mov', 'flv', 'wmv'),
	),
	'pic' => array ( 
		'type' => array ('jpg', 'jpeg', 'gif', 'png', 'jpeg', 'bmp', 'svg')
	),
	'doc' => array (
		'type' => array ('txt', 'css', 'html', 'xhtml', 'js', 'jsp', 'jspx', 'php', 'sql', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'csv', 'odt'),
	),
	'size' => 329789000 /* 329789000 = 500MB,  609402 = 595k 659578 = 1M 32978900 = 50M*/ 
	);

	$sorts = array(
		'users' => array('all', 'active', 'deactivate', 'delete'),
		'posts' => array('all', 'unapprove', 'approve', 'disapprove', 'delete', 'edit'),
		'downloads' => array('all', 'unapprove', 'approve', 'disapprove', 'delete')
		);
	
	$non_admin_where['u'] = !empty(non_admin_where('u')) ? "&& ". non_admin_where('u') : "";
	$non_admin_where['p'] = !empty(non_admin_where('p')) ? "&& ". non_admin_where('p') : "";
	$non_admin_where['d'] = !empty(non_admin_where('d')) ? "&& ". non_admin_where('d') : "";
	
	$StLmt = new StLmt();
	$page = $start = $StLmt->start;
	$limit = $StLmt->limit;
	$link_error = false;

	$_SESSION['limit']=$limit;
	$_SESSION['start']=$start;
	

	$fr = isset($_POST['fr']) ? urldecode($_POST['fr']) : '';
	
	if(empty($fr)){
		$fr = isset($_GET['fr']) ? urldecode($_GET['fr']) : SITE_DOMAIN_NAME;
	}
	
	//redirect user to respective page if he is not in that page e.g signup
	if(isset($_POST['signup']) && CURRENT_PAGE_NAME != 'signup')
	{
		$gets = '';
		foreach ($_POST as $key => $value) {
			$gets .= $key.'='.$value.'&';
		}
		header('Location:'. SITE_DOMAIN_NAME .'/signup.php?'.$gets.'fr='. CURRENT_PAGE);
	}
	
	$_gets = array();
	if(count($_GET) > 0)
	{
		$_gets = array_keys($_GET);
	}

	//MAKE AND UPDATE USERS TABLES WITH AN AVATAR
	// $sws = "SELECT id, firstname FROM users";
	// $sqld = $conn->query($sws);
	
	// while($eee = $sqld->fetch_assoc())
	// {
	// 	$array = make_avatar($eee['firstname'][0],'u',true);
	// 	$colors = explode(',', $array['color']);

	// 	$sws = "UPDATE users SET avatar='". $array['file'] ."', color_r='". $colors[0] ."', color_g='". $colors[1] ."', color_b='". $colors[2] ."' WHERE id=". $eee['id'];
	// 	echo $conn->query($sws) ? $eee['id'] .' : '. $eee['firstname'] : $eee['id'] .' X '. $eee['firstname'];
	// 	echo BR;
	// }

	// UPDATE every user's color with a randon 6 digits color
	// $sws = "SELECT id, firstname FROM users";
	// $sqld = $conn->query($sws);
	
	// while($eee = $sqld->fetch_assoc())
	// {
	// 	$sws = "UPDATE users SET color='". randon_color() ."' WHERE id=". $eee['id'];
	// 	echo $conn->query($sws) ? $eee['id'] .' : '. $eee['firstname'] : $eee['id'] .' X '. $eee['firstname'];
	// 	echo BR;
	// }

	//UPDATE USERS password with new pass (username)
	// $sws = "SELECT id, username, firstname FROM users";
	// $sqld = $conn->query($sws);
	
	// while($eee = $sqld->fetch_assoc())
	// {
	//	 $sws = "UPDATE users SET password='". my_hash($eee['username']) ."' WHERE id=". $eee['id'];
	//	 echo $conn->query($sws) ? $eee['id'] .' : '. $eee['firstname'] : $eee['id'] .' X '. $eee['firstname'];
	//	 echo BR;
	// }

	// MAKE AND UPDATE GROUPS TABLES WITH AN AVATAR
	// $sws = "SELECT gr_key, gr_name FROM groups";
	// $sqld = $conn->query($sws);
	
	// while($eee = $sqld->fetch_assoc())
	// {
		// $sws = "UPDATE groups SET gr_avatar='". make_avatar($eee['gr_name'][0],'g') ."' WHERE gr_key='". $eee['gr_key']."'";
		// echo $conn->query($sws) ? $eee['gr_key'] .' : '. $eee['gr_name'] : $eee['gr_key'] .' X '. $eee['gr_name'].$conn->error;
		// echo BR;
	// }

	//echo pre_r($_SESSION); die;
	//echo pre_r($_COOKIE['v']); die;

	//<a href="https://imguploader.net/fNBj"><img src="https://imguploader.net/i/fNBj.th.gif" alt="fNBj.th.gif" border="0"></a>

	// search-ms:displayname=Search%20Results%20in%20OTHERS&crumb=System.Generic.String%3Apayment&crumb=location:C%3A%5CUsers%5CUSER%5CDocuments%5COTHERS

$vvv = "&lt;!DOCTYPE html&gt;
&lt;html&gt;
	&lt;head&gt;
		&lt;title&gt;New&lt;/title&gt;
	&lt;/head&gt;
	&lt;body&gt;
	&lt;div&gt; Lorem ipsnim ad moris nisi ut aliqui &lt;/div&gt;
	  &lt;div class=&quot;row&quot;&gt;
		A div with class of row
	&lt;/div&gt;
	&lt;p&gt; Lore isjiuf jud misyb hsuj kjf mdhsuj kjus &lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
**pre**
***pre***
&lt;!DOCTYPE html&gt;
&lt;html&gt;
	&lt;head&gt;
		&lt;title&gt;New&lt;/title&gt;
	&lt;/head&gt;
	&lt;body&gt;
	&lt;div&gt; Lorem ipsnim ad moris nisi ut aliqui &lt;/div&gt;
	  &lt;div class=&quot;row&quot;&gt;
		A div with class of row
	&lt;/div&gt;
	&lt;p&gt; Lore isjiuf jud misyb hsuj kjf mdhsuj kjus &lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
***/pre***

***prehtml***
&lt;!DOCTYPE html&gt;
&lt;html&gt;
	&lt;head&gt;
		&lt;title&gt;New&lt;/title&gt;
	&lt;/head&gt;
	&lt;body&gt;
	&lt;div&gt; Lorem ipsnim ad moris nisi ut aliqui &lt;/div&gt;
	  &lt;div class=&quot;row&quot;&gt;
		A div with class of row
	&lt;/div&gt;
	&lt;p&gt; Lore isjiuf jud misyb hsuj kjf mdhsuj kjus &lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
***/pre***
**css**
***pre***
body{
	background: #000;
	color: #fff;
}
***/pre***
**pre css**
***precss***
body{
	background: #000;
	color: #fff;
}
***/pre***

**PHP**
&lt;?php
	$ var = 'value';
	$ var2 = 'value2';

	echo $ var;
	echo $ var2;

	$ var = &quot;value&quot;;
	$ var2 = &quot;value2&quot;;

	echo $ var;
	echo $ var2;
?&gt;
**pre**
***pre***
&lt;?php
	$ var = 'value';
	$ var2 = 'value2';

	echo $ var;
	echo $ var2;

	$ var = &quot;value&quot;;
	$ var2 = &quot;value2&quot;;

	echo $ var;
	echo $ var2;
?&gt;
***/pre***
__pre php__
***prephp***
&lt;?php
	$ var = 'value';
	$ var2 = 'value2';

	echo $ var;
	echo $ var2;

	$ var = &quot;value&quot;;
	$ var2 = &quot;value2&quot;;

	echo $ var;
	echo $ var2;
?&gt;
***/pre***";

//echo read_codes($vvv);
//echo read_css($vvv);
//die;

include_once 'show_chats.inc.php';

function show_login_form(){
	$output = '<div class="container container-login vac">
		<div class="row">
			<div class="col-sm-2 col-lg-2"></div>

			<div class="col-sm-8 col-lg-8">
				<div class="pd-l">
					<div class="mg-r">
						<div class="bd-white-6">';
							$output .= session_msg();
								
								if(!isset($_GET['fr'])){
									$output .= (isset($_GET['user']) && $_GET['user'] == 'n') ? '<div class="bld bd-rad pd bg-white-5 bd-info color-info" style="margin: 5px auto;">
									<h2 class="bld text-center mg-b color-success">Congratulatios...</h2>
										Your registration sucessfull, just enter your password below and proceed ...
									</div>' : '';

									$output .= isset($_SESSION['newUser']) ? '<p class="italic color-info bd-info bd-rad mg-b pd">You have just registered successfully, Please enter your pasword to proceed!!!</p>' : '';

									$output .= isset($_SESSION['login']) ? '<p>'. $_SESSION['login'] .'<p>' : '';

								} else { $output .= '<div class="pd bd-warning color-warning"><small>You must Login first!</small></div>'; }
								
								$output .= display_form('login');
						$output .= '</div>
					</div>
				</div>
			</div>

		</div><!-- row -->
	</div> <!-- container -->';
	return $output;
}

/*
<div class="container container-login vac">
			<div class="row">
				<div class="col-sm-2 col-lg-2"></div>

				<div class="col-sm-8 col-lg-8">
					<div class="pd-l">
						<div class="mg-r">
							<div class="bd-white-6">
								<?php
									//echo session_msg();
									
									echo (isset($_GET['user']) && $_GET['user'] == 'n') ? '<div class="bld bd-rad pd bg-white-5 bd-info color-info" style="margin: 5px auto;">
									<h2 class="bld text-center mg-b color-success">Congratulatios...</h2>
										Your registration sucessfull, just enter your password below and proceed ...
									</div>' : '';

									echo isset($_SESSION['newUser']) ? '<p class="italic color-info bd-info bd-rad mg-b pd">You have just registered successfully, Please enter your pasword to proceed!!!</p>' : '';
									echo isset($_SESSION['login']) ? '<p>'. $_SESSION['login'] .'<p>' : '';
									echo display_form('login');
								?>
							</div>
						</div>
					</div>
				</div>

			</div><!-- row -->
	</div> <!-- container -->
*/
	?>
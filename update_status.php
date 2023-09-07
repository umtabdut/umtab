<?php
header("Content-Type: application/json");
session_start();

if(!isset($_SESSION['user_id'])){ return; }

$u_id = $_SESSION['user_id'];

$time = time()+100;

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');

$last_login = "UPDATE users SET last_login='$time' WHERE id='$u_id'";
$conn->query($last_login);

$q = "SELECT new_message, new_notification, new_group_message, new_friend FROM users WHERE id='$u_id'";

$q = "SELECT msg.ms_id AS new_message, notif.n_id AS new_notification, grp_msg.mm_id AS new_group_message, frnd.fr_u_id AS new_friend
	FROM messages AS msg, notifications AS notif, group_messages AS grp_msg, friends AS frnd
	WHERE msg.ms_to='$u_id', notif.n_to='$u_id', grp_msg.mm_g_key IN (SELECT groups.gr_key AS gr_key, group_messages.gm_g_key AS gr_key FROM groups, group_messages WHERE groups.gr_u_id='$u_id' || group_messages.gm_u_id='$u_id')";
$result = $conn->query($q);
$rows = $result->fetch_assoc();

$output=array();
foreach ($rows as $key => $value) {
	$output[$key]['new'] = $value < 1 ? 'u' : preg_replace('/_/', '-', $key);
	$output[$key]['value'] = $value < 1 ? '' : $value;
}

// $output['new_message']['new_message'] = ';;';
$output['new_from_update_status'] = 1;

echo json_encode($output);

/*
//ANALYZE CURRENT_PAGE_PAGE and make some updates to the database tables

$current_page = $_SESSION['CURRENT_PAGE'];
$current_page_name = $_SESSION['CURRENT_PAGE_NAME'];
$site = $_SESSION['SITE_DOMAIN_NAME'];

$site_ = preg_replace('/[^a-zA-Z0-9_-]/', "\\\\$0", $site.'/'.$current_page_name);

$_gets = preg_replace('/'.$site_.'/', '', $current_page);

//Remove the begining question mark (?)
$_gets = preg_replace('/^\?/', '', $_gets);
$_gets = preg_split('/&/', $_gets);

if($current_page_name == 'groups' && count($_gets) > 0){

	//if group page and first url variable is group key

	//$output = '<pre>'.print_r($_gets, true).'</pre>';

	//NOW update the database
	$g_key = $_gets[0];

	if(!empty($g_key)){
		$last_login = "UPDATE groups_members_last_present SET gm_l_last_update='$time' WHERE gm_l_g_key='$g_key' && gm_l_u_id='$u_id'";
		$conn->query($last_login);
	}
}

*/
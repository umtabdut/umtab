<?php
header("Content-Type: application/json");
session_start();
if(!isset($_SESSION['user_id'])){ return; }


$u_id = $_SESSION['user_id'];
$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$output='';

$_gets = array_keys($_GET);

/*
if($_gets[0] == 'notification' || $_gets[0] == 'message' || $_gets[0] == 'friend' || $_gets[0] == 'group_message'){

	$col = 'new_'.$_gets[0];
	$new = "SELECT $col FROM users WHERE id='$u_id'";
	$result = $conn->query($new);
	$row = $result->fetch_assoc();
	
	$output = $row[$col] > 0 ? 'new-'.$_gets[0] : '';
	//restore _ to -
	$output = preg_replace('/_/', '-', $output);

	if(isset($_GET['count'])){
		$output = $row[$col] > 0 ? $row[$col] : '';
	}

	if(isset($_GET['id']) && $_gets[0] == 'message'){
		//return total count
		$from = mysqli_real_escape_string($conn, $_GET['id']);
		$count = "SELECT ms_id FROM messages WHERE ms_from='$from' && ms_to='$u_id' && ms_read='0'";
		$result = $conn->query($count);
		$output = $result->num_rows;
	}
}elseif($_gets[0] == 'my_new_group'){
	//NOW find user's new created group
	
	$count = "SELECT gr_key, gr_name, gr_avatar FROM groups WHERE gr_u_id='$u_id' ORDER BY gr_date DESC LIMIT 1";
	$result = $conn->query($count);
	
	while($row = $result->fetch_assoc()){
		include 'classes/Group.php';
		$group = new Group;
		$group->select($row['gr_key']);
		$output = $group->show();
	}
}
*/

//$output = '';
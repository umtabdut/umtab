<?php
session_start();

if(!isset($_SESSION['user_id'])){ return; }

$uid = $_SESSION['user_id'];

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$output = '';


$invite_to = mysqli_real_escape_string($conn, $_POST['invite_to']);
$id = mysqli_real_escape_string($conn, $_POST['id']);

if($invite_to == 'g' && !empty($id)){

	if($invite_to == 'g'){
		$sql = $conn->query("SELECT gr_key FROM groups WHERE gr_key='$id' LIMIT 1;");
		//$row = $sql->fetch_assoc();
		if($sql->num_rows == 1){
			$friends = getFriends($conn,$uid);

			$output .= '<ol>';
			foreach ($friends as $friend) {
				$output .= '<li> 09sijj'.$friend.'</li>';
			}
			$output .= '</ol>';
		}
	}
}

echo $output;



function getFriends($conn,$uid){
	$q = "SELECT fr_u_id, fr_u_id_2 FROM friends WHERE (fr_u_id='$uid' OR fr_u_id_2='$uid') && fr_accept='1'";

	$sql = $conn->query($q);

	$friends=array();
	while ($row = $sql -> fetch_assoc()) {
		$friends[] = $row['fr_u_id'] != $uid ? $row['fr_u_id'] : $row['fr_u_id_2'];
	}

	return $friends;
}
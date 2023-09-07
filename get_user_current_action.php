<?php
session_start();
if(!isset($_SESSION['user_id'])){ return; }

$output = '';
$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');

$u_id = $_SESSION['user_id'];
$id = mysqli_real_escape_string($conn, $_GET['id']);
$q = "SELECT current_action FROM users WHERE id='$id' && current_action_on='$u_id'";
//echo $q;
$result=$conn->query($q);
$row = $result->fetch_assoc();
if($result->num_rows>0){
	$output = $row['current_action'];
}
echo $output;
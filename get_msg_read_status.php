<?php
session_start();
if(!isset($_SESSION['user_id'])){ return; }

$output = 'none';
$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');

$id = mysqli_real_escape_string($conn, $_GET['id']);
$q = "SELECT ms_read from messages WHERE ms_id='$id'";
$result=$conn->query($q);
$row = $result->fetch_assoc();
if($result->num_rows > 0){
	$output = $row['ms_read'] == 1 ? 'read' : 'sent';
}
echo $output;
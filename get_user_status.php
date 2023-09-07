<?php
session_start();
if(!isset($_SESSION['user_id'])){ return; }

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');

$id = isset($_GET['id']) ? preg_replace("/[\D]/", "", $_GET['id']) : 0;
$online = 'offline';
$time = time();

if($id>0){
	$result = $conn->query("SELECT last_login FROM users WHERE id='$id'");
	$row = $result->fetch_assoc();
	if($row['last_login'] > $time){
		$online = 'online';
	}
}
echo $online;
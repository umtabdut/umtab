<?php
session_start();
if(!isset($_SESSION['user_id'])){ return; }

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');

$id = $_SESSION['user_id'];
$action = mysqli_real_escape_string($conn, $_POST['action']);
$action_on = mysqli_real_escape_string($conn, $_POST['action_on']);

$q = "UPDATE users SET current_action='$action', current_action_on='$action_on' WHERE id='$id'";
$conn->query($q);
echo $q;
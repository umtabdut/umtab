<?php
session_start();

if(!isset($_SESSION['user_id']) || !isset($_GET['color'])){ return; }

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$output = '';
$noColumn = '';
$time = time();

$id = $_SESSION['user_id'];
$color_code = mysqli_real_escape_string($conn, $_GET['color']);

$getColor = $conn->query("SELECT * FROM colors WHERE color_code='$color_code'");
if($getColor->num_rows == 0 && $color_code != '000000'){
	$setColor = $conn->query("INSERT INTO colors (color_u_id, color_code, color_date) VALUES ('$id', '$color_code', '$time')");

	$color_id = $conn->insert_id;
	
	$output = '<div class="d-flex" style="--code: #'.$color_code.';">
		#'. $color_code .'
	</div>';
}
echo $output;
<?php
session_start();

$u_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$time = time();

function non_admin_where($table)
{
	include 'includes/core/database/conn.php';
	$where = '';
	$tables = array('u', 'p', 'd');
	$wheres = array(
		"u" => "deactivate!='1' && user_delete!='1'",
		"p" => "post_approve='1' && post_remove!='1' && post_delete!='1'",
		"d" => "dl_approve='1' && dl_remove!='1' && dl_delete!='1'"
	);

	$wheres2 = array(
		"u" => "user_delete!='1'",
		"p" => "post_delete!='1'",
		"d" => "dl_delete!='1'"
	);
	
	if(in_array($table, $tables))
	{
		$where = $wheres[$table];
		
		if(isset($_SESSION['is_admin']))
		{
			require_once 'includes/classes/Admin.php';
			$admin = new Admin;
			$admin->setId($_SESSION['user_id']);
			$admin->check();
			if($admin->is_admin() && !$admin->master()){
				$where = $wheres2[$table];
			}elseif($admin->is_admin() && $admin->master()){
				$where = '';
			}
		}
	}
	return $where;
	//source in includes/functions/user-functions.php
}

$_gets = array_keys($_GET);

//id : 23	type : post action : like/dislike
$item_id = mysqli_real_escape_string($conn, htmlentities($_GET['id']));
$like_type = mysqli_real_escape_string($conn, htmlentities($_GET['action']));
$item_type = mysqli_real_escape_string($conn, htmlentities($_GET['item_type']));

$like_id=0;
$i_liked = false;
$i_disliked = false;

if(isset($_SESSION['user_id'])){ //only if logged in user

	$q = "SELECT like_id, like_type FROM likes WHERE like_u_id='$u_id' && like_item_id='$item_id' && like_item_type='$item_type'";
	$sql = $conn->query($q);
	$affect_likes = $sql->num_rows < 1 ? 'insert' : 'update';
	if($affect_likes == 'update'){
		$row = $sql->fetch_assoc();
		$like_id = $row['like_id'];
		$i_liked = ($row['like_type'] == 1);
		$i_disliked = ($row['like_type'] == 2);
	}

	$proceed = false;

	switch ($item_type)
	{
		case 'post':
			$itemQ = "SELECT post_id FROM posts WHERE post_id='$item_id'";
			$itemSql = $conn->query($itemQ);
			$proceed = ($itemSql->num_rows > 0);
			break;
		
		case 'comment':
			$itemQ = "SELECT comment_id FROM comments WHERE comment_id='$item_id'";
			$itemSql = $conn->query($itemQ);
			$proceed = ($itemSql->num_rows > 0);
			break;
		
		default:
			if($item_type == 'audio' || $item_type == 'video' || $item_type == 'image' || $item_type == 'document'){
				$itemQ = "SELECT dl_id FROM downloads WHERE dl_id='$item_id'";
				$itemSql = $conn->query($itemQ);
				$proceed = ($itemSql->num_rows > 0);
			}
	}

	if($proceed){

		if( $like_type == 'like'){
			$like_type=1;
		}elseif($like_type == 'dislike'){
			$like_type=2;
		}

		if($affect_likes=='update'){
			if($i_liked && $like_type == 1 || $i_disliked && $like_type == 2){
				$like_type=0;
			}
			$q = "UPDATE likes SET like_type='$like_type', like_last_update='$time' WHERE like_id='$like_id'";
		}
		else
		{
			$q = "INSERT INTO likes (like_u_id, like_item_id, like_item_type, like_type,like_date) VALUES ('$u_id', '$item_id', '$item_type', '$like_type', '$time')";
		}
		//affect the chages to the database
		$conn->query($q);
		unset($q);
	}

} //only if logged in user

include 'like_dislike_button.php';
echo $like_dislike_button;
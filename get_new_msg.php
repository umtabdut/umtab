<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['user_id'])){ return; }

include 'classes/Chat.php';
include 'classes/GroupChat.php';

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$output = '';
$current_action = '';
$u_id = $_SESSION['user_id'];
$time = time();
$last_login_time = $time+50;

$show = isset($_POST['show']) ? $_POST['show'] : false; // true/false

$last_id = isset($_POST['last_id']) ? mysqli_real_escape_string($conn, $_POST['last_id']) : 0;
$last_id = is_numeric($last_id) ? $last_id : 0; //valid digit number
$from = mysqli_real_escape_string($conn, $_POST['from']);
$from_type = mysqli_real_escape_string($conn, $_POST['from_type']);

if(!isset($_POST['id'])){
	//get new message and return (meaning to summarize the message to a short form) e.g 

	/*
		--text--
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		
		--to--
		Lorem ipsum dolor sit amet, consectetur
	*/
	
	if($from_type=='u'){

		// firstly check if the other user is typing to the user
		$q = "SELECT current_action FROM users WHERE current_action_on='$u_id' && id='$from' && current_action!='';";
		$sql = $conn->query($q);
		if($sql->num_rows > 0 && isset($_GET['current_action']))
		{
			$output = $sql->fetch_assoc()['current_action'];
			$output = '<span class="current-action" data-id="13" style="padding-right: .4em; color: #28ee5a; font-weight: 100;">'.$output.'</span>';
		}
		else
		{
			$only_unread = $show ? "(ms_from='$from' && ms_to='$u_id') OR (ms_from='$u_id' && ms_to='$from') " : "ms_from='$from' && ms_to='$u_id' && ms_read='0'";

			$q = "SELECT ms_id, ms_to, ms_content, ms_date, ms_read_date FROM messages WHERE $only_unread ORDER BY ms_id DESC LIMIT 1";
			$result=$conn->query($q);

			if($result->num_rows > 0)
			{
				while ($row = $result->fetch_assoc()) {
					//set the read status to one (meaning that the targeted user has readed the message)
					if($row['ms_to'] == $u_id && $show == false){
						$q = "UPDATE messages SET ms_read='1', ms_read_date='$time' WHERE ms_id='".$row['ms_id']."'";
						$conn->query($q);

						$q = "SELECT new_message FROM users WHERE id='$u_id'";
						$result = $conn->query($q);
						$rowUser = $result->fetch_assoc();
						$count = 0;
						if($rowUser['new_message']>0){
							$count = $rowUser['new_message']-1;
						}
						$q = "UPDATE users SET new_message='$count' WHERE id='$u_id';";
						$conn->query($q);
					}
					$conversation=new Chat;
					$conversation->select($row['ms_id']);
					$output .= $conversation->show($show);
				}
			}
		}
	}
	elseif($from_type=='g'){
		//UPDATE USER'S last present in groups
		$q = "SELECT gm_l_u_id FROM groups_members_last_present WHERE gm_l_g_key='$from' && gm_l_u_id='$u_id' LIMIT 1;";
		$result=$conn->query($q);
		if($result->num_rows > 0)
		{
			$q = "UPDATE groups_members_last_present SET gm_l_last_update='$last_login_time' WHERE gm_l_g_key='$from' && gm_l_u_id='$u_id';";
		}
		else
		{
			$q = "INSERT INTO groups_members_last_present (gm_l_g_key, gm_l_u_id, gm_l_date) VALUES ('$from', '$u_id', '$time');";
		}
		$result=$conn->query($q);

		$only_unread = $show ? "mm_g_key='$from'" : "mm_g_key='$from' && mm_id > $last_id";

		$q = "SELECT mm_id FROM groups_messages WHERE $only_unread ORDER BY mm_id DESC LIMIT 1";
		$result=$conn->query($q);
		if($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$conversation=new GroupChat;
			$conversation->select($row['mm_id']);
			$output .= $conversation->show($show);
		}
	}
}
else
{
	if($from_type=='u'){
		//get last unread message id and return
		$q = "SELECT ms_id FROM messages WHERE ( (ms_from='$from' && ms_to='$u_id') OR (ms_from='$u_id' && ms_to='$from')  && ms_id > $last_id ) ORDER BY ms_id DESC LIMIT 1";
		$result=$conn->query($q);
		$row = $result->fetch_assoc();
		$output = $result->num_rows > 0 ? $row['ms_id'] : $last_id;		
	}
	elseif($from_type=='g'){
		$q = "SELECT mm_id FROM groups_messages WHERE mm_g_key='$from' && mm_id > $last_id ORDER BY mm_id DESC LIMIT 1";
		$output = $q;
		$result=$conn->query($q);
		$row = $result->fetch_assoc();
		$output = $result->num_rows > 0 ? $row['mm_id'] : $last_id;
	}
}

mysqli_close($conn);
echo $output;
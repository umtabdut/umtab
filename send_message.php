<?php
session_start();

if(!isset($_SESSION['user_id'])){ return; }

include 'classes/Chat.php';
include 'classes/GroupChat.php';

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');

$q='';
$output = '';

$u_id = $_SESSION['user_id'];
$time = time();

$to = mysqli_real_escape_string($conn, $_POST['to']);
$to_type = mysqli_real_escape_string($conn, $_POST['to_type']);
$rto = mysqli_real_escape_string($conn, $_POST['rto']);
$message = mysqli_real_escape_string($conn, htmlentities($_POST['message']));

$time=time();

if(!empty(trim($_POST['message'])))
{
	//select from friends table
	$result = $conn->query("SELECT id FROM users WHERE id='$to' LIMIT 1");
	if($result->num_rows > 0 && $to_type='u')
	{
		$row = $result->fetch_assoc();
		$to = $row['id'];
	}
	
	//validate the existence of group
	$q = "SELECT gr_name FROM groups WHERE gr_key='$to' LIMIT 1";
	$result=$conn->query($q);
	if($result->num_rows > 1 && $to_type='g')
	{
		$rowGroup = $result->fetch_assoc();
		//validate the eixtence of the user in the groups_members table.
		//$q = "SELECT gm_g_key FROM groups_members WHERE gm_g_key='$to' && gm_u_id='$u_id' && gm_accept='1' && gm_leave='0' && gm_remove='0' LIMIT 1";

		$q = "SELECT gm_g_key FROM groups_members WHERE gm_g_key='$to' && gm_u_id='$u_id' && gm_leave='0' && gm_remove='0' LIMIT 1";
		//also correct it in the Group class file

		$result = $conn->query($q);
		if($result->num_rows < 1){
			$output = 'Sorry, you are no longer a member of <b>'.$rowGroup['gr_name'].'.</b>';

			//Empty targeted id to NOT allow the insertion
			$to='';
		}
	}

	if(!empty($to) && !empty($to_type)){
		
		$q='';
		//now send the message
		if($to_type=='u')
		{ //sending to user
			$q = "INSERT INTO messages (ms_from, ms_to, ms_rto, ms_content, ms_date) VALUES ('$u_id', '$to', '$rto', '$message', '$time')";
		}
		elseif($to_type=='g')
		{ //sending to group

			//get all the members an notify them about anew message
			$q = "SELECT gr_u_id FROM groups WHERE gr_u_id!='$u_id' && gr_key='$to' LIMIT 1;";
			$members_id = array();

			$result = $conn->query($q);
			if($result->num_rows > 0){
				$row = $result->fetch_assoc();
				$members_id[] = $row['gr_u_id'];
			}

			//Select distinct users from groups_members table and notify ONLY members that are not removed or leave the group
			$q = "SELECT DISTINCT gm_u_id FROM groups_members, groups_members_last_present WHERE gm_g_key='$to' && gm_leave='0' && gm_remove='0' && gm_u_id!='$u_id';";
			$result = $conn->query($q);

			while ($row = $result->fetch_assoc()) {
				array_push($members_id, $row['gm_u_id']);
			}

			$q = "SELECT id, new_group_message FROM users WHERE id IN (";
			foreach ($members_id as $member_id) {
				$q .= $member_id.', ';
			}
			$q = preg_replace('/, $/', ')', $q);
			$q .= ';';
			$sql = $conn->query($q);

			if($sql->num_rows > 0){
				$q = array();
				while ($row = $sql->fetch_assoc()) {
					array_push($q, "UPDATE users SET new_group_message='".($row['new_group_message']+1)."' WHERE id='".$row['id']."';");
				}
			}
			
			//NOW update them with new message notification

			if(is_array($q)){
				foreach ($q as $q) {
					$conn->query($q);
				}
			}
			/*include 'classes/Group.php';
			$group = new Group;
			$group->count_members=true;
			$group->select($row['gr_key']);
			*/
			$q = "INSERT INTO groups_messages (mm_u_id, mm_g_key, mm_rto, mm_content, mm_date) VALUES ('$u_id', '$to', '$rto', '$message', '$time')";
		}

		if(!empty($q))
		{
			if($conn->query($q))
			{
				if($to_type=='u')
				{
					//message sent to user not group
					$where = is_numeric($to) ? "id='$to'" : "token='$to'";
					$q = "SELECT new_message FROM users WHERE $where";
					$result = $conn->query($q);
					$rowUser = $result->fetch_assoc();
					$count = $rowUser['new_message'];
					$count++;
					
					$q = "UPDATE users SET new_message='$count' WHERE $where";
					$conn->query($q);

					if($to != $_SESSION['user_token']){
						$q = "SELECT ms_id, ms_content, ms_date, ms_read, ms_read_date FROM messages WHERE ms_from='$u_id' ORDER BY ms_id DESC LIMIT 1";
						$result=$conn->query($q);
						if($result->num_rows>0){
							while ($row = $result->fetch_assoc()) {
								$conversation=new Chat;
								$conversation->select($row['ms_id']);
								$output .= $conversation->show();
							}
						}
					}
				}
				elseif($to_type=='g')
				{
					$q = "SELECT mm_id, mm_content, mm_date FROM groups_messages WHERE mm_u_id='$u_id' ORDER BY mm_id DESC LIMIT 1";
					$result=$conn->query($q);
					if($result->num_rows>0){
						while ($row = $result->fetch_assoc()) {
							$conversation=new GroupChat;
							$conversation->select($row['mm_id']);
							$output .= $conversation->show();
						}
					}
				}
			}
			else
			{
				$output = 'Not sent'.$conn->error;
			}
		}
	}
}

echo $output;
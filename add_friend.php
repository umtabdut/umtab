<?php
session_start();

if(!isset($_SESSION['user_id'])){ return; }

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$output = '';
$noColumn = '';
$time = time();

$id = $_SESSION['user_id'];
$to = mysqli_real_escape_string($conn, $_GET['id']);
$token = mysqli_real_escape_string($conn, $_GET['token']);
$action = mysqli_real_escape_string($conn, $_GET['action']);

if(!empty($to) && is_numeric($to))
{
	//check if user exixts in the database
	$result = $conn->query("SELECT new_friend FROM users WHERE id='$id' LIMIT 1");
	$row = $result->fetch_assoc();
	$row['new_friend_add'] = $row['new_friend'] + 1;
	$row['new_friend_minus'] = $row['new_friend'] > 0 ? $row['new_friend'] - 1 : 0;
	
	$result = $conn->query("SELECT id, token, new_friend, new_accept, new_reject FROM users WHERE id='$to' LIMIT 1");
	$row_to = $result->fetch_assoc();
	$row_to['new_friend_add'] = $row_to['new_friend'] + 1;
	$row_to['new_friend_minus'] = $row_to['new_friend'] > 0 ? $row_to['new_friend'] - 1 : 0;
	
	if($action=='add'){
		$row_to['new_friend_add'] = $row_to['new_friend'] + 1;
		$row_to['new_friend_minus'] = $row_to['new_friend'] > 0 ? $row_to['new_friend'] - 1 : 0;
	}
	elseif($action=='accept' || $action=='reject'){
		$row_to['new_'.$action.'_add'] = $row_to['new_'.$action] + 1;
		$row_to['new_'.$action.'_minus'] = $row_to['new_'.$action] > 0 ? $row_to['new_'.$action] - 1 : 0;
	}
	
	//only if user exist in the database
	if($result->num_rows == 1 && ($action == 'add' || $action == 'accept' || $action == 'reject' || $action=='cancel'))
	{
		if ((empty($token) && !empty($row_to['token'])) || (!empty($token) && empty($row_to['token']))) {
			$output = "Something went wrong, try again!";
		}else{
			//check if one of the users sent request
			$q = "SELECT fr_id, fr_u_id, fr_u_id_2, fr_accept FROM friends WHERE (fr_u_id='$id' AND fr_u_id_2='$to') OR (fr_u_id='$to' AND fr_u_id_2='$id')";
			$result_f = $conn->query($q);
			$num = $result_f->num_rows;
			if($num < 1 && $action == 'add'){
				$q = "INSERT INTO friends (fr_u_id, fr_u_id_2, fr_date) VALUES ('$id', '$to', '".$time."')";
				if(!$conn->query($q)){ $output = 'Something went wrong, try again!'.$conn->error; }

				//notify the uers that someone request him to be friend
				$result = $conn->query("UPDATE users SET new_friend='".$row_to['new_friend_add']."' WHERE id='$to' LIMIT 1");
				
			}else if($num > 0 ){
				$row_f = $result_f->fetch_assoc();
				
				//make sure that the user sent the request wishing to cancel
				if($id==$row_f['fr_u_id'] && $action=='cancel'){
					//Now delete the request from the friends table
					$result = $conn->query("DELETE FROM friends WHERE fr_u_id='$id' && fr_u_id_2='$to';");
					
					//notify the user that someone accept/reject him
					$result = $conn->query("UPDATE users SET new_friend='".$row_to['new_friend_minus']."' WHERE id='$to';");
				}
				else
				{
					if(($id==$row_f['fr_u_id'] || $id==$row_f['fr_u_id_2']) && $action == 'add')
					{
						/*
							If user2 tries to add user1 and user1 aleady sent him friend request,
							proceed to accept the request
						*/
						$action = 'accept';
					}

					$noColumn = ", fr_accept='0'";
					if($action == 'accept' && $row_f['fr_accept'] == '0'){
						$noColumn = ", fr_reject='0'";
					}
					//subtract 1 from new_friend column of the current user as he accep/reject one request
					$result = $conn->query("UPDATE users SET new_friend='".$row['new_friend_minus']."' WHERE id='$id';");
					
					//notify the user that someone accept/reject him
					$result = $conn->query("UPDATE users SET new_friend='".$row_to['new_friend_add']."' WHERE id='$to';");
					
					if($action=='accept' || $action=='reject'){
						//add date action taken
						$noColumn .= ", fr_".$action."_date='".$time."'";
						$q = "UPDATE friends SET fr_".$action."='1' $noColumn WHERE fr_id = '".$row_f['fr_id'] ."';";
						if(!$conn->query($q)){ $output = 'Something went wrong, try again!'.$conn->error; }
					}
				}
			}	
		}
	}
}
echo $output;
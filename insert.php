<?php
require_once 'includes/variables/variables.define.php';

function insertPost_()
{
	require 'core/database/conn.php';
	require 'includes/array_variables.php';
	$msgSuccess = $msgFail = '';
	$postImg = $postImgError = false;
	$q = array();
	if(logged_in())
	{
		$u_id = $_SESSION['user_id'];
		$user = new User; $user->setId($u_id); $user->select();
		$admin = new Admin; $admin->setId($u_id); $admin->check();
		extract($_POST);
		if(isset($_POST['p_sbmt'])) //post
		{
			$title = mysqli_real_escape_string($conn, htmlentities($p_title));
			$content = mysqli_real_escape_string($conn, htmlentities($p_content));
			$category = mysqli_real_escape_string($conn, $category);
			if(isset($_FILES['p_image']) && !empty($_FILES['p_image']['name']))
			{
				$image = $_FILES['p_image'];
				$imageNam = $image['name'];
				$mime = $image['type'];
				$ext = explode('.', $image['name']);
				$imageExt = end($ext);
				$ext = strtolower(end($ext));
				$imageName = preg_replace('/\.'. $imageExt .'$/', '', $imageNam);
				$imageNameNew = uniqid(date('U') .'_', true) .'.'. $imageExt;
			}
			else
			{
				$image = '';
			}
			
			if(empty($title) || empty($content))
			{
				if(empty($title))
				{
					$msgFail = '<div class="alert alert-warning">Post title can\'t be empty! <br>';	
				}

				if(empty($content))
				{
					$msgFail .= 'Post main body can\'t be empty! <br>';	
				}
				$msgFail .= 'please fill '. (empty($title) && empty($content) ? 'them' : 'it') .' out and resend!</div>';
				$_SESSION['msg'] = $msgFail;
			}
			else
			{
				if(strlen($title) < 10 || strlen($content) < 20)
				{
					if(strlen($title) < 10)
					{
						$_SESSION['msg'] = '<div class="alert alert-warning">Post title can\'t be less than 10 characters! <br>';	
					}

					if(strlen($content) < 20)
					{
						$_SESSION['msg'] .= 'Post title can\'t be less than 20 characters! <br>';
					}
					$_SESSION['msg'] .= 'please add some word(s) and resend!</div>';
				}
				else
				{
					if(empty($image))
					{
						//let the user know wether his post was approved or not
						$msgSuccess = '<div class="alert alert-success">Post successiflly inserted into the database';
						$msgSuccess .= APP_PST ? '!' : '<br>But it will not be available untill Admin approved it, Please do not repost this post again!.';
						$msgSuccess .= '</div>';
						$msgFail = '<div class="alert alert-warning">Post unsuccessful <br> please try again!</div>';
					}
					else
					{
						$postImg = true;
						/*
						if($image['error'] == true)
						{
							$postImgError = true;
							$msgFail = '<div class="alert alert-warning">Post unsuccessful, because the file intended to be uploaded contained some errors <br> please check and try again!</div>';
							$_SESSION['msg'] = $msgFail;
						}
						else
						{ // }
						*/

						if(strlen($imageName) > 200)
						{
							$postImgError = true;
							$_SESSION['msg'] = '<div class="alert alert-warning">Post unsuccessful, because the file name exceed 200 characters <br> please reduce name length and try again!</div>';
						}
						else
						{
							if(!in_array($ext, $allowed_up_file['pic']['type']))
							{
								$postImgError = true;
								$_SESSION['msg'] = '<div class="alert alert-warning">Post unsuccessful, because the file format <b> not allowed </b> <br> note that only the following types are allowed! 
									<ul>';
									foreach ($allowed_up_file['pic']['type'] as $type)
									{
										$_SESSION['msg'] .= '<li>'. $type .'</li>';
									}
									$_SESSION['msg'] .= '</ul>
								</div>';
							}
							else
							{
								$image_target = 'uploads/image/post/'. $imageNameNew;
							}
						}
					}

					if($postImg == false)
					{
						$insert = "INSERT INTO posts (post_u_id, post_title, post_content, post_category, post_approve, post_date) VALUES ('$u_id', '$title', '$content', '$category', '". APP_PST ."', '". CURRENT_TIMESTAMP."');";
					}
					else
					{
						if($postImgError == true)
						{
							$_SESSION['msg'] = $msgFail;
						}
						else
						{
							//print_r($_FILES); exit();
							if(!move_uploaded_file($image['tmp_name'], $image_target))
							{
								$_SESSION['msg'] = '<div class="alert alert-warning">Post unsuccessful due to some errors! <br> <b>Tips:</b> Refresh the page and try again! </div>';
							}
							else
							{
								$insert = "INSERT INTO posts (post_u_id, post_title, post_content, post_category, post_image_name, post_image, post_mime, post_approve, post_date) VALUES ('$u_id', '$title', '$content', '$category', '$imageName', '$imageNameNew', '$mime', '". APP_PST ."', '". CURRENT_TIMESTAMP."');";
							}	
						}
					}

					if(!empty($insert))
					{
						//echo $insert; exit();
						$insertPost = $conn -> query($insert);
						if($insertPost)
						{
							$_SESSION['msg'] = $msgSuccess;
							unset($_POST);
							unset($insert);
							$select = "SELECT post_id FROM posts WHERE post_u_id='$u_id' && post_title='$title' && post_date='". CURRENT_TIMESTAMP ."' ORDER BY post_id DESC LIMIT 1";
							$select = $conn -> query($select);
							if($select -> num_rows == 1)
							{
								while ($post_id = $select -> fetch_assoc())
								{
									foreach ($user->followers() as $follower)
									{
										$qs[] = "INSERT INTO notifications (n_from, n_to, n_item_owner, n_item_id, n_item_type, n_content, n_date) VALUES ('$u_id', '$follower', '$u_id', '". $post_id['post_id'] ."', 'p', 'post', '". CURRENT_TIMESTAMP."')";
									}
								}
								foreach ($qs as $q)
								{
									//echo $q .'<br>';
									$query = $conn -> query($q);
								}
							}
							//process all
							echo '<pre>'; print_r($q); echo '</pre>'; exit();
							unset($qs);
							header("Location:". $fr );
							exit();
						}
					}
				}
			}
		}
	}
}//insertPost
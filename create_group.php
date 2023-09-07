<?php
session_start();

if(!isset($_SESSION['user_id'])){ return; }

$conn = mysqli_connect('localhost', 'root', '');
$db = mysqli_select_db($conn, 'umtab');
$output = 'Group name should NOT be empty or less than 10 characters!';

function make_avatar_group($char,$for_='u'){

	$char = strtoupper($char);
	//SOURCE IS IN
	//include 'includes/functions/users-function.php';

	$fileNameUnique = md5(uniqid(true)) .'.png';
	$fors = ['u','g'];
	
	if(!in_array($for_, $fors)){
		return;
	}
	$paths['u'] = '../wap-content/uploads/image/avatar/';
	$paths['g'] = '../wap-content/groups/avatar/';
	
	$path = $paths[$for_] . $fileNameUnique;

	$fontType = 'includes/fonts/arial.ttf';
	
	$red = rand(0,255);
	$green = rand(0,255);
	$blue = rand(0,255);
	
	$image = imagecreate(40, 40);
	imagecolorallocate($image, $red, $green, $blue);

	$textcolor = imagecolorallocate($image, 255, 255, 255);

	//imagechar(image, font, x, y, c, color);
	imagechar($image, 100, 15, 12, $char, $textcolor);
		
	// AS I AM GETTING ERROR (Invalid font filename ) WITH FUNCTION imagettftext, I prefer to user imagechar function
	//imagettftext($image, 100, 0, 55, 150, $textcolor, 'arial.ttf', $char);

	imagepng($image, $path);

	//created the image data
	//imagejpeg($image);
	//header('Content-Type: image/png');
	imagedestroy($image);
	return $fileNameUnique;
}


function checkKeysGroup($conn, $tbl, $key)
{
	$sql = $conn -> query("SELECT gr_key FROM groups");
	$keyExists = false;
	while($row = $sql -> fetch_assoc())
	{
		$keyExists = ($key == $row['gr_key']) ? true : false;
	}
	return $keyExists;
}

function generateKeyGroup($conn, $tbl, $length, $digits=false)
{
	$str = !$digits ? $_SESSION['CHARS'] :  preg_replace('/\D/', '', $_SESSION['CHARS']);
	//$output = '';		
	$output = str_shuffle($str);
	$output = substr($output, 0, $length);
	$checkKey = checkKeysGroup($conn, $tbl, $output);

	while($checkKey == true)
	{
		$output = substr($output, 0, $length);
		$checkKey = checkKeysGroup($conn, $tbl, $output);
	}

	return $output;
}

//source in general-function.php file
$gr_key = generateKeyGroup($conn, 'groups', $_SESSION['key_length']['groups']);

$id = $_SESSION['user_id'];

$gr_name = mysqli_real_escape_string($conn, $_POST['group_name']);
$time = time();

if(!empty(trim($gr_name)))
{
	//check if user created a group with given group name
	$result = $conn->query("SELECT gr_name FROM groups WHERE gr_u_id='$id' && gr_name='$gr_name' LIMIT 1");
	$row = $result->fetch_assoc();
	
	if($result->num_rows > 0 && strtolower($row['gr_name']) == strtolower($gr_name))
	{
		//while($row = $result->fetch_assoc()){}
		$output = 'You have already created a group with name: <span class="bld">'.$gr_name.'<span>!';

	}else{

		$grpNum = 20;
		$result = $conn->query("SELECT gr_name, gr_key, gr_date FROM groups WHERE gr_u_id='$id'");
		if($result->num_rows >= $grpNum){
			$output = 'Sorry, you have exceed the total number of '. $grpNum .' groups you can have.';
			$output .= '<div class="mg-t pd bd bd-rad" style="background: #fff; color: #333;">
				<span class="bld">Here they are:</span><br> <ol class="mg">';

				while($row = $result->fetch_assoc()){
					$output .= '<li>
					<div class="d-flex">
						<a class="flex" style="text-decoration: none; color: inherit; font-weight: 500;" href="'.$_SESSION['SITE_DOMAIN_NAME'].'/groups?'.$row['gr_key'].'">'.$row['gr_name'] .'</a><span class="date"> Since: &nbsp;&nbsp;'. date('d-m-Y',$row['gr_date']).'</span>
					</div>
					</li>';
				}

				//$output = preg_replace('/, $/', '', $output);

			$output .= '</ol></div>';
		}
		else
		{
			if(strlen($_POST['group_name']) >= 50){
				$output = 'Group Name too long (greater than 50 characters)';
			}
			else{
				$q = "INSERT INTO groups (gr_u_id, gr_name, gr_avatar, gr_key, gr_date) VALUES ('$id', '$gr_name', '". make_avatar_group($gr_name[0],'g') ."',  '$gr_key', '$time')";
				$output = $conn->query($q) ? '' : 'Something went wrong'. $conn->error;				
			}
		}
	}
}
echo $output;
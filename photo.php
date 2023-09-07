<?php
require '../core/database/conn.php';
if(isset($_GET['key']) && isset($_GET['table']))
{
	$table = mysqli_escape_string($conn, $_GET['table']);
	$key = mysqli_escape_string($conn, $_GET['key']);
	if($table == 'downloads')
	{
		$target = 'uploads/image/download/';
		$q = "SELECT dl_file, dl_mime FROM downloads WHERE dl_key='$key';";
		$query = $conn -> query($q);
		while($row = $query -> fetch_assoc())
		{	
			if($row['dl_file'] != '')
			{
				$target .= $row['dl_file'] ;
				$mime = $row['dl_mime'];
				$file = glob($target);
				$photo = SITE_DOMAIN_NAME .'/'. $file[0] .'?.'. mt_rand();
			}
		}

		echo $photo;
		//header("content-type:". $mime);
	}
}
<?php
$like_id=0;
$i_liked = false;
$i_disliked = false;

$likedQ = "SELECT like_id, like_u_id, like_type FROM likes WHERE like_type='1' && like_item_id='$item_id' && like_item_type='$item_type'";
$likedSql = $conn->query($likedQ);
$likedNum = $likedSql->num_rows;

$dislikeQ = "SELECT like_id, like_u_id, like_type FROM likes WHERE like_type='2' && like_item_id='$item_id' && like_item_type='$item_type'";
$dislikeSql = $conn->query($dislikeQ);
$dislikeNum = $dislikeSql->num_rows;

$likeQ = "SELECT like_id, like_u_id, like_type FROM likes WHERE like_item_id='$item_id' && like_item_type='$item_type'";
$likeSql = $conn->query($likeQ);

while ($likerows = $likeSql->fetch_assoc()) {
	if($likerows['like_u_id'] == $u_id){
		$i_liked = $likerows['like_type'] == 1 ? true : false;
		$i_disliked = $likerows['like_type'] == 2 ? true : false;
	}
}

// if $u_id == 0 => not logged in
// form_display(this,\'lb-lk-'.$item_type.$item_id.'\')
$more='';
if($u_id == 0){
	// $data_lk = 'onclick="alert(\'You must logged in before Like/Dislike!\')" title="You must logged in before Like/Dislike!"';
	// $data_dlk = 'onclick="alert(\'You must logged in before Like/Dislike!\')" title="You must logged in before Like/Dislike!"';

	$data_lk = 'onclick="modal_display(\'lb-lk-'.$item_type.$item_id.'\')" title="You must logged in before Like/Dislike!"';
	$data_dlk = 'onclick="modal_display(\'lb-lk-'.$item_type.$item_id.'\')" title="You must logged in before Like/Dislike!"';
	$more = '
	<span onclick="modal_display(\'lb-lk-'.$item_type.$item_id.'\')">More+</span>

	<div class="modal" id="lb-lk-'.$item_type.$item_id.'" style="">
		<div class="modal-container">
			<span class="close" onclick="modal_display(\'lb-lk-'.$item_type.$item_id.'\')">×</span>
			<div class="modal-content">
				<h1 style="color: var(--foreground);">Login First!</h1>
				<small class="block">tips</small>
				<div class="">
					<p>Login before Like/Dislike</p>'.BR.'
					<a class="btn btn-success" href="'.SITE_DOMAIN_NAME.'/login'.$_SESSION['DOT_PHP'].'?fr='.urlencode(CURRENT_PAGE).'">Login</a>
					&nbsp;
					<button class="btn btn-primary" onclick="modal_display(\'lb-lk-'.$item_type.$item_id.'\')">Cancel</button>
				</div>
			</div>
		</div>
	</div>';
}
else
{
	$data_lk = 'data-action="like" data-status-like="'. ($i_liked ? 0 : 1) .'"';
	$data_dlk = 'data-action="dislike" data-status-dislike="'. ($i_disliked ? 0 : 1 ) .'"';
}

$like_dislike_button = '
<span class="count-like">'. $likedNum .'</span>
<button class="btn-like '. ($i_liked ? 'btn-liked' : '' ) .'" '. $data_lk.'>Like'. ($i_liked  ? 'd': ($likedNum < 2 ? '' : 's')) .'</button>

<span class="count-dislike">'. $dislikeNum .'</span>
<button class="btn-like '. ($i_disliked ? 'btn-disliked' : '' ) .'" '. $data_dlk.'>Dislike'. ($i_disliked ? 'd' : ($dislikeNum < 2 ? '' : 's')) .'</button>'. $more;

// $like_dislike_button = '
// <div>
// 	<span class="like-dislike" data-id="'.$item_id.'" data-item="'.$item_type.'">Show</span>
// </div>
// <span id="reacts">'.
// 	$like_dislike_button
// .'</span>';

return $like_dislike_button;
<?php if(in_array(CURRENT_PAGE_NAME, ['quran', 'watch', 'audios', 'listen', 'edit-watch'])) { ?>
<!-- <script src="<?= SITE_DOMAIN_NAME ?>/includes/js/video-player.js"></script> -->
<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/media-player.js"></script>
<?php } 

if(CURRENT_PAGE_NAME != 'login' && CURRENT_PAGE_NAME != 'signup'){ ?>

	<script>
		window.load = function(){
			if((document.querySelector("#loader")) != null) {
		 	document.querySelector("#loader")document.querySelector("#loader").remove(); }
		}
	</script>

<?php } ?>

<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/jquery-3.4.0-jquery.min.js"></script>

<style>
	.lb-lk{
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%);
		background: #333;
		font-size: .8rem;
		padding: .4em;
		color: #fafafa;
		border-radius: 6px;
	}
	[data-hide='1']{
		background: red !important;
		width: 0 !important;
		height: 0 !important;
		overflow: hidden;
		animation: hideAnim 2s linear infinite;
	}
	@keyframes hideAnim{
		100%{
			width: 0 !important;
			height: 0 !important;
			overflow: hidden;
		}
	}
</style>

<script>

//remove alert box
setInterval(function(){
	var toBeRemoved = document.querySelector(".alert");
	if(toBeRemoved != null){ toBeRemoved.remove(); }
}, 10000);

// data-hide-after
// setInterval(function(){
// 	var toHide = document.querySelector("[data-hide-after='3']");
// 	if(toHide != null ){
// 		// alert(toHide);
// 		var toggle = toHide.getAttribute('data-hide');
// 		if(toggle==null || toggle=='0'){
// 			toHide.dataset.hide = '1';
// 		}
// 		toHide.innerHTML += '<p>hide</p>';
// 	}
// }, 3000);

// data-hide-after
// setInterval(function(){
// 	var toHide = document.querySelector("[data-hide='1']");
// 	if(toHide != null ){
// 		// toHide.style.display='none';
// 		toHide.style.background='red';
// 		toHide.remove();
// 		toHide.innerHTML += '<p class="bd">hided</p>';
// 	}
// }, 1000);

const wrapper = document.querySelector(".wrapper");
const footer = document.querySelector('footer.footer-main');	
//reacts
wrapper.onclick = function(e){
	//e.preventDefault();
	var el = e.target;

	var elParent = el.parentNode;
	var elParentClass = elParent.getAttribute("class");
	var elGrandParent = elParent.parentNode;
	var elGrandParentClass = elParent.parentNode.getAttribute("class");
	//The user reacts to something (post/comment/audio e.t.c)
	if(elGrandParent.getAttribute('data-reacts') != null)
	{
		var action = el.getAttribute('data-action');
		var item_id = elGrandParent.getAttribute('data-id')
		var item_type = elGrandParent.getAttribute('data-reacts');
		if(action == 'like' || action == 'dislike'){
			$.ajax({
				url: 'includes/reacts.php?id='+item_id+'&item_type='+item_type+'&action='+action,
				success: function(response){
					const reacts = document.querySelectorAll('[data-reacts]');
					reacts.forEach( pnt => {
						if(pnt.getAttribute('data-id') == item_id && pnt.getAttribute('data-reacts') == item_type){
							//Update the current form to the actual status of all the occurence of the item in the page
							pnt.querySelector('#reacts').innerHTML = response;
						}
					});
				}
			});
		}
	}
	else
	{
		if(el.getAttribute('data-add-friend') != null){ // is_add_friend_button
			//el.onclick = add_friend;
			el.onclick = add_friend(el);
		}
		else if(el.getAttribute('data-invite') != null)
		{
			el.onclick = invitation(el);
		}
		else if(el.getAttribute('class') == 'copy'){
			var toBeCopied = document.createElement('textarea');
			var copiedcontent='copied';
			var whatCopied='';
			if(elParent.localName == 'pre'){
				whatCopied = 'Code';
				copiedcontent = elParent.textContent;
			}else if(elParent.classList[0] == 'post-content'){
				whatCopied = 'Article';
				copiedcontent = elParent.textContent;

			}
			else{
				console.log(e);
			}

			toBeCopied.value = copiedcontent;
			elParent.appendChild(toBeCopied);
			toBeCopied.select();
			document.execCommand('copy');
			copiedcontent='';
			toBeCopied.remove();
			elParent.innerHTML += '<div class="alert alert-fast alert-working">'+ whatCopied+' copied!</div>';
		}
		else
		{
			if(el.getAttribute('data-action') != null)
			{
				let ac = el.getAttribute('data-action');
				let role = el.getAttribute('data-item');
				if(ac == role+'_approve' || ac == role+'_remove' || ac == role+'_delete')
				{
					// required datas { url, fm, data {role, cName, cVal} }
					let url = role == 'user' ? 'action-on-user.inc.php' : 'action-on-post.inc.php';
					let datas = {
						parent: el.getAttribute('data-parent'),
						fm: document.querySelector('#'+el.getAttribute('data-parent')),
						url: url,
						data: {
							id: el.getAttribute('data-id'),
							idx: el.getAttribute('data-idx'),
							cName: ac,
							cVal: el.getAttribute('data-value'),
							role: role,
							update: 'update'
						}
					};

					take_action(datas);
				}
			}
		}
	}
}

setInterval(function(){
	//Add copy tag for pre tags, just to allow user copy source codes
	var pres = document.querySelectorAll('pre');
	pres.forEach( el => {
		if(el.querySelector('span.copy') == null){
			el.style.position = 'relative';
			var span = document.createElement('span');
			span.classList = 'copy';
			el.appendChild(span);
		}
	});
}, 1000);

setInterval(function(){
	//find and remove after 5s
	var removeAfter = document.querySelectorAll("[data-remove-after]");
	removeAfter.forEach( el => {
		el.remove();
	});
}, 5000);

setInterval(function(){
	var toBeRemoved = document.querySelector(".alert-fast");
	if(toBeRemoved != null){ toBeRemoved.remove(); }
}, 1500);

document.addEventListener('submit', e => {
	var fm = e.target;
	var msg = fm.parentNode.querySelector('.msg');
	var form_name = fm.name;
	
	if(form_name == 'login' || form_name == 'group' || form_name == 'u_post')
	{
		e.preventDefault();
	}

	if(form_name == 'login'){
		e.preventDefault();
		$.ajax({
			url: 'process.user.php',
			type: 'post',
			data: { login: '', username: fm.username.value, password: fm.password.value },
			success: function (response){
				if(response.trim()==''){
					msg.innerHTML = msg_('Login successfully!', 's');
					redirect(fm.fr.value);
					fm.remove();
				}else{
					msg.innerHTML = msg_(response,'f');
				}
			}
		});
	}
	else if(form_name == 'group'){
		e.preventDefault();
		$.ajax({
			url: 'includes/create_group.php',
			type: 'post',
			data: {
				group_name: fm.group_name.value,
			},
			success: function(response){
				if(response == ''){
					var group_num = document.querySelector('#group_num');
					var my_groups = document.querySelector('#my_groups');
					var group_number = parseInt(group_num.textContent);
					group_num.textContent = group_number + 1;
					
					//NOW get my new group and show
					$.ajax({
						url: 'includes/there_is_new.php?my_new_group',
						success: function(new_group){
							my_groups.innerHTML += new_group;		
						}
					});
					
					msg.innerHTML = msg_('Group <span class="bld">'+fm.group_name.value+'</span> is  successfully created.','s');
					fm.group_name.value='';
				}
				else{
					msg.innerHTML = msg_(response,'f');
				}
			}
		});
	}
	else if(form_name == 'u_post'){
		e.preventDefault();
		// console.log(fm);
		var mnp = document.querySelector("#my_new_post");
		var mnpr = document.querySelector("#my_new_post_remove");
		var insert = true;
		var data = {
			_to: fm._to.value,
			p_title: fm.p_title.value,
			p_content: fm.p_content.value,
			fr: fm.fr.value,
			// _image: URL.createDataUrl(p_image),
			category: fm.category.value,
			p_sbmt: 'p_sbmt'
		}
		//temporarily hide the post form as the user send the request 
		modal_display('postForm');
		if(data.p_title.length < 10 || data.p_content.length < 20){
			mnpr.innerHTML += '<div class="alert alert-danger">Post title/content too short!</div>';
			insert = false;
		}
		if(insert){
			$.ajax({
				url: 'insert.php',
				type: 'post',
				data: data,
				beforeSend: function(){
					mnpr.innerHTML += '<div class="alert alert-working">Posting your article....</div>';
				},
				success: function(response){
					//reset the values to empty
					fm.p_title.value='';
					fm.p_content.value='';
					mnpr.innerHTML = '';
					var mnpcontent = mnp.innerHTML;
					mnp.innerHTML = response + mnpcontent;
				}		
			});
		}
	}
	else if(form_name == 'comment'){
		e.preventDefault();
		var insert = true;

		var data = {
			item_id: fm.item_id.value,
			item_type: fm.item_type.value,
			item_owner_id: fm.item_owner_id.value,
			comment_content: fm.comment_content.value,
			comment_sbmt: 'comment'
		}
		if(data.comment_content.length < 3){
			fm.innerHTML += '<div class="alert alert-danger">Comment too short!</div>';
			insert = false;
		}

		if(insert){
			$.ajax({
				url: 'insert.php',
				type: 'post',
				data: data,
				beforeSend: function(){
					fm.innerHTML += '<div class="alert alert-fast alert-working">Adding your coment...</div>';
				},
				success: function(response){
					//hide back the form as it is replying comment form
					if(data.item_type == 'comment'){
						fm.parentNode.style.display = 'none';
					}
					
					//reset the values to empty
					var add_comment_to = document.querySelectorAll('[data-'+fm.item_type.value+']');
					add_comment_to.forEach( comment_cnt => {
						if(comment_cnt.getAttribute('data-'+fm.item_type.value) == fm.item_id.value){
							var comments_container = comment_cnt.querySelector('.comments');
							if(comments_container == null){
								/*if there is no comments container exists in the comment container,
								then create and add to it*/
								comments_container = document.createElement('div');
								comments_container.classList ='comments';
								comments_container.innerHTML = response;
								comment_cnt.appendChild(comments_container);
							}
							else{
								comments_container.innerHTML += response;
							}
						}
					});
					fm.comment_content.value='';
				}
			});
		}
		//alert("submitting "+ form_name);
	}
	else{
		//console.log(e);
		//var fd = new FormData(fm);
		// var nM = 'Umar Tahir Bako';
		// fd.append({umar: nM});
		console.log(fm);
		//alert("submitting "+ form_name);
	}
});

const msg_ = function(str, f='f'){
	return '<span class="msg'+f.toUpperCase()+'">'+str+'</span>';
}

function redirect(url){
	if(url == ''){
		url=document.referrer;
		if(url== ''){
			url = 'http://www.umtab.com';		
		}
	}
	var go = setTimeout(function(){window.location = url}, 2000, true);
}
</script>

<?php if(logged_in()){
	// admins action on user/post
	include 'includes/js/action-on-user-post.js.php';
?>

<script>

	function update_current_action(a,on)
	{
		//set current action to none
		$.ajax({
			url: 'includes/update_current_action.php',
			type: 'post',
			data: {
				action: a,
				action_on: on
			}
		});
	}
	update_current_action('','');

	function msg_action(id, action){
		document.querySelector('#chat_dail').remove();
		
		if(action == 'reply'){
			form_chat.rto.value = id;
			var str = el.querySelector('p').innerHTML;
			sstr = str.slice(0,50);
		
			html = '<div class="remove-after-chat"> <span class="close" onclick="cancel_(\'reply\')"> &times; </span> <span style="font-size: 1em; font-weight: bold;">Reply:</span> <span>'+sstr+'<span></div>';
			document.querySelector('div#rtotxt').innerHTML = html;
		}
	}

	function cancel_(type){
		if(type == 'reply'){
			form_chat.rto.value = 0;
			document.querySelector('div#rtotxt').innerHTML = '';
		}
	}

</script>

<?php if(CURRENT_PAGE_NAME != 'login' && CURRENT_PAGE_NAME != 'signup') { ?>

	<script>
		const new_message = document.querySelector('#newMessage');
		const new_notification = document.querySelector('#newNotification');
		const new_friend = document.querySelector('#newFriend');
		const new_group_message = document.querySelector('#newGroup');
		const notify = document.querySelector('#notify');
		const onofLine = document.querySelector('[data-friends-online-offline]');

		function updateUserStatus()
		{

			if(onofLine != null)
			{
				$.ajax({
					url: 'inc.online-ofline-user.php?id='+ onofLine.getAttribute('data-friends-online-offline'),
					success: function(res){
						onofLine.innerHTML = res;
						// console.log(res)
					}
				})
			}
			// console.log('I have runned');
			$.ajax({
				url: 'includes/update_status.php',
				success: function (obj){
					console.log(obj.new_from_update_status+' -- obj');

					new_message.dataset.newMessage = obj.new_message.new;
					new_message.innerHTML = obj.new_message.value;

					new_notification.dataset.newNotification = obj.new_notification.new;
					new_notification.innerHTML = obj.new_notification.value;

					new_friend.dataset.newFriend = obj.new_friend.new;
					new_friend.innerHTML = obj.new_friend.value;

					new_group_message.dataset.newGroupMessage = obj.new_group_message.new;
					new_group_message.innerHTML = obj.new_group_message.value;
				}
			});
		}
		setInterval(updateUserStatus, 3000);
	</script>

<?php } //if not groups page 
?>

<script>
	
	//Update users online status every 5secs
	// var i=0;
	setInterval(function(){
		const usersId = document.querySelectorAll('[data-online]');
		usersId.forEach( el => {
			$.ajax({
				type: 'get',
				url: 'includes/get_user_status.php?id='+el.getAttribute('data-id'),
				success: function (status){
					el.dataset.online = status;
				}
			});
		});
	}, 5000);

	function add_friend(el)
	{
		var addFriends = document.querySelectorAll(".add-friend");
		//var el = e.target;
		var id = el.getAttribute('data-id');
		var token = el.getAttribute('data-token');
		var action = el.getAttribute('data-add-friend');
		var cancel_request = false;

		if(el.getAttribute('data-cancel') != null){
			cancel_request=true;
		}

		$.ajax({
			type: 'get',
			url: 'includes/add_friend.php',
			data: {
				id: id,
				token: token,
				action: action
			},
			success: function (response){
				if(response == ''){
					if(action == 'add')
					{
						addFriends.forEach( e => {
							if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'add'){
								var prnt = el.parentNode;
								var prnt2 = e.parentNode;
								if(prnt == null){
									prnt=prnt2;
								}
								prnt.innerHTML = '<button class="btn add-friend" data-add-friend="sent">Request Sent!</button>';
								if(cancel_request){
									prnt.innerHTML += ' <button class="btn add-friend" data-add-friend="cancel" data-id="'+id+'" data-token="'+token+'">Cancel</button>';
								}
							}
						});
						
					}else if(action == 'cancel'){
						addFriends.forEach( e => {
							if(e.getAttribute('data-id') == id){
								var prnt = el.parentNode;
								var prnt2 = e.parentNode;
								if(prnt == null){
									prnt=prnt2;
								}

								prnt.innerHTML = '<button class="btn add-friend" data-add-friend="add" data-id="'+id+'" data-token="'+token+'">Add Friend</button>';
								wrapper.innerHTML += '<span class="alert alert-success alert-fast">Request Canceled!</span>';
								//el.remove();
								//e.remove();
							}
						});
					}else if(action == 'accept'){
						// el.dataset.addFriend="accepted";
						// el.innerHTML = 'Request Accepted!';
						addFriends.forEach( e => {
							if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'accept'){
								e.dataset.addFriend="accepted";
								e.dataset.removeAfter="5";
								e.innerHTML = 'Request Accepted!';
							}else if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'reject'){
								e.remove();
							}
						});
					}else if(action == 'reject'){
						addFriends.forEach( e => {
							if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'reject'){
								e.dataset.addFriend="rejected";
								e.innerHTML = 'Request Rejected!';
							}else if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'accept'){
								e.remove();
							}
						});
					}

				} else{
					alert(response);
				}
			}
		});
	}

	function invitation(el){
		var invites = document.querySelectorAll(".invite");
		//var el = e.target;
		var id = el.getAttribute('data-key');
		var invite_to = el.getAttribute('data-invite');

		$.ajax({
			type: 'post',
			url: 'includes/invitation.php',
			data: { id: id, invite_to: invite_to},
			success: function(response){
				//wrapper.innerHTML += response;
				//alert(response);
				console.log(response);
			}
		});
	}
</script>
<?php } if(CURRENT_PAGE_NAME == 'chat' || CURRENT_PAGE_NAME == 'groups' || CURRENT_PAGE_NAME == 'friends') {?>
<script>
	
	var chats = document.querySelector('#chats'); //chats container
	scrollTo(chats, 'bottom');

	var form_chat = document.querySelector('#form_chat');
	
	if(form_chat!=null){
		
		form_chat.message.focus();

		form_chat.querySelector("#msg_attachment").addEventListener("change", function(){
			//console.log(this.value);
			var fmDt = new FormData();
			fmDt.append('fff', 'sddd');
			console.log(fmDt);
			$.ajax({
				url: 'send_attachment.php',
				type: 'post',
				data: fmDt,
				success: function(text){ console.log(text); }
			});
			chats.innerHTML += '<br><embed src="'+this.value+'" width="100" height="100"></embed>'+this.value;
		});
		
		form_chat.sbmt_chat.addEventListener("click", function(e){
			e.preventDefault();
			$.ajax({
				url: 'includes/send_message.php',
				type: 'post',
				data: {
					message: form_chat.message.value,
					to: form_chat.to.value,
					to_type: form_chat.to_type.value,
					rto: form_chat.rto.value
				},
				success: function (response){
					chats.innerHTML += response;
					form_chat.message.value='';
					update_current_action('','');
					form_chat.rto.value=0;

					//Get and update last chat id
					var current_last_id = chats.getAttribute('data-last-id');
					
					//make it vali number
					current_last_id = parseInt(current_last_id);
					
					current_last_id++;
					chats.dataset.lastId =  current_last_id;
					current_last_id=0; //restore value to zero
					
					console.log(chats);

// 					scrollHeight: 5374
// 					scrollLeft: 0					​
// 					scrollLeftMax: 0
// 					​scrollTop: 4897
// 					​scrollTopMax: 4988
// 					​scrollWidth: 525
// ​

// 					scrollHeight: 5464
// 					scrollLeft: 0
// 					scrollLeftMax: 0
// 					scrollTop: 5078
// 					scrollTopMax: 5078
// 					scrollWidth: 377
					// scroll to the bottom of chats container
					scrollTo(chats, 'bottom');
					
					form_chat.message.focus();
					if(document.querySelector('.remove-after-chat') != null){
						document.querySelector('.remove-after-chat').remove();
					}
				}
			});
		});
	}
	
	if(chats!=null){
		chats.addEventListener('click', e => {
			el = e.target;
			var id = 0;
			var html = '';
			var parent = '';

			if(el.getAttribute('data-id') == null){
				id = el.parentNode.getAttribute('data-id');
				parent = el.parentNode;
				if(id == null){
					id = parent.parentNode.getAttribute('data-id');
					//parent = parent.parentNode;
					//alert(parent.target)
				}
			}
			else
			{
				id = el.getAttribute('data-id');
				parent = el;
			}
			if(id>0){
				//remove all diallogs
				var dails = document.querySelectorAll('#chat_dail');
				dails.forEach( dl => {
					if(dl.getAttribute('data-id') != id){
						dl.remove();
					}
				});
				
				var dd = parent.querySelector('#chat_dail');
				if(dd == null){
					var dd = '<div id="chat_dail" data-id="'+id+'">	<span onclick="msg_action('+id+',\'reply\')">Reply</span>	</div>';
					parent.innerHTML += dd;
				}else{
					dd.remove();
				}
			}
		});
	}

	function get_new_message(){
		const usersChats = document.querySelector('.user-chats');
		const usersActions = document.querySelectorAll('.current-action');
		const usersLastMsg = document.querySelectorAll('.user-last-msg');
		const groupsLastMsg = document.querySelectorAll('.group-last-msg');

		if(chats!=null){

			if(form_chat.message.value.length > 0){
				update_current_action('typing...',form_chat.to.value);
			}else{ update_current_action('',''); }

			$.ajax({
				url: 'includes/get_new_msg.php',
				type: 'post',
				data: {
					last_id: chats.getAttribute('data-last-id'),
					from: form_chat.to.value,
					from_type: form_chat.to_type.value
				},
				success: function (response){
					if(response != '')
					{
						chats.innerHTML += response;
						scrollTo(chats, 'bottom');
						
						if(document.querySelector('.remove-after-chat') != null){
							document.querySelector('.remove-after-chat').remove();
						}
					}
				}
			});
		}
		
		if(usersActions!=null){
			usersActions.forEach( el => {
				var id = el.getAttribute("data-id");
				var msgCount = document.querySelector('[data-has-new-msg="'+id+'"]')
				$.ajax({
					type : 'get',
					url: 'includes/get_user_current_action.php?id='+id,
					success: function (status){
						el.innerHTML=status;
					}
				});

				$.ajax({
					type: 'get',
					url: 'includes/there_is_new.php?message&id='+id,
					success: function (count){
						if(count > 0){
							msgCount.innerHTML = count;
							msgCount.style.background = '#555';
							msgCount.style.color = '#ffff';
						}
						else{
							msgCount.innerHTML = '';
							msgCount.style.background = 'transparent';
							msgCount.style.color = 'transparent';
						}
					}
				});
			});
		}

		if(usersLastMsg!=null){
			usersLastMsg.forEach( el => {
				var id = el.getAttribute("data-id");
				$.ajax({
					url: 'includes/get_new_msg.php?current_action',
					type: 'post',
					data: {
						from: id,
						from_type: 'u',
						show: true
					},
					success: function (response){
						//make sure to have some text before updating the msg handler
						if(response != '') { el.innerHTML = response; }
					}
				});
			});
		}

		if(groupsLastMsg!=null){
			groupsLastMsg.forEach( el => {
				var id = el.getAttribute("data-id");
				$.ajax({
					url: 'includes/get_new_msg.php',
					type: 'post',
					data: {
						from: id,
						from_type: 'g',
						show: true
					},
					success: function (response){
						el.innerHTML = response;
					}
				});
			});
		}

		//update read status of my sent messages
		const myMsgs = document.querySelectorAll('.chat-me');
		myMsgs.forEach( el => {
			$.ajax({
				type: 'get',
				url: 'includes/get_msg_read_status.php?id='+el.getAttribute("data-id"),
				success: function (status){
					el.dataset.readStatus = status;
					
				}
			});
		});
	}
	setInterval(get_new_message, 2000);

</script>

<?php 
//chat page and group pages
} 

if( CURRENT_PAGE_NAME == 'groups'){ ?>
<script>

	const jGroup = document.querySelectorAll('[data-join]');
	jGroup.forEach( ele => {
		
		ele.onclick = function(e){
			var el  = e.target;
			var join = el.getAttribute('data-join');
			var gKey = el.getAttribute('data-key');
			var elJoin = 'join';
			var elClass = 'btn btn-default';
			var elText = 'Join Group';
			
			if(join == 'join'){
				elJoin = 'leave';
				elClass = 'btn btn-primary';
				elText = 'Cancel Request';
			}
			if(join == 'join' || join == 'leave'){
				$.ajax({
					url: 'group_join.php?key='+gKey+'&join='+join,
					success: function(response){
						if(response.trim()==''){
							var jGroups = document.querySelectorAll('[data-join]');
							jGroups.forEach( btn => {
								if(btn.getAttribute('data-key') == gKey && btn.getAttribute('data-join') == join){
									btn.innerHTML=elText;
									btn.classList=elClass;
									btn.dataset.join=elJoin;									
								}
							});

						}else{
							console.log(response);
						}
					}
				});
			}
			else{
				console.log('ssss');
			}			
		}
	});

</script>
<?php if(count($_gets) > 0){ ?>
<script>
	
	const content = document.querySelector("#content");
	function get_group_(gKey,tab,page=0){
		if(tab != ''){
			$.ajax({
				url: 'get_group.php?key='+gKey+'&tab='+tab+'&page='+page,
				beforeLoad: function(){
					content.innerHTML = 'Content loading...';
				},
				success: function (response){
					topNavs.dataset.tab = tab;
					topNavs.dataset.page = 0;
					content.innerHTML = response;
					cTab = tab;
					cPage = page;
					update_pagination();
				}
			});
		}
	}

	//var page = topNavs.getAttribute('data-page');
	var update_pagination = function(){

		const pagination = content.querySelectorAll('.pagination a');
		cTab = topNavs.getAttribute('data-tab');
		//console.log(pagination)
		var tabs = topNavs.querySelectorAll('div[data-tab]');
		tabs.forEach( el => {
			if(el.getAttribute('data-tab') == cTab){
				el.classList = 'active';
			}else{
				el.classList = '';
			}
		});

		pagination.forEach( el => {
			var tPage=0;
			el.onclick = function(e){
				tPage = el.getAttribute('data-page');
				console.log(tPage);
				console.log(gKey);
				console.log(cTab);
				get_group_(gKey,cTab,tPage);
			}
		});
	};

</script>

<?php
}

//groups page
} ?>

	<!-- <link rel="stylesheet" type="text/css" href="example.css"> -->
	<!-- <script src="example.js"></script> -->

		</div> <!-- wrapper -->
	</body>
</html>

<?php die;

function getResources($conn){
	$sql = $conn->query("SELECT * FROM states WHERE 1 ORDER BY st_name;");
	$states = array();
	while ($row = $sql->fetch_assoc()) {
		$states[$row['st_id']] = $row['st_name'];
	}
	
	$sql = $conn->query("SELECT * FROM lga WHERE 1 ORDER BY lga_state, lga_name;");
	$lga = array();
	while ($row = $sql->fetch_assoc()) {
		$lga[$row['lga_id']] = ['lga_name' => $row['lga_name'], 'lga_state' => $row['lga_state']];
	}

	return ['states' => $states, 'lga' => $lga];
}


function addForm($conn,$type){
	extract($_POST);
	$exist=false;
	if(isset($_POST['add']) && in_array($type, ['lga', 'states']) && strlen(trim($value)) > 2)
	{
		if($type == 'lga')
		{
			$q = "SELECT lga_name FROM lga WHERE lga_name='".strtolower(trim($value))."' && lga_state='".$value2."';";
			$insert = "INSERT INTO $type (lga_name, lga_state) VALUES ('".sanitize($conn, $value)."', '".sanitize($conn, $value2)."')";
		}
		elseif($type == 'states')
		{
			$q = "SELECT st_name FROM states WHERE st_name='".strtolower(trim($value))."';";
			$insert = "INSERT INTO $type (st_name) VALUES ('".sanitize($conn, $value)."')";
		}

		$sql = $conn->query($q);
		$exist = $sql->num_rows > 0;

		if($exist){ ?>
			<script>
				alert('Already exists');
			</script>
		<?php } elseif($conn->query($insert)){ unset($_POST); ?>
			<script>
				alert('Data saved successfull');
			</script>
		<?php }
		// echo $q; die;
	}
	
	$fm=$input2='';
	if($type == 'lga'){
		$input2 = '<select name="value2">
			<option>Select State</option>';
		foreach ($_SESSION['res']['states'] as $sts_id  => $sts_name ){
			$input2 .= '<option value="'.$sts_id.'">'.ucfirst($sts_name).'</option>';
		}
		$input2 .= '</select>';
	}

	$fm .= '<div>
		<h2>Add '. $type.'</h2>
		<form method="post">
			'.($type == 'staffs' ? '' : '<input name="value" value="'.(isset($_POST['value']) ? $_POST['value'] : '').'" placeholder="Enter '.$type.' here" autofocus>' ) 
			.$input2.'
			<button name="add" value="'.$type.'">Add</button>
		</form>
	</div>';

	return $fm;
}
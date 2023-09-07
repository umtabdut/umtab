

<?php if(CURRENT_PAGE_NAME == 'watch' || CURRENT_PAGE_NAME == 'listen'){ ?>
<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/video-player.js"></script>
<?php } ?>

<script src="<?= SITE_DOMAIN_NAME ?>/includes/js/jquery-3.4.0-jquery.min.js"></script>
<script>
	const chats = document.querySelector('#chats'); //chats container
	
	document.addEventListener('submit', e => {
		e.preventDefault();
		var form = e.target;
		console.log(form)
		var msg = form.parentNode.querySelector('.msg');

		var form_name = form.name;

		if(form_name == 'login'){
			$.ajax({
				url: 'process.user.php',
				type: 'post',
				data: { login: '', username: form.username.value, password: form.password.value },
				success: function (response){
					if(response.trim()==''){
						msg.innerHTML = msg_('Login successfully!', 's');
						redirect(form.fr.value);
						form.remove();
					}else{
						msg.innerHTML = msg_(response,'f');
					}
				}
			});
		}
		else if(form_name == 'group'){

			$.ajax({
				url: 'includes/create_group.php',
				type: 'post',
				data: {
					group_name: form.group_name.value,
				},
				success: function(response){
					if(response == ''){
						msg.innerHTML = msg_('Group <span class="bld">'+form.group_name.value+'</span> is  successfully created.','s');
						form.group_name.value='';
					}
					else{
						msg.innerHTML = msg_(response,'f');
					}
				}
			});
		}else if(form_name=='form_chat'){
			//var fd = new formData();
			//console.log(fd);
			console.log(form);
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
					document.querySelector('.remove-after-chat').remove();
				}
			});
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
	var go = setInterval(function(){window.location = url}, 2000, true);
}
</script>

<?php if(logged_in()){ ?> 

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

<?php if(CURRENT_PAGE_NAME != 'groups') { ?>

<script>
	const newNotif = document.querySelector('#newNotification');
	const newMsg = document.querySelector('#newMessage');
	const newFrd = document.querySelector('#newFriend');

	function updateUserStatus()
	{
		$.ajax({
			url: 'includes/update_status.php'
		});
		
		$.ajax({
			url: 'includes/there_is_new.php?notification',
			success: function (response){
				newNotif.dataset.newNotification=response;
			}
		});
		$.ajax({
			url: 'includes/there_is_new.php?notification&count',
			success: function (count){
				if(count > 0){
					newNotif.innerHTML = count;
				}
			}
		});
		
		$.ajax({
			url: 'includes/there_is_new.php?message',
			success: function (response){
				newMsg.dataset.newMessage=response;
			}
		});
		$.ajax({
			url: 'includes/there_is_new.php?message&count',
			success: function (count){
				if(count > 0){
					newMsg.innerHTML = count;
				}
			}
		});
		
		$.ajax({
			url: 'includes/there_is_new.php?friend',
			success: function (response){
				newFrd.dataset.newFriend=response;
			}
		});
		$.ajax({
			url: 'includes/there_is_new.php?friend&count',
			success: function (count){
				if(count > 0){
					newFrd.innerHTML = count;
				}
			}
		});

		const usersId = document.querySelectorAll('.online-status');
		usersId.forEach( el => {
			$.ajax({
				type: 'get',
				url: 'includes/get_user_status.php?id='+el.getAttribute('data-id'),
				success: function (status){
					el.dataset.online = status;
				}
			});
		});
	}
	setInterval(updateUserStatus, 3000);
</script>

<?php

} //if not groups page 
?>

<script>

	const addFriends = document.querySelectorAll(".add-friend");
	addFriends.forEach( el => {
		el.addEventListener('click', function(){
			var id = el.getAttribute('data-id');
			var token = el.getAttribute('data-token');
			var action = el.getAttribute('data-add-friend');
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
							el.dataset.addFriend="sent";
							el.innerHTML = 'Request Sent!';
						}else if(action == 'accept')
						
						{
							el.dataset.addFriend="accepted";
							el.innerHTML = 'Request Accepted!';
							addFriends.forEach( e => {
								if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'reject'){
									e.style.display = 'none';
								}
							});
						}
						else if(action == 'reject')
						{
							el.dataset.addFriend="rejected";
							el.innerHTML = 'Request Rejected!';
							addFriends.forEach( e => {
								if(e.getAttribute('data-id') == id && e.getAttribute('data-add-friend') == 'accept'){
									e.style.display = 'none';
								}
							});
						}

					} else{
						alert(response);
					}
				}
			});
		});
	});

</script>
<?php } if(CURRENT_PAGE_NAME == 'chat' OR (CURRENT_PAGE_NAME == 'groups' && count($_gets) > 0)) { ?> 

<script>
	
	const form_chat = document.querySelector('#form_chat');

	form_chat.message.addEventListener("keydown", function(e){
		if(e.value != ''){
			update_current_action('typing...',form_chat.to.value);
		}else{ update_current_action('',form_chat.to.value); }
	});

	form_chat.sbmt_chat.addEventListener("click", function(e){
		e.preventDefault();
		
	});
	
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

	function get_new_message(){
		const usersActions = document.querySelectorAll('.current-action');
		
		chat_last_id = chats.getAttribute('data-last-id'),
		//chats.dataset.lastChat=
		$.ajax({
			url: 'includes/get_new_msg.php',
			type: 'post',
			data: {
				last_id: chat_last_id,
				from: form_chat.to.value,
				from_type: form_chat.to_type.value
			},
			success: function (response){
				chats.innerHTML += response;
				if(response != ''){ document.querySelector('.remove-after-chat').remove();}
			}
		});

		$.ajax({
			url: 'includes/get_new_msg.php',
			type: 'post',
			data: {
				id: '',
				last_id: chat_last_id,
				from: form_chat.to.value,
				from_type: form_chat.to_type.value
			},
			success: function (last_id){ chats.dataset.lastId = last_id; }
		});

		usersActions.forEach( el => {
			var id = el.getAttribute("data-id");
			var msgCount = document.querySelector('[data-has-new-msg="'+id+'"]')
			$.ajax({
				type : 'get',
				url: 'includes/get_user_current_action.php?id='+id ,
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
	setInterval(get_new_message, 3000);

	//update read status of my sent messages
	setInterval(function (){
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
	}, 3500);

</script>

<?php 
//chat page
} elseif( CURRENT_PAGE_NAME == 'groups'){ ?>
<script>
	
	const topNavs = document.querySelector(".group-counts");
	const gKey = topNavs.getAttribute('data-key');
	var cTab = topNavs.getAttribute('data-tab');
	var cPage = topNavs.getAttribute('data-page');
	const content = document.querySelector("#content");
	
	function dd (topNavs) {
		console.log(topNavs);
	}
	dd();
	
	const jGroup = document.querySelector('[data-join]');
	jGroup.onclick = function(e){
		var el  = e.target;
		var join = el.getAttribute('data-join');
		var elClass = 'btn btn-default';
		var elText = 'Rejoin Group';
		var elData = 'join';
		
		console.log(el);
		
		if(join == 'join'){
			elClass = 'btn btn-warning';
			elText = 'Leave Group';
			elData = 'leave';
		}
		if(join == 'join' || join == 'leave'){
			$.ajax({
				url: 'group_join.php?key='+gKey+'&join='+join,
				success: function(response){
					if(response.trim()==''){
						jGroup.innerHTML=elText;
						jGroup.classList=elClass;
						jGroup.dataset.join=elData;
					}else{
						content.innerHTML = response;
					}
				}
			});
		}
	}

	topNavs.onclick = function(e){
		var el = e.target;
		console.log(e);
		var tab = el.getAttribute('data-tab');
		if(tab.trim()==''){
			el = el.parentNode;
			tab = el.getAttribute('data-tab');
		}
		if(tab.trim()!='')
		{
			get_group_(gKey,tab);
		}
	};

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
				get_group_(gKey,cTab,tPage);
			}

		});
	};


</script>

<?php 
//groups page
} ?>


		</div> <!-- wrapper -->
	</body>
</html>
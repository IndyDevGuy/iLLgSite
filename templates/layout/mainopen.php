<?php

?>
 <!-- Main -->
  <div id="main">
    <div id="main-bot">
    	
    	<div id="navBar">
    		<?php
    		if($registry->guest == false)
    		{
    		?>
             <ul id="nav" class="user-nav">
				<li id="userimage_li"><img width="35" height="35" src="<?php echo $registry->user['avatarSmall']; ?>" />
					<a href="#"><?php echo $registry->user['nickname']; ?> <i class="fa fa-caret-down"></i></a>
					<div id="userContainer" style="display:none;">
						<div id="userBody" class="notifications">
							<ul>
								<p>Profile Links</p>
								<li class="user_item">
									<a href="/user/profile">Profile</a>
								</li>
								<li class="user_item">
									<a href="/user/editprofile">Edit Profile</a>
								</li>
								<li class="user_item">
									<a href="/user/settings">Settings</a>
								</li>
								<li class="user_item">
									<a href="/user/clans">My Clans</a>
								</li>
								<li class="user_item">
									<a href="/user/friends">Friends</a>
								</li>
								<li class="user_item">
									<a href="/user/friendrequest">Friend Request</a>
								</li>
								<li>
									<a href="/messages">Messages</a>
								</li>
								<li class="user_item">
									<a href="/messages/compose">Compose New</a>
								</li>
								<li class="user_item">
									<a href="/notifications">Notifications</a>
								</li>
							</ul>
						</div>
					</div>
				</li>
				<li id="create_li" class="createLink">
					<a href="#">Create <i class="fa fa-caret-down"></i></a>
					<div id="createContainer">
						<div id="createBody" class="notifications">
							<ul>
								<li class="create_item">
									<a href="/user/profile"><i class="fa fa-comment"></i> Status</a>
								</li>
								<li class="create_item">
									<a href="/forums/newTopic"><i class="fa fa-plus-square"></i> Topic</a>
								</li>
								<li class="create_item">
									<i class="fa fa-plus-square"></i>
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" >
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="BVNBCYPNBST5E">
										<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
										<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
									</form>
								</li>
						
					</ul>
						</div>
					</div>
				</li>
				<li id="notification_li">
					<div id="noti_Container"  class="notificationLink">
					    <a href="/notifications"><i class="fa fa-bell"></i></a>
					    <?php
					    $display = 'block';
					    $count = $registry->NC->getUserNotificationCount($registry->user['uid']);
					    if($count == 0)
					    {
							$display = 'none';
						}
					    ?>
					    <div class="noti_bubble" style="display:<?php echo $display; ?>;">
					    	<?php 
					    	
					    	if ($count > 0)
					    		echo $count;
					    	?>	
					     </div>
					</div>

					<div id="notificationContainer">
						<div id="notificationTitle"><i class="fa fa-bell"></i>  Notifications <a id="quickNotificationSettingsLink" href="/notifications/settings" style="float:right;font-size: 10px;"><i class="fa fa-cog"></i> Notification Settings</a></div>
						<div id="notificationsBody" class="notifications"></div>
						<div id="notificationFooter"><a id="quickViewNotificationsLink" href="/notifications"><i class="fa fa-bars"></i>View All Notifications</a></div>
					</div>

				</li>
				<li id="message_li">
					<div id="noti_Container" class="notificationMessageLink">
						<a href="/messages"><i class="fa fa-envelope"></i></a>
						<?php
					    $display = 'block';
					    $count = $registry->NC->getUserMessNotificationCount($registry->user['uid']);
					    if($count == 0)
					    {
							$display = 'none';
						}
					    ?>
						<div class="noti_mess_bubble" style="display:<?php echo $display; ?>;">
							<?php
							$count = $registry->NC->getUserMessNotificationCount($registry->user['uid']);
							if($count > 0)
								echo $count;
							?>
						</div>
					</div>
					<div id="notificationMessageContainer">
						<div id="notificationMessageTitle">
							<i class="fa fa-envelope"></i>  Inbox 
							<a id="quickComposeNewLink" class="compose_button" href="#" onclick="return false;" style="float:right;font-size: 10px;">Compose New</a>
							<?php 
								$dialog = new dialog('Compose New Message','','composeDialog','"close"');
								$dialog->showDialog();
							?>
						</div>
						<div id="notificationsMessageBody" class="notifications">
							<?php
							$dateTime = new DatesTimes();
							$userMessages = $registry->users->getUserMessages($registry->user['uid']);
							if(isset($userMessages[0]['from_uid']))
							{
							
								foreach($userMessages as $message)
								{
									if($message['reply'] == 0)
									{
										//get the from user info
										$fromUser = $registry->users->getUserByUid($message['from_uid']);
										echo '
										<div id="quickMessageContainer"  class="quickMessageContainer">
										<input type="hidden" value="'.$message['id'].'" />
										<img src="'.$fromUser['avatar'].'" width="50" height="50" />';
										if($message['unread'] == 0)
										{
											echo '
												<i class="fi-burst-new quickmessagenew"></i>
											';
										}
										echo
										'
										<div class="quickmessagetext">'.$message['message'].'</div>
										<p class="quickmessagefrom">From: '.$fromUser['nickname'].'</p>';
										$dates = explode(' ', $message['date']);
										$time = $dates[1];
										$date = $dates[0];
										
										$splitTime = explode(':',$time);
										
										$newDate = $dateTime->showDateBasedOffToday($date);
										$newTime = date_format(date_create($message['date']), 'g:i A');
										$newDate .= ' @ '.$newTime;
																		
										echo '
										<p class="quickmessagetime">'.$newDate.'</p>
									</div>
										';
									}
								} 
							}
							else
							{
								echo '<p>You have no messages.</p>';
							}
							?>
						</div>
						<div id="notificationMessageFooter"><a id="quickInboxLink" href="/messages"><i class="fa fa-bars"></i> Go to Inbox</a></div>
					</div>
				</li>
			</ul> 
			
			
			<div class="" id="searchWrap">
				<div id="elSearch" class="">
					<form id="searchSite" name="searchSite" method="get" action="/search/" accept-charset="utf-8">
						<a id="selectFilter" style="display:none;">
							<span class="searchIn">
							All Content
							</span>
							<i class="fa fa-caret-down"></i>
						</a>
				
						<input type="search" name="q" placeholder="Search Site..." id="quickSearchField">
						<button onclick="return false;" id="searchButton" type="submit"><i class="fa fa-search"></i></button>
					</form>
					<ul class="" id="searchMenu" style="left: -117px; top: 44px; position: absolute; z-index: 5200; opacity: 1; display: none;">
						<li class="searchAllLink">
							<a title="All Content" href="">All Content</a>
						</li>
						<hr>
						<li class="searchPostsLink">
							<a href="#">Posts</a>
						</li>
						<li class="searchClansLink">
							<a href="#">Clans</a>
						</li>
						<li class="searchUsersLink">
							<a href="#">Users</a>
						</li>
						<li class="searchForumsLink">
							<a href="#">Forums</a>
						</li>
						<li class="searchNewsLink">
							<a href="#">News</a>
						</li>
						<hr>
						<li class="advancedSearchLink">
							<a href="/search/"><i class="fa fa-cog"></i> Advanced Search</a>
						</li>
					</ul>
					<div id="quickSearchContainer" style="display:none;">
						
					</div>
				</div>
			</div>
			
			
			<script>
			$(document).ready(function()
			{
				searchResultContainer = $("#quickSearchContainer");
				searchIn = 'all';
				quickMessages = [];
				quickNotifications = [];
				notes = '';
				mess = '';
				title = '';
				quickMessagesContainer = $("#notificationsMessageBody");
				quickNotificationsContainer = $("#notificationsBody");
				$("#quickInboxLink").on('click', function(e){
					window.location.href = $("#quickInboxLink").attr('href');
				});
				
				//socket function for when server sends message info
				socket.on('quickMessages', function(data){
					quickMessages = [];
					$.each(data, function(dex,val){
						quickMessages.push(val);
					})
					updateQuickMessages();
				});
				socket.on('quickNotifications', function(data){
					quickNotifications = [];
					$.each(data, function(dex,val){
						quickNotifications.push(val);
					})
					updateQuickNotifications();
				});
				
				function addToMess(con)
				{
					mess = mess + con;
				}
				function addToNotes(con)
				{
					notes = notes + con;
				}
				
				function updateQuickNotifications()
				{
					$.each(quickNotifications, function(dex,val){
						var st = '';
						if(val.seen == 0)
						{
							st = '<i class="fi-burst-new quicknotificationnew"></i>';
						}
						$.ajax({
							url:'/json/dateFormat/&ajax=true&date='+val.date,
							cache:false,
							success:function(data)
							{
								addToNotes('<div id="quickNotificationContainer" class="quickNotificationContainer"><input type="hidden" class="note_id" value="'+val.id+'" /><input class="note_links" type="hidden" value="'+val.links+'" />'+st+'<img src="'+val.image+'" width="50" height="50" /><div class="quicknotificationtext">'+val.content+'</div><p class="quickbotificationtime">'+data.date+'</p></div>');
							}
						});
					});
					quickNotificationsContainer.html('');
					quickNotificationsContainer.append(notes);
					notes = '';
					$(".quickNotificationContainer").on('click',function(e){
						note_id = $(this).find(".note_id").val();
						links = $(this).find(".note_links").val();
						newIcon = $(this).find(".quicknotificationnew");
						var uri = '/notifications/view&ajax=true&id='+note_id;
						$.ajax({
							url:uri,
							cache:false,
							success:function(html)
							{
								if(newIcon != null)
								{
									newIcon.hide('fast');
								}
							},
							complete:function(e)
							{
								window.location.href = links;
							}
						});
					});
					
				}
				
				function updateQuickMessages()
				{
					$.each(quickMessages, function(dex,val){
						var st = '';
						if(val.unread == 0)
						{
							st = '<i class="fi-burst-new quickmessagenew"></i>';
						}
						$.ajax({
							url:'/json/dateFormat/&ajax=true&date='+val.date,
							cache:false,
							success: function(data)
							{
								addToMess('<div id="quickMessageContainer" class="quickMessageContainer"><input type="hidden" value="'+val.id+'" /><img src="'+val.avatar+'" width="50" height="50" />'+st+'<div class="quickmessagetext">'+val.message+'</div><p class="quickmessagefrom">From: '+val.username+'</p><p class="quickmessagetime">'+data.date+'</p></div>');
							}
						});
					});
					quickMessagesContainer.html('');
					quickMessagesContainer.append(mess);
					mess = '';
					$(".quickMessageContainer").on('click',function()
					{
						$("#composeDialog_content").html('');
						message_id = $(this).find('input').val();
						newIcon = $(this).find(".quickmessagenew");
						var uri = '/messages/view&ajax=true&id='+message_id;
						$.ajax
						({
							url:'/json/getMessageInfo/&ajax=true&id='+message_id,
							success:function(data)
							{
								if(newIcon != null)
								{
									newIcon.hide('fast');
								}
								setTitle(data.username + ' sent you a message!');
							},
							complete:function(e)
							{
								$("#composeDialog").dialog
								({
									width:500,
									height:500,
									draggable:false,
									title:title,
									open:function(event)
									{
										$("#composeDialog_content").html('<div id="loadingImage" style="text-align:center"><img src="/images/loading2.gif" /></div>');
										$.ajax
										({
								        	url: uri,
								        	cache: false,
								        	success: function(html){
								        		$("#composeDialog_content").html(html);
								        		loaded = true;
								        	},
								        	complete: function(data){
								        	
								        	}
										})
									},
									buttons: [
									{
							    		text:'Reply',
							    		click:function(e)
							    		{
											if(loaded == true)
											{
												$('#composeDialog').dialog("close");
												$("#composeDialog_content").html('');
												$.ajax
												({
													url:'/json/getMessageInfo/&ajax=true&id='+message_id,
													success:function(data)
													{
														setTitle('Reply to '+data.username+'\'s message');
													},
													complete:function(e)
													{
														$("#composeDialog").dialog
														({
															width:500,
															height:500,
															draggable:false,
															title:title,
															open:function(event)
															{
																$("#composeDialog_content").html('<div id="loadingImage" style="text-align:center"><img src="/images/loading2.gif" /></div>');
																uri = '/messages/compose&ajax=true&reply=true&id='+message_id;
																$.ajax
																({
																	url:uri,
																	success:function(data)
																	{
																		$("#composeDialog_content").html(data);
																		loaded = true;
																	}	
																})
															},
															buttons : [
															{
																text : 'Send',
																click : function(e)
																{
																	var oEditor = CKEDITOR.instances.messageEditor;
																	var message = oEditor.getData();
																	var errors = false;
																	if(loaded = true)
																	{
																		if(message == '')
																		{
																			errors = true;
																			$("#messageError").show('fast');
																		}
																		else
																		{
																			$("#messageError").hide('fast');
																			//make ajax request to send message to user
																			var uri = '/messages/compose&ajax=true&replied=true&messageId='+message_id;
																			var ids = $("#user_id").val();
																			$.post(uri,
																			{
																				send:true,
																				uid: <?php echo $registry->user['uid']; ?>,
																				ids: ids,
																				message: message
																			},
																			function(data,status)
																			{
																				if (data.complete == true)
																				{
																					//the message was sent, close dialog and remove the message from the message list
																					//loop thru the messages
																					for(var i = 0; i < quickMessages.length; i++)
																					{
																						//if this message.id = message_if
																						if(quickMessages[i].id == message_id)
																						{
																							quickMessages.splice(i, 1);
																						}
																					}
																					updateQuickMessages();
																					$("#composeDialog").dialog("close");
																				}
																				else
																				{
																					//there was a problem sending the message
																					$("#composeDialog").dialog("close");
																				}
																			});
																		}
																	}
																}	
															}]
														});
													}
												});
											}
										}
									}]
								
								});
							
							}
						});
						
					});
				} 
				
				
				
				
				
				
				
				
				
				$(".quickMessageContainer").on('click',function()
					{
						$("#composeDialog_content").html('');
						message_id = $(this).find('input').val();
						newIcon = $(this).find(".quickmessagenew");
						var uri = '/messages/view&ajax=true&id='+message_id;
						$.ajax
						({
							url:'/json/getMessageInfo/&ajax=true&id='+message_id,
							success:function(data)
							{
								if(newIcon != null)
								{
									newIcon.hide('fast');
								}
								setTitle(data.username + ' sent you a message!');
							},
							complete:function(e)
							{
								$("#composeDialog").dialog
								({
									width:500,
									height:500,
									draggable:false,
									title:title,
									open:function(event)
									{
										$("#composeDialog_content").html('<div id="loadingImage" style="text-align:center"><img src="/images/loading2.gif" /></div>');
										$.ajax
										({
								        	url: uri,
								        	cache: false,
								        	success: function(html){
								        		$("#composeDialog_content").html(html);
								        		loaded = true;
								        	},
								        	complete: function(data){
								        	
								        	}
										})
									},
									buttons: [
									{
							    		text:'Reply',
							    		click:function(e)
							    		{
											if(loaded == true)
											{
												$('#composeDialog').dialog("close");
												$("#composeDialog_content").html('');
												$.ajax
												({
													url:'/json/getMessageInfo/&ajax=true&id='+message_id,
													success:function(data)
													{
														setTitle('Reply to '+data.username+'\'s message');
													},
													complete:function(e)
													{
														$("#composeDialog").dialog
														({
															width:500,
															height:500,
															draggable:false,
															title:title,
															open:function(event)
															{
																$("#composeDialog_content").html('<div id="loadingImage" style="text-align:center"><img src="/images/loading2.gif" /></div>');
																uri = '/messages/compose&ajax=true&reply=true&id='+message_id;
																$.ajax
																({
																	url:uri,
																	success:function(data)
																	{
																		$("#composeDialog_content").html(data);
																		loaded = true;
																	}	
																})
															},
															buttons : [
															{
																text : 'Send',
																click : function(e)
																{
																	var oEditor = CKEDITOR.instances.messageEditor;
																	var message = oEditor.getData();
																	var errors = false;
																	if(loaded = true)
																	{
																		if(message == '')
																		{
																			errors = true;
																			$("#messageError").show('fast');
																		}
																		else
																		{
																			$("#messageError").hide('fast');
																			//make ajax request to send message to user
																			var uri = '/messages/compose&ajax=true&replied=true&messageId='+message_id;
																			var ids = $("#user_id").val();
																			$.post(uri,
																			{
																				send:true,
																				uid: <?php echo $registry->user['uid']; ?>,
																				ids: ids,
																				message: message
																			},
																			function(data,status)
																			{
																				if (data.complete == true)
																				{
																					//the message was sent, close dialog and remove the message from the message list
																					//loop thru the messages
																					for(var i = 0; i < quickMessages.length; i++)
																					{
																						//if this message.id = message_if
																						if(quickMessages[i].id == message_id)
																						{
																							quickMessages.splice(i, 1);
																						}
																					}
																					updateQuickMessages();
																					$("#composeDialog").dialog("close");
																				}
																				else
																				{
																					//there was a problem sending the message
																					$("#composeDialog").dialog("close");
																				}
																			});
																		}
																	}
																}	
															}]
														});
													}
												});
											}
										}
									}]
								
								});
							
							}
						});
						
					});
				
				
				
				
				
				
				
				
				
				function setTitle(str)
				{
					title = str;
				}
				//compose dialog and link
				$('#quickComposeNewLink').on('click',function(e){
					var uri = '/messages/compose&ajax=true';
					var loaded = false;
					$("#composeDialog").dialog({
						width:500,
						height:500,
						draggable:false,
						open:function(event){
							$("#composeDialog_content").html('<div id="loadingImage" style="text-align:center"><img src="/images/loading2.gif" /></div>');
							$.ajax({
						        url: uri,
						        cache: false,
						        success: function(html){
						        	$("#composeDialog_content").html(html);
						        	loaded = true;
						        },
						        complete: function(){
						        	
						        }
						    });
						},
						close:function(event){
							$('.token-input-dropdown').remove();	
						},
						buttons: [
						{
							text:'Send',
							click:function()
							{
								if(loaded == true)
								{
									var ids = 'ids';
									var friendSelected = false;
									var friends = $('#friendSelect').tokenInput("get");
									var oEditor = CKEDITOR.instances.messageEditor;
									var message = oEditor.getData();
									$.each(friends,function(dex,value){
										friendSelected = true;
										ids = ids + "," + value.id;
									})
									var errors = false;
									if(friendSelected == false)
									{
										errors = true
										$("#friendError").show('fast');	
									}
									else
									{
										$("#friendError").hide('fast');
									}
									
									
									if(message == '')
									{
										errors = true;
										$("#messageError").show('fast');
									}
									else
									{
										$("#messageError").hide('fast');
									}
									if (errors == false)
									{
										//make ajax request to send message to user
										var uri = '/messages/compose&ajax=true';
										$.post(uri,
										{
											send:true,
											uid: <?php echo $registry->user['uid']; ?>,
											ids: ids,
											message: message
										},
										function(data,status)
										{
											if (data.complete == true)
											{
												//the message was sent
												$("#composeDialog").dialog("close");
											}
											else
											{
												//there was a problem sending the message
												$("#composeDialog").dialog("close");
											}
										});
									}
								}
									
							}	
						}
						]
					});
				});
				
				
				$('#quickViewNotificationsLink').on('click',function(e){
					window.location.href = $('#quickViewNotificationsLink').attr('href');
				});
				
				$('#quickNotificationSettingsLink').on('click',function(e){
					window.location.href = $('#quickNotificationSettingsLink').attr('href');
				});
				
				$(".compose_button").button();
				
				
				filterMenuActive = false;
				
				$(".notificationLink").click(function()
				{
					filterMenuActive = false;
					$("#searchMenu").hide('slide',{direction: 'up'}, 500);
					setTimeout(function(){			
						$("#selectFilter").hide('slide',{direction: 'right'}, 500);
					},500);
					$("#notificationMessageContainer").hide('slide',{direction: 'up'}, 500);
					$("#createContainer").hide('slide',{direction: 'up'}, 500);
					$("#userContainer").hide('slide',{direction:'up'},500);
					$("#notificationContainer").show('slide',{direction: 'up'}, 500);
					return false;
				});

				//Popup on click
				$("#notificationContainer").click(function()
				{
					return false;
				});
				
				
				$(".notificationMessageLink").click(function()
				{
					filterMenuActive = false;
					$("#searchMenu").hide('slide',{direction: 'up'}, 500);
					setTimeout(function(){			
						$("#selectFilter").hide('slide',{direction: 'right'}, 500);
					},500);
					$("#createContainer").hide('slide',{direction: 'up'}, 500);
					$("#notificationContainer").hide('slide',{direction: 'up'}, 500);
					$("#userContainer").hide('slide',{direction:'up'},500);
					$("#notificationMessageContainer").show('slide',{direction: 'up'}, 500);
					if($("#notificationMessageContainer").is(':visible'))
					{
						//set all of this users unseen messages to seen
						$.ajax({
							url : '/json/seenMessages/&ajax=true&uid='+<?php echo $registry->user['uid']; ?>,
							cached:false,
							complete:function()
							{
								
							}
						})
					}
					return false;
				});
				
				$(".createLink").click(function()
				{
					filterMenuActive = false;
					$("#searchMenu").hide('slide',{direction: 'up'}, 500);
					setTimeout(function(){			
						$("#selectFilter").hide('slide',{direction: 'right'}, 500);
					},500);
					$("#notificationMessageContainer").hide('slide',{direction: 'up'}, 500);
					$("#notificationContainer").hide('slide',{direction: 'up'}, 500);
					$("#userContainer").hide('slide',{direction:'up'},500);
					$("#createContainer").show('slide',{direction: 'up'}, 500);
					return false;
				});
				
				$("#userimage_li").click(function()
				{
					filterMenuActive = false;
					$("#searchMenu").hide('slide',{direction: 'up'}, 500);
					setTimeout(function(){			
						$("#selectFilter").hide('slide',{direction: 'right'}, 500);
					},500);
					$("#notificationMessageContainer").hide('slide',{direction: 'up'}, 500);
					$("#notificationContainer").hide('slide',{direction: 'up'}, 500);
					$("#createContainer").hide('slide',{direction: 'up'}, 500);
					$("#userContainer").show('slide',{direction:'up'},500);
					return false;
				});

				//Popup on click
				$("#createContainer").click(function()
				{
					return false;
				});

			});
			
			
			//search functions and Code
			$("#selectFilter").on('click', function(event){
				$("#createContainer").hide('slide',{direction: 'up'}, 500);
				$("#notificationContainer").hide('slide',{direction: 'up'}, 500);
				$("#notificationMessageContainer").hide('slide',{direction: 'up'}, 500);
				$("#userContainer").hide('slide',{direction:'up'},500);
				$("#searchMenu").show('slide',{direction: 'up'}, 500);
				filterMenuActive = true;
				return false;
			});
			//show search filter list when search input is clicked
			$("#quickSearchField").on('click',function(event){
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				$("#notificationMessageContainer").hide('slide',{direction: 'up'}, 500);
				$("#createContainer").hide('slide',{direction: 'up'}, 500);
				$("#userContainer").hide('slide',{direction:'up'},500);
				$("#notificationContainer").hide('slide',{direction: 'up'}, 500);
				$("#selectFilter").show('slide',{direction: 'right'}, 500);
				return false;
			});
			
			$(".searchAllLink").on('click',function(event)
			{
				searchIn = 'all';
				$(".searchIn").text('All Content');
				$("#selectFilter").css({'left':'-106px'});
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				return false;
			});
			$('.searchPostsLink').on('click',function(event)
			{
				searchIn = 'posts';
				$(".searchIn").text('Posts');
				$("#selectFilter").css({'left':'-73px'});
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				return false;
			});
			$(".searchClansLink").on('click',function(event)
			{
				searchIn = 'clans';
				$(".searchIn").text('Clans');
				$("#selectFilter").css({'left':'-72px'});
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				return false;
			});
			$(".searchUsersLink").on('click',function(event)
			{
				searchIn = 'users';
				$(".searchIn").text('Users');
				$("#selectFilter").css({'left':'-73px'});
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				return false;
			});
			$('.searchForumsLink').on('click',function(event)
			{
				searchIn = 'forums';
				$(".searchIn").text('Forums');
				$("#selectFilter").css({'left':'-86px'});
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				return false;
			});
			$('.searchNewsLink').on('click',function(event)
			{
				searchIn = 'news';
				$(".searchIn").text('News');
				$("#selectFilter").css({'left':'-72px'});
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				return false;
			});
			
			
			//Document Click hiding the popup 
			$(document).click(function()
			{
				filterMenuActive = false;
				$("#userContainer").hide('slide',{direction:'up'},500);
				$("#searchMenu").hide('slide',{direction: 'up'}, 500);
				setTimeout(function(){			
					$("#selectFilter").hide('slide',{direction: 'right'}, 500);
				},500);
				$("#notificationMessageContainer").hide('slide',{direction: 'up'}, 500);
				$("#createContainer").hide('slide',{direction: 'up'}, 500);
				$("#notificationContainer").hide('slide',{direction: 'up'}, 500);
				searchResultContainer.hide('slide',{direction: 'up'}, 500);
			});
			
			$("#searchSite").submit(function(event)
			{
				//searchSite();
				return false;
			});
			
			$("#quickSearchField").bind("input paste", function(event)
			{
				searchResultContainer.show('slide',{direction: 'up'}, 500);
				searchSite();
				return false;	
			});
			
			$("#searchButton").on('click', function(event)
			{
				//searchSite();
				return false;
			});
			
			function searchSite()
			{
				searchTerm = $("#quickSearchField").val();
				searchHtml = '';
				if(searchTerm != '')
				{
					if(searchIn == 'users')
					{
						var i = 1;
						$.ajax
						({
							url:'/search/?ajax=true&category='+searchIn+'&q='+searchTerm,
							success:function(data)
							{
								$.each(data,function(dex,val)
								{
									if(val.friends == true)
									{
										friendsHTML = '<span status="friends" id="friendsbutton'+i+'">Friends</span>';
									}
									else if(val.friends == "pending")
									{
										friendsHTML = '<span status="pending" id="friendsbutton'+i+'">Request Pending</span>';
									}
									else
									{
										friendsHTML = '<span status="request" id="friendsbutton'+i+'">Send Friend Request</span>';
									}
									searchHtml = searchHtml + '<div id="quickSearchItem"><img id="quickSearchImage" src="'+val.avatar+'" width="50" height="50" /><p><a href="/user/profile/'+val.uid+'">'+val.nickname+'</a>'+friendsHTML+'<input type="hidden" class="uid'+i+'" value="'+val.uid+'" /></p></div>';
									i++;
								});
							},
							complete:function(event)
							{
								//add the returned data to the search field container and show the container
								searchResultContainer.html('');
								searchResultContainer.append(searchHtml);
								for(var h = 1; h <= i; h++)
								{
									friendsButton = $("#friendsbutton"+h);
									friendsButton.on('click',function(e)
									{
										e.stopImmediatePropagation();
										id = $(this).siblings('input[type=hidden]').val();
										status = $(this).attr("status");
										switch(status)
										{
											case 'pending':
												break;
											case 'friends':
												break;
											default:
												$(this).html('Sending Request..');
												$(this).attr("status",'pending');
												$.ajax
												({
													url:'/json/sendFriendRequest/?ajax=true&f_uid='+id,
													success:function(data)
													{
														
														
													},
													complete:function(event)
													{
														
													}
												});
												$(this).html('Request Pending');
												break;		
										}
									});
								}
							}	
						});
					}
				}
				
			}
			
			</script>
			        
			<?php
			}
			else
			{
			?>
			<p>U aint logged in nigga!</p>
			<?php	
			}
			?>
</div>
    	
    	
    	
    	
      <div class="cl">&nbsp;</div>
      <!-- Content -->
      <div id="content">
      
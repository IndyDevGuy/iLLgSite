<?php
class UserController
{
	public $registry;
	public $dbh;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->dbh = $this->registry->db;
	}
	
	public function Logout()
	{
		$_SESSION['illg_sid'] = null;
		$this->registry->youtube->logout();
		redirect('index.php');
	}
	
	public function Profile()
	{
		if (isset($_GET['errors']))
		{
			$error = $_GET['errors'];
			if ($error == 'permissions')
			{
				$dialog = new dialog('Insufficient Permissions','You do not have the correct permissions to view that page!');
				$dialog->showDialog();
			}
		}
		$ownProfile = null;
		if(isset($_GET['param']) && $_GET['param'] != 'youtube')
		{
			$my_id = $_GET['param'];
			$sth = $this->dbh->query("SELECT * FROM users WHERE uid = '$my_id'");
			$ownProfile = false;
			$user = $this->registry->users->getUserByUid($my_id);
		}
		else
		{
			if (isset($_SESSION['illg_sid']))
			{
				$my_id = $_SESSION['illg_sid'];
				$sth = $this->dbh->query("SELECT * FROM users WHERE sid = '$my_id'");
				$ownProfile = true;
				$user = $this->registry->users->getUserBySid($my_id);
			}
			else
			{
				redirect('index.php');
			}
			
		}
        $api = "F1EB0DDF52B4948266220BF38AB0A479";
       
        $profileurl = $user['profileurl'];
        $avatarfull = $user['avatar'];
        $personaname = $user['nickname'];
        $realname = $user['name'];
        $sid = $user['sid'];
        
        //variable strings for display wether it is their profile or someone elses
        $profileHeaderText = '';
        $myGamesText = '';
        $myFriendsText = '';
        $myClansText = '';
        
        if ($ownProfile)
        {
        	$this->registry->pageTitle = 'My Profile | iLLuSioN GrOuP';
			$profileHeaderText = 'My Profile';
			$myGamesText = 'My Steam Games';
			$myFriendsText = 'My Steam Friends';
			$myClansText = 'My Clans';
			//display Youtube Linked
			if($user['youtube_linked'] == 1)
			{
				$this->registry->youtube->refreshUserTokens($this->registry->user['uid']);
				$playlist = $this->registry->youtube->getUserPlaylists();
				foreach($playlist['items'] as $playlist)
				{
					$pid = $playlist['id'];
					echo '<p>'.$playlist['snippet']['title'].'</p>';
					$playlistItemsResponse =  $this->registry->youtube->getVideosByPlaylist($pid);
					foreach($playlistItemsResponse['items'] as $playlistItem)
					{
						echo sprintf('<li>%s (%s)</li>', $playlistItem['snippet']['title'],
          $playlistItem['snippet']['resourceId']['videoId']);
          				

					}
				}
				$youtubeStatus = 'Linked';
			}
			else
			{
				$youtubeStatus = $this->registry->youtube->showLinkAccountLink($this->registry->user['uid']);
			}
		}
		else
		{
			//someone elses profile
			$profileHeaderText = $personaname . '\'s Profile';
			$this->registry->pageTitle = $personaname . '\'s Profile | iLLuSioN GrOuP';
			$profileHeaderText = $personaname . '\'s Profile';
			$myGamesText = $personaname . '\'s Steam Games';
			$myFriendsText = $personaname . '\'s Steam Friends';
			$myClansText = $personaname . '\'s Clans';
			//ensure user has linked his youtube account
			//$this->registry->youtube->refreshUserTokens($my_id);
			$youtubeStatus = '';
		}
		$online = '';
        if ($this->registry->Steam->isOnline($sid) == 1)
        {
			$online = 'Currently <span style="color:green;">Online</span>';
		}
		else
		{
			$online = 'Currently <span style="color:red;">Offline</span>';
		}
        //$data = $this->registry->Steam->getOwnedGames($sid);
        //var_dump($data);
        //EXAMPLE
        echo '
        <div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt">
              <h3>'.$profileHeaderText.'</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="row-articles articles">
            <div class="cl">&nbsp;</div>
            <div class="article last-article">'."
        <a href='$profileurl'><img src='$avatarfull' width='150px'></a>
        $personaname<br>$realname<br>
        <p>".$online . "</p>
        <br />
        $youtubeStatus
        ".
	    '
	    <a href="/user/clans/'.$user['uid'].'">View '.$user['nickname'].'\'s Clans</a>
	    <div id="accordion">
  
			<h3>'.$myClansText.'</h3>
				<div>
    				<p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
  				</div>
			<h3>'.$myGamesText.'</h3>
				<div>
    				<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
  				</div>
			<h3>'.$myFriendsText.'</h3>
  
  				<div>
    				<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
    				<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
  				</div>
  			</div>
   <div class="cl">&nbsp;</div>
          </div>
</div>
</div>
      </div>
  <script>
  $(function() {
    $( "#accordion" ).accordion();
  });
  </script>';
		
	}
	
	public function Clans()
	{
		echo '
		<div class="block">
	        <div class="block-bot">';
		if(isset($_GET['param']))
		{
			$uid = $_GET['param'];
			$user = $this->registry->users->getUserByUid($uid);
			echo '
			<div class="ui-widget-header ui-corner-top titlespacer1">
	            <div class="head-cnt"> 
	              <span style="float:left;margin-top:7px;margin-left:5px;"><h3>'.$user['nickname'].'\'s Clans</h3></span>
	              <div class="cl">&nbsp;</div>
	            </div>
	          </div>
	           <div class="row-articles articles">';
			$this->registry->pageTitle = $user['nickname'].'\'s Clans | iLLuSioN GrOuP';
			$clanClass = new Clans($this->registry);
            $clanIds = $clanClass->getUserClans($uid);
            foreach($clanIds as $cid)
            {
		   		$clan = $clanClass->getClan($cid['cid']);
		   		$memberCount = $clanClass->getMemberCount($cid['cid']);
		   	 	echo '
		   	 	<div id="clan_item">
		   	 		<img src="/uploads/clans/'.$clan['tag'].'/'.$clan['logo'].'" width="75px" height="75px">
		   	 		<h3>'.$clan['name'].'</h3>
		   	 		<p>Members: '.$memberCount.'</p>
		   	 	</div>
		   	 	';
		     }
		}
		else
		{
			echo '
			<div class="ui-widget-header ui-corner-top titlespacer1">
	            <div class="head-cnt"> 
	              <span style="float:left;margin-top:7px;margin-left:5px;"><h3>My Clans</h3></span>
	              <div class="cl">&nbsp;</div>
	            </div>
	          </div>
	           <div class="row-articles articles">';
			$this->registry->pageTitle = 'My Clans | iLLuSioN GrOuP';
			$clanClass = new Clans($this->registry);
            $clanIds = $clanClass->getUserClans($this->registry->user['uid']);
            foreach($clanIds as $cid)
            {
		   	 	$clan = $clanClass->getClan($cid['cid']);
		   	 	$memberCount = $clanClass->getMemberCount($cid);
		   		echo '
		   		<div id="clan_item">
		   			<img src="/uploads/clans/'.$clan['tag'].'/'.$clan['logo'].'" width="75px" height="75px">
		   			<h3>'.$clan['name'].'</h3>
		   			<p>Members: '.$memberCount.'</p>
		   		</div>
		   		';
		    }
	 	} 
	           echo '
	           </div>
	       </div>
	    </div>
		';
	}
	
	public function Clan()
	{
		$this->registry->users->isLoggedInRedirect();
		if (isset($_SESSION['illg_sid']))
		{
			$uid = $this->registry->user['uid'];
		}
		elseif (isset($_GET['param']))
		{
			$uid = $_GET['param'];
		}
		else
		{
			redirect('/user/profile');
		}
		$clanClass = new Clans($this->registry);
		$clanId = $clanClass->getUserClans($uid);
		if (isset($clanId['cid']))
		{
			$clan = $clanClass->getClan($clanId['cid']);
		}
		else
		{
			$clan = array();
			$clan['name'] = 'None';
		}
		echo '
		<div class="block">
	        <div class="block-bot">
	          <div class="ui-widget-header ui-corner-top titlespacer1">
	            <div class="head-cnt"> 
	              <span style="float:left;margin-top:7px;margin-left:5px;"><h3>My Clan: '.$clan['name'].'</h3></span>
	              <div class="cl">&nbsp;</div>
	            </div>
	          </div>
	           <div class="row-articles articles">
	    ';
		
		if (isset($clanId['cid']))
		{
			//lets check to see if the user is the admin of the clan
			if ($clan['admin_id'] == $this->registry->user['uid'])
			{
				$this->registry->pageTitle = $clan['name'] . ' ['.$clan['tag'].'] Admin Page | iLLuSioN GrOuP';
				echo 'you are the admin of the clan';
			}
			else
			//check their role and permissions in the clan
			{
				$this->registry->pageTitle = $clan['name'] . ' Member Page';
			}
		}
		else
		{
			$this->registry->pageTitle = 'Create or join a clan | iLLuSioN GrOuP';
			echo '<p>You havent joined or made a clan yet!</p><a id="newClan" href="/clans/newclan">New Clan</a>
			
			<script>
				$("#newClan").button();
				
			</script>';
		}
		
		echo '</div></div></div>';
	}
	
	public function Posts()
	{ 
		$this->registry->users->isLoggedInRedirect();
		$this->registry->pageTitle = 'My Posts | iLLuSioN GrOuP';
		$page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
		if ($page <= 0) $page = 1;
		 
		$per_page = 5; // Set how many records do you want to display per page.
		 
		$startpoint = ($page * $per_page) - $per_page;
		
		$viewing = '';
		if (isset($_GET['success']))
		{
			$dialog = new dialog('Post Added','Your post has been added!');
			$dialog->showDialog();
		}
		if (isset($_GET['param']))
		{
			$uid = $_GET['param'];
			$viewing = 'other';
			$user = $this->registry->users->getUserByUid($uid);
			$name = $user['nickname'].'\'s ';
			$noPostMessage = $user['nickname'] . ' has not added any post yet.';
		}
		else
		{
			$uid = $this->registry->user['uid'];
			$viewing = 'mine';
			$name = 'My ';
			$noPostMessage = 'You have not added any post yet.';
		}
		
		
		$sql = "SELECT * FROM user_post WHERE uid = :uid ORDER BY id ASC LIMIT :startpoint , :per_page";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':uid',$uid, PDO::PARAM_INT);
		$stmt->bindParam(':startpoint',$startpoint, PDO::PARAM_INT);
		$stmt->bindParam(':per_page',$per_page, PDO::PARAM_INT);
		$stmt->execute();
		$posts = $stmt->fetchAll();
		
		echo '<div class="block">
		        <div class="block-bot">
		          <div class="ui-widget-header ui-corner-top titlespacer1">
		            <div class="head-cnt"> 
		             <a style="float:right;margin-top:3px;" id="rust_news_button" href="index.php?rt=Post&method=NewPost" class="">New Post</a>
		              <span style="float:left;margin-top:7px;margin-left:5px;"><h3>'.$name.'Posts</h3></span>
		              <div class="cl">&nbsp;</div>
		            </div>
		          </div>
		          <div class="row-articles articles">
		            <div class="cl">&nbsp;</div>';
		            
		            echo '<div class="article-pager">'.$this->registry->Steam->pagination('user_post',$per_page,$page,'?rt=User&method=Posts&', "uid = $uid") . '</div>';
		            if (is_array($posts))
		            {
		            	$string = '';
		            	$i = 1;
						foreach($posts as $post)
						{
							if ($i == $per_page)
							{
								$string = '<div class="article">';
							}
							else
							{
								$string = '<div class="article">';
							}
							echo $string;
							if (isset($post['title']))
							{
								//echo $string
								echo  '
							        	<div class="cl">&nbsp;</div>
							        	<div class="image"> 
							        		<a href="/index.php?rt=Post&method=View&pid='.$post['id'].'" target=""><img alt="'.$post['title'].'" src="http://illusiongroup.us/uploads/'.$uid.'/posts/'.$post['filename'].'"></a> 
							        	</div>
							        	
							      		<div class="cnt">
							        		<h4><a href="/index.php?rt=Post&method=View&pid='.$post['id'].'" target="">'.$post['title'].'</a></h4>
							        		<p>'.$this->registry->validator->limit_text($post['body'],400).'</p>
							      		</div>
							      		<div class="cl">&nbsp;</div>
							    	</div>
								';
							}
							else
							{
								echo '<p>'.$noPostMessage.'</p>';
							}
							$i++;
						}
					}
					else
					{
						echo '<p>'.$noPostMessage.'</p>';
					}
		            echo $this->registry->Steam->pagination('user_post',$per_page,$page,'?rt=User&method=Posts&', "uid = $uid");
		            echo '
		            <div class="cl">&nbsp;</div>
		          </div>
		        </div>
		      </div>
		      
		      <script>
         $(function() {
            $( "#rust_news_button" ).button();
         });
      </script>';
	}
	
	public function Videos()
	{
		$this->registry->pageTitle = 'My Videos | iLLuSioN GrOuP';
	}
	
	public function Friends()
	{
		$dateTime = new DatesTimes();
		if(isset($_GET['param']))
		{
			//someone elses profile
			$uid = $_GET['param'];
			$user = $this->registry->users->getUserByUid($uid);
			$this->registry->pageTitle = $user['nickname'].'\'s Friends | iLLuSioN GrOuP';
			echo '
			<div class="block">
		        <div class="block-bot">
					<div class="ui-widget-header ui-corner-top titlespacer1">
						<div class="head-cnt"> 
							<span style="float:left;margin-top:7px;margin-left:5px;"><h3>'.$user['nickname'].'\'s Friends</h3></span>
							<div class="cl">&nbsp;</div>
						</div>
			          </div>
			          <div class="row-articles articles">
				          <div id="friends_container">';
			         	 $friends = $this->registry->users->getFriends($uid);
			         	 foreach($friends as $friend)
			         	 {
					  		$dates = explode(' ', $friend['date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$newDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($friend['date']), 'g:i A');
							$newDate .= ' @ '.$newTime;
					  		echo '
					  		<div id="friend_item">
					  			<img src="'.$friend['avatar'].'" width="75px" height="75px" />
					  			<h3>'.$friend['nickname'].'</h3>
					  			<p>Friends since: '.$newDate.'</p>
					  		</div>';	
					  	}
			          
			      echo '</div>
			      	</div>
			     </div>
			</div>
			';
		}
		else
		{
			//our profile
			$this->registry->pageTitle = 'My Friends | iLLuSioN GrOuP';
			
			echo '
			<div class="block">
		        <div class="block-bot">
					<div class="ui-widget-header ui-corner-top titlespacer1">
						<div class="head-cnt"> 
							<span style="float:left;margin-top:7px;margin-left:5px;"><h3>My Friends</h3></span>
							<div class="cl">&nbsp;</div>
						</div>
			          </div>
			          <div class="row-articles articles">
			          	<div id="friends_container">';
			          	$friends = $this->registry->users->getFriends($this->registry->user['uid']);
			          	foreach($friends as $friend)
			          	{
			          		$dates = explode(' ', $friend['date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$newDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($friend['date']), 'g:i A');
							$newDate .= ' @ '.$newTime;
					  		echo '
					  		<div id="friend_item">
					  			<input type="hidden" value="'.$friend['uid'].'"/>
					  			<img src="'.$friend['avatar'].'" width="75px" height="75px" />
					  			<h3>'.$friend['nickname'].'</h3>
					  			<p>Friends since: '.$newDate.'</p>
					  			<a id="unfriendBtn" href="">Unfriend</a>
					  			<a id="blockBtn" href="">Block</a>
					  			<a id="reportBtn" href="">Report</a>
					  		</div>';
					  	}
			          	echo '
			          	</div>
			          </div>
			     </div>
			</div>
			';
		}
		
	}
	
	public function FriendRequest()
	{
		$this->registry->pageTitle = 'Friend Requests | iLLuSioN GrOuP';
		echo '
		<div class="block">
	        <div class="block-bot">
				<div class="ui-widget-header ui-corner-top titlespacer1">
					<div class="head-cnt"> 
						<span style="float:left;margin-top:7px;margin-left:5px;"><h3>Friend Requests</h3></span>
						<div class="cl">&nbsp;</div>
					</div>
		          </div>
		          <div class="row-articles articles">
		          	<div id="requestDialog" style="display:none;"><div id="requestInfo"></div></div>
		          	<div id="request_container" style="padding:10px;">';
		          	$requests = $this->registry->users->getFriendRequests($this->registry->user['uid']);
		          	foreach($requests as $request)
		          	{
						//get the information of the user that sent the Request
						$user = $this->registry->users->getUserByUid($request['uid']);
						$dateTime = new DatesTimes();
						if($request['approved'] == 0)
						{
							//remove this request from the Notifications
							$this->registry->users->deleteNotification($request['nid']);
							$dates = explode(' ', $request['date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$newDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($request['date']), 'g:i A');
							$newDate .= ' @ '.$newTime;
							echo '
							<div id="request_item" style="padding:5px;background-color:#3d3d3d;height:75px;">
								<img width="75" height="75" src="'.$user['avatar'].'" style="float:left;" />
								<h3><a href="user/profile/'.$user['uid'].'">'.$user['nickname'].'</a> sent you a friend request!</h3>
								<div id="request_dates">
									<span class="request_sent_date">Sent: '.$newDate.'</span>
								</div>
								<div id="requestBtns" style="position:absolute;bottom:15px;right:15px;">
									<input type="hidden" value="'.$request['id'].'" />
									<a href="#" onclick="return false;" id="approveBtn'.$request['id'].'">Approve</a>
									<a href="#" onclick="return false;" id="denyBtn'.$request['id'].'">Deny</a>
								</div>
							</div>
							<script>
							$("#approveBtn'.$request['id'].'").button();
							$("#approveBtn'.$request['id'].'").on("click",function(e){
								e.stopImmediatePropagation();
								rid = $(this).siblings("input").val();
								$.ajax({
									url:"/json/approveFriend/?ajax=true&rid="+rid+"&f_uid="+'.$user['uid'].',
									cache:false,
									success:function(e){
										window.location.href="/user/friendrequest/?approved=true";
									}
								});
							});
							$("#denyBtn'.$request['id'].'").button();
							$("#denyBtn'.$request['id'].'").on("click",function(e){
								e.stopImmediatePropagation();
								rid = $(this).siblings("input").val();
								$.ajax({
									url:"/json/denyFriend/?ajax=true&rid="+rid+"&f_uid="+'.$user['uid'].',
									cache:false,
									success:function(e){
										winow.location.href="/user/friendrequest/?&denied=true";
									}
								});
							});
							</script>
							';
						}
						elseif($request['approved'] == 2)
						{
							//remove this request from the Notifications
							$this->registry->users->deleteNotification($request['d_nid']);
							//deny request this user has sent out
							$dates = explode(' ', $request['denied_date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$deniedDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($request['denied_date']), 'g:i A');
							$deniedDate .= ' @ '.$newTime;

							
							$dates = explode(' ', $request['date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$newDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($request['date']), 'g:i A');
							$newDate .= ' @ '.$newTime;
							echo '
							<div id="request_item" style="padding:5px;background-color:#3d3d3d;height:75px;">
								<img width="75" height="75" src="'.$user['avatar'].'" style="float:left;" />
								<h3>You denied <a href="/user/profile/'.$user['uid'].'">'.$user['nickname'].'\'s</a> friend request!</h3>
								<div id="request_dates">
									<span class="request_sent_date">Sent: '.$newDate.'</span>
									<span class="request_denied_date">Denied: '.$deniedDate.'</span>
								</div>
								<div id="requestBtns" style="position:absolute;bottom:15px;right:15px;">
									<input type="hidden" value="'.$request['id'].'" />
									<a href="#" onclick="return false;" id="approveBtn'.$request['id'].'">Approve</a>
								</div>
							</div>
							<script>
							$("#approveBtn'.$request['id'].'").button();
							$("#approveBtn'.$request['id'].'").on("click",function(e){
								e.stopImmediatePropagation();
								rid = $(this).siblings("input").val();
								$.ajax({
									url:"/json/approveFriend/?ajax=true&rid="+rid+"&f_uid="+'.$user['uid'].',
									cache:false,
									success:function(e){
										window.location.href="/user/friendrequest/?approved=true");
									}
								});
							});
							</script>';
						}
						else
						{
							//remove this request from the Notifications
							$this->registry->users->deleteNotification($request['a_nid']);
							//display accepted request
							$dates = explode(' ', $request['approved_date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$approvedDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($request['approved_date']), 'g:i A');
							$approvedDate .= ' @ '.$newTime;
							
							$dates = explode(' ', $request['date']);
							$time = $dates[1];
							$date = $dates[0];
							
							$splitTime = explode(':',$time);
							
							$newDate = $dateTime->showDateBasedOffToday($date);
							$newTime = date_format(date_create($request['date']), 'g:i A');
							$newDate .= ' @ '.$newTime;
							echo '
							<div id="request_item" style="padding:5px;background-color:#3d3d3d;height:75px;">
								<img width="75" height="75" src="'.$user['avatar'].'" style="float:left;" />
								<h3>You approved <a href="/user/profile/'.$user['uid'].'">'.$user['nickname'].'\'s</a> friend request!</h3>
								<div id="request_dates">
									<span class="request_sent_date">Sent: '.$newDate.'</span>
									<span class="request_approved_date">Approved: '.$approvedDate.'</span>
								</div>
								<div id="requestBtns" style="position:absolute;bottom:15px;right:15px;">
									<input type="hidden" value="'.$request['id'].'" />
									<a href="#" onclick="return false;" id="unfriendBtn'.$request['id'].'">Unfriend</a>
								</div>
							</div>
							<script>
							$("#unfriendBtn'.$request['id'].'").button();
							$("#unfriendBtn'.$request['id'].'").on("click",function(e){
								e.stopImmediatePropagation();
								rid = $(this).siblings("input").val();
								$.ajax({
									url:"/json/unfriend/?ajax=true&rid="+rid+"&f_uid="+'.$user['uid'].',
									cache:false,
									success:function(e){
										window.location.href="/user/friendrequest/?unfriend=true";
									}
								});
							});
							</script>';
						}
					}
		          echo '
		          	</div>
		          </div>
				</div>
			</div>	
					
		';
	}
}
?>
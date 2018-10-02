<?php
class steamlogin
{
	public $registry;
	public $dbh;
	public $api;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->dbh = $this->registry->db;
		$this->api = "81447548332DB950551680BC5A39DDAD";
	}
	
	public function saveGameNews($db,$appid, $filename)
	{
		$sql = "DELETE FROM $db";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$api = $this->api;
		$getcontent = file_get_contents("http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=$appid&count=1000&maxlength=500&format=json");
        
        $data = json_decode($getcontent);
        foreach($data->appnews->newsitems as $news)
        {
        	$found = preg_match_all('/(https?:\/\/\S+\.(?:jpg|png|gif))\s+/', $news->contents, $image);
			if($found != false)
			{
				$sql = "INSERT INTO $db (title,image,content,url,author) VALUES(:title,:image,:content,:url,:author)";
            	$stmt = $this->dbh->prepare($sql);
            	$stmt->bindParam(':title',$news->title, PDO::PARAM_STR);
            	$stmt->bindParam(':image',$image[0][0], PDO::PARAM_STR);
            	$stmt->bindParam(':content',$news->contents, PDO::PARAM_STR);
            	$stmt->bindParam(':url',$news->url, PDO::PARAM_STR);
            	$stmt->bindParam(':author',$news->author, PDO::PARAM_STR);
            	$stmt->execute();
			}
			else
			{
				$image = $filename;
				$sql = "INSERT INTO $db (title,image,content,url,author) VALUES(:title,:image,:content,:url,:author)";
            	$stmt = $this->dbh->prepare($sql);
            	$stmt->bindParam(':title',$news->title, PDO::PARAM_STR);
            	$stmt->bindParam(':image',$image, PDO::PARAM_STR);
            	$stmt->bindParam(':content',$news->contents, PDO::PARAM_STR);
            	$stmt->bindParam(':url',$news->url, PDO::PARAM_STR);
            	$stmt->bindParam(':author',$news->author, PDO::PARAM_STR);
            	$stmt->execute();
			}
        }
	}
	
	public function pagination($db,$per_page=10,$page=1,$url='?', $where = null)
	{
		if ($where != null)
		{
			$sql = "SELECT count(*) FROM $db WHERE $where";
		}
		else
		{
			$sql = "SELECT count(*) FROM $db";	
		}
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$total = $stmt->fetchColumn(); 
		$adjacents = "2"; 
      
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $lastlabel = "Last &rsaquo;&rsaquo;";
	    
	    $page = ($page == 0 ? 1 : $page);  
	    $start = ($page - 1) * $per_page;                               
	      
	    $prev = $page - 1;                          
	    $next = $page + 1;
	    
	    $lastpage = ceil($total/$per_page);
	    $lpm1 = $lastpage - 1; // //last page minus 1
	    
	    $pagination = "";
	    if($lastpage > 1){   
	        $pagination .= "<ul class='pagination'>";
	        $pagination .= "<li class='page_info'>Page {$page} of {$lastpage}</li>";
	              
	        if ($page > 1) $pagination.= "<li><a href='{$url}page={$prev}'>{$prevlabel}</a></li>";
	              
	        if ($lastpage < 7 + ($adjacents * 2)){   
	            for ($counter = 1; $counter <= $lastpage; $counter++){
	                if ($counter == $page)
	                    $pagination.= "<li><a class='current'>{$counter}</a></li>";
	                else
	                    $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
	            }
	          
	        } elseif($lastpage > 5 + ($adjacents * 2)){
	              
	            if($page < 1 + ($adjacents * 2)) {
	                  
	                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
	                    if ($counter == $page)
	                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
	                    else
	                        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
	                }
	                $pagination.= "<li class='dot'>...</li>";
	                $pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
	                $pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";  
	                      
	            } elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
	                  
	                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
	                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
	                $pagination.= "<li class='dot'>...</li>";
	                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
	                    if ($counter == $page)
	                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
	                    else
	                        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
	                }
	                $pagination.= "<li class='dot'>..</li>";
	                $pagination.= "<li><a href='{$url}page={$lpm1}'>{$lpm1}</a></li>";
	                $pagination.= "<li><a href='{$url}page={$lastpage}'>{$lastpage}</a></li>";      
	                  
	            } else {
	                  
	                $pagination.= "<li><a href='{$url}page=1'>1</a></li>";
	                $pagination.= "<li><a href='{$url}page=2'>2</a></li>";
	                $pagination.= "<li class='dot'>..</li>";
	                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
	                    if ($counter == $page)
	                        $pagination.= "<li><a class='current'>{$counter}</a></li>";
	                    else
	                        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter}</a></li>";                    
	                }
	            }
	        }
	          
	            if ($page < $counter - 1) {
	                $pagination.= "<li><a href='{$url}page={$next}'>{$nextlabel}</a></li>";
	                $pagination.= "<li><a href='{$url}page=$lastpage'>{$lastlabel}</a></li>";
	            }
	          
	        $pagination.= "</ul>";        
	    }
	      
	    return $pagination;
	}
	
	public function isOnline($sid)
	{
		$api = $this->api;
		$getcontent = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$api&steamids=$sid");
        
		$data = json_decode($getcontent);
		
		$onlineStatus = $data->response->players[0]->personastate;
		return $onlineStatus;
		
	}
	
	public function getGameNews($db, $per_page=10,$page=1,$url='?',$pager = true)
	{
		$page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
		if ($page <= 0) $page = 1;
		 
		$per_page = 10; // Set how many records do you want to display per page.
		 
		$startpoint = ($page * $per_page) - $per_page;
		
		//$results = mysqli_query($conDB,"SELECT * FROM {$statement} LIMIT {$startpoint} , {$per_page}");
 
		
		
		$sql = "SELECT * FROM $db ORDER BY id ASC LIMIT :startpoint , :per_page";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':startpoint',$startpoint, PDO::PARAM_INT);
		$stmt->bindParam(':per_page',$per_page, PDO::PARAM_INT);
		$stmt->execute();
		$news = $stmt->fetchAll();
	
		
        $string = '';
        $i = 1;
        if ($pager)
			$string .= $this->pagination($db,$per_page,$page,$url);
        foreach($news as $n)
        {
			if ($i == $per_page)
			{
				$string .= '<div class="article last-article">';
			}
			else
			{
				$string .= '<div class="article">';
			}	
			$i++;
			$found = preg_match_all('/(https?:\/\/\S+\.(?:jpg|png|gif))\s+/', $n['content'], $image);
			if($found != false)
			{
				$string .= '
		        	<div class="cl">&nbsp;</div>
		        	<div class="image"> <a target="_blank" href="'.$n['url'].'"><img src="'.$n['image'].'" alt="" /></a> </div>
		      		<div class="cnt">
		        		<h4><a target="_blank" href="'.$n['url'].'">'.$n['title'] . '</a></h4>
		        		<p>' . $n['content'] .'</p>
		      		</div>
		      		<div class="cl">&nbsp;</div>
		    	</div>
				';
			}
			else
			{
				$string .= '
		        	<div class="cl">&nbsp;</div>
		        	<div class="image"> <a target="_blank" href="'.$n['url'].'"><img src="http://illusiongroup.us/uploads/games/'.$db.'/'.$n['image'].'" alt="" /></a> </div>
		      		<div class="cnt">
		        		<h4><a target="_blank" href="'.$n['url'].'">'.$n['title'] . '</a></h4>
		        		<p>' . $n['content'] . '</p>
		      		</div>
		      		<div class="cl">&nbsp;</div>
		    	</div>
				';
			}	
		}	
		if ($pager)
			$string .= $this->pagination($db,$per_page,$page,$url);
		return $string;
	}
	
	public function showLink()
	{
		/*
        DATABASE INFO FOR steamids
        1       userid  int(11) //when u have login and register system, here u put user's id
        2       steamid varchar(30)
        3       profileurl      text
        4       nickname        varchar(50)
        5       name    varchar(50)    
        6       avatar  text
        */
        
        // DOWNLOAD IT HERE IF U DONT HAVE IT
        // http://pastebin.com/xqEgaeX9
       $api = $this->api;
       
        $OpenID = new openid("illusiongroup.us");
        
		if(!$OpenID->mode) {
            $OpenID->identity = "https://steamcommunity.com/openid/";
            $logmein = $OpenID->authUrl();
           
            echo "<a href=$logmein><img src='css/images/steamlogin.png' /></a>";
           
        } elseif($OpenID->mode == 'cancel') {
        	echo 'User has canceled authentication!';
        } else {
            $_SESSION['T2SteamAuth'] = $OpenID->validate() ? $OpenID->identity : null;
            $_SESSION['T2SteamID64'] = str_replace("http://steamcommunity.com/openid/id/", "", $_SESSION['T2SteamAuth']);
            if($_SESSION['T2SteamAuth'] !== null) 
            {
                $steamidonly = $_SESSION['T2SteamID64'];	
                $user = $this->registry->users->getUserBySid($steamidonly);
                if (!$user)
                {
                	//user needs to be registered because they dont exists in database
                	$this->registry->users->RegisterUserSid($steamidonly);
                	//$query = mysql_query("INSERT INTO users VALUES('$steamidonly','','','','')");
            	}
            	$_SESSION['illg_sid'] = $steamidonly;
            	
        		$data = $this->registry->data->getData("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$api&steamids=$steamidonly");
		        
		        $user_steamid = $data->response->players[0]->steamid;
		        $personaname = $data->response->players[0]->personaname;
		        $profileurl = $data->response->players[0]->profileurl;
		        $avatarfull = $data->response->players[0]->avatarfull;
		        $steamVisibility = $data->response->players[0]->communityvisibilitystate;
		        $steamLastLogOff = $data->response->players[0]->lastlogoff;
		        $avatarMedium = $data->response->players[0]->avatarmedium;
		        $avatarSmall = $data->response->players[0]->avatar;
		        $publicUpdateQuery = '';
		        if ($steamVisibility == 3)
		        {
		        	$realname = $data->response->players[0]->realname;
		        	$primaryclanid = $data->response->players[0]->primaryclanid;
		        	$timecreated = $data->response->players[0]->timecreated;
		        	$currentplayinggame = $data->response->players[0]->gameid;
		        	$gameserverip = $data->response->players[0]->gameserverip;
		        	$gameextrainfo = $data->response->players[0]->gameextrainfo;
		        	$loccountrycode = $data->response->players[0]->loccountrycode;
		        	$locstatecode = $data->response->players[0]->locstatecode;
		        	//$loccityid = $data->response->players[0]->loccityid;
		        	
					$publicUpdateQuery = ', name = :name, primaryclanid = :primaryclanid, timecreated = :timecreated, currentplayinggame = :currentplayinggame, gameserverip = :gameserverip, gameextrainfo = :gameextrainfo, loccountrycode = :loccountrycode, locstatecode = :locstatecode';
				}
		        $sql = "UPDATE users SET profileurl = :profileurl, nickname = :nickname, avatar = :avatar, steamVisibility = :steamVisibility, steamLastLogOff = :steamLastLogOff, avatarMedium = :avatarMedium, avatarSmall = :avatarSmall$publicUpdateQuery WHERE sid = :sid";
		        $stmt = $this->dbh->prepare($sql); 
		        $stmt->bindParam(':profileurl',$profileurl);
		        $stmt->bindParam(':nickname',$personaname);
		        $stmt->bindParam(':avatar',$avatarfull);
		        $stmt->bindParam(':steamVisibility',$steamVisibility);
		        $stmt->bindParam(':steamLastLogOff',$steamLastLogOff);
		        $stmt->bindParam(':avatarMedium',$avatarMedium);
		        $stmt->bindParam(':avatarSmall',$avatarSmall);
		        $stmt->bindParam(':sid',$steamidonly);
		        if ($steamVisibility == 3)
		        {
		        	$stmt->bindParam(':name',$realname);
			        $stmt->bindParam(':primaryclanid',$primaryclanid);
			        $stmt->bindParam(':timecreated',$timecreated);
			        $stmt->bindParam(':currentplayinggame',$currentplayinggame);
			        $stmt->bindParam(':gameserverip',$gameserverip);
			        $stmt->bindParam(':gameextrainfo',$gameextrainfo);
			        $stmt->bindParam(':loccountrycode',$loccountrycode);
			        $stmt->bindParam(':locstatecode',$locstatecode);	
			        //$stmt->bindParam(':loccityid',$loccityid);
		        }
		        $stmt->execute();
		        
		        
		        $data = $this->registry->data->getData("https://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?key=$api&steamid=$steamidonly");
		        //add steam level and badges to database
		        $sql = "UPDATE users SET steamlevel = :steamlevel WHERE sid = :sid";
		        $stmt = $this->dbh->prepare($sql);
		        
		        $this->registry->users->updateOwnedGames($steamidonly);
		       
            }
            redirect("index.php?rt=User&method=Profile"); //change it to your file
        }
	}
}
?>
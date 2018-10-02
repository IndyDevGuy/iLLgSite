<?php
class users
{
	public $registry;
	public $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}
	
	public function getUsers()
	{
		$sql = 'SELECT * FROM users';
		return $this->db->query($sql);
	}
	
	public function getUserByUid($uid)
	{
		$sth = $this->db->query("SELECT * FROM users WHERE uid = '$uid'");
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getUserBySid($sid)
	{
		$sth = $this->db->query("SELECT * FROM users WHERE sid = '$sid'");
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function RegisterUserSid($sid)
	{
		$sql = "INSERT INTO users(sid) VALUES(:sid)";
    	$stmt = $this->db->prepare($sql);
    	$stmt->bindParam(':sid',$sid, PDO::PARAM_STR);
    	$stmt->execute();
	}
	
	public function searchUsers($q)
	{
		$sql = 'SELECT * FROM users WHERE nickname LIKE :q';
		$stmt = $this->db->prepare($sql);
		$q = '%'.$q.'%';
		$stmt->bindParam(':q',$q,PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	
	public function UsersFriendList($uid)
	{
		$sql = 'SELECT * FROM user_friends WHERE uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	
	public function updateOwnedGames($sid,$gameInfo = true, $includeFree = true)
	{
		$api = "81447548332DB950551680BC5A39DDAD";;
		$data = $this->registry->data->getData("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=$api&steamid=$sid&include_appinfo=$gameInfo&include_played_free_games=$includeFree&format=json");
		$data = $data->response;
		$gameCount = $data->game_count;
		$sql = 'UPDATE users SET game_count = :game_count WHERE sid = :sid';
		$stmt = $this->db->prepare($sql); 
		$stmt->bindParam(':game_count',$gameCount, PDO::PARAM_INT);
		$stmt->bindParam(':sid',$sid, PDO::PARAM_INT);
		$stmt->execute();
		
		$this->deleteUserGames($sid);

		foreach ($data->games as $game)
		{
			$sql = 'INSERT INTO user_games (sid, appid, name, playtime_2weeks, playtime_forever, img_icon_url, img_logo_url, has_community_visible_stats) VALUES(:sid, :appid, :name, :playtime_2weeks, :playtime_forever, :img_icon_url, :img_logo_url, :has_community_visible_stats)';
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':sid', $sid, PDO::PARAM_INT);
			$stmt->bindParam(':appid',$game->appid, PDO::PARAM_INT);
			$stmt->bindParam(':name',$game->name, PDO::PARAM_STR);
			$stmt->bindParam(':playtime_2weeks',$game->playtime_2weeks, PDO::PARAM_INT);
			$stmt->bindParam(':playtime_forever',$game->playtime_forever, PDO::PARAM_INT);
			$stmt->bindParam(':img_icon_url',$game->img_icon_url, PDO::PARAM_STR);
			$stmt->bindParam(':img_logo_url',$game->img_logo_url, PDO::PARAM_STR);
			$stmt->bindParam(':has_community_visible_stats',$game->has_community_visible_stats, PDO::PARAM_STR);
			$stmt->execute();
		}
	}
	
	public function deleteUserGames($sid)
	{
		$sql = "DELETE FROM user_games WHERE sid = :sid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':sid',$sid, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function getOwnedGames($uid)
	{
		
	}
	
	public function getGameAchievements()
	{
		
	}
	
	public function isLoggedInRedirect()
	{
		if (!isset($_SESSION['illg_sid']))
		{
			$this->registry->guest = true;
			redirect('/index.php?errors=login');
		}
		$this->registry->sid = $_SESSION['illg_sid'];
		$this->registry->user = $this->getUserBySid($this->registry->sid);
	}
	
	public function isLoggedIn()
	{
		if (!isset($_SESSION['illg_sid']))
		{
			$this->registry->guest = true;
		}
		else
		{
			$this->registry->guest = false;
			$this->registry->sid = $_SESSION['illg_sid'];
			$this->registry->user = $this->getUserBySid($this->registry->sid);
		}
	}
	
	public function isAdminRedirect()
	{
		$this->isLoggedInRedirect();
		if($this->registry->user['role'] != 1)
		{
			redirect('index.php?rt=User&method=Profile&errors=permissions');
		}
		return false;
	}
	
	
	
	public function getUserRole()
	{
		if (isset($_SESSION['illg_sid']))
		{
			$sid = $_SESSION['illg_sid'];
			$user = $this->getUserBySid($sid);
			return $user['role'];
		}
		else
		{
			return 2;
		}
	}
	
	public function startConversation($to,$from,$message)
	{
		$user = $this->getUserByUid($from);
		$sql = 'INSERT INTO user_messages (to_uid, from_uid, message,date, username, avatar) VALUES(:to_uid,:from_uid,:message,:date, :username, :avatar)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':to_uid',$to, PDO::PARAM_INT);
		$stmt->bindParam(':from_uid',$from,PDO::PARAM_INT);
		$stmt->bindParam(':message',$message,PDO::PARAM_STR);
		$date = date("Y-m-d H:i:s");
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':username', $user['nickname'], PDO::PARAM_STR);
		$stmt->bindParam(':avatar', $user['avatarMedium'], PDO::PARAM_STR);
		$stmt->execute();
		$id = $this->db->lastInsertId();
		$sql = 'UPDATE user_messages SET parent_id = :parent_id WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':parent_id',$id,PDO::PARAM_INT);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
		return $id;
	}
	
	public function sendMessage($to,$from,$message)
	{
		$user = $this->getUserByUid($from);
		$sql = 'INSERT INTO user_messages (to_uid, from_uid, message,date, username, avatar) VALUES(:to_uid,:from_uid,:message,:date, :username, :avatar)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':to_uid',$to, PDO::PARAM_INT);
		$stmt->bindParam(':from_uid',$from,PDO::PARAM_INT);
		$stmt->bindParam(':message',$message,PDO::PARAM_STR);
		$date = date("Y-m-d H:i:s");
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':username', $user['nickname'], PDO::PARAM_STR);
		$stmt->bindParam(':avatar', $user['avatarMedium'], PDO::PARAM_STR);
		$stmt->execute();
		return $this->db->lastInsertId();
	}
	
	public function updateMessageParent($parent_id,$message_id)
	{
		$sql = 'UPDATE user_messages SET parent_id = :parent_id WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':parent_id',$parent_id,PDO::PARAM_INT);
		$stmt->bindParam(':id',$message_id,PDO::PARAM_INT);
		$stmt->execute();
		return $this->db->lastInsertId();;
	}
	
	public function getUserMessages($uid)
	{
		$sql = 'SELECT * FROM user_messages WHERE to_uid = :uid ORDER BY id DESC';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	
	public function seenMessages($uid)
	{
		$sql = 'UPDATE user_messages SET seen = :seen WHERE to_uid = :uid';
		$stmt = $this->db->prepare($sql);
		$num = 1;
		$stmt->bindParam(':seen',$num,PDO::PARAM_INT);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function getMessage($id)
	{
		//update the message and mark it as read
		$sql = 'UPDATE user_messages SET unread = :unread WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$num = 1;
		$stmt->bindParam(':unread', $num, PDO::PARAM_INT);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
		$sql = 'SELECT * FROM user_messages WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
		
	}
	
	public function isFriend($uid,$f_uid)
	{
		$sql = 'SELECT * FROM user_friends WHERE uid = :uid AND f_uid = :f_uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->bindParam(':f_uid',$f_uid,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $r)
		{
			if(isset($r['id']))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	public function getFriendRequests($uid)
	{
		$sql = 'SELECT * FROM friends_request WHERE uid = :uid ORDER BY approved ASC';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function sendFriendRequest($uid,$f_uid)
	{
		$date = date("Y-m-d H:i:s");
		$username = $this->getNickname($f_uid);
		$avatar = $this->getAvatar($f_uid);
		$nid = $this->sendNotification($uid,$username.' sent you a friends request!','/user/friendrequest',$avatar,$date);
		$sql = 'INSERT INTO friends_request (uid,f_uid,date,nid) VALUES(:uid,:f_uid,:date,:nid)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->bindParam(':f_uid',$f_uid,PDO::PARAM_INT);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':nid',$nid,PDO::PARAM_INT);
		$stmt->execute();
		$id = $this->db->lastInsertId();
		return $id;
	}
	
	public function getNickname($uid)
	{
		$sql = 'SELECT nickname FROM users WHERE uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	public function getAvatar($uid)
	{
		$sql = 'SELECT avatar FROM users WHERE uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	public function sendNotification($uid,$content,$link,$image,$date)
	{
		$sql = 'INSERT INTO user_notifications (content, links, uid, image, date) VALUES(:content,:link,:uid, :image, :date)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':content',$content,PDO::PARAM_STR);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':link',$link,PDO::PARAM_STR);
		$stmt->bindParam(':image',$image,PDO::PARAM_STR);
		$stmt->execute();
		return $this->db->lastInsertId();	
	}
	
	public function checkRequestFromUser($myUid,$fUid)
	{
		$sql = 'SELECT * FROM friends_request WHERE uid = :uid AND f_uid = :f_uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$myUid,PDO::PARAM_INT);
		$stmt->bindParam(':f_uid',$fUid,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	public function replyToFriendsRequest($id, $approved)
	{
		switch($approved)
		{
			case 1:
				//user approved
				break;
			default:
				//user denied
				break;	
		}
	}
	
	public function seenNotification($id)
	{
		$sql = 'UPDATE user_notifications SET seen = :seen WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$seen = 1;
		$stmt->bindParam(':seen', $seen, PDO::PARAM_INT);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function approveRequest($id,$f_uid)
	{
		$date = date("Y-m-d H:i:s");
		$content = $this->registry->user['nickname'].' has approved you\'r friend request, write something on their profile!';
		$link = '/user/profile/'.$this->registry->user['uid'];
		$image = $this->registry->user['avatar'];
		$nid = $this->sendNotification($f_uid,$content,$link,$image,$date);
		$sql = 'UPDATE friends_request SET approved=:approved, approved_date=:date, a_nid=:a_nid WHERE id=:id';
		$approved = 1;
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':approved',$approved,PDO::PARAM_INT);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':a_nid',$nid,PDO::PARAM_INT);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
		$sql = 'INSERT INTO user_friends (uid,f_uid,rid,date) VALUES(:uid,:f_uid,:rid,:date)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$this->registry->user['uid'],PDO::PARAM_INT);
		$stmt->bindParam(':f_uid',$f_uid,PDO::PARAM_INT);
		$stmt->bindParam('rid',$id,PDO::PARAM_INT);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->execute();
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$f_uid,PDO::PARAM_INT);
		$stmt->bindParam(':f_uid',$this->registry->user['uid'],PDO::PARAM_INT);
		$stmt->bindParam('rid',$id,PDO::PARAM_INT);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function getFriends($uid)
	{
		$sql = 'SELECT * FROM user_friends WHERE uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		$fList = $stmt->fetchAll();
		$users = array();
		foreach($fList as $list)
		{
			$user = $this->getUserByUid($list['f_uid']);
			$user['date'] = $list['date'];
			$users[] = $user;
		}
		return $users;
	}
	
	public function denyRequest($id,$f_uid)
	{
		$date = date("Y-m-d H:i:s");
		$content = $this->registry->user['nickname'].' has denied you\'r friend request, maybe send them another at a later date?';
		$link = '/user/profile/'.$this->registry->user['uid'];
		$image = $this->registry->user['avatar'];
		$nid = $this->sendNotification($f_uid,$content,$link,$image,$date);
		$sql = 'UPDATE friends_request SET approved=:approved, denied_date=:date, d_nid=:d_nid WHERE id=:id';
		$stmt = $this->db->prepare($sql);
		$approved = 2;
		$stmt->bindParam(':approved',$approved,PDO::PARAM_INT);
		$stmt->bindParam(':date',$date,PDO::PARAM_STR);
		$stmt->bindParam(':d_nid',$nid,PDO::PARAM_INT);
		$stmt->bindParam(':id',$id);
		$stmt->execute();
		
	}
	
	public function deleteNotification($nid)
	{
		$sql = 'DELETE FROM user_notifications WHERE id=:id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id',$nid);
		$stmt->execute();
	}
}
?>
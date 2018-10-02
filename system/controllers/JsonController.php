<?php
class JsonController
{
	protected $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}
	
	public function SearchGameList()
	{
		if (!isset($_GET['q']))
		{
		}
		else
		{
			$q = $_GET['q'];
			$gamesClass = new Games($this->registry);
			$games = $gamesClass->searchGames($q);
			$json = array();
			foreach($games as $game)
			{
				$g = array();
				$g['id'] = $game['appid'];
				$g['name'] = $game['name'];
				$json[] = $g;
			}
			header('Content-Type: application/json');
			echo json_encode($json);
		}
	}
	
	public function saveAllVideoThumbnails()
	{
		if(!isset($_GET['duration']) || !isset($_GET['videoUrl']) || !isset($_GET['size']) || !isset($_GET['videoId']))
		{
		}
		else
		{
			
		}
	}
	
	public function getVideoThumbnail()
	{
		if(!isset($_GET['second']) || !isset($_GET['videoUrl']) || !isset($_GET['size']) || !isset($_GET['videoId']))
		{
		}
		else
		{
			$url = $_GET['videoUrl'];
			$size = $_GET['size'];
			$id = $_GET['videoId'];
			$second = $_GET['second'];
			$second = round($second);
			$ffMPEG = new Ffmpeg($this->registry);
          	$image = $ffMPEG->getVideoThumbnail($url,$size,$second,$id);
          	if($image == 'error')
          	{
				$result = array();
				$result['error'] = 'There was a problem getting this thumbnail!';
				header('Content-Type: application/json');
				echo json_encode($result);
			}
			$result = array();
			$result['success'] = true;
			$result['image'] = 'http://illusiongroup.us/'.$image;
			header('Content-Type: application/json');
			echo json_encode($result);
			
		}
	}
	
	public function SearchUserFriendsList()
	{
		$uid = $_GET['uid'];
		$q = $_GET['q'];
		$friends = $this->registry->users->UsersFriendList($uid);
		$users = $this->registry->users->searchUsers($q);
		$json = array();
		foreach ($users as $user)
		{
			foreach($friends as $friend)
			{
				if($user['uid'] == $friend['f_uid'])
				{
					$q = array();
					$user = $this->registry->users->getUserByUid($friend['f_uid']);
					$q['name'] = '<img width="25" height="25" src="'.$user['avatar'].'"/>'.$user['nickname'];
					$q['id'] = $user['uid'];
					$json[] = $q;
				}
			}
		}
		header('Content-Type: application/json');
		echo json_encode($json);
	}
	
	public function dateFormat()
	{
		$dater = $_GET['date'];
		$dateTime = new DatesTimes();
		$dates = explode(' ', $dater);
		$time = $dates[1];
		$date = $dates[0];
		
		$splitTime = explode(':',$time);
		
		$newDate = $dateTime->showDateBasedOffToday($date);
		$newTime = date_format(date_create($time), 'g:i A');
		$newDate .= ' @ '.$newTime;
		$json['date'] = $newDate;
		header('Content-Type: application/json');
		echo json_encode($json);
	}
	
	public function seenMessages()
	{
		$uid = $_GET['uid'];
		$this->registry->users->seenMessages($uid);
		$json['success'] = true;
		header('Content-Type: application/json');
		echo json_encode($json);
	}
	
	public function getMessageInfo()
	{
		$id = $_GET['id'];
		$message = $this->registry->users->getMessage($id);
		$json = array();
		foreach($message as $mess)
		{
			$user = $this->registry->users->getUserByUid($mess['from_uid']);
			$json['username'] = $user['nickname'];
			$json['uid'] = $user['uid'];
		}
		header('Content-Type: application/json');
		echo json_encode($json);
	}
	
	public function sendFriendRequest()
	{
		$uid = $_GET['f_uid'];
		$f_uid = $this->registry->user['uid'];
		$id = $this->registry->users->sendFriendRequest($uid,$f_uid);
		$result = array();
		$result['success'] = true;
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	
	public function approveFriend()
	{
		$rid = $_GET['rid'];
		$f_uid = $_GET['f_uid'];
		$this->registry->users->approveRequest($rid,$f_uid);	
	}
	
	public function denyFriend()
	{
		$rid = $_GET[''];
		$f_uid = $_GET['f_uid'];
		$this->registry->users->denyRequest($rid,$f_uid);
	}
}
?>
<?php
class SearchController
{
	protected $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}
	
	public function Index()
	{
		if (!isset($_GET['category']) || !isset($_GET['q']))
		{
			redirect('/index.php');
		}
		$category = $_GET['category'];
		$q = $_GET['q'];
		if(isset($_GET['ajax']))
		{
			$result = null;
			switch($category)
			{
				case 'all' :
					break;
				case 'posts' :
					break;
				case 'users' :
					$sql = 'SELECT * FROM users WHERE nickname LIKE :q';
					$stmt = $this->db->prepare($sql);
					$q = '%'.$q.'%';
					$stmt->bindParam(':q',$q,PDO::PARAM_STR);
					$stmt->execute();
					if($this->registry->guest == false)
					{
						$users = $stmt->fetchAll();
						$tempUsers = array();
						foreach($users as $user)
						{
							$tempUser = array();
							$tempUser['nickname'] = $user['nickname'];
							$tempUser['avatar'] = $user['avatar'];
							$tempUser['uid'] = $user['uid'];
							$myUid = $this->registry->user['uid'];
							if($this->registry->users->isFriend($myUid, $user['uid']))
								$tempUser['friends'] = true;
							else
							{
								//check if a request was sent to the user
								$id = $this->registry->users->checkRequestFromUser($user['uid'],$myUid);
								if($id > 0)
									$tempUser['friends'] = 'pending';
								else
									$tempUser['friends'] = false;
							}
							$tempUsers[] = $tempUser;
						}
						$result = $tempUsers;	
					}
					else
					{
						$result = $stmt->fetchAll();
					}
					break;
				case 'clans' :
					break;
				case 'news' :
					break;
				case 'forums':
					break;
				default:
					break;
			}
			$json = $result;
			header('Content-Type: application/json');
			echo json_encode($json);
		}
		else
		{
			echo 'non ajax results';
		}
	}
	
	public function AdvancedSearch()
	{
		echo 'advanced search form with javascript and jquery';
	}
}
?>
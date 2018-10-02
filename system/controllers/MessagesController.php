<?php
class MessagesController
{
	protected $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
		$this->registry->users->isLoggedInRedirect();
	}
	
	public function Index()
	{
		$this->registry->pageTitle = 'My Messages | iLLuSioN GrOuP';
		echo 'My Messages';
	}
	
	public function Compose()
	{
		if (isset($_GET['reply']) && $_GET['reply'] == true)
		{
			$id = $_GET['id'];
			$messages = $this->registry->users->getMessage($id);
			foreach($messages as $message)
			{
				$user = $this->registry->users->getUserByUid($message['from_uid']);
				$ckeditor = new ckeditor($this->registry,'messageEditor');
				echo $user['nickname'] . ' said: <br />'. $message['message'];
				echo '<form action="" method="post">';
				echo '<input id="user_id" type="hidden" value="'.$user['uid'].'" />
				<span id="messageError" class="error" style="display:none;">Message cannot be blank!</span> <br />
				';
				$ckeditor->Display();
				echo '</form>';
			}
		}
		elseif(isset($_POST['send']))
		{
			if(isset($_GET['replied']))
			{
				$ids = explode(',',$_POST['ids']);
				$message = $_POST['message'];
				$uid = $_POST['uid'];
				foreach($ids as $id)
				{
					if($id != 'ids')
					{
						$id = $this->registry->users->sendMessage($id,$uid,$message);
						$id = $this->registry->users->updateMessageParent($_GET['messageId'],$id);
						$json = array();
						if($id > 0)
						{
							//message was sent output success json
							$json['complete'] = true;
						}
						else
						{
							//message was not sent output json
							$json['complete'] = false;
						}
					}
				}
			}
			else
			{
				$ids = explode(',',$_POST['ids']);
				$message = $_POST['message'];
				$uid = $_POST['uid'];
				foreach ($ids as $id)
				{
					if($id != 'ids')
					{
						$id = $this->registry->users->startConversation($id,$uid,$message);
						$json = array();
						if($id > 0)
						{
							//message was sent output success json
							$json['complete'] = true;
						}
						else
						{
							//message was not sent output json
							$json['complete'] = false;
						}
					}
				}
			}
			header('Content-Type: application/json');
			echo json_encode($json);
		}
		else
		{	
			$defaultVal = '';
			if(isset($_GET['errors']))
			{
				$defaultVal = $_GET['to'];
			}
			$this->registry->pageTitle = 'New Message | iLLuSioN GrOuP';
			$multiSelect = new multiSelect($this->registry,'/json/SearchUserFriendsList/?uid='.$this->registry->user['uid'].'&ajax=true','To: ','','friendSelect','friendSelect','Start typing a friends name.','No friends found!','Searching through your friends list.');
			$ckeditor = new ckeditor($this->registry,'messageEditor');
			echo '<form id="messageForm" name="messageForm" method="POST" action="">';
			$multiSelect->Display();
			echo '
			<span id="friendError" class="error" style="width: 90%;display:none;">Please select a friend!</span>
			<span id="messageError" class="error" style="width:90%;display:none;">Message cannot be blank!</span>
			<label for="message">Message: </label>';
			$ckeditor->Display();
			echo '</form>';
		}
	}
	
	public function View()
	{
		if(isset($_GET['ajax']))
		{
			$id = $_GET['id'];
			$messages = $this->registry->users->getMessage($id);
			foreach($messages as $message)
			{
				echo '<p>'.$message['message'].'</p>';
			}
		}
		else
		{
			$this->registry->pageTitle = 'Some Message | iLLuSioN GrOuP';
		}
	}
	
	public function Delete()
	{
		$this->registry->pageTitle = 'Delete Some Message | iLLuSioN GrOuP';
	}
}
?>
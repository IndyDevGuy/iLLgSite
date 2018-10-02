<?php
class NotificationsController
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
		$this->registry->pageTitle = 'Notifications | iLLuSioN GrOuP';
		echo 'All Notifications';
	}
	
	public function Settings()
	{
		$this->registry->pageTitle = 'Notification Settings | iLLuSioN GrOuP';
		echo 'Notification Settings';
	}
	
	public function View()
	{
		if(isset($_GET['ajax']))
		{
			$id = $_GET['id'];
			$this->registry->users->deleteNotification($id);
			return 'true';
		}
		else
		{
			$this->registry->pageTitle = 'Some Message | iLLuSioN GrOuP';
		}
	}
}
?>
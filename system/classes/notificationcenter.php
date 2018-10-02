<?php
class NotificationCenter
{
	protected $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}
	
	public function getUserNotificationCount($uid)
	{
		$sql = "SELECT COUNT(*) FROM user_notifications WHERE uid = :uid AND seen = :seen";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$num = 0;
		$stmt->bindParam(':seen',$num,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	public function getUserMessNotificationCount($uid)
	{
		$sql = "SELECT COUNT(*) FROM user_messages WHERE to_uid = :uid AND seen = :seen";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$num = 0;
		$stmt->bindParam(':seen', $num, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
}
?>
<?php
class post
{
	public $registry;
	public $db;
	
	public function __construct($registry)
	{
		$this->registery = $registry;
		$this->db = $registry->db;
	}	
	
	public function AddPost($uid,$title,$body, $filename)
	{
		$sql = "INSERT INTO user_post(uid,title,body,filename) VALUES(:uid, :title, :body, :filename)";
    	$stmt = $this->db->prepare($sql);
    	$stmt->bindParam(':uid',$uid, PDO::PARAM_INT);
    	$stmt->bindParam(':title',$title, PDO::PARAM_STR);
    	$stmt->bindParam(':body',$body, PDO::PARAM_STR);
    	$stmt->bindParam(':filename',$filename, PDO::PARAM_STR);
    	$stmt->execute();
	}
	
	public function UpdatePost($pid,$title,$body)
	{
		$sql = 'UPDATE user_post SET title = :title, body = :body WHERE pid = :pid';
		$stmt = $this->db->prepare($sql); 
		$stmt->bindParam(':pid',$pid, PDO::PARAM_INT);
		$stmt->bindParam(':title',$title, PDO::PARAM_STR);
		$stmt->bindParam(':body',$body, PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function DeletePost($pid)
	{
		$sql = "DELETE FROM user_post WHERE pid = :pid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':pid',$pid, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function getUserPosts($uid)
	{
		$query = $this->db->prepare("SELECT * FROM user_post WHERE uid = $uid");
		$query->execute();
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		
		return $result;
		
	}
	
	public function getAllPosts()
	{
		
	}
}
?>
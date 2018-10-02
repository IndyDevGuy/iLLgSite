<?php
class ClanPermissions
{
	protected $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}
	
	public function getPerms()
	{
		$query = $this->db->prepare("SELECT * FROM clan_perms");
		$query->execute();
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		
		return $result;
	}
	
	public function addRank($cid,$name,$des)
	{
		$sql = 'INSERT INTO clan_roles(cid,name,description) VALUES(:cid,:name,:description)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid, PDO::PARAM_INT);
		$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		$stmt->bindParam(':description',$des, PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function addPerm($name,$des)
	{
		$sql = 'INSERT INTO clan_perms(name,description) VALUES(:name,:description)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':name',$name,PDO::PARAM_STR);
		$stmt->bindParam(':description',$des,PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function changeRankPerm($cid,$rid,$pid,$active)
	{
		$sql = 'SELECT id FROM clan_roles_perms WHERE cid = :cid AND pid = :pid AND rid = :rid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':pid',$pid,PDO::PARMA_INT);
		$stmt->bindParam(':rid',$rid,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if(isset($result->id))
		{
			$this->updateRankPerm($result->id,$cid,$rid,$pid,$active);
		}
	}
	
	public function rankHasPerm($cid,$rid,$pid)
	{
		$sql = 'SELECT active FROM clan_roles_perms WHERE cid = :cid AND rid = :rid AND pid = :pid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':rid',$rid,PDO::PARAM_INT);
		$stmt->bindParam(':pid',$pid,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if ($result->active == 1)
		{
			return true;
		}
		return false;
	}
	
	protected function updateRankPerm($id,$cid,$rid,$pid,$active)
	{
		$sql = 'UPDATE clan_roles_perms SET cid = :cid, rid = :rid, pid = :pid, active = :active WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':rid',$rid,PDO::PARAM_INT);
		$stmt->bindParam(':pid',$pid,PDO::PARAM_INT);
		$stmt->bindParam(':active',$active,PDO::PARAM_STR);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function addRankPerm($cid,$rid,$active)
	{
		$perms = $this->getPerms();
		foreach($perms as $perm)
		{
			$sql = 'INSERT INTO clan_roles_perms(cid,rid,pid,active) VALUES(:cid,:rid,:pid,:active)';
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
			$stmt->bindParam(':rid',$rid,PDO::PARAM_INT);
			$stmt->bindParam(':pid',$perm['id'],PDO::PARAM_INT);
			$stmt->bindParam(':active',$active,PDO::PARAM_INT);
			$stmt->execute();
		}
		
	}

}
?>
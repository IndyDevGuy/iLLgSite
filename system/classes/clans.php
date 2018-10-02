<?php
class Clans
{
	protected $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}	
	
	public function RanksTitle()
	{
		echo 'ranks title';
	}
	
	public function getUserClans($uid)
	{
		$sql = 'SELECT * FROM user_clans WHERE uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}
	
	public function getRanks($cid)
	{
		$query = $this->db->prepare("SELECT * FROM clan_roles WHERE cid = $cid");
		$query->execute();
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		
		return $result;
	}
	
	public function countRanks($cid)
	{
		$sql = "SELECT * FROM clan_roles WHERE cid = :cid";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->rowCount();
	}
	
	public function addRank($cid,$rid,$name,$description)
	{
		$sql = 'INSERT INTO clan_roles(cid,rid,name,description) VALUES(:cid,:rid,:name,:description)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':rid',$rid,PDO::PARAM_INT);
		$stmt->bindParam(':name',$name,PDO::PARAM_STR);
		$stmt->bindParam(':description',$description,PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function addClanMember($cid,$uid,$rank)
	{
		$sql = 'INSERT INTO user_clans(cid,uid,rank) VALUES(:cid,:uid,:rank)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->bindParam(':rank',$rank,PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function getClanMembers($cid)
	{
		$sth = $this->db->query("SELECT * FROM user_clans WHERE cid = $cid");
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getMemberCount($cid)
	{
		$sql = 'SELECT COUNT(*) FROM user_clans WHERE cid=:cid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
	
	public function userIsMember($cid,$uid)
	{
		$sql = 'SELECT id FROM user_clans WHERE cid = :cid AND uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if (isset($result->id))
		{
			return true;
		}
		return false;
	}
	
	public function getUserRank($uid,$cid)
	{
		$sql = 'SELECT rank FROM user_clans WHERE cid = :cid AND uid = :uid';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':cid',$cid,PDO::PARAM_INT);
		$stmt->bindParam(':uid',$uid,PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		return $result->rank;
	}
	
	public function getClan($cid)
	{
		$sth = $this->db->query("SELECT * FROM clans WHERE cid = $cid");
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function saveClan($name, $tag, $date, $filename, $about, $uid, $age_restrict, $age, $privacy, $app, $games)
	{
		$sql = 'INSERT INTO clans(name, tag, founded_date, logo, age_restrict, age, privacy, admin_id, about, application) VALUES(:name, :tag, :founded_date, :logo, :age_restrict, :age, :privacy, :admin_id, :about, :application)';
		$stmt = $this->db->prepare($sql);
    	$stmt->bindParam(':name',$name, PDO::PARAM_STR);
    	$stmt->bindParam(':tag',$tag, PDO::PARAM_STR);
    	$stmt->bindParam(':founded_date',$date, PDO::PARAM_STR);
    	$stmt->bindParam(':logo',$filename, PDO::PARAM_STR);
    	$stmt->bindParam(':age_restrict',$age_restrict, PDO::PARAM_INT);
    	$stmt->bindParam(':age',$age, PDO::PARAM_INT);
    	$stmt->bindParam(':privacy',$privacy, PDO::PARAM_INT);
    	$stmt->bindParam(':admin_id',$uid, PDO::PARAM_INT);
    	$stmt->bindParam(':about',$about, PDO::PARAM_STR);
    	$stmt->bindParam(':application',$app, PDO::PARAM_INT);
    	$stmt->execute();
    	$id = $this->db->lastInsertId();
    	foreach($games as $game=>$val)
    	{
			$sql = 'INSERT INTO clan_games(cid, gid) VALUES(:cid,:gid)';
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':cid',$id, PDO::PARAM_INT);
			$stmt->bindParam(':gid',$val, PDO::PARAM_INT);
			$stmt->execute();
		}
		return $id;
	}
	
	public function saveApplicationFields($names,$types)
	{
		$i = 0;
		foreach ($names as $name)
		{
			$sql = 'INSERT INTO clan_app_fields(name,type) VALUES(:name,:type)';
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':name',$name,PDO::PARAM_STR);
			$stmt->bindParam(':type', $types[$i], PDO::PARAM_STR);
			$stmt->execute();
		}
	}
	
	public function getClanGames($cid)
	{
		$sql = 'SELECT * FROM clan_games WHERE cid = :cid';
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$result = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		
		return $result;
	}
}
?>
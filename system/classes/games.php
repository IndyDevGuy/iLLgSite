<?php
class Games
{
	protected $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
	}
	
	public function updateGameList()
	{
		$sql = "DELETE * FROM steam_games";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$data = $this->registry->data->getData('http://api.steampowered.com/ISteamApps/GetAppList/v0001/');
		foreach($data->applist->apps->app as $app)
		{
			$sql = 'INSERT INTO steam_games(appid, name) VALUES(:appid, :name)';
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':appid', $app->appid);
			$stmt->bindParam(':name', $app->name);
			$stmt->execute();
		}
	}
	
	public function searchGames($q)
	{
		$query = 'SELECT * FROM steam_games WHERE name LIKE :q';
		$stmt = $this->db->prepare($query);
		$q = '%'.$q.'%';
		$stmt->bindParam(':q', $q, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}
	
	public function getGamesList()
	{
		
	}
	
	public function getGames()
	{
		$query = $this->db->prepare("SELECT * FROM games");
		$query->execute();
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		
		return $result;
	}
	
	public function getGameScreens($id)
	{
		$query = $this->db->prepare("SELECT * FROM games_screenshots WHERE game_id = $id");
		$query->execute();
		$result = array();
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		
		return $result;
	}
	
	public function getGameVideos($id)
	{
		$query = $this->db->prepare("SELECT * FROM games_movies WHERE game_id = $id");
		$query->execute();
		$result = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			$result[] = $row;
		}
		return $result;
	}
	
	public function getGame($id)
	{
		$query = $this->db->prepare("SELECT * FROM games WHERE id = $id");
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}	
}
?>
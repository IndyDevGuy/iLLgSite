<?php
class database
{
	private $user;
	private $password;
	private $database;
	private $server;
	public $db;
	
	public function __construct($user,$password,$database,$server)
	{
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->server = $server;
		$this->initDatabase();
	}
	
	private function initDatabase()
	{
		$this->db = new PDO('mysql:host='.$this->server .';dbname='.$this->database, $this->user, $this->password);	
	}
	
	public function __deconstruct()
	{
		//$this->db = null;
	}
}
?>
<?php
class Google
{
	protected $registry;
	protected $db;
	public $client;
	
	public function __construct($registry,$client_id,$client_secret, $scope, $redirect, $accessType)
	{
		$this->registry= $registry;
		$this->db = $registry->db;
		$this->client = $this->newClient($client_id,$client_secret, $scope, $redirect, $accessType);
	}
	
	public function newClient($client_id,$client_secret, $scope, $redirect, $accessType)
	{
		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setScopes($scope);
		$client->setRedirectUri($redirect);
		$client->setAccessType($accessType);
		return $client;
	}
}
?>
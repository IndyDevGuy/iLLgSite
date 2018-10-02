<?php
class data
{
	public $url;
	
	public function __construct()
	{
		
	}	
	
	public function getData($url)
	{
		$getcontent = file_get_contents($url);
		return json_decode($getcontent);
	}
}
?>
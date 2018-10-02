<?php
class fancyFile
{
	protected $registry;
	protected $siteUrl;
	protected $postUrl;
	protected $deleteUrl;
	protected $fileLocation;
	protected $label;
	protected $name;
	
	public function __construct($registry,$siteUrl,$postUrl,$deleteUrl,$fileLocation,$label)
	{
		$this->registry = $registry;
		$this->registry->siteHeader .= '
		<script type="text/javascript" src="/js/CircularLoader.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script> 
		<script type="text/javascript" src="/js/fancyFile.jquery.js"></script>
		<link rel="stylesheet" href="/css/fancyFile.css" type="text/css" media="all" />
		';
		$this->siteUrl = $siteUrl;
		$this->postUrl = $postUrl;
		$this->deleteUrl = $deleteUrl;
		$this->fileLocation = $fileLocation;
		$this->label = $label;
		$this->name = str_replace(' ','_',$this->label);
	}
	
	public function setSettings()
	{
		echo '
		<script>
			$("#'.$this->name.'").fancyFile({
				siteUrl : \''.$this->siteUrl.'\',
				postUrl : \''.$this->postUrl.'\',
				deleteUrl : \''.$this->deleteUrl.'\',
				fileLocation : \''.$this->fileLocation.'\'
			});
		</script>
		';
	}
	
	public function Display()
	{
		echo '
		<label for="'.$this->name.'">'.$this->label.'</label>
		<div id="'.$this->name.'"></div>
		';
		$this->setSettings();
	}
	
}
?>
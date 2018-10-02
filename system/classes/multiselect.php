<?php
class multiSelect
{
	protected $registry;
	protected $url;
	protected $label;
	protected $defaultValue;
	protected $name;
	protected $idName;
	protected $helpText;
	protected $noResultsText;
	protected $searchingText;
	
	public function __construct($registry, $url, $label,$defaultValue, $name = 'games', $idName = 'multiSelect',$helpText = 'Start typing a search term.',$noResultText = 'No results.',$searchingText = 'Searching..')
	{
		$this->registry = $registry;
		$this->url = $url;
		$this->name = $name;
		$this->label = $label;
		$this->registry->siteHeader .= '
		<script type="text/javascript" src="/js/jquery.tokeninput.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/token-input.css" />
		';
		$this->idName = $idName;
		$this->helpText = $helpText;
		$this->noResultsText = $noResultText;
		$this->searchingText = $searchingText;
	}
	
	public function setSettings()
	{
		echo '
		<script>
		$("#'.$this->idName.'").tokenInput("'.$this->url.'", {
			hintText: "'.$this->helpText.'",
			noResultsText: "'.$this->noResultsText.'",
			searchingText: "'.$this->searchingText.'"
		});
		</script>
		';
	}
	
	public function Display()
	{
		echo '
		<label for="'.$this->name.'">'.$this->label .'</label>
		<input type="text" name="'.$this->name.'" id="'.$this->idName.'" value="'.$this->defaultValue.'"/>
		';
		$this->setSettings();
	}
}
?>
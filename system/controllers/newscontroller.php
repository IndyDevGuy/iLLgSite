<?php
class newscontroller
{
	public $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}
	
	public function Index()
	{
		$this->registry->pageTitle = 'Global News | iLLuSioN GrOuP';
	}
	
	public function Site()
	{
		$this->registry->pageTitle = 'Site News | iLLuSioN GrOuP';
	}
	
	public function Games()
	{
		$games = new Games($this->registry);
		$view = 'default';
		if (isset($_GET['param']))
		{
			$view = $_GET['param'];
		}
		if ($view != 'default')
		{
			$game = $games->getGame($view);
			$page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
			$this->registry->pageTitle = $game['name'].' News | iLLuSioN GrOuP';
			$string = '
			
			<div class="block">
	        <div class="block-bot">
	          <div class="ui-widget-header ui-corner-top titlespacer">
	            <div class="head-cnt"> 
	              <h3>Official Rust News</h3>
	              <div class="cl">&nbsp;</div>
	            </div>
	          </div>
	          <div class="row-articles articles">
	            <div class="cl">&nbsp;</div>
	            '.$this->registry->Steam->getGameNews($game['news_db'],10,$page,'/news/games/'.$game['id'].'/?') . '
	            <div class="cl">&nbsp;</div>
	          </div>
	        </div>
	      </div>
		
		';
		echo $string;
		}
		else
		{
			
		}
	}
}
?>
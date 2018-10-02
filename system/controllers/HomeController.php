<?php
class HomeController
{
	public $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}
	
	public function Index()
	{
		$this->registry->pageTitle = 'Home | iLLuSioN GrOuP';
		echo '
		
		 <div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt"> <a href="http://all-free-download.com/free-website-templates/" class="view-all">view all</a>
              <h3>Top Reviews</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="col-articles articles">
            <div class="cl">&nbsp;</div>
            <div class="article">
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img4.jpg" alt="" /></a> </div>
              <h4><a href="http://all-free-download.com/free-website-templates/">F.E.A.R.2</a></h4>
              <p class="console"><strong>PSP3</strong></p>
            </div>
            <div class="article">
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img5.jpg" alt="" /></a> </div>
              <h4><a href="http://all-free-download.com/free-website-templates/">FALLOUT 3</a></h4>
              <p class="console"><strong>PC</strong></p>
            </div>
            <div class="article">
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img6.jpg" alt="" /></a> </div>
              <h4><a href="http://all-free-download.com/free-website-templates/">STARCRAF II</a></h4>
              <p class="console"><strong>PC</strong></p>
            </div>
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
      <div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer1">
            <div class="head-cnt"> <a style="float:right;margin-top:3px;" id="rust_news_button" href="index.php?rt=News&method=Rust" class="">View All</a>
              <span style="float:left;margin-top:7px;margin-left:5px;"><h3>Official Rust News</h3></span>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="row-articles articles">
            <div class="cl">&nbsp;</div>
           
           <a id="button" href="http://illusiongroup.us/fraps.rar">Fraps Download</a>
           
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
      <script>
      	$("#button").button();
         $(function() {
            $( "#rust_news_button" ).button();
         });
      </script>
		
		
		
		';
	}
}
?>
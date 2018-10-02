<?php
class notfoundcontroller 
{
	public function __construct()
	{
		
	}
	
	public function index()
	{
		echo '
		<div class="block">
        <div class="block-bot">
          <div class="head">
            <div class="head-cnt"> 
            <span style="font-size: 2em;float:right;padding-right:10px;color:#000;" class="typcn typcn-warning"></span>
              <h3>Page not found!</h3>
              
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="row-articles articles">
	          <div class="article">
	              <div class="cl">&nbsp;</div>
	              <div class="image"> <a href="http://www.lanordica-extraflame.com/images/404.png"><img alt="" src="css/images/404.png"></a> </div>
	              <div class="cnt">
	                <h4><a href="http://all-free-download.com/free-website-templates/">Whoops!</a> </h4>
	                <p>We are sorry but the page you requested was not found on our servers!</p>
	              </div>
	              <div class="cl">&nbsp;</div>
	            </div>
	            </div>
        </div>
      </div>
		';
	}	
}
?>
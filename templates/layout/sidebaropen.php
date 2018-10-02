<?php

?>
</div>
    <!-- / Content -->
    <!--  -->
    <div id="sidebar">
      <!-- Search -->
      <div id="search" class="block">
        <div class="block-bot">
          <div class="block-cnt">
            <form action="http://all-free-download.com/free-website-templates/" method="post">
              <div class="cl">&nbsp;</div>
              <div class="fieldplace">
                <input type="text" class="field" value="Search" title="Search" />
              </div>
              <input type="submit" class="button" value="GO" />
              <div class="cl">&nbsp;</div>
            </form>
          </div>
        </div>
      </div>
      <!-- / Search -->
      <!-- Sign In -->
      <div id="sign" class="block">
        <div class="block-bot">
         	
         <?php 
         if (!isset($_SESSION['illg_sid']))
         {
		 	?>
         
         <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt"> 
             <span style="font-size: 2em;float:right;padding-right:10px;color:#000;" class="typcn typcn-group"></span>
              <h3>User Login</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="block-cnt">
          
            <div class="cl">&nbsp;</div>
            <div class="image-articles articles">
          <div class="article">  
            <?php $steamLogin = new steamlogin($registry);?>
             <p style="text-align: center;"><?php $steamLogin->showLink(); ?></p>
            <div class="cl">&nbsp;</div>
          </div>
          </div>
          <?php
          } else {
		  	?>
		  	
		  	<div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt"> 
             <span style="font-size: 2em;float:right;padding-right:10px;color:#000;" class="typcn typcn-group"></span>
              <h3>Account Dashboard</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="block-cnt">
          
            <div class="cl">&nbsp;</div>
          <div class="image-articles articles">
            <div id="userNotifyCount"></div>
             <ul id="menu">
             <li><a href="/user/profile">My Profile</a></li>
             <li><a href="/user/clan">My Clan</a></li>
             <li><a href="/user/videos">My Videos</a></li>
             <li><a href="/user/posts">My Posts</a></li>
             <li><a href="/user/logout">Logout</a></li>
             </ul>
             <script>
             	$( "#menu" ).menu();
             </script>
            <div class="cl">&nbsp;</div>
            
          </div>
		  	
		  	<?php
		  } 
		  ?>
		  </div>
        	<div class="article" style="text-align:center;">
        		

        	</div>
        	<?php
        	$role = $registry->users->getUserRole();
        	if ($role == 1)
		  { ?>
		  	<div class="ui-widget-header ui-corner-top titlespacer">
              <div class="head-cnt">
            <h3>Admin Links</h3>
            </div>
          </div>
          <div class="block-cnt">
		   <div class="image-articles articles">
          
          	<ul id="admin_menu">  
          	<li><a href="/admin">Admin Panel</a></li>
             <li><a href="/admin/users">All Users</a></li>
             <li><a href="/admin/addGame">Add A Steam Game</a></li>
             </ul>
             <script>$( "#admin_menu" ).menu();</script>
            <div class="cl">&nbsp;</div>
            </div>
          </div>
		  <?php }
		   ?>
        </div>
      </div>
      <!-- / Sign In -->
      <div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
              <div class="head-cnt">
            <h3>Top Games</h3>
            </div>
          </div>
          <div class="image-articles articles">
            <div class="cl">&nbsp;</div>
            <div class="article">
              <div class="cl">&nbsp;</div>
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img1.gif" alt="" /></a> </div>
              <div class="cnt">
                <h4><a href="http://all-free-download.com/free-website-templates/">TMNT</a></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie </p>
              </div>
              <div class="cl">&nbsp;</div>
            </div>
            <div class="article">
              <div class="cl">&nbsp;</div>
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img2.gif" alt="" /></a> </div>
              <div class="cnt">
                <h4><a href="http://all-free-download.com/free-website-templates/">F.E.A.R.2</a></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie </p>
              </div>
              <div class="cl">&nbsp;</div>
            </div>
            <div class="article">
              <div class="cl">&nbsp;</div>
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img3.gif" alt="" /></a> </div>
              <div class="cnt">
                <h4><a href="http://all-free-download.com/free-website-templates/">Steel Fury</a></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie </p>
              </div>
              <div class="cl">&nbsp;</div>
            </div>
            <div class="cl">&nbsp;</div>
            <a href="http://all-free-download.com/free-website-templates/" class="view-all">view all</a>
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
      <div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt">
              <h3>Top Videos</h3>
            </div>
          </div>
          <div class="image-articles articles">
            <div class="cl">&nbsp;</div>
            <div class="article">
              <div class="cl">&nbsp;</div>
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img1.gif" alt="" /></a> </div>
              <div class="cnt">
                <h4><a href="http://all-free-download.com/free-website-templates/">FALLOUT3</a></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie </p>
              </div>
              <div class="cl">&nbsp;</div>
            </div>
            <div class="article">
              <div class="cl">&nbsp;</div>
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img2.gif" alt="" /></a> </div>
              <div class="cnt">
                <h4><a href="http://all-free-download.com/free-website-templates/">Crysis</a></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie </p>
              </div>
              <div class="cl">&nbsp;</div>
            </div>
            <div class="article">
              <div class="cl">&nbsp;</div>
              <div class="image"> <a href="http://all-free-download.com/free-website-templates/"><img src="css/images/img3.gif" alt="" /></a> </div>
              <div class="cnt">
                <h4><a href="http://all-free-download.com/free-website-templates/">F.E.A.R.2</a></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie </p>
              </div>
              <div class="cl">&nbsp;</div>
            </div>
            <div class="cl">&nbsp;</div>
            <a href="http://all-free-download.com/free-website-templates/" class="view-all">view all</a>
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
      <div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt">
              <h3>Latest Articles</h3>
            </div>
          </div>
          <div class="text-articles articles">
            <div class="article">
              <h4><a href="http://all-free-download.com/free-website-templates/">Dolor amet sodales leo</a></h4>
              <small class="date">21.07.09</small>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie urna, id scele- risque leo sodales sit amet</p>
            </div>
            <div class="article">
              <h4><a href="http://all-free-download.com/free-website-templates/">Amet sed lorem sit</a></h4>
              <small class="date">20.07.09</small>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
            </div>
            <div class="article">
              <h4><a href="http://all-free-download.com/free-website-templates/">Adipsicing elit elementum</a></h4>
              <small class="date">19.07.09</small>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie.</p>
            </div>
            <div class="article">
              <h4><a href="http://all-free-download.com/free-website-templates/">Consectetur elit sed molestie</a></h4>
              <small class="date">15.07.09</small>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum molestie.</p>
            </div>
            <div class="cl">&nbsp;</div>
            <a href="http://all-free-download.com/free-website-templates/" class="view-all">view all</a>
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
<?php
?>
<!-- Header -->
  <div id="header">
    <!-- Top Navigation -->
   
    <!-- / Top Navigation -->
    <div class="cl">&nbsp;</div>
    <!-- Logo -->
    <div id="logo">
      <h1><a href="index.php">iLLuSioN <span>GrOuP</span></a></h1>
      <p class="description">Get outta my town boy!</p>
    </div>
    <!-- / Logo -->
    <!-- Main Navigation -->
    <div id="main-nav">
      <div class="bg-right">
        <div class="bg-left">
          <ul>
            <li><a href="/community">community</a></li>
            <li><a href="/forum">forums</a></li>
            <li><a href="/videos">videos</a></li>
            <li><a href="/about">about</a></li>
            <li><a href="/streams">streams</a></li>
            <li><a href="/downloads">downloads</a></li>
            <li><a href="/rules">rules</a></li>
            <li><a href="/contact">contact</a></li>
          </ul>
        </div>
      </div>
    </div>
    <!-- / Main Navigation -->
    <div class="cl">&nbsp;</div>
    <!-- Sort Navigation -->
    <div id="sort-nav">
      <div class="bg-right">
        <div class="ui-widget-header" style="text-align: center;height: 51px">
          <nav>
	          <ul class="nav-menu">
	            <li class="nav-item"><a href="/index.php">Home</a>
	            	<div class="sub-nav">
	            	</div>
	            </li>
	            <li class="nav-item"><a href="/clans">Clans</a>
	            	<div class="sub-nav">
	            	</div>
	            </li>
	            <li class="nav-item"><a href="/post">Posts</a>
	            	<div class="sub-nav">
	            	</div>
	            </li>
	            <li class="nav-item"><a href="/news">News</a>
	            	<div class="sub-nav">
	            		<ul class="sub-nav-group">
		            		
				            <div class="mega-menu-head">
				           		<h3>Game News</h3>
				            </div>
				            
		            		<ul id="game_news_menu">
			            		<?php 
			            		$db_database = 'rust';
								$db_server = 'localhost';
								$db_user = 'root';
								$db_pass = '';
			            		$registry = new registry();
								$db = new database($db_user,$db_pass,$db_database,$db_server);
								$registry->db = $db->db;
			            		$games = new Games($registry);
			            		$games = $games->getGames();
			            		foreach ($games as $game)
			            		{
									echo '<li><a href="/news/games/'.$game['id'].'">'.$game['title'].' News</a></li>';
								}
			            		?>
		            		</ul>
		            	</ul>
		            	<ul class="sub-nav-group">
		            		
				            <div class="mega-menu-head">
				            	<h3>News</h3>
				            </div>
				            
		            		<ul id="site_news_menu">
			            		<li><a href="/news/site">Site News</a></li>
			            		<li><a href="/news/clans">Clan News</a></li>
		            		</ul>
		            	</ul>
	            	</div>
	            </li>
	            <li class="nav-item"><a href="/clans/members/iLLg">iLLg Members</a>
	            	<div class="sub-nav">
	            	</div>
	            </li>
	            <li class="nav-item"><a href="/clans/conflicts/all">Conflicts</a>
		            <div class="sub-nav">	
		            	<ul class="sub-nav-group">
		            		<li><a href="/clans/conflicts/active">Active</a></li>
		            		<li><a href="/clans/conflicts/resolved">Resolved</a></li>
		            	</ul>
		            </div>
	            </li>
	            <li class="nav-item"><a href="/clans/uc">United Clans</a>
		            <div class="sub-nav">
		            	<ul class="sub-nav-group">
		            		<li><a href="/clans/uc/members">UC Members</a></li>
		            		<li><a href="/clans/uc/apply">Apply For UC</a></li>
		            		<li><a href="/clans/uc/about">About the UC</a></li>
		            		<li><a href="/clans/uc/enemies">UC Enemies</a></li>
		            	</ul>
		            </div>
	            </li>
	            <li class="nav-item" style="margin-left: 2px;"><a href="/games">Games We Play</a>
	            	<div class="sub-nav">
	            		<ul class="sub-nav-group">
	            			<?php 
	            			foreach ($games as $game)
	            			{
								echo '<li><a href="/games/view/'.$game['id'].'">'.$game['title'].'</a></li>';
							}
	            			?>
	            		</ul>
	            	</div>
	            </li>
	          </ul>
          </nav>
          <script>
          	$("nav:first").accessibleMegaMenu({
		        /* prefix for generated unique id attributes, which are required 
		           to indicate aria-owns, aria-controls and aria-labelledby */
		        uuidPrefix: "accessible-megamenu",

		        /* css class used to define the megamenu styling */
		        menuClass: "nav-menu",

		        /* css class for a top-level navigation item in the megamenu */
		        topNavItemClass: "nav-item",

		        /* css class for a megamenu panel */
		        panelClass: "sub-nav",

		        /* css class for a group of items within a megamenu panel */
		        panelGroupClass: "sub-nav-group",

		        /* css class for the hover state */
		        hoverClass: "hover",

		        /* css class for the focus state */
		        focusClass: "focus",

		        /* css class for the open state */
		        openClass: "open"
		    });
		    $( "#site_news_menu" ).menu();
		    $( "#game_news_menu" ).menu();
          </script>
        </div>
      </div>
    </div>
    <!-- / Sort Navigation -->
  </div>
  <!-- / Header -->

<?php
class AdminController
{
	public $registry;
	protected $db;
	
	public function __construct($registry)
	{
		$registry->users->isAdminRedirect();
		$this->registry = $registry;
		$this->db = $this->registry->db;
	}
	
	public function Index()
	{
		$this->registry->pageTitle = 'Admin Panel | iLLuSioN GrOuP';
		echo '
		<div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt"> 
              <h3>Admin Panel</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="row-articles articles">
            <div class="cl">&nbsp;</div>
            '.'
           <a id="steamgames" href="/admin/updateSteamGames">Update Steam Games</a>
           <a id="addgame" href="/admin/addGame">Add a game to DB</a>
           <a id="addperm" href="/admin/addPerm">Add a new Permission</a>
           <script>
           	$("#steamgames").button();
           	$("#addgame").button();
           	$("#addperm").button();
           </script>
            '. '
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
		';
	}
	
	public function UpdateSteamGames()
	{
		$gamesClass = new Games($this->registry);
		$gamesClass->updateGameList();
	}
	
	public function AddPerm()
	{
		$name = '';
		$description = '';
		if (isset($_GET['nameField']))
		{
			$name = $_GET['nameField'];
		}
		if(isset($_GET['descriptionField']))
		{
			$description = $_GET['descriptionField'];
		}
		$editor = new ckeditor($this->registry,'description',$description);
		echo '
		<div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt"> 
              <h3>Add a Permission</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="row-articles articles">
            <div class="cl">&nbsp;</div>
		';
		
		echo '
			<form action="/admin/savePerm" method="POST">
				<label for="name">Permission Name</label>
				<input type="text" name="name" value=""/>';
				if (isset($_GET['nameError']))
				{
					echo '<span class="error">'.$_GET['nameError'].'</span>';
				}
				echo '
				<br />
				<label for="description">Description</label>
				<div id="fieldset">
				';
				echo $editor->Display();
				if(isset($_GET['descriptionError']))
				{
					echo '<span class="error">'.$_GET['descriptionError'].'</span>';
				}
				echo '
				</div>
				<input type="submit" value="Save Permission" id="submitVal" />
			</form>
			
		';
		echo '
		 <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
		';
	}
	
	public function SavePerm()
	{
		if(!isset($_POST))
		{
			redirect('/admin/addperm');
		}
		$errors = false;
		$fieldErrors = array();
		if($_POST['name'] == '')
		{
			$errors = true;
			$fieldErrors['name'] = 'Name cannot be blank!';
		}
		if($_POST['description'] == '')
		{
			$errors = true;
			$fieldErrors['description'] = 'Description cannot be blank!';
		}
		if ($errors == true)
		{
			$string = '?&errors=true';
			if(isset($fieldErrors['name']))
			{
				$string .= '&nameError='.$fieldErrors['name'];
			}
			if(isset($fieldErrors['description']))
			{
				$string .= '&descriptionError='.$fieldErrors['description'];
			}
			$string .= '&nameField='.$_POST['name'].'&descriptionField='.$_POST['description'];
			redirect('/admin/addperm/'.$string);
		}
		else
		{
			$name = $_POST['name'];
			$description = $_POST['description'];
			$clanPermsClass = new ClanPermissions($this->registry);
			$clanPermsClass->addPerm($name,$description);
			redirect('/admin/?success=true');
		}
	}
	
	public function AddGame()
	{
		echo '
		<div class="block">
        <div class="block-bot">
          <div class="ui-widget-header ui-corner-top titlespacer">
            <div class="head-cnt"> 
              <h3>Add a steam game</h3>
              <div class="cl">&nbsp;</div>
            </div>
          </div>
          <div class="row-articles articles">
            <div class="cl">&nbsp;</div>
            '.'
            <form action="/admin/saveGame" method="POST" enctype="multipart/form-data">
            	<label for="title">Game title:</label>
            	<input type="text" id="title" name="title" value="';
            	if (isset($_GET['titleField']))
            	{
					echo $_GET['titleField'];
				}
            	echo '" />';
            	if (isset($_GET['titleError']))
            	{
					echo '<span class="error">'. $_GET['titleError'].'</span>';
				}
            	echo '
            	<label for="steam_id">Steam App ID:</label>
            	<input type="text" id="steam_game_id" name="steam_id" value="';
            	if (isset($_GET['appField']))
            	{
					echo $_GET['appField'];
				}
            	echo '" />';
            	if (isset($_GET['appError']))
            	{
					echo '<span class="error">'. $_GET['appError'] . '</span>';
				}
            	echo '
            	<label for="fileToUpload">Default news image:</label>
            	<input type="file" name="fileToUpload" id="fileToUpload">';
    			if (isset($_GET['imageError']))
				{
					echo '<span class="error">'.$_GET['imageError'].'</span>';
				} 
				echo '
            	<input type="submit" name="submit" value="Add Game" id="submit"/>
            	<script>$("#submitForm").button()</script>
            </form>
            '. '
            <div class="cl">&nbsp;</div>
          </div>
        </div>
      </div>
		';
	}
	
	public function SaveGame()
	{
		if (isset($_POST['submit']))
		{
			$errors = false;
			if (!isset($_POST['steam_id']) || $_POST['steam_id'] == '')
			{
				$fieldErrors['app'] = 'The Steam App Id cannot be blank!';
				$errors = true;
			}
			if (!isset($_POST['title']) || $_POST['title'] == '')
			{
				$fieldErrors['title'] = 'The game title cannot be blank!';
				$errors = true;
			}
			if ($errors == false)
			{
				
				$appid = $_POST['steam_id'];
				$title = $_POST['title'];
				$data = $this->registry->data->getData("http://store.steampowered.com/api/appdetails?appids=$appid");
				if (!is_object($data))
				{
					$fieldErrors['app'] = 'The appid is wrong or the steam servers are down.';
					$errors = true;
				}
				$data = $data->$appid->data;
				$name = $data->name;
				$age = $data->required_age;
				$description = $data->detailed_description;
				$about = $data->about_the_game;
				$laguages = $data->supported_languages;
				$header_image = $data->header_image;
				$website = $data->website;
				$release_date = $data->release_date->date;
				$recomendations = $data->recommendations;
				
				//pc requirements
				$minimum = $data->pc_requirements->minimum;
				
				//price
				$fin = 'final';
				$price = $data->price_overview->$fin;
				//devs
				$devs = $data->publishers;
				
				//platforms
				$windows = $data->platforms->windows;
				$mac = $data->platforms->mac;
				$linux = $data->platforms->linux;	
				
				$table = preg_replace("/[^a-zA-Z]+/", "", $title) . '_news';
				
				//upload the file
				$target_dir = "uploads/games/$table/";
				if (!file_exists($target_dir)) {
				    mkdir($target_dir, 0777, true);
				}
				$target_file = $target_dir . $_FILES['fileToUpload']['name'];
				$imageFileType = $this->registry->validator->getMimeType($_FILES['fileToUpload']['name']);
				
			    //$check = getimagesize($_FILES['fileToUpload']['tmp_name']);
			   
			   if ($target_file == $target_dir)
			   {
			   		$fieldErrors['image'] = 'A image is required!';
			   		$errors = true;		
			   }
			   
				if (file_exists($target_file)) {
				    $fieldErrors['image'] = 'Sorry, file already exists.';
				    $errors = true;
				}
				 // Check file size
				elseif ($_FILES["fileToUpload"]["size"] > 5000000) {
				    $fieldErrors['image'] = 'Sorry, your file is too large.';
				    $errors = true;
				} 
				
				if ($errors != true)
				{
					if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
				       	
				    } 
				    else
				    {
						$fieldErrors['image'] = 'Sorry, there was an error uploading your file.';
						$errors = true;
					}
				}
				
				//if errors persist redirect the user
				if ($errors == true)
				{
					$string = '?errors=true';
					if (isset($fieldErrors['title']))
					{
						$string .= '&titleError='.$fieldErrors['title'];
					}
					if(isset($fieldErrors['app']))
					{
						$string .= '&appError='.$fieldErrors['app'];
					}
					if (isset($fieldErrors['image']))
					{
						$string .= '&imageError='.$fieldErrors['image'];
					}
					$string .= '&titleField='.$_POST['title'].'&appField='.$_POST['steam_id'];
					redirect('/admin/addgame/'.$string);
				}
				$filename = basename( $_FILES["fileToUpload"]["name"]);
				
				//make game news database
				$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$sql ="CREATE TABLE IF NOT EXISTS $table(
			     ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
			     title VARCHAR( 100 ) NOT NULL, 
			     image TEXT NOT NULL,
			     content TEXT NOT NULL, 
			     url TEXT NOT NULL, 
			     author TEXT NOT NULL);" ;
			    $this->db->exec($sql);
				
				//save game into database
				$sql = "INSERT INTO games (steam_id,name,required_age,description,about,languages,header_image,website,release_date,minimum,windows,mac,linux,price, news_db, title, filename) VALUES(:steam_id, :name, :required_age, :description, :about, :languages, :header_image, :website, :release_date, :minimum, :windows, :mac, :linux, :price, :news_db, :title, :filename)";
		    	$stmt = $this->db->prepare($sql);
		    	$stmt->bindParam(':steam_id',$appid, PDO::PARAM_INT);
		    	$stmt->bindParam(':name',$name, PDO::PARAM_STR);
		    	$stmt->bindParam(':description',$description, PDO::PARAM_STR);
		    	$stmt->bindParam(':required_age',$age, PDO::PARAM_INT);
		    	$stmt->bindParam(':about',$about, PDO::PARAM_STR);
		    	$stmt->bindParam(':languages',$laguages, PDO::PARAM_STR);
		    	$stmt->bindParam(':header_image',$header_image, PDO::PARAM_STR);
		    	$stmt->bindParam(':website',$website, PDO::PARAM_STR);
		    	$stmt->bindParam(':release_date',$release_date, PDO::PARAM_STR);
		    	$stmt->bindParam(':minimum',$minimum, PDO::PARAM_STR);
		    	$stmt->bindParam(':windows',$windows, PDO::PARAM_BOOL);
		    	$stmt->bindParam(':mac',$mac, PDO::PARAM_BOOL);
		    	$stmt->bindParam(':linux',$linux, PDO::PARAM_BOOL);
		    	$stmt->bindParam(':price',$price, PDO::PARAM_STR);
		    	$stmt->bindParam(':required_age',$age, PDO::PARAM_STR);
		    	$stmt->bindParam(':news_db',$table, PDO::PARAM_STR);
		    	$stmt->bindParam(':title',$title, PDO::PARAM_STR);
		    	$stmt->bindParam(':filename',$filename, PDO::PARAM_STR);
		    	$stmt->execute();
				
				//get the id of the game after it has been submitted
				$gid = $this->db->lastInsertId();
				
				//upload game news
				$this->registry->Steam->saveGameNews($table, $appid, $filename);
				
				//screenies
				foreach ($data->screenshots as $screen)
				{
					//insert each scrrenshot value into database for game screenshots
					$thumb_path = $screen->path_thumbnail;
					$path = $screen->path_full;
					
					$sql = "INSERT INTO games_screenshots(game_id, path_thumb, path_full) VALUES(:game_id, :path_thumb, :path_full)";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':game_id', $gid, PDO::PARAM_INT);
					$stmt->bindParam(':path_full', $path, PDO::PARAM_STR);
					$stmt->bindParam(':path_thumb', $thumb_path, PDO::PARAM_STR);
					$stmt->execute();
				}
				
				//movies
				foreach ($data->movies as $movie)
				{
					$mov_title = $movie->name;
					$mov_thumb = $movie->thumbnail;
					$num = '480';
					$mov_low = $movie->webm->$num;
					$mov_max = $movie->webm->max;
					
					$sql = "INSERT INTO games_movies(game_id, title, thumb, low, high) VALUES(:game_id, :title, :thumb, :low, :high)";
					$stmt = $this->db->prepare($sql);
					$stmt->bindParam(':game_id', $gid, PDO::PARAM_INT);
					$stmt->bindParam(':title', $mov_title, PDO::PARAM_STR);
					$stmt->bindParam(':thumb', $mov_thumb, PDO::PARAM_STR);
					$stmt->bindParam(':low', $mov_low, PDO::PARAM_STR);
					$stmt->bindParam(':high', $mov_max, PDO::PARAM_STR);
					$stmt->execute();
				}
				redirect('/admin/managegames/?success=true');
			}
			else 
			{
				$string = '?errors=true';
				if (isset($fieldErrors['title']))
				{
					$string .= '&titleError='.$fieldErrors['title'];
				}
				if(isset($fieldErrors['app']))
				{
					$string .= '&appError='.$fieldErrors['app'];
				}
				if (isset($fieldErrors['image']))
				{
					$string .= '&imageError='.$fieldErrors['image'];
				}
				$string .= '&titleField='.$_POST['title'].'&appField='.$_POST['steam_id'];
				redirect('/admin/addgame/'.$string);
			}
		}

	}
	
	public function ManageGames()
	{
		if (isset($_GET['success']))
		{
			$dialog = new dialog('Game Added','Game data has been successfully saved!');
			$dialog->showDialog();
		}
	}
	
	public function Users()
	{
		$users = $this->registry->users->getUsers();
		echo '
			<div class="block">
		        <div class="block-bot">
		          <div class="ui-widget-header ui-corner-top titlespacer">
		            <div class="head-cnt"> 
		              <h3>All Site Users</h3>
		              <div class="cl">&nbsp;</div>
		            </div>
		          </div>
		          <div class="col-articles articles">
		            <div class="cl">&nbsp;</div>
		            ';
		            foreach ($users as $user)
		            {
						
		            echo '
		            <div class="article">
		              <div class="image"> <a href="/index.php?rt=User&method=Profile&uid='.$user['uid'].'"><img alt="" src="'.$user['avatar'].'"></a> </div>
		              <h4><a href="'.'">'.$user['nickname'].'</a></h4>
		              <p class="console"><strong>Clan: iLLg</strong></p>
		            </div>
		            ';
		           }
		           echo '
		            <div class="cl">&nbsp;</div>
		          </div>
		        </div>
		      </div>
		';
	}
	
	public function Update()
	{
		
	}
	
}
?>
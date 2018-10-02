<?php
class ClansController
{
	public $registry;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->registry->clans = new Clans($registry);
	}
	
	public function Index()
	{
		
	}
	
	public function Conflicts()
	{
		if (!isset($_GET['param']))
		{
			redirect('/clans/conflicts/all');
		}
		$view = $_GET['param'];
		switch($view)
		{
			case 'resolved':
				echo 'all resolved clan conflicts';
				break;
			case 'active':
				echo 'all active clan conflicts';
				break;
			default:
				echo 'All conflicts resolved and active';
				break;
		}
	}
	
	public function Uc()
	{
		$view = 'default';
		if (isset($_GET['param']))
		{
			$view = $_GET['param'];
		}
		switch ($view)
		{
			case 'about':
				echo 'about the uc';
				break;
			case 'apply':
				echo 'apply for the uc';
				break;
			case 'members':
				echo 'uc members';
				break;
			case 'enemies':
				echo 'uc enemies';
				break;
			default :
				echo 'uc home';
				break;
		}
	}
	
	public function Members()
	{
		if (!isset($_GET['param']))
		{
			redirect('/clans');
		}
		$clan = $_GET['param'];
		echo $clan . ' Members';
	}
	
	public function NewClan()
	{
		$this->registry->users->isLoggedInRedirect();
		$name = '';
		$tag = '';
		$date = '';
		$age = '16';
		$games = '';
		$about = '';
		if(isset($_GET['nameField']))
		{
			$name = $_GET['nameField'];
		}
		if(isset($_GET['tagField']))
		{
			$tag = $_GET['tagField'];
		}
		if(isset($_GET['dateField']))
		{
			$date = $_GET['dateField'];
		}
		if(isset($_GET['ageField']))
		{
			$age = $_GET['ageField'];
		}
		if(isset($_GET['aboutField']))
		{
			$about = $_GET['aboutField'];
		}
		if(isset($_GET['gamesField']))
		{
			$games = $_GET['gamesField'];
		}
		
		$editor = new ckeditor($this->registry,'about',$about);
		$multiSelect = new multiSelect($this->registry,'/json/SearchGameList/?ajax=true','Games the clan plays: ',$games);
		echo '
		<div class="block">
			<div class="block-bot">
				<div class="ui-widget-header ui-corner-top titlespacer1">
					<div class="head-cnt"> 
						<span style="float:left;margin-top:7px;margin-left:5px;"><h3>Create a new clan</h3></span>
						<div class="cl">&nbsp;</div>
					</div>
				</div>
				<div class="row-articles articles">
		';
		//form
		echo '
					<form method="POST" action="/clans/saveclan" name="" id="" enctype="multipart/form-data">
						<fieldset>
							<div class="fieldset">
								<label for="name">Clan name: </label>
								<input type="text" name="name" value="'.$name.'"/>';
							if(isset($_GET['nameError']))
							{
								echo '<span class="error">'.$_GET['nameError'].'</span>';
							}
							echo '
							</div>
							<div class="fieldset">
								<label for="tag">Clan Tag: </label>
								<input type="text" name="tag" value="'.$tag.'" />';
							if(isset($_GET['tagError']))
							{
								echo '<span class="error">'.$_GET['tagError'].'</span>';
							}
							echo '
							</div>
							<div class="fieldset">
								<label for="date">Date Founded: </label>
								<input type="date" id="datepicker" name="date" value="'.$date.'" />';
							if(isset($_GET['dateError']))
							{
								echo '<span class="error">'.$_GET['dateError'].'</span>';
							}
							echo '
							</div>
							<div class="fieldset">
								<label for="fileToUpload">Clan Logo: </label>
								<input type="file" name="fileToUpload" id="fileToUpload">';
							if(isset($_GET['imageError']))
							{
								echo '<span class="error">'.$_GET['imageError'].'</span>';
							}
							echo '
							</div>
							<div class="fieldset">
								<label for="restrict">Restrict Age: </label>
								<span>Yes <input type="radio" name="restrict" value="1" /></span>
								<span>No <input checked="checked" type="radio" name="restrict" value="0" /></span>
							</div>
							<div class="fieldset">
								<label for="age">Age: </label>
								<input type="text" name="age" value="'.$age.'" />';
							if(isset($_GET['ageError']))
							{
								echo '<span class="error">'.$_GET['ageError'].'</span>';
							}
							echo '
							</div>
							<div class="fieldset">
								<label for="privacy">Clan Privacy: </label>
								<span>Private <input type="radio" name="privacy" value="1" /></span>
								<span>Public <input type="radio" name="privacy" checked="checked" value="0" /></span>
							</div>
							<div class="fieldset">
								<label for="application">Create a Clan Application </label>
								<span>Yes <input type="radio" name="application" value="1" /></span>
								<span>No <input type="radio" name="application" checked="checked" value="0" /></span>
							</div>
							<div class="fieldset">
								';
								$multiSelect->Display();
							if(isset($_GET['gamesError']))
							{
								echo '<span class="error">'.$_GET['gamesError']. '</span>';
							}
							echo '
							</div>
							<div class="fieldset">';
							if(isset($_GET['aboutError']))
							{
								echo '<span class="error">'.$_GET['aboutError'].'</span>';
							}	
							$editor->Display();
							echo '
							</div>
							<input type="submit" name="submit" value="Create" id="submitBtn"/>
						</fieldset>							
					</form>
					<script>
					$("#submitBtn").button();
					$( "#datepicker" ).datepicker({
							changeMonth: true,
      						changeYear: true
						});
					</script>
		';
		//close the main content area
		echo '
				</div>
			</div>
		</div>
		';
	           
	   
	}
	
	public function SaveClan()
	{
		$this->registry->users->isLoggedInRedirect();
		if (!isset($_POST))
		{
			redirect('/clans/newclan');
		}
		$game_ids = $_POST['games'];
		$name = $_POST['name'];
		$tag = $_POST['tag'];
		$date = $_POST['date'];
		$about = $_POST['about'];
		$restrict = $_POST['restrict'];
		$age = $_POST['age'];
		$privacy = $_POST['privacy'];
		$app = $_POST['application'];
		
		$filename = basename( $_FILES["fileToUpload"]["name"]);
	
		$errors = false;
		if ($game_ids == '')
		{
			$errors = true;
			$fieldErrors['games'] = 'You must select at least one game your clan plays.';
		}
		if ($name == '')
		{
			$errors = true;
			$fieldErrors['name'] = 'Clan name cannot be blank!';
		}
		if ($tag == '')
		{
			$errors = true;
			$fieldErrors['tag'] = 'Clan tag cannot be blank!';
		}
		if ($about == '')
		{
			$errors = true;
			$fieldErrors['about'] = 'The description of the clan cannot be blank!';
		}
		if($restrict == 1)
		{
			if ($age == '')
			{
				$errors = true;
				$fieldErrors['age'] = 'Age cannot be blank';
			}
		}
		$target_dir = "uploads/clans/$tag/";
		if (!file_exists($target_dir)) {
		    mkdir($target_dir, 0777, true);
		}
		$target_file = $target_dir . $_FILES['fileToUpload']['name'];
		$imageFileType = $this->registry->validator->getMimeType($_FILES['fileToUpload']['name']);
		
	    //$check = getimagesize($_FILES['fileToUpload']['tmp_name']);
	   
		if (file_exists($target_file)) {
		    $fieldErrors['image'] = 'Sorry, file already exists.';
		    $errors = true;
		}
		 // Check file size
		elseif ($_FILES["fileToUpload"]["size"] > 5000000) {
		    $fieldErrors['image'] = 'Sorry, your file is too large.';
		    $errors = true;
		}
		if ($errors == true)
			{
				$string = '?';
				if (isset($fieldErrors['games']))
				{
					$string .= '&gamesError='.$fieldErrors['games'];
				}
				if (isset($fieldErrors['name']))
				{
					$string .= '&nameError='.$fieldErrors['name'];
				}
				if(isset($fieldErrors['tag']))
				{
					$string .= '&tagError='.$fieldErrors['tag'];
				}
				if(isset($fieldErrors['date']))
				{
					$string .= '&dateError='.$fieldErrors['date'];
				}
				if(isset($fieldErrors['about']))
				{
					$string .= '&aboutError='.$fieldErrors['about'];
				}
				if(isset($fieldErrors['age']))
				{
					$string .= '&ageError='.$fieldErrors['age'];
				}
				if (isset($fieldErrors['image']))
				{
					$string .= '&imageError='.$fieldErrors['image'];
				}
				$string .= '&nameField='.$_POST['name'].'&tagField='.$_POST['tag'].'&dateField='.$_POST['date'].'&aboutField='.$_POST['about'].'&ageField='.$_POST['age'];
				redirect('/clans/newclan/'.$string);
			}
		
		if ($errors != true)
		{
			if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) 
			{
				$games = explode(',',$game_ids);
		       	$clanClass = new Clans($this->registry);
		       	
				$id = $clanClass->saveClan($name, $tag, $date, $filename, $about, $this->registry->user['uid'], $restrict, $age, $privacy, $app, $games);
				$clanClass->addClanMember($id,$this->registry->user['uid'],0);
				//add the default ranks and permsissions values to database
				$clanClass->addRank($id,0,'Owner','This is the top rank. This rank by default has all permissions of the clan.');
				$clanClass->addRank($id,1,'Member','This is the default rank a user will get when they join the clan.');
				$clanPermClass = new ClanPermissions($this->registry);
				$clanPermClass->addRankPerm($id,0,1);
				$clanPermClass->addRankPerm($id,1,0);
				if($app == 1)
				{
					redirect('/clans/newapp/'.$id);
				}
				else
				{
					redirect('/user/clan/?success=true');
				}
		    } 
		    else
		    {
				$fieldErrors['image'] = 'Sorry, there was an error uploading your file.';
				$errors = true;
			}
		} 
		
	}
	
	public function NewApp()
	{
		if(!isset($_GET['param']))
		{
			redirect('/user/profile');
		}
		else
		{
			$id = $_GET['param'];
		}
		if(isset($_GET['error']))
		{
			echo '<span class="error">You must have at least one field in your application!</span>';
		}
		echo '
		
		<fieldset id="buildyourform">
		    <legend>Create your clan application</legend>
		    <p>Add fields to your form with the button below.</p>
		    <form id="app_form" action="/clans/saveapp/'.$id.'" method="POST">
		    </form>
		</fieldset>
		<p class="add" id="preview">Preview Application</p>
		<p class="add" id="add">Add A Field</p>
		<p class="add" id="save_app">Save Application</p>
		
			<script>
				$(document).ready(function() {
					$("#preview").button();
					$("#add").button();
					$("#save_app").button();
				    $("#add").click(function() {
				        var intId = $("#buildyourform div").length + 1;
				        var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");
				        var fName = $("<input type=\"text\" class=\"fieldname\" name=\"fieldName[]\" />");
				        var fType = $("<select class=\"fieldtype\" name=\"fieldType[]\"><option value=\"checkbox\">Checked</option><option value=\"textbox\">Text</option><option value=\"textarea\">Paragraph</option></select>");
				        var removeButton = $("<p id=\"remove\">X</p>");
				        removeButton.button();
				        removeButton.click(function() {
				            $(this).parent().remove();
				        });
				        fieldWrapper.append(fName);
				        fieldWrapper.append(fType);
				        fieldWrapper.append(removeButton);
				        $("#app_form").append(fieldWrapper);
				    });
				    $("#preview").click(function() {
				        $("#yourform").remove();
				        var fieldSet = $("<fieldset id=\"yourform\"><legend>Your Form</legend></fieldset>");
				        $("#buildyourform div").each(function() {
				            var id = "input" + $(this).attr("id").replace("field","");
				            var label = $("<label for=\"" + id + "\">" + $(this).find("input.fieldname").first().val() + "</label>");
				            var input;
				            switch ($(this).find("select.fieldtype").first().val()) {
				                case "checkbox":
				                    input = $("<input type=\"checkbox\" id=\"" + id + "\" name=\"" + id + "\" />");
				                    break;
				                case "textbox":
				                    input = $("<input type=\"text\" id=\"" + id + "\" name=\"" + id + "\" />");
				                    break;
				                case "textarea":
				                    input = $("<textarea id=\"" + id + "\" name=\"" + id + "\" ></textarea>");
				                    break;    
				            }
				            fieldSet.append(label);
				            fieldSet.append(input);
				        });
				        $("#content").append(fieldSet);
				    });
				    $("#save_app").click(function() {
				    	$("form#app_form").submit();	
				    });
				});
			</script>
		';
	}
	
	public function SaveApp()
	{
		if(!isset($_GET['param']))
		{
			redirect('/user/profile');
		}
		else
		{
			$id = $_GET['param'];
		}
		if (!isset($_POST['fieldType']) || !isset($_POST['fieldName']))
		{
			redirect('/clans/newapp/'.$id.'/?error=true');
		}
		$clanClass = new Clans($this->registry);
		$clanClass->saveApplicationFields($_POST['fieldName'], $_POST['fieldType']);
		echo 'good';
		
	}
	
	public function Edit()
	{
		
	}
	
	public function Delete()
	{
		
	}
	
	public function Ranks()
	{
		if(!isset($_GET['param']))
		{
			redirect('/clans');
		}
		$cid = $_GET['param'];
		$clanClass = new Clans($this->registry);
		
		$clanInfo = $clanClass->getClan($cid);
		$this->registry->pageTitle = 'Clans Ranks';
		echo '
		<div class="block">
			<div class="block-bot">
				<div class="ui-widget-header ui-corner-top titlespacer1">
					<div class="head-cnt"> 
						<span style="float:left;margin-top:7px;margin-left:5px;"><h3>'.$clanInfo['name'].'\'s Ranks</h3></span>
						<div class="cl">&nbsp;</div>
					</div>
				</div>
				<div class="row-articles articles custom-article">
		';
		if (isset($this->registry->guest))
		{
			//ur a guest. just view the ranks of the clan if its set to public else display this clans info is set to private
		}
		else
		{
			//user is logged in, check if user is a member of the clan
			$userIsClanMember = $clanClass->userIsMember($cid,$this->registry->user['uid']);
			if($userIsClanMember == true)
			{
				//user is in the clan get the rank of the user
				$clanPermClass = new ClanPermissions($this->registry);
				$rank = $clanClass->getUserRank($this->registry->user['uid'],$cid);
				//check to see if the rank has permissions to edit ranks
				$permPass = $clanPermClass->rankHasPerm($cid,$rank,9);
				$string = '';
				if ($permPass == true)
				{
					//show the edit ranks button bc users rank has permissions
					echo '
					<div id="admin_ranks" style="overflow:auto;">
						<a style="float:left;margin-right:6x;" id="editRank" href="/clans/editrank/">Edit Rank</a>
						<a style="float:left;margin-right:6px;" id="editPerms" href="/clans/editperms/'.$cid.'">Edit Permissions</a>
						<a style="float:left;margin-right:6px;" id="deleteRank" href="/clans/deleterank">Delete Rank</a>
						<a style="float:left;" id="addRank" href="/clans/addrank/'.$cid.'">Add Rank</a>
						<div id="admin_rank_desc">
							<p>Click a rank to enable the buttons.</p>
						</div>
					</div>
					<script>
					$("#editPerms").button({
						disabled : true
					});
					$("#addRank").button({
					});
					$("#editRank").button({
						disabled : true
					});
					$("#deleteRank").button({
						disabled : true
					});
					</script>';
				}
				$ranks = $clanClass->getRanks($cid);
				foreach($ranks as $rank)
				{
					if($permPass == true)
					{
						
						$string .= '
						<script>
						var oldButtonSelected = "";
						var oldDiv = "";
						var added'.$rank['rid'].' = false;
						$("#rank_container'.$rank['rid'].'").click(function(){
								added'.$rank['rid'].' = !added'.$rank['rid'].';
								if(added'.$rank['rid'].' == true)
								{	
									oldButtonSelected = "button'.$rank['rid'].'";
									oldDiv = "#rank_container'.$rank['rid'].'";
									var input = $("#rankId'.$rank['rid'].'");
									var editRankUrl = "/clans/editrank/'.$cid.'/?rid="+input.val();
									var editPermUrl = "/clans/editperms/'.$cid.'/?rid="+input.val();
									var deleteRankUrl = "/clans/deleterank/'.$cid.'/?rid="+input.val();
									var deleteBtn = $("#deleteRank");
									var permBtn = $("#editPerms");
									var rankBtn = $("#editRank");
									deleteBtn.attr("href", deleteRankUrl);
									permBtn.attr("href", editPermUrl);
									rankBtn.attr("href", editRankUrl);
									permBtn.button({
										disabled : false
									});
									$(deleteBtn).button({
										disabled : true
									});
									rankBtn.button({
										disabled : false
									});
									if(input.val() != 0 && input.val() != 1)
									{
										$(deleteBtn).button({
											disabled : false
										});
									}
									$(".rank_container").each(function(){
										$(this).removeClass("rank_container_selected");
									})
									$(this).addClass("rank_container_selected");
								}
								else if(oldButtonSelected != "button'.$rank['rid'].'")
								{
									oldButtonSelected = "button'.$rank['rid'].'";
									
									var input = $("#rankId'.$rank['rid'].'");
									var editRankUrl = "/clans/editrank/'.$cid.'/?rid="+input.val();
									var editPermUrl = "/clans/editperms/'.$cid.'/?rid="+input.val();
									var deleteRankUrl = "/clans/deleterank/'.$cid.'/?rid="+input.val();
									var deleteBtn = $("#deleteRank")
									var permBtn = $("#editPerms");
									var rankBtn = $("#editRank");
									deleteBtn.attr("href", deleteRankUrl);
									permBtn.attr("href", editPermUrl);
									rankBtn.attr("href", editRankUrl);
									$(deleteBtn).button({
										disabled : true
									});
									permBtn.button({
										disabled : false
									});
									rankBtn.button({
										disabled : false
									});
									if(input.val() != 0 && input.val() != 1)
									{
										$(deleteBtn).button({
											disabled : false
										});
									}
									$(".rank_container").each(function(){
										$(this).removeClass("rank_container_selected");
									})
									$(this).addClass("rank_container_selected");
								}
								else
								{
									var permBtn = $("#editPerms");
									var rankBtn = $("#editRank");
									permBtn.button({
										disabled : true
									});
									rankBtn.button({
										disabled : true
									});
									$("#deleteRank").button({
											disabled : true
										});
									$(this).removeClass("rank_container_selected");
								}
							});
						</script>
						';
						}
					echo '
					<div class="rank_container" id="rank_container'.$rank['rid'].'">
						<h3>'.$rank['name'].'</h3>
						<p>'.$rank['description'].'</p>
						<input type="hidden" name="rankId" id="rankId'.$rank['rid'].'" value="'.$rank['rid'].'"/>
					</div>
					';
				}
				echo $string;
			}
			else
			{
				//user is not a member of this clan show nfo based on clan privacy
			}
		}
		echo '
		</div></div></div>
		';
	}
	
	public function AddRank()
	{
		if(!isset($_GET['param']))
		{
			redirect('/clans');
		}
		$name = '';
		$description = '';
		$permClass = new ClanPermissions($this->registry);
		$perms = $permClass->getPerms();
		if(isset($_GET['nameField']))
		{
			$name = $_GET['nameField'];
		}
		if(isset($_GET['descriptionField']))
		{
			$description = $_GET['descriptionField'];
		}

		$editor = new ckeditor($this->registry,'description', $description);
		$cid = $_GET['param'];
		$clanClass = new Clans($this->registry);
		$rank_id = $clanClass->countRanks($cid);
		$fancyFile = new fancyFile($this->registry,'http://illusiongroup.us','/clans/uploadranklogo/'.$cid.'/?rid='.$rank_id.'&ajax=true&fileLocation=uploads/clans/ranks/','clans/deleteranklogo/'.$cid.'/?rid='.$rank_id.'&ajax=true','/clanname/ranks/', 'Rank Logo');
		echo '
		<div class="block">
			<div class="block-bot">
				<div class="ui-widget-header ui-corner-top titlespacer1">
					<div class="head-cnt"> 
						<span style="float:left;margin-top:7px;margin-left:5px;"><h3>Add A Rank</h3></span>
						<div class="cl">&nbsp;</div>
					</div>
				</div>
				<div class="row-articles articles">
		';
		
		echo '
			<div id="form_container">
			<form enctype="multipart/form-data" action="/clans/saveRank/'.$cid.'" method="POST">
				<div class="form-group">
					<div class="form-group">
						<label for="name">Rank Title</label>
						';
						if(isset($_GET['nameError']))
						{
							echo '<span class="error">'.$_GET['nameError'].'</span>';
						}
						echo'
						<br />
						<input type="text" name="name" value="" />
					</div>
					<br />
					<div class="">
						';
						$fancyFile->Display();
						echo '
						
					</div>
					<div class="form-group">
					<label for="description">Renk Description:</label>
					 ';
					 if(isset($_GET['descriptionError']))
					 {
					 	echo '<span class="error">'.$_GET['descriptionError'].'</span>';
					 }
					 echo '<br />'.$editor->Display();
					 echo '
					 </div>	
					 <div class="form-group">
					 	<input type="submit" value="Save Rank" id="saveRankBtn"/>
					 </div>
				</div>
			</form>
			</div>
			<script>
			$("saveRankBtn").button();
			</script>
		';
		
		echo '
		</div></div></div>
		';
	}
	
	public function UploadRankLogo()
	{
		if(!isset($_GET['ajax']) || !isset($_GET['rid']) || !isset($_GET['param']) || !isset($_FILES['ff_selector']) || !isset($_GET['fileLocation']))
		{
			echo 'This page must be called with ajax. This is part of the web api currently in development!';
		}
		header('Content-Type: application/json');
		$target_dir = $_GET['fileLocation'];
		if (!file_exists($target_dir)) {
			mkdir($target_dir, 0777, true);
		}
		$target_file = $target_dir . $_FILES['ff_selector']['name'];
		
		$imageFileType = $this->registry->validator->getMimeType($_FILES['ff_selector']['name']);
		
	    //$check = getimagesize($_FILES['fileToUpload']['tmp_name']);
	   $error = false;
	   $errors = array();
	   $fieldErrors = array();
		if (file_exists($target_file)) {
		    $fieldErrors['image'] = 'Sorry, file already exists.';
		    $error = true;
		}
		 // Check file size
		elseif ($_FILES["ff_selector"]["size"] > 50000000) {
		    $fieldErrors['image'] = 'Sorry, your file is too large.';
		    $error = true;
		}
		if($error == true)
		{
			$errors['error'] = $fieldErrors['image'];
			echo json_encode($errors);
		}
		else
		{
			if (move_uploaded_file($_FILES['ff_selector']['tmp_name'], $target_file)) 
			{
				$success = array();
				$success['success'] = 'success';
				$success['image'] = $target_file;
				echo json_encode($success);
		    } 
		    else
		    {
				$fieldErrors['image'] = 'Sorry, there was an error uploading your file.';
				$errors['error'] = $fieldErrors['image'];
				echo json_encode($errors);
			}
			
			
		}
		
		
	}
	
	public function DeleteRankLogo()
	{
		if(!isset($_GET['image']))
		{
			
		}
		
		header('Content-Type: application/json');
		$success['success'] = 'success';
		$success['image'] = $_GET['image'];
		echo json_encode($success);
	}
	
	public function SaveRank()
	{
		if(!isset($_GET['param']))
		{
			redirect('/clans');
		}
		$cid = $_GET['param'];
		if(!isset($_POST))
		{
			redirect('/clans/addrank/'.$cid);
		}
		$name = $_POST['name'];
		$description = $_POST['description'];
		$clanClass = new Clans($this->registry);
		$rank_id = $clanClass->countRanks($cid);
		$errors = false;
		$fieldErrors = array();
		if($name == '')
		{
			$errors = true;
			$fieldErrors['name'] = 'Rank title cannot be blank!';
		}
		if($description == '')
		{
			$errors = true;
			$fieldErrors['description'] = 'Rank description cannot be blank!';
		}
		if($errors == true)
		{
			$string = '?errors=true';
			if (isset($fieldErrors['name']))
				$string .= '&nameError='.$fieldErrors['name'];
			if(isset($fieldErrors['description']))
				$string .= '&descriptionError='.$fieldErrors['description'];
			$string .= '&nameField='.$name.'&descriptionField='.$description;
			redirect('/clans/addrank/'.$cid.'/'.$string);
		}
		else
		{
			$clanClass->addRank($cid,$rank_id,$name,$description);
			redirect('clans/ranks/'.$cid.'/?success=true&message=Rank Added!');
		}
	}
}

?>
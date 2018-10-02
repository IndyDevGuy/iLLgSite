<?php
class PostController
{
	public $registry;
	public $postClass;
	
	public function __construct($registry)
	{
		$this->registry = $registry;
		$this->registry->users->isLoggedInRedirect();
		$this->postClass = new post($this->registry);
	}
	
	public function Index()
	{
		echo '<div class="block">
		        <div class="block-bot">
		          <div class="ui-widget-header ui-corner-top titlespacer">
		            <div class="head-cnt"> 
		             <a style="float:right;margin-top:3px;" id="rust_news_button" href="index.php?rt=Post&method=NewPost" class="">New Post</a>
		              <h3>Public Posts</h3>
		              <div class="cl">&nbsp;</div>
		            </div>
		          </div>
		          <div class="col-articles articles">
		            <div class="cl">&nbsp;</div>
		            <p>You have not posted anything yet.</p>
		            <div class="cl">&nbsp;</div>
		          </div>
		        </div>
		      </div>
		      
		      <script>
         $(function() {
            $( "#rust_news_button" ).button();
         });
      </script>';
	}
	
	public function NewPost()
	{
		$title = '';
		$body = '';
		if (isset($_GET['titleField']))
		{
			$title = $_GET['titleField'];
		}
		if (isset($_GET['bodyField']))
		{
			$body = $_GET['bodyField'];
		}
		$fieldErrors = array();
		$editor = new ckeditor($this->registry,'editor1',$body);
		echo '<div class="block">
		        <div class="block-bot">
		          <div class="ui-widget-header ui-corner-top titlespacer">
		            <div class="head-cnt"> 
		              <h3>New Post</h3>
		              <div class="cl">&nbsp;</div>
		            </div>
		          </div>
		          <div class="col-articles articles">
		            <div class="cl">&nbsp;</div>
			            <div class="form-holder">	
			            	<form enctype="multipart/form-data" role="form" method="post" action="/index.php?rt=Post&method=SavePost&type=New" method="post">
				            	<div class="form-group">
				            		<label for="title">Title:</label>
				            		<input type="text" name="title" value="'.$title.'">';
				            		if (isset($_GET['titleError']))
				            		{
										echo '<span class="error">'.$_GET['titleError'].'</span>';
									}
				            		echo '
				            		Select image to upload:
    								<input type="file" name="fileToUpload" id="fileToUpload">';
    								if (isset($_GET['imageError']))
				            		{
										echo '<span class="error">'.$_GET['imageError'].'</span>';
									}
	    							echo '
					            	</div>
					            	<div class="form-group">
				            		<label for="editor1">Body:</label>';
				            		if (isset($_GET['bodyError']))
				            		{
										echo '<span class="error">'.$_GET['bodyError'].'</span>';
									}
				            		$editor->Display();
				            		echo '
				            		<input id="submit" type="submit" value="Save" style="float:right;margin-top:10px;margin-bottom:10px;">
				            		</div>
				            	</div>
				            
			            	</form>
			            </div>
                ';
               
                echo'
                <script>
                
                $( "#submit" ).button();
            </script>
		            <div class="cl">&nbsp;</div>
		          </div>
		        </div>
		      </div>
		      
		     ';
	}
	
	public function SavePost()
	{
		if (!isset($_GET['type']) || (!isset($_POST['title'])))
		{
			redirect('/index.php?rt=Post&method=Index');
		}
		$uid = $this->registry->user['uid'];
		$type = $_GET['type'];
		if ($type == 'New')
		{
			$errors = false;
			if ($_POST['title'] == '')
			{
				$fieldErrors['title'] = 'Title field cannot be blank!';
				$errors = true;
			}
			elseif (!$this->registry->validator->checkLength($_POST['title'],5))
			{
				$fieldErrors['title'] = 'Title must be 5 characters or greater!';
				$errors = true;
			}
			if ($_POST['editor1'] == '')
			{
				$fieldErrors['body'] = 'Body field cannot be blank!';
				$errors = true;
			}
			elseif(!$this->registry->validator->checkLength($_POST['editor1'],20))
			{
				$fieldErrors['body'] = 'Body must be 20 characters or better!';
				$errors = true;
			}
			$target_dir = "uploads/$uid/posts/";
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
		    
			if ($errors == true)
			{
				$string = '';
				if (isset($fieldErrors['title']))
				{
					$string .= '&titleError='.$fieldErrors['title'];
				}
				if(isset($fieldErrors['body']))
				{
					$string .= '&bodyError='.$fieldErrors['body'];
				}
				if (isset($fieldErrors['image']))
				{
					$string .= '&imageError='.$fieldErrors['image'];
				}
				$string .= '&titleField='.$_POST['title'].'&bodyField='.$_POST['editor1'];
				redirect('index.php?rt=Post&method=NewPost'.$string);
			}
			$title = $_POST['title'];
			$body = $_POST['editor1'];
			$filename = basename( $_FILES["fileToUpload"]["name"]);
			$this->postClass->AddPost($uid,$title,$body,$filename);
			redirect('index.php?rt=User&method=Posts&success=true');	
			
		}
		elseif($type == 'Edit')
		{
			
		}
	}
	
	public function View()
	{
		if(!isset($_GET['pid']))
		{
			redirect('index.php?rt=User&method=Profile');
		}
	}
}
?>
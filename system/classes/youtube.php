<?php
class Youtube
{
	protected $registry;
	protected $db;
	protected $google;
	protected $clientId;
	protected $clientSecret;
	public $youtube;
	
	
	public function __construct($registry,$redirect)
	{
		$this->registry = $registry;
		$this->db = $registry->db;
		$this->clientId = '393462944207-erthqovafmeo5281ehgj39frrgl2c75s.apps.googleusercontent.com';
		$this->clientSecret = '2yjV2amskKcYVkhtGTzGJk21';
		$this->google = new Google($this->registry,$this->clientId,$this->clientSecret,'https://www.googleapis.com/auth/youtube', $redirect, 'offline');
		$this->youtube = new Google_Service_YouTube($this->google->client);
	}
	
	public function setRedirect($uri)
	{
		$this->google->setRedirectUri($uri);
	}
	
	public function showLinkAccountLink($uid)
	{
		// if code param is set then this is the code to auth from google
		if (isset($_GET['code'])) 
		{
			if (strval($_SESSION['state']) !== strval($_GET['state'])) 
			{
				die('The session state did not match.');
			}
			//auth code with google client
			$this->google->client->authenticate($_GET['code']);
			//set the temporary access token
			$_SESSION['illg_youtube_token'] = $this->google->client->getAccessToken();
			//get the refresh token to use when clan members of this users clan with correct perms wants to upload vids 
			
			$refreshToken = $this->google->client->getRefreshToken();
			$accessToken = $this->google->client->getAccessToken();
			$this->saveUserTokens($refreshToken,$accessToken,$uid);
			
			redirect('/user/profile/youtube/?pass='.$refreshToken);
		}

		if (isset($_SESSION['illg_youtube_token'])) 
		{
			$this->google->client->setAccessToken($_SESSION['illg_youtube_token']);
		}

		// Check to ensure that the access token was successfully acquired.
		if ($this->google->client->getAccessToken()) 
		{
			return 'account linked successfully!';
		}
		else
		{
			$state = mt_rand();
			$this->google->client->setState($state);
			$_SESSION['state'] = $state;
			$authUrl = $this->google->client->createAuthUrl();
			return '<a id="youtube_button" href="'.$authUrl.'">Link Youtube Account</a>';
		}
	}
	
	public function addVideo($videoPath,$videoTitle,$videoDesc,$tags,$categoryId,$videoStatus)
	{
		// Create a snippet with title, description, tags and category ID
		// Create an asset resource and set its snippet metadata and type.
		// This example sets the video's title, description, keyword tags, and
		// video category.
		$snippet = new Google_Service_YouTube_VideoSnippet();
		$snippet->setTitle($videoTitle);
		$snippet->setDescription($videoDesc);
		$snippet->setTags($tags);
		 // Numeric video category. See
    	// https://developers.google.com/youtube/v3/docs/videoCategories/list 
		$snippet->setCategoryId($categoryId);
		// Set the video's status to "public". Valid statuses are "public",
 	    // "private" and "unlisted".
		$status = new Google_Service_YouTube_VideoStatus();
    	$status->privacyStatus = $videoStatus;
    	
    	// Associate the snippet and status objects with a new video resource.
	    $video = new Google_Service_YouTube_Video();
	    $video->setSnippet($snippet);
	    $video->setStatus($status);
	    
	    // Specify the size of each chunk of data, in bytes. Set a higher value for
	    // reliable connection as fewer chunks lead to faster uploads. Set a lower
	    // value for better recovery on less reliable connections.
	    $chunkSizeBytes = 1 * 1024 * 1024;

	    // Setting the defer flag to true tells the client to return a request which can be called
	    // with ->execute(); instead of making the API call immediately.
	    $this->google->client->setDefer(true);

	    // Create a request for the API's videos.insert method to create and upload the video.
	    $insertRequest = $this->youtube->videos->insert("status,snippet", $video);

	    // Create a MediaFileUpload object for resumable uploads.
	    $media = new Google_Http_MediaFileUpload(
	        $client,
	        $insertRequest,
	        'video/*',
	        null,
	        true,
	        $chunkSizeBytes
	    );
	    $media->setFileSize(filesize($videoPath));


	    // Read the media file and upload it chunk by chunk.
	    $status = false;
	    $handle = fopen($videoPath, "rb");
	    while (!$status && !feof($handle)) {
	      $chunk = fread($handle, $chunkSizeBytes);
	      $status = $media->nextChunk($chunk);
	    }

	    fclose($handle);

	    // If you want to make other calls after the file upload, set setDefer back to false
	    $this->google->client->setDefer(false);

		return $status;
	}
	
	public function getUserPlaylists()
	{
		$opts = array();
		$opts['mine'] = true;
		 return $this->youtube->playlists->listPlaylists('snippet', $opts);
	}
	
	public function getVideosByPlaylist($pid)
	{
		return $this->youtube->playlistItems->listPlaylistItems('snippet', array(
        'playlistId' => $pid,
        'maxResults' => 50
      ));
	}
	
	public function refreshUserTokens($uid)
	{
		$tokens = $this->getUserTokens($uid);
		$this->google->client->setAccessToken($tokens->youtube_token);
		if ($this->google->client->isAccessTokenExpired()) 
		{
			$this->google->client->refreshToken($tokens->youtube_refresh_token);
			$this->saveUserTokens($tokens->youtube_refresh_token, $this->google->client->getAccessToken(),$uid);
		}
	}
	
	public function getUserTokens($uid)
	{
		$sql = 'SELECT youtube_refresh_token, youtube_token FROM users WHERE uid = :uid';
		$stmt= $this->db->prepare($sql);
		$stmt->bindParam(':uid',$uid);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_OBJ);
		
	}
	
	public function saveUserTokens($refreshToken,$token,$uid)
	{
		$sql = 'UPDATE users SET youtube_linked = :linked, youtube_refresh_token = :refresh, youtube_token = :token WHERE uid = :uid';
		$stmt = $this->db->prepare($sql);
		$num = 1;
		$stmt->bindParam(':linked',$num);
		$stmt->bindParam(':refresh',$refreshToken);
		$stmt->bindParam(':token',$token);
		$stmt->bindParam(':uid',$uid);
		
		$stmt->execute();
	}
	
	public function logout()
	{
		if(isset($_SESSION['illg_youtube_token']))
		{
			unset($_SESSION['illg_youtube_token']);
		}
	}
}
?>
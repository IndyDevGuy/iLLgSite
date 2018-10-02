<?php

include("Crypt" . DIRECTORY_SEPARATOR . "RSA.php");

class SteamUser
{
  	//Login related variables
	private $username;
	private $password;
	private $encryptedPassword;

	//RSA related variables
	private $publicKeyModulus;
	private $publicKeyExponent;
	private $timestamp;

	//Steam Community session and stuff
	private $cookie;
	private $session;
	private $steamid64;
	private $token;

	public function __construct($gUsername, $gPassword)
	{
		$this->username = $gUsername;
		$this->password = $gPassword;
	}

	public function getPublicRSAKey()
	{
		if(strlen($this->username) > 0)
		{
			$postData = array("username" => $this->username);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/login/getrsakey/");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$rsaResponse = curl_exec($ch);
			curl_close($ch);

			$jsonRSA = json_decode($rsaResponse);
			if(isset($jsonRSA->publickey_mod) && isset($jsonRSA->publickey_exp) && isset($jsonRSA->timestamp))
			{
				$this->publicKeyModulus = new Math_BigInteger($jsonRSA->publickey_mod, 16);
				$this->publicKeyExponent = new Math_BigInteger($jsonRSA->publickey_exp, 16);
				$this->timestamp = $jsonRSA->timestamp;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}


	/**
	 * SteamUser::doLogin()
	 *
	 * @return bool Returns true if the login was successful, false otherwise.
	 * This method should only be called after SteamUser::getPublicRSAKey()
	 */
	public function doLogin()
	{
		if(isset($this->publicKeyExponent) && isset($this->publicKeyModulus))
		{
			$rsa = new Crypt_RSA();
			$rsa->loadKey( array("modulus" => $this->publicKeyModulus,
								 "exponent" => $this->publicKeyExponent),
						 	CRYPT_RSA_PUBLIC_FORMAT_RAW);
			$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
			$this->encryptedPassword = base64_encode( $rsa->encrypt($this->password) );


			/* Community Update of July 31st
			   * Valve added "remember_login" parameter
			*/
			$postData = array("password" => $this->encryptedPassword,
				  			  "username" => $this->username,
				  			  "emailauth" => "",
				  			  "captchagid" => "-1",
				  			  "captcha_text" => "",
				  			  "emailsteamid" => "",
				  			  "rsatimestamp" => $this->timestamp,
				  			  "remember_login" => false);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://steamcommunity.com/login/dologin/");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$out = curl_exec($ch);
			curl_close($ch);

			if(preg_match("/steamLogin=(.*);/i", $out, $cookies))
			{
				$this->cookie = $cookies[1];

				/* Community Update of July 31st
				 * Valve moved the "sessionid" cookie to another URL.
				*/
				$ch2 = curl_init();
				curl_setopt($ch2, CURLOPT_URL, "http://steamcommunity.com/actions/RedirectToHome");
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch2, CURLOPT_HEADER, true);
				curl_setopt($ch2, CURLOPT_COOKIE, "steamLogin=" . $this->cookie);

				$out2 = curl_exec($ch2);
				curl_close($ch2);


				if(preg_match("/sessionid=(.*);/i", $out2, $sessID))
				{
					$this->session = $sessID[1];

					$jsonTXT = substr($out,strpos($out, "{"));
					$jsonFeed = json_decode($jsonTXT);
					if(isset($jsonFeed->transfer_parameters->steamid) && isset($jsonFeed->transfer_parameters->token))
					{
						$this->steamid64 = $jsonFeed->transfer_parameters->steamid;
						$this->token = $jsonFeed->transfer_parameters->token;
						return true;
					}
				}
				else
				{
					return false;
				}

			}
			else
			{
				return false;
			}

		}
		else
		{
			return false;
		}
	}

	/**
	 * SteamUser::postAnnouncement()
	 *
	 * @param string $groupName Steam Community group Abbreviation. If the group URL is http://steamcommunity.com/groups/name , then $groupName is "name"
	 * @param string $title Announcement title
	 * @param string $body Announcement body
	 * @return bool True if the announcement was posted, False otherwise.
	 */
	public function postAnnouncement($groupName, $title, $body)
	{
		if(!isset($this->cookie) || !isset($this->session))
			return false;

		$message = array("action" => "post",
						 "headline" => $title,
						 "body" => $body,
						 "sessionID" => urldecode($this->session));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://steamcommunity.com/groups/$groupName/announcements");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, "steamLogin=" . $this->cookie . "; sessionid=" . $this->session);

		$out = curl_exec($ch);
		if(!$ch)
		{
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		return true;
	}

	/**
	 * SteamUser::postEvent()
	 *
	 * @param string $groupName Steam Community group Abbreviation. If the group URL is http://steamcommunity.com/groups/name , then $groupName is "name"
	 * @param string $eventTitle Event title
	 * @param string $eventType Event Type (GameEvent,BroadcastEvent,OtherEvent,PartyEvent,MeetingEvent,SpecialCauseEvent,MusicAndArtsEvent,SportsEvent,TripEvent)
	 * @param string $eventDescription Event Description
	 * @param string $startDate Event start date (09/23/15)
	 * @param int $startHour Event start hour (1-12)
	 * @param int $startMinute Event start minute (0-59)
	 * @param string $startAMPM Event AM or PM (AM, PM)
	 * @param string $eventQuickTime Event quick start (now, 5m, 15m, 30m, 1h)
	 * @return bool True if the Event was posted, False otherwise.
	 */
	public function postEvent($groupName, $eventTitle,$eventType $eventDescription, $startDate, $startHour, $startMinute, $startAMPM, $eventQuickTime)
	{
		if(!isset($this->cookie) || !isset($this->session))
			return false;

		$message = array("action" => "post",
						 "eventTitle" => $eventTitle,
						 "eventType" => $eventType,
						 "eventDescription" => $eventDescription,
						 "startDate" => $startDate,
						 "startHour" => $startHour,
						 "startMinute" => $startMinute,
						 "startAMPM" => $startAMPM,
						 "eventQuickTime" => $eventQuickTime,
						 "sessionID" => urldecode($this->session));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://steamcommunity.com/groups/$groupName/eventEdit");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, "steamLogin=" . $this->cookie . "; sessionid=" . $this->session);

		$out = curl_exec($ch);
		if(!$ch)
		{
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		return true;
	}


	/**
	 * SteamUser::joinGroup()
	 *
	 * @param string $group Steam Community group Abbreviation. If the group URL is http://steamcommunity.com/groups/name, then $group is "name"
	 * @return
	 */
	public function joinGroup($group)
	{
		if(!isset($this->cookie) || !isset($this->session))
			return false;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://steamcommunity.com/groups/$group?action=join&sessionID=".$this->session);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, "steamLogin=" . $this->cookie . "; sessionid=" . $this->session);

		curl_exec($ch);
		if(!$ch)
		{
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		return true;

	}

	public function dumpGroupHistory($group)
	{
		if(!isset($this->cookie) || !isset($this->session))
			return false;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://steamcommunity.com/groups/$group/history");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, "steamLogin=" . $this->cookie . "; sessionid=" . $this->session);

		$pageContent = curl_exec($ch);
		if(!$ch)
		{
			curl_close($ch);
			return false;
		}
		curl_close($ch);

		//"&nbsp;...&nbsp;<a href="?p=21">21</a>&nbsp;<a class='pagebtn'";
		$sPos = strpos($pageContent, '<div class="pageLinks">');
		$ePos = strpos($pageContent, '<p>', $sPos);
		$sub = substr($pageContent, $sPos, $ePos - $sPos + 1);

		echo PHP_EOL.PHP_EOL;
		if(preg_match('#<a href="\?p=[0-9]{1,2}">([0-9]{1,2})</a>&nbsp;<a class=\'pagebtn\'#i', $sub, $page))
		{
			$totalPage = (int)$page[1];
			echo "Dumping page 1 of $totalPage" . PHP_EOL;
			file_put_contents("history-$group-1.html", $pageContent);
			for($i=2; $i<=$totalPage; $i++)
			{
				echo "Dumping page $i of $totalPage" . PHP_EOL;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://steamcommunity.com/groups/$group/history?p=$i");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_COOKIE, "steamLogin=" . $this->cookie . "; sessionid=" . $this->session);

				$pageContent = curl_exec($ch);
				file_put_contents("history-$group-$i.html", $pageContent);

				curl_close($ch);
			}
			echo "Done!".PHP_EOL;
		}
		else
		{
			echo "REGEX FAILED" . PHP_EOL;
		}
	}

}






?>
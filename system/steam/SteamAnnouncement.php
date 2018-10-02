<?php

include "class.SteamUser.php";

$community = new SteamUser("LOGIN", "PASSWORD");

if($community->getPublicRSAKey() && $community->doLogin())
{
	$community->postAnnouncement("heffe_test2", "Announcement Title" , "Announcement body");

}
else
{
	echo "LOGIN FAILED";
}

?>
<?php
//Created by M@St3r_iLLuSioN of iLLuSioN GrOuP
//version 1.0
date_default_timezone_set('America/Indiana/Indianapolis');
require_once('libraries/vendor/autoload.php');
session_start();
$db_database = 'rust';
$db_server = 'localhost';
$db_user = 'root';
$db_pass = '';
function loadClasses($class)
{
	$filename = 'system/classes/' . $class . '.php';
	if (file_exists($filename))
	{
    include('classes/' . $class . '.php');
	}
    // Check to see whether the include declared the class
    
}
spl_autoload_register('loadClasses');
function loadControllers($class)
{
	$filename = 'system/controllers/' . $class . '.php';
	if (file_exists($filename))
	{
		include('controllers/' . $class . '.php');
	}
    // Check to see whether the include declared the class
    
}
spl_autoload_register('loadControllers');

function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
$registry = new registry();

$page = '';
if (!isset($_GET['ajax']))
{
	ob_start();
	
	$page .= include('templates/layout/htmlopen.php');
	$page .= include('templates/layout/bodyopen.php');
	$page .= include('templates/layout/header.php');
	$db = new database($db_user,$db_pass,$db_database,$db_server);
$registry->db = $db->db;
$registry->users = new users($registry);
$registry->users->isLoggedIn();
$registry->NC = new NotificationCenter($registry);
	$page .= include('templates/layout/mainopen.php');
}
$registry->siteHeader = '
    <script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery-ui/jquery-ui.js"></script>
<link rel="stylesheet" href="/js/jquery-ui/jquery-ui.css">
<script type="text/javascript" src="/js/fns.js"></script>';
$registry->pageTitle = '';
$registry->validator = new validator();
$db = new database($db_user,$db_pass,$db_database,$db_server);
$registry->db = $db->db;
$registry->data = new data();
$registry->users = new users($registry);
$registry->users->isLoggedIn();
//If user is logged in connect to node.js server for RTN and RTU (Real Time Notification and Real Time Updates)
if($registry->guest == false)
{
	$registry->siteHeader .= '
	<script src="node_modules/socket.io-client/socket.io.js"></script>
	<script>
	 var socket = io.connect(\'50.90.2.130:8000\');
	 socket.emit(\'UserInfo\',{uid:'.$registry->user['uid'].'});
	 //when server emits the data for the notification count
	 socket.on(\'notificationCount\', function(data){
	 		//alert(data);
	 	if(data != 0)
	 	{
			$(".noti_bubble").show(\'fast\');
			$(".noti_bubble").html(data);
		}
		else
		{
			$(".noti_bubble").hide(\'fast\');
			$(".noti_bubble").html(data);
		}
	 	$(".noti_bubble").html(data);
	 	socket.emit(\'UserInfo\',{uid:'.$registry->user['uid'].'});	
	 });
	 //when server emits ther data for the message count
	 socket.on(\'messageCount\', function(data){
	 	if(data != 0)
	 	{
			$(".noti_mess_bubble").show(\'fast\');
			$(".noti_mess_bubble").html(data);
		}
		else
		{
			$(".noti_mess_bubble").hide(\'fast\');
			$(".noti_mess_bubble").html(data);
		}
	 });
	</script>
	';
}
$registry->Steam = new steamlogin($registry);
$registry->router = new router($registry);
$registry->youtube = new Youtube($registry,'http://illusiongroup.us/user/profile/youtube/');
$page .= $registry->router->doRoute();
if (!isset($_GET['ajax']))
{
	$page .= include('templates/layout/sidebaropen.php');
	$page .= include('templates/layout/sidebarclose.php');
	$page .= include('templates/layout/footeropen.php');
	$page .= include('templates/layout/footerclose.php');
	$page .= include('templates/layout/mainclose.php');
	$page .= include('templates/layout/bodyclose.php');
	$page .= include('templates/layout/htmlclose.php');
	$buffer=ob_get_contents();
	ob_end_clean();
	$page =  str_replace ('<!--TITLE-->', $registry->pageTitle, $buffer);
	echo str_replace('<!--HEADER-->',$registry->siteHeader,$page);
}
else
{
	echo $page;
}
?>
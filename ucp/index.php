<?php
session_start();
ob_start();

@ini_set('magic_quotes_runtime', 0);

require_once("includes/config.php");
require_once("includes/classes.php");

// check $page
if (!isset($_GET['page']) and !isset($_GET['ucp']) and !isset($_GET['fairplay']) and !isset($_GET['tc']) and !isset($_GET['admin']))
{
	Header("Location: /page/home/");
	die();
}

addFlashObject("/flash/banner.swf", "banner", "1067", "252", "/flash/preloader.swf");

if (isset($_GET['page']))
	switch ($_GET['page'])
	{
		case 'home':
			require_once("includes/header.php");
			require_once("pages/main.php");
			require_once("includes/footer.php");	
			break;
		case 'about':
			require_once("includes/header.php");
			require_once("pages/about.php");
			require_once("includes/footer.php");	
			break;
		case 'staff':
			require_once("includes/header.php");
			require_once("pages/staff.php");
			require_once("includes/footer.php");	
			break;
		case 'guides':
			require_once("includes/header.php");
			require_once("pages/guides.php");
			require_once("includes/footer.php");	
			break;
		default:
			echo "server made a boo boo.";
			break;
	}
elseif (isset($_GET['ucp']))
{
	require_once("includes/ucp_functions.php");
	switch ($_GET['ucp'])
	{
		case 'login':
			require_once("pages/ucp/login.php");	
			break;
		case 'logout':
			require_once("pages/ucp/logout.php");	
			break;
		case 'register':
			require_once("pages/ucp/register.php");	
			break;
		case 'writeapplication':
			require_once("pages/ucp/writeapplication.php");	
			break;
		case 'forgot-password':
			require_once("pages/ucp/forgot-password.php");
			break;
		case 'editdetails':
			require_once("pages/ucp/editdetails.php");
			break;
		case 'character':
			require_once("pages/ucp/chardetail.php");	
			break;
		case 'interior':
			require_once("pages/ucp/interiordetail.php");	
			break;		
		case 'perktransfer':
			require_once("pages/ucp/perktransfer.php");	
			break;
		case 'donate':
			require_once("includes/paypal.class.php");
			require_once("pages/ucp/donate.php");	
			break;		
		case 'main':
			require_once("pages/ucp/main.php");	
			break;
		default:
			echo "server made a boo boo at the ucp.";
			break;
	}
}
elseif (isset($_GET['fairplay']))
{
	switch ($_GET['fairplay'])
	{
		case 'main':
			require_once("includes/header.php");
			require_once("pages/fairplay/main.php");	
			require_once("includes/footer.php");
			break;
		default:
			echo "server made a boo boo at fairplay.";
			break;
	}
}
elseif (isset($_GET['tc']))
{
	require_once("includes/ucp_functions.php");
	switch ($_GET['tc'])
	{
		case 'main':
			require_once("includes/header.php");
			require_once("pages/tc/main.php");
			require_once("includes/footer.php");
			break;
		case 'view':
			require_once("includes/header.php");
			require_once("pages/tc/view.php");
			require_once("includes/footer.php");
			break;
		case 'new':
			require_once("includes/header.php");
			require_once("pages/tc/new.php");
			require_once("includes/footer.php");
			break;
		default:
			echo "server made a boo boo at ticket center.";
			break;
	}
}
elseif (isset($_GET['admin']))
{
	require_once("includes/ucp_functions.php");
	switch ($_GET['admin'])
	{
		case 'main':
			require_once("includes/header.php");
			require_once("pages/admin/main.php");
			require_once("includes/footer.php");
			break;
		case 'interiors':
			require_once("includes/header.php");
			require_once("pages/admin/interiors/main.php");
			require_once("includes/footer.php");
			break;
		case 'players':
			require_once("includes/header.php");
			require_once("pages/admin/players/main.php");
			require_once("includes/footer.php");
			break;
		default:
			echo "server made a boo boo at the admin module.";
			break;
	}
}
?>
<?php
if (!isset($_SESSION['ucp_loggedin']) or !$_SESSION['ucp_loggedin'])
{
	header("Location: /ucp/login/");
	die();
}

$MySQLConn = @mysql_connect($Config['database']['hostname'], $Config['database']['username'], $Config['database']['password']);
if (!$MySQLConn) 
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$selectdb = @mysql_select_db($Config['database']['database'], $MySQLConn);

$userID = $_SESSION['ucp_userid'];
$mQuery1 = mysql_query("SELECT `username`,`admin` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) == 0)
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$userRow = mysql_fetch_assoc($mQuery1);
$username = $userRow['username'];
$adminLevel = $userRow['admin'];

if ($adminLevel < 2)
{
	header("Location: /ucp/main/");
	die();
}

if (!isset($_GET['action']) or $_GET['action'] == "main")
{
	require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
					<div id="content-middle">
						<div class="content-box">
							<div class="content-holder">
								<h2>AdminCP - Interiors</h2>
								<BR />
								<BR />
								- <A HREF="/admin/interiors/inactivitylist/">Property Inactivity Lists (PIL)</A><BR />
							</div>
						</div>
					</div>
<?php
	require_once("includes/footer.php");
}
elseif ($_GET['action'] == "inactivitylist")
{
	require_once("pages/admin/interiors/inactivitylist.php");
}
mysql_close($MySQLConn);
?>
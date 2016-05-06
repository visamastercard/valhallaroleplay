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
								<h2>AdminCP - Players</h2>
								<BR />
								<BR />
								- <A HREF="/admin/players/perkslist/">Donation and perkists (Lead+)</A><BR />
								- <A HREF="/admin/players/guntrace/">Gun serial trace (Admin+)</A><BR />
								- <A HREF="/admin/players/logs/">Search in logs (Admin+)</A><BR />
							</div>
						</div>
					</div>
<?php
	require_once("includes/footer.php");
}
elseif ($_GET['action'] == "perkslist")
{
	if ($adminLevel < 4)
	{
		header("Location: /ucp/main/");
		die();
	}
	require_once("pages/admin/players/perkslist.php");
}
elseif ($_GET['action'] == "logs")
{
	require_once("pages/admin/players/logs.php");
}
elseif ($_GET['action'] == 'guntrace')
{
	//if ($adminLevel < 4)
	//{
	//	header("Location: /ucp/main/");
	//	die();
	//}
	require_once("pages/admin/players/guntrace.php");
}
else {
	echo 'epicfail?';
}
mysql_close($MySQLConn);
?>
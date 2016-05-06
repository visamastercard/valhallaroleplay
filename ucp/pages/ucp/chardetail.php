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
$mQuery1 = mysql_query("SELECT `username` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) == 0)
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$userRow = mysql_fetch_assoc($mQuery1);
$username = $userRow['username'];

// /ucp/characters/<name>/ $param2
if (!isset($_GET['param2']))
{
	header("Location: /ucp/main/");
	exit;
}

$charactername = mysql_real_escape_string($_GET['param2']);

$mQuery2 = mysql_query("SELECT `id`,`charactername`,`skin`,`faction_id`,`faction_rank`,`money`,`bankmoney`,`age`,`height`,`active` FROM `characters` WHERE (`charactername`='".$charactername."' OR id='".$charactername."') AND `account`='".$userID."'");
if (mysql_num_rows($mQuery2) == 0 || mysql_num_rows($mQuery2) > 1)
{
	header("Location: /ucp/main/");
	exit;
}

$characterData = mysql_fetch_assoc($mQuery2);

$changed = false;

// start update fields
if (isset($_POST['heightval']))
{
	$newheight = $_POST['heightval'];
	if ($newheight >= 150 and $newheight <= 200)
	{
		mysql_query("UPDATE `characters` SET `height`='".$newheight."' WHERE `id`='".$characterData['id']."'");
		$changed = true;
	}
}

if (isset($_POST['ageval']))
{
	$newage = $_POST['ageval'];
	if ($newage >= 18 and $newage < 100)
	{
		mysql_query("UPDATE `characters` SET `age`='".$newage."' WHERE `id`='".$characterData['id']."'");
		$changed = true;
	}
}

if (isset($_POST['activatechar']))
{
	mysql_query("UPDATE `characters` SET `active`=1 WHERE `id`='".$characterData['id']."'");
	$changed = true;
}

if (isset($_POST['deactivatechar']))
{
	mysql_query("UPDATE `characters` SET `active`=0 WHERE `id`='".$characterData['id']."'");
	$changed = true;
}

if ($changed)
{
	header("Location: /ucp/character/".$characterData['charactername']."/");
	exit;
}
// end update fields

// workaround for the current image links
$add = '';
$addd = '';
if (strlen($characterData['skin']) != 3)
	$add='0';
if (strlen($characterData['skin'])+1 < 3)
	$addd='0';
// end workaround

// faction
$factionStr = "";

if ($characterData['faction_id'] != -1)
{
	$mQuery3 = mysql_query("SELECT `name`, `rank_". $characterData['faction_rank'] ."` FROM `factions` WHERE `id`='".mysql_real_escape_string($characterData['faction_id'])."'");
	if (mysql_num_rows($mQuery3) == 1)
	{
		$factionRow = mysql_fetch_assoc($mQuery3);
		$factionStr = $factionRow['rank_'. $characterData['faction_rank'] ] . ' at '. $factionRow['name'].'<BR />';
	}
}
// end faction

// Netto worth
$nettworth = 0;
$nettworth = $nettworth + $characterData['money'];
$nettworth = $nettworth + $characterData['bankmoney'];

$mQuery4 = mysql_query("SELECT sum(`cost`) AS 'inttotal' FROM `interiors` WHERE `owner`='".$characterData['id']."' ");
if (mysql_num_rows($mQuery4) > 0)
{
	$intWorthRow = mysql_fetch_assoc($mQuery4);
	$nettworth = $nettworth + $intWorthRow['inttotal'];
}
// End netto worth

// properties
$propArr = array();
if (!isset($_SESSION['houseallow']))
	$_SESSION['houseallow'] = array();
	
$mQuery5 = mysql_query("SELECT `id`,`name` FROM `interiors` WHERE `owner`='".$characterData['id']."'");
while ($introw = mysql_fetch_assoc($mQuery5))
{
	$_SESSION['houseallow'][$introw['id']] = true;
	$propArr[$introw['id']] = $introw['name'];
}
// end properties

if (!isset($_GET['param3']) || $_GET['param3'] == 'view')
{	// Show character details and edit fields
	require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
					<div id="content-middle">
						<div class="content-box">
							<div class="content-holder">
								<form action="" method="post">
									<h2>User Control Panel - Character information</h2>
									<table width="100%" border="0">
										<tr>
											<td valign="top" ><img src="/images/chars/<?php echo $add.$addd.$characterData['skin']; ?>.png"></td>
											<td colspan="2" valign="top" align="right">
												<?php echo str_replace("_", " ", $characterData["charactername"]); ?><BR />
												<?php echo $factionStr; ?>
												Nett worth: $ <?php echo $nettworth; ?><BR />
											</td>
										</tr>
										<tr>
											<td valign="top">
												Edit character information: <BR />
												<BR />
												<!--Active: <input type="checkbox" name="charactive" <?php if ($characterData['active'] == 1) echo 'CHECKED'; ?> /><BR /> -->
												<?php if ($characterData['active'] == 1) { ?>
												<input type="submit" name="deactivatechar" value="Deactivate" style="width: 150px;">
												<?php } else { ?>
												<input type="submit" name="activatechar" value="Activate" style="width: 150px;">
												<?php } ?>
												<BR />
												Age: (18-99)<BR /> 
												<input type="text" name="ageval" value="<?php echo $characterData['age']; ?>" maxlength="2" size="1" /> years old <BR />
												<BR />
												Height: (150-200)<BR /> 
												<input type="text" name="heightval" value="<?php echo $characterData['height']; ?>" maxlength="3" size="1" /> CM<BR />
												<BR />
												<input type="submit" name="submitsave" value="Save" style="width: 150px;">
											</td>
											<td valign="top" colspan="2">
												Properties owned:
												<ul>
<?php
	if (count($propArr) == 0)
	{
	?>													<li>None</li>
	<?php
	}
	else {
		foreach ($propArr as $propertyID => $propertyName)
		{
			echo "													<li><a href=\"/ucp/interior/".$propertyID."/\">".$propertyName."</a></li>\r\n";
		}
	}
?>
												</ul>
											</td>
										</tr>
									</table>
								</form>
							</div>
						</div>
					</div>	
<?php
	require_once("includes/footer.php");
}

?>
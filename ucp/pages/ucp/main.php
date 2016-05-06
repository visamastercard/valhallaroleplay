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
$mQuery1 = mysql_query("SELECT `username`, `admin`, `appstate`, `appreason`, `banned`, `email`, `appdatetime` > NOW() as 'noapply', HOUR(TIMEDIFF(NOW(), `appdatetime`)) as 'timehour', MINUTE(TIMEDIFF(NOW(), `appdatetime`)) as 'timeminute', `banned_reason` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) == 0)
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$userRow = mysql_fetch_assoc($mQuery1);

// Application stuff
$applicationState = $userRow['appstate'];
$applicationReason = $userRow['appreason'];
$timeminute = $userRow['timeminute'];
$timehour = $userRow['timehour'];
$cantApply = $userRow['noapply'];

// Generall stuff
$bannedState = $userRow['banned'];
$emailAddress = $userRow['email'];

require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Account Information</h2>
							<span style="text-align:center;">
							 <?php
								if ($applicationState == 2)
									echo "<p><font color='#FF0000'>Your application was Denied!.</font></p><p>Reason: " . $applicationReason . "</p>";
							?>
							</span>
							
							<ul style="list-style-type:none;margin-left:0px;padding-left:0px;">
								<li><b>Application Status:</b>							
								<?php
									if ($applicationState == 0)
										echo "<a href='/ucp/writeapplication/'><font color='#FF9900' align='left'>Click here to write one</font></a></li>";
									elseif ($applicationState == 1)
										echo "<font color='#FF9900' align='left'>Pending Review</font></li>";
									elseif ($applicationState == 2)
										if( $cantApply == 1 )
											echo "<font color='#FF0000'>Denied - You need to wait " . (($timehour > 0)?($timehour . ' hours and '):('')) . $timeminute . " minutes before applying again.</font></a></li>";
										else
											echo "<a href='/ucp/writeapplication/'><font color='#FF0000'>Denied - Click here to write a new application.</font></a></li>";
									elseif ($applicationState == 3)
										echo "<font color='#66FF00'>Accepted</font></li>";
								?>
								<li><b>Account Standing:</b> <?php echo getStandingFromIndex($bannedState) ?></li>
								<li><b>Email Address:</b> <?php echo $emailAddress; ?></li>
							</ul>
						</div>
					</div>
<?php
//<li><b>Forum Signature:</b> <a href="http://www.mta.vg/pages/create_sig.php?show=1&id=p echo $userID; " target="_blank">Click to view and get code</a><li>
$charQuery = mysql_query("SELECT `id`, `charactername`,`cked`,`skin` FROM `characters` WHERE `account`='".$userID."' AND `active`=1");
if (mysql_num_rows($charQuery) > 0)
{
?>					<div class="content-box">
						<div class="content-holder">
							<h2>Your Active characters</h2>
							<p><a href="/ucp/perktransfer/">Do a perks tranfer - Move perks between characters! - Click here!</a></p>
							<table align="center" border="0">
								<tr>
<?php
	$rowpos = 1;
	while ($row = mysql_fetch_assoc($charQuery))
	{
		if ($rowpos == 6)
		{
			echo "								</tr>\r\n";
			echo "								<tr>\r\n";
			
			$rowpos = 1;
		}
		
		// workaround for the current image links
		$add = '';
		$addd = '';
		if (strlen($row['skin']) != 3)
			$add='0';
		if (strlen($row['skin'])+1 < 3)
			$addd='0';
		// end workaround
		
		if ($row["cked"] == 0)
			$status = '<font color="#66FF00">Alive</font>';
		else
			$status = '<font color="#FF0000">Dead</font>';
		echo "									<td align=\"center\"><a href=\"/ucp/character/".$row["id"]."/\"><img src=\"/images/chars/".$add.$addd.$row['skin'].".png\"><BR />".str_replace("_", " ", $row["charactername"])."</a><BR />".$status."</td>\r\n"; // <BR /><a href='http://www.mta.vg/pages/create_sig.php?show=2&id=" . $row['id'] . "' target='_blank'>View Sig</a>
		$rowpos++;
	}
	
						
?>								</tr>
							</table>
						</div>
					</div>
<?php
}
$inactivecharQuery = mysql_query("SELECT `id`, `charactername`,`cked`,`skin` FROM `characters` WHERE `account`='".$userID."' AND `active`=0");
if (mysql_num_rows($charQuery) > 0)
{
?>
					<div class="content-box">
						<div class="content-holder">
							<h2>Inactive characters</h2>
							<h4>(These don't show up in character selection ingame)</h4>
							<table align="center" border="0">
								<TR>
<?php
	$rowpos = 1;
	while ($row = mysql_fetch_assoc($inactivecharQuery))
	{
		if ($rowpos == 6)
		{
			echo "								</tr>\r\n";
			echo "								<tr>\r\n";
			
			$rowpos = 1;
		}
		
		// workaround for the current image links
		$add = '';
		$addd = '';
		if (strlen($row['skin']) != 3)
			$add='0';
		if (strlen($row['skin'])+1 < 3)
			$addd='0';
		// end workaround
		
		if ($row["cked"] == 0)
			$status = '<font color="#66FF00">Alive</font>';
		else
			$status = '<font color="#FF0000">Dead</font>';
		echo "									<td align=\"center\"><a href=\"/ucp/character/".$row["id"]."/\"><img src=\"/images/chars/".$add.$addd.$row['skin'].".png\"><BR />".str_replace("_", " ", $row["charactername"])."</a><BR />".$status."</td>\r\n"; // <BR /><a href='http://www.mta.vg/pages/create_sig.php?show=2&id=" . $row['id'] . "' target='_blank'>View Sig</a>
		$rowpos++;
	}
?>
								</TR>
							</TABLE>
						</div>
					</div>
<?php } ?>
				</div>
<?php

require_once("includes/footer.php");


?>
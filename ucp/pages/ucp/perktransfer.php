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
$mQuery1 = mysql_query("SELECT `username`,`transfers` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) == 0)
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$userRow = mysql_fetch_assoc($mQuery1);
$username = $userRow['username'];
$transfers = $userRow['transfers'];

$charArr = array();
$mQuery2 = mysql_query("SELECT `id`,`charactername` FROM `characters` WHERE `account`='".$userID."' ORDER BY `charactername` ASC");
while ($characterRow = mysql_fetch_assoc($mQuery2))
{
	$charArr[ $characterRow['id'] ] = $characterRow['charactername'];
}


// /ucp/perktransfer/<name>/ $param2
/*

					
*/

if (!isset($_GET['param2']) || $_GET['param2'] == 'view' || $_GET['param2'] == 'step2' || $_GET['param2'] == 'step3')
{
	require_once("includes/header.php");
	if (isset($_POST['fromcharacter']) AND isset($_POST['tocharacter']))
	{
		if (isset($charArr[$_POST['fromcharacter']]) AND isset($charArr[$_POST['tocharacter']]))
		{
			if ($_POST['fromcharacter'] != $_POST['tocharacter'])
			{
				require_once("includes/mta_sdk.php");
				$mtaServer = new mta($Config['server']['hostname'], 22003, $Config['server']['username'], $Config['server']['password'] );
				$isPlayerOnline = $mtaServer->getResource("mtavg")->call("isPlayerOnline", $userID);
				if (!$isPlayerOnline[0]) { // if (true) { //
					$fromCharacterQry = mysql_query("SELECT `id`,`charactername`,`skin`,`bankmoney`,`money` FROM `characters` WHERE `account`='".mysql_real_escape_string($userID)."' AND `id`='".mysql_real_escape_string($_POST['fromcharacter'])."'");
					$toCharacterQry = mysql_query("SELECT `id`,`charactername`,`skin` FROM `characters` WHERE `account`='".mysql_real_escape_string($userID)."' AND `id`='".mysql_real_escape_string($_POST['tocharacter'])."'");
					if (mysql_num_rows($fromCharacterQry) == 1 AND mysql_num_rows($toCharacterQry) == 1)
					{
						$fromCharacterRow = mysql_fetch_assoc($fromCharacterQry);
						$toCharacterRow = mysql_fetch_assoc($toCharacterQry);
						
						while (strlen($fromCharacterRow['skin']) < 3)
							$fromCharacterRow['skin'] = '0'.$fromCharacterRow['skin'];
							
						while (strlen($toCharacterRow['skin']) < 3)
							$toCharacterRow['skin'] = '0'.$toCharacterRow['skin'];
						
						$vehicleArr = array();
						$fromCharacterVehicleQuery = mysql_query("SELECT `id`, `model` FROM `vehicles` WHERE `owner`='".mysql_real_escape_string($fromCharacterRow['id'])."'");
						while ($vehicleRow = mysql_fetch_assoc($fromCharacterVehicleQuery))
						{
							$ret = $mtaServer->getResource("carshop-system")->call("isForSale", $vehicleRow['model']);
							if($ret && $ret[0] === true)
							{
								$vehicleArr[ $vehicleRow['id'] ] = $vehicleRow['model'];
							}
						}
						
						$interiorArr = array();
						$fromCharacterInteriorQuery = mysql_query("SELECT `id`, `name` FROM `interiors` WHERE `owner`='".mysql_real_escape_string($fromCharacterRow['id'])."'");
						while ($interiorRow = mysql_fetch_assoc($fromCharacterInteriorQuery))
						{
							$interiorArr[ $interiorRow['id'] ] = $interiorRow['name'];
						}
						
						if (isset($_POST['bankmoney']) AND isset($_POST['money']))
						{
							$continue = true;
							$error = '';
							// Validate input
							if (!isDecimalNumber($_POST['bankmoney']) or $_POST['bankmoney'] > $fromCharacterRow['bankmoney'] or $_POST['bankmoney'] < 0)
							{
								$continue = false;
								$error .= 'Invalid amount of bankmoney. ';
							}
							
							if (!isDecimalNumber($_POST['money']) or $_POST['money'] > $fromCharacterRow['money'] or $_POST['money'] < 0)
							{	
								$continue = false;
								$error .= 'Invalid amount of money in pocket. ';
							}			
							
							if (isset($_POST['vehicle']))
							{
								foreach ($_POST['vehicle'] as $tempVehicleID)
								{
									if (!isset($vehicleArr[$tempVehicleID]))
									{
										$continue = false;
										$error .= 'Tried to transfer a vehicle thats not yours. ';
									}
								}
							}
							
							if (isset($_POST['interior']))
							{
								foreach ($_POST['interior'] as $tempInteriorID)
								{
									if (!isset($interiorArr[$tempInteriorID]))
									{
										$continue = false;
										$error .= 'Tried to transfer an interior thats not yours. ';
									}
								}
							}
							
							if ($transfers == 0)
							{
								$continue = false;
								$error .= 'No perk transfers available. ';
							}
							
							if ($continue)
							{ 	// TRANSFER THAT SHITZ
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Perk transfer</h2>
							<BR />
							Going to start... <b>now</b>!<BR /><BR />
<?php
								mysql_query("INSERT INTO `logtable` VALUES (NOW(), '1', 'ac".mysql_real_escape_string($userID)."', 'ac".mysql_real_escape_string($userID).";ch".$fromCharacterRow['id'].";ch".$toCharacterRow['id']."', 'Starting stattransfer between ".mysql_real_escape_string($fromCharacterRow["charactername"])." and ".mysql_real_escape_string($toCharacterRow["charactername"])."')");
mysql_query("INSERT INTO `adminhistory` (`user`, `user_char`,`admin_char`,`admin`,`date`, `action`,`duration`,`reason`,`hiddenadmin`) VALUES ('".mysql_real_escape_string($userID)."', 'N/A','N/A','".mysql_real_escape_string($userID)."', now(), 6, '0','Stattransfer from ".$fromCharacterRow['charactername']." to ".$toCharacterRow['charactername']."','0')");

								mysql_query("UPDATE `accounts` SET `transfers`=`transfers`-1 WHERE `id`='".mysql_real_escape_string($userID)."'");
								if ($_POST['money'] > 0 or $_POST['bankmoney'] > 0)
								{
									mysql_query("INSERT INTO `logtable` VALUES (NOW(), '1', 'ac".mysql_real_escape_string($userID)."', 'ac".mysql_real_escape_string($userID).";ch".$fromCharacterRow['id'].";ch".$toCharacterRow['id'].";', 'Transfering bank ".mysql_real_escape_string($_POST['bankmoney'])." and cash ".mysql_real_escape_string($_POST['money'])." from ".mysql_real_escape_string($fromCharacterRow["charactername"])." to ".mysql_real_escape_string($toCharacterRow["charactername"])."')");
									mysql_query("UPDATE `characters` SET `money`=`money`-".$_POST['money'].", `bankmoney`=`bankmoney`-".$_POST['bankmoney']." WHERE `id`='".$fromCharacterRow['id']."'");
									mysql_query("UPDATE `characters` SET `money`=`money`+".$_POST['money'].", `bankmoney`=`bankmoney`+".$_POST['bankmoney']." WHERE `id`='".$toCharacterRow['id']."'");
									echo '- Money transfered<BR />';
									flush();
								}
								
								if(isset($_POST['vehicle']))
								{
									foreach ($_POST['vehicle'] as $tempVehicleID)
									{
										mysql_query("INSERT INTO `logtable` VALUES (NOW(), '1', 'ac".mysql_real_escape_string($userID)."', 'ac".mysql_real_escape_string($userID).";ch".$fromCharacterRow['id'].";ch".$toCharacterRow['id'].";ve".$tempVehicleID.";', 'Transfering vehicle ".mysql_real_escape_string($tempVehicleID)." from ".mysql_real_escape_string($fromCharacterRow["charactername"])." to ".mysql_real_escape_string($toCharacterRow["charactername"])."')");
										$mtaServer->getResource("mtavg")->call("deleteItem", 3, $tempVehicleID);
										echo '- Removed old keys for vehicle '.$tempInteriorID.'<BR />';
										mysql_query("UPDATE `vehicles` SET `owner`='".$toCharacterRow['id']."' WHERE `id`='".$tempVehicleID."' AND `owner`='".$fromCharacterRow['id']."'");
										echo '- Vehicle '.$tempVehicleID.' transfered<BR />';
										mysql_query("INSERT INTO `items` (type, owner, itemID, itemValue) VALUES ('1', '".$toCharacterRow['id']."', '3', '".$tempVehicleID."')");
										echo '- Giving "'.$toCharacterRow['charactername']. '" a key for vehicle '.$tempVehicleID.'<BR />';
										flush();
									}
								}
								
								if (isset($_POST['interior']))
								{
									foreach ($_POST['interior'] as $tempInteriorID)
									{
										mysql_query("INSERT INTO `logtable` VALUES (NOW(), '1', 'ac".mysql_real_escape_string($userID)."', 'ac".mysql_real_escape_string($userID).";ch".$fromCharacterRow['id'].";ch".$toCharacterRow['id'].";in".$tempInteriorID.";', 'Transfering interior ".mysql_real_escape_string($tempInteriorID)." from ".mysql_real_escape_string($fromCharacterRow["charactername"])." to ".mysql_real_escape_string($toCharacterRow["charactername"])."')");
										$mtaServer->getResource("mtavg")->call("deleteItem", 4, $tempInteriorID);
										$mtaServer->getResource("mtavg")->call("deleteItem", 5, $tempInteriorID);
										echo '- Removed old keys for interior '.$tempInteriorID.'<BR />';
										mysql_query("UPDATE `interiors` SET `owner`='".$toCharacterRow['id']."' WHERE `id`='".$tempInteriorID."' AND `owner`='".$fromCharacterRow['id']."'");
										echo '- Interior '.$tempInteriorID.' transfered<BR />';
										mysql_query("INSERT INTO `items` (type, owner, itemID, itemValue) VALUES ('1', '".$toCharacterRow['id']."', '4', '".$tempInteriorID."')");
										echo '- Giving "'.$toCharacterRow['charactername']. '" a key for vehicle '.$tempInteriorID.'<BR />';
										flush();
									}
								}
							
								mysql_query("INSERT INTO `logtable` VALUES (NOW(), '1', 'ac".mysql_real_escape_string($userID)."', 'ac".mysql_real_escape_string($userID).";ch".$fromCharacterRow['id'].";ch".$toCharacterRow['id']."', 'Ending stattransfer between ".mysql_real_escape_string($fromCharacterRow["charactername"])." and ".mysql_real_escape_string($toCharacterRow["charactername"])."')");
								echo '<BR /><BR />All done!';
								$mtaServer->getResource("mtavg")->call("statTransfer", $userID, $fromCharacterRow['id'], $toCharacterRow['id']);
?>						</div>
					</div>
				</div>
<?php						}
							else { // Invalid input
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Perk transfer</h2>
							<BR />
							Error: Something went wrong. Remember: You can't transfer things you don't have ;).<BR />
							<BR />
							Error message: <?php echo $error; ?>
						</div>
					</div>
				</div>			
<?php						}
							
						}
						else 
						{
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Perk transfer</h2>
							<BR />
							<form action="/ucp/perktransfer/step3/" method="post">
								<input type="hidden" name="fromcharacter" value="<?php echo $_POST['fromcharacter']; ?>" />
								<input type="hidden" name="tocharacter" value="<?php echo $_POST['tocharacter']; ?>" />
								
								<table width="100%" border="0">
									<tr>
										<td align="center">
											<?php echo "<a target=\"_new\" href=\"/ucp/character/".$fromCharacterRow["id"]."/\"><img src=\"/images/chars/".$fromCharacterRow['skin'].".png\"><BR />".str_replace("_", " ", $fromCharacterRow["charactername"])."</a>"; ?>
										</td>
										<td align="center">To<BR /> =></td>
										<td align="center">
											<?php echo "<a target=\"_new\" href=\"/ucp/character/".$toCharacterRow["id"]."/\"><img src=\"/images/chars/".$toCharacterRow['skin'].".png\"><BR />".str_replace("_", " ", $toCharacterRow["charactername"])."</a>"; ?>
										</td>
									</tr>
								</table>
								<p>Please select the perks you want to transfer to the character shown on the right.</p>
								<table>
									<tr>
										<td valign="top">
											General perks<BR />
											<BR />
											Bankmoney: <BR />
											<input type="text" name="bankmoney" value="<?php echo $fromCharacterRow['bankmoney']; ?>" /><BR /><BR />
											Money on hand: <BR />
											<input type="text" name="money" value="<?php echo $fromCharacterRow['money']; ?>" /><BR />
										</td>
										<td valign="top">
											Vehicles<BR /><BR />
<?php					
						foreach ($vehicleArr as $vehicleID => $vehicleModel)
						{
								if ($mtaServer->getResource("carshop-system")->call("isForSale", $vehicleModel))
								echo "											<input type=\"checkbox\" name=\"vehicle[]\" value=\"".$vehicleID."\" CHECKED> ".$vehicleID." - ".$vehicleIDtoName[$vehicleModel]."<BR /><BR />";
							else
								echo "										<i>".$vehicleID." - ".$vehicleIDtoName[$vehicleModel]." - Not transferable</i><BR /><BR />";
				}
?>
										</td>
										<td valign="top">
											Interiors<BR /><BR />
<?php
						foreach ($interiorArr as $interiorID => $interiorName)
						{
							echo "											<input type=\"checkbox\" name=\"interior[]\" value=\"".$interiorID."\" CHECKED> ".$interiorID." - ".$interiorName."<BR /><BR />";
						}
?>										
										</td>
									</tr>
									<tr>
										<td colspan="3"><input type="submit" name="submit" value="Next step >>" /></td>
									</tr>
								</table>
							</form>
						</div>
					</div>
				</div>	
<?php					}
					}
					else {
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Perk transfer</h2>
							<BR />
							Error: Something went wrong, please contact an lead+ administrator.
						</div>
					</div>
				</div>			
<?php				}
				}
				else {
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Perk transfer</h2>
							<BR />
							Error: the account is currently logged into the game, please log out!
						</div>
					</div>
				</div>		
<?php			}
			}
			else {
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Perk transfer</h2>
							<BR />
							Error: The source and destionation character cannot be the same!
						</div>
					</div>
				</div>	
<?php		}
		}
	}
	else {
?>				<!-- Middle Column - main content -->
					<div id="content-middle">
						<div class="content-box">
							<div class="content-holder">
								<h2>User Control Panel - Perk transfer</h2>
								<BR />
								<BR />
								At this page you can use your available perktransfer to transfer (some) perks to an alternate character. Doing this will cost you one 'perktransfer'. You can get those by donating to our server, for more information see the left side of this side.<BR /><BR />
								This function has three steps. The first step is shown below, where you can select the source and destionation character. The next step is giving you the option what you want to move, everything is by default selected and will be transfered. On the third step the actual transfer is happening. You need to be disconnected from the gameserver, otherwise the perktransfer is not functioning.<BR /><BR />
								You have currently <b><?php echo $transfers; ?> transfer(s) remaining</b>. <!--If you donated by the old system, please contact an Head Administrator or higher on the forums by PM, including the link to your donation report and your ingame name.<BR /><BR />-->
								<form action="/ucp/perktransfer/step2/" method="post">
									<table width="100%" border="0">
										<tr>
											<td align="center">
												From: 
												<select name="fromcharacter">
<?php
foreach($charArr as $characterID => $characterName)
{
echo"													<option value=\"".$characterID."\">".str_replace("_", " ", $characterName)."</option>\r\n";
}
?>												</select>
											</td>
											<td align="center">To<BR />=></td>
											<td align="center">
												To:
												<select name="tocharacter">
<?php
foreach($charArr as $characterID => $characterName)
{
echo"													<option value=\"".$characterID."\">".str_replace("_", " ", $characterName)."</option>\r\n";
}
?>												</select>
											</td>
										</tr>
										<tr>
											<td colspan="3"><input type="submit" name="submit" value="Next step >>" /></td>
										</tr>
									</table>
								</form>
							</div>
						</div>
					</div>	
<?php
	}
	require_once("includes/footer.php");
}

?>
<?php

	// Validating etc has been done by the file that includes this one
	require_once("includes/header.php");
	require_once("includes/mta_sdk.php");
	
	$logTypes = array (
	'1' 	=> array('Chat /h', 				6,	false),		
	'2' 	=> array('Chat /l', 				5,	false),		
	'3' 	=> array('Chat /a', 				4,	false),		
	
	'4' 	=> array('Admin commands', 			4,	false),		
	'5'		=> array('Anticheat warnings', 		4,	false),		
	'6'		=> array('Vehicle related actions',	3,	false),	
	'7'		=> array('Player /say',				2,	false),		
	'8'		=> array('Player /b',				2,	false),		
	'9'		=> array('Player /r',				2,	false),		
	'10'	=> array('Player /d',				2,	false),		
	'11'	=> array('Player /f',				2,	false),		
	'12'	=> array('Player /me',				2,	false),		
	'13'	=> array('Player /district',		2,	false),		
	'14'	=> array('Player /do',				2,	false),		
	'15'	=> array('Player /pm',				4,	false),		
	'16'	=> array('Player /gov**',			2,	true),		
	'17'	=> array('Player /don',				4,	false),		
	'18'	=> array('Player /o',				4,	false),		
	'19'	=> array('Player /s',				2,	false),		
	'20'	=> array('Player /m',				2,	false),		
	'21'	=> array('Player /w',				2,	false),		
	'22'	=> array('Player /c',				2,	false),		
	'23'	=> array('Player /n**',				2,	true),	
	'24'	=> array('Gamemaster chat',			4,	false),	
	'25'	=> array('Cash transfer',			2,	false),
	'27'	=> array('Connection/Charselect',	2,	false),
	'28'	=> array('Roadblock & Spikes**',	2,	true),
	'29'	=> array('Phone logs',				2,	false),
	'30'	=> array('SMS logs',				2,	false),
	'31'	=> array('veh/int locking/unlocking',	2,	false),
	'32'	=> array('UCP Logs',				6,	false),
	'33'	=> array('Stattransfers',			2,	false),
	'34'	=> array('Kill logs/Lost items',	3,	false),
	'35'	=> array('Faction actions',	2,	true),
	'36'	=> array('Ammunation logs',	4,	true),
	);

	$characterCache = array();
	
	function nameCache($id)
	{
		global $characterCache, $MySQLConn;
		if (isset($characterCache[$id]))
			return $characterCache[$id];
		
		$pos = strpos($id, "ch");
		if ($pos === false) {
			$pos = strpos($id, "fa");
			if ($pos === false) {
				$pos = strpos($id, "ve");
				if ($pos === false) {
					$pos = strpos($id, "ac");
					if ($pos === false) {
						$pos = strpos($id, "in");
						if ($pos === false) {
							$pos = strpos($id, "ph");
							if ($pos === false) {
								$characterCache[$id] = $id.'[unrec]';
								return $id;
							}
							else {
								$tempid = substr($id, 2);
								$characterCache[$id] = "phone ".$tempid;
								return $id;
							}
						}
						else {
							$tempid = substr($id, 2);
							$characterCache[$id] = "interior ".$tempid;
							return $id;
						}
					}
					else {
						$tempid = substr($id, 2);
						$awsQry = mysql_query("SELECT `username` FROM `accounts` WHERE `id`='".$tempid."'");
						if (mysql_num_rows($awsQry) == 1)
						{
							$awsRow = mysql_fetch_assoc($awsQry);
							$characterCache[$id] = $awsRow['username'];
							return $awsRow['username'];
						}
						else {
							$characterCache[$id] = $id;
							return $id;
						}
					}
				}
				else {
					$tempid = substr($id, 2);
					$characterCache[$id] = "vehicle ".$tempid;
					return $characterCache[$id];
				}
			}
			else {
				$tempid = substr($id, 2);
				$awsQry = mysql_query("SELECT `name` FROM `factions` WHERE `id`='".$tempid."'");
				if (mysql_num_rows($awsQry) == 1)
				{
					$awsRow = mysql_fetch_assoc($awsQry);
					$characterCache[$id] = '[F]'.$awsRow['name'];
					return $awsRow['name'];
				}
				else {
					$characterCache[$id] = $id;
					return $id;
				}
			}
		}
		else
		{
			$tempid = substr($id, 2);
			$awsQry = mysql_query("SELECT `charactername` FROM `characters` WHERE `id`='".$tempid."'");
			if (mysql_num_rows($awsQry) == 1)
			{
				$awsRow = mysql_fetch_assoc($awsQry);
				$characterCache[$id] = $awsRow['charactername'];
				return $awsRow['charactername'];
			}
			else {
				$characterCache[$id] = $id.'['.$tempid.']';
				return $id;
			}
		}
	}

	$selectarr = array();
	if (isset($_POST['logtype']))
		foreach ($_POST['logtype'] as $entry)
			$selectarr[$entry] = true;



?>				<!-- Middle Column - main content -->
					<div id="content-middle">
						<div class="content-box">
							<div class="content-holder">
								<form name='searchform' action='' method='POST'>
									<table border="0">
										<tr>
											<td valign="top">
												Search <input type="input" name="charname"<?php if (isset($_POST['charname'])) echo ' VALUE="'.$_POST['charname'].'"'; ?>>
											</td>
											<td rowspan="4"><?php foreach ($logTypes as $id => $detailarr) if ($detailarr[1] <= $adminLevel) { ?>
											<input type="checkbox" name=logtype[] value="<?php echo $id; ?>"<?php if (isset($selectarr[$id])) echo ' CHECKED'; ?>> <?php echo $detailarr[0]; ?><BR />
											<?php } ?></td>
										</tr>
										<tr>
											<td valign="top">
											Type search:
												<select name="type">
													<option value="player"<?php if (isset($_POST['type']) and $_POST['type']=="player") echo ' SELECTED'; ?>>Players (search on name)</option>
													<option value="vehicle"<?php if (isset($_POST['type']) and $_POST['type']=="vehicle") echo ' SELECTED'; ?>>Vehicles (vehicle ID)</option>
													<option value="interior"<?php if (isset($_POST['type']) and $_POST['type']=="interior") echo ' SELECTED'; ?>>Interior (interior ID)</option>
													<option value="account"<?php if (isset($_POST['type']) and $_POST['type']=="account") echo ' SELECTED'; ?>>Account name</option>
													<option value="phone"<?php if (isset($_POST['type']) and $_POST['type']=="phone") echo ' SELECTED'; ?>>Phone number</option>
													<option value="logtext"<?php if (isset($_POST['type']) and $_POST['type']=="logtext") echo ' SELECTED'; ?>>Log text</option>
												</select>
											</td>
										</tr>
										<tr>
											<td valign="top">
												Timespan 
												<select name="time">
													<option value="1"<?php if (isset($_POST['time']) and $_POST['time']==1) echo ' SELECTED'; ?>>Last hour</option>
													<option value="2"<?php if (isset($_POST['time']) and $_POST['time']==2) echo ' SELECTED'; ?>>2 hours ago till now</option>
													<option value="3"<?php if (isset($_POST['time']) and $_POST['time']==3) echo ' SELECTED'; ?>>3 hours ago till now</option>
													<option value="6"<?php if (isset($_POST['time']) and $_POST['time']==6) echo ' SELECTED'; ?>>6 hours ago till now</option>
													<option value="12"<?php if (isset($_POST['time']) and $_POST['time']==12) echo ' SELECTED'; ?>>12 hours ago till now</option>
													<option value="24"<?php if (isset($_POST['time']) and $_POST['time']==24) echo ' SELECTED'; ?>>one day ago till now</option>
													<option value="48"<?php if (isset($_POST['time']) and $_POST['time']==48) echo ' SELECTED'; ?>>two days ago till now</option>
													<option value="72"<?php if (isset($_POST['time']) and $_POST['time']==72) echo ' SELECTED'; ?>>three days ago till now</option>
													<option value="168"<?php if (isset($_POST['time']) and $_POST['time']==168) echo ' SELECTED'; ?>>a week ago till now</option>
<option value="8736"<?php if (isset($_POST['time']) and $_POST['time']==8736) echo ' SELECTED'; ?>>this year</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<input type="submit" name="dosearch" value="Search" />
											</td>
										</tr>
									</table>
									<table border="0">
										<tr>
											<th>Time</th>
											<th>Action</th>
											<th>Player</th>
											<th>Data</th>
											<th></th>
										</tr>
										<?php 
										if (isset($_POST['charname'])) 
										{
											$foundElement = "none";
											$error = 'none';
											if (isset($_POST['type']))
											{
												if ($_POST['type'] == 'player')
												{
													if (strlen($_POST['charname']) > 4)
													{
														$fetchIDquery = mysql_query("SELECT `id`,`charactername` FROM `characters` WHERE `charactername` LIKE '%".mysql_real_escape_string(str_replace(" ", "_", $_POST['charname']))."%'");
														if (mysql_num_rows($fetchIDquery) == 1)
														{
															$sqlRow = mysql_fetch_assoc($fetchIDquery);
															$foundElement = 'ch'.$sqlRow['id'];
														}
														elseif (mysql_num_rows($fetchIDquery) == 0) {
															$error = 'No-one found :\'(';
														}
														else 
														{
															$fetchIDquery = mysql_query("SELECT `id`,`charactername` FROM `characters` WHERE `charactername` = '".mysql_real_escape_string(str_replace(" ", "_", $_POST['charname']))."'");
															if (mysql_num_rows($fetchIDquery) == 1)
															{
																$sqlRow = mysql_fetch_assoc($fetchIDquery);
																$foundElement = 'ch'.$sqlRow['id'];
															}
															else
															{
																$error = 'The following people matched your query:<BR />';
																while($sqlRow = mysql_fetch_assoc($fetchIDquery))
																	$error .= ' '.htmlspecialchars($sqlRow['charactername']).'<BR />';
															}
														}
													}
													else {
														$error = 'none';
													}
												}
												elseif ($_POST['type'] == 'vehicle')
												{
													$fetchIDquery = mysql_query("SELECT `id` FROM `vehicles` WHERE `id`='".mysql_real_escape_string($_POST['charname'])."'");
													if (mysql_num_rows($fetchIDquery) == 1)
													{
														$sqlRow = mysql_fetch_assoc($fetchIDquery);
														$foundElement = 've'.$sqlRow['id'];
													}
													elseif (mysql_num_rows($fetchIDquery) == 0) {
														$error = 'No vehicle found :\'(';
													}
												}
												elseif ($_POST['type'] == 'interior')
												{
													$fetchIDquery = mysql_query("SELECT `id` FROM `interiors` WHERE `id`='".mysql_real_escape_string($_POST['charname'])."'");
													if (mysql_num_rows($fetchIDquery) == 1)
													{
														$sqlRow = mysql_fetch_assoc($fetchIDquery);
														$foundElement = 'in'.$sqlRow['id'];
													}
													elseif (mysql_num_rows($fetchIDquery) == 0) {
														$error = 'No interior found :\'(';
													}
												}
												elseif ($_POST['type'] == 'account')
												{
													$fetchIDquery = mysql_query("SELECT `id`,`username` FROM `accounts` WHERE `username` = '".mysql_real_escape_string($_POST['charname'])."'");
													if (mysql_num_rows($fetchIDquery) == 1)
													{
														$sqlRow = mysql_fetch_assoc($fetchIDquery);
														$foundElement = 'ac'.$sqlRow['id'];
													}
													elseif (mysql_num_rows($fetchIDquery) == 0) {
														$error = 'No account found :\'(';
													}
												}
												elseif ($_POST['type'] == 'phone')
												{
													$fetchIDquery = mysql_query("SELECT `phonenumber` FROM `phone_settings` WHERE `phonenumber`='".mysql_real_escape_string($_POST['charname'])."'");
													if (mysql_num_rows($fetchIDquery) == 1)
													{
														$sqlRow = mysql_fetch_assoc($fetchIDquery);
														$foundElement = 'ph'.$sqlRow['phonenumber'];
													}
													elseif (mysql_num_rows($fetchIDquery) == 0) {
														$error = 'No phone found :\'(';
													}
												}
											}

											
											//if ($foundElement != 'none' or $_POST['type'] == 'logtext')
											if ($error == 'none')
											{
												$selecterror = false;
												$qryadd = '';
												foreach ($_POST['logtype'] as $logID)
													if (isset($logTypes[$logID]))
														if ($logTypes[$logID][1] <= $adminLevel)
															if ((!$logTypes[$logID][2] and $foundElement != 'none') or (!$logTypes[$logID][2] and $_POST['type'] == 'logtext') or $logTypes[$logID][2])
																$qryadd .= "`action`='".mysql_real_escape_string($logID)."' OR ";

												if ($qryadd != '')
												{
													if (isset($_POST['time']) and is_numeric($_POST['time']) and $_POST['time'] > 0 and $_POST['time'] < 8737)
													{
														if ($_POST['time'] != mysql_real_escape_string($_POST['time']) or !is_numeric($_POST['time']))
														{
															die();
														}
														
														if ($_POST['type'] != 'logtext')
														{
															$awesomeQuery = "SELECT `time` - INTERVAL 1 hour as 'newtime',`action`,`source`,`affected`,`content` FROM `logtable` WHERE ";
															if ($foundElement != 'none')
																$awesomeQuery .= "(`source`='".mysql_real_escape_string($foundElement)."' or `affected` LIKE '%".mysql_real_escape_string($foundElement).";%') AND ";
															$awesomeQuery .= "(".$qryadd." 1=2) AND `time` > (NOW() - INTERVAL ".$_POST['time']." HOUR) ORDER BY `time` ASC";
														}
														else
															$awesomeQuery = "SELECT `time` - INTERVAL 1 hour as 'newtime',`action`,`source`,`affected`,`content` FROM `logtable` WHERE `content` LIKE '%".mysql_real_escape_string($_POST['charname'])."%' AND (".$qryadd." 1=2) AND `time` > (NOW() - INTERVAL ".$_POST['time']." HOUR) ORDER BY `time` ASC";
															
														$awesomeQryExe = mysql_query($awesomeQuery);
														//echo $awesomeQuery;
														if (mysql_num_rows($awesomeQryExe) > 0)
														{
															while ($row = mysql_fetch_assoc($awesomeQryExe) )
															{
																$explodedArr = explode(';', $row['affected']);
																$explodedStr = "Affected: <BR />";
																foreach ($explodedArr as $objectid)
																	if ($objectid != '')
																		$explodedStr .= htmlspecialchars(nameCache($objectid))."<BR />";
																
																
																echo "<tr><td>".htmlspecialchars($row['newtime'])."</td><td>".$logTypes[$row['action']][0]."</td><td>".htmlspecialchars(nameCache($row['source']))."</td><td>".htmlspecialchars($row['content'])."</td><td>".$explodedStr."</td></tr>\r\n";
															}
														}
														
														else 
															echo "<tr><td colspan=5>No results found.</td></tr>";
															
														echo mysql_error();
													}
													else
														echo "<tr><td colspan=5>Please select a time criterea.</td></tr>";
												}											
												else 
													echo "<tr><td colspan=5>Please select filter criterea.</td></tr>";
											}
											else 
												echo "<tr><td colspan=5>".$error."</td></tr>";
										}
										else 
											echo "<tr><td colspan=5>Nothing to show here, just search first.</td></tr>";
										?>
									</table>
								</form>
							</div>
						</div>
					</div>
<?php
	require_once("includes/footer.php");
	echo mysql_error();
?>

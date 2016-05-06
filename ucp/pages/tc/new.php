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
$mQuery1 = mysql_query("SELECT `username`, `admin`,`banned`,`banned_by`,`banned_reason` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) == 0)
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$userRow = mysql_fetch_assoc($mQuery1);

// Application stuff
$username = $userRow['username'];
$admin = $userRow['admin'];

// Banned stuff, might be usefull for reports:
$isBanned = $userRow['banned'];
if ($isBanned)
{
	$isBannedBy = $userRow['banned_by'];
	$isBannedReason = $userRow['banned_reason'];
}
else {
	$isBannedBy ='';
	$isBannedReason='';
}
if(isset($_POST['message']) && strlen($_POST['message']) > 5)
{
	$sub = mysql_real_escape_string( htmlentities( $_POST['subject'] ) );
	$mes = mysql_real_escape_string( htmlentities( $_POST['message'] ) );
	
	$ci = mysql_query("SELECT id FROM tc_tickets WHERE subject = '" . $sub . "' AND message = '" . $mes . "'", $MySQLConn);
	$count = mysql_num_rows($ci);
	if($count > 0)
	{
		$existsRow = mysql_fetch_assoc($ci);
		Header("Location: /ticketcenter/view/".$existsRow['id']."/");
		die();
	}
	else
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$time = time();
		$make = mysql_query("INSERT INTO tc_tickets(creator, posted, subject, message, status, lastpost, assigned, IP)VALUES('" . $userID . "', '" . $time . "', '" . $sub . "', '" . $mes . "', '0', '" . $time . "', -1, '" . $ip . "')");
		if($make)
		{
			$getid = mysql_insert_id();
			Header("Location: /ticketcenter/view/".$getid."/");
			die();
		}
		else
		{
			echo mysql_error();
		}
	}
}
else {

?>
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Ticket Center - New Ticket</h2>
							<div style="text-align:center;">										
								<p>Please fill out the form below completely in order to submit your ticket.<br />
								<b>Before</b> posting a new ticket, please be sure to use the right format. Please check them at the bottom of the page.</p><br />
								<form name="new_ticket" action="<?php $PHP_SELF; ?>" method="post">
									<table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
<?php
	if (!isset($_POST['type']) OR $_POST['type'] == 'none')
	{
?>										<tr>
											<td width="20%" align="right" valign="top">Type of ticket: </td>
											<td width="80%" align="left" valign="top">
											<select name="type" onChange='document.new_ticket.submit();'>
												<option value="none"></option>
												<option value="account">Account Related Question</option>
												<option value="radmin">Report Admin</option>
												<option value="rplayer">Report Player</option>
												<option value="unban">Unban Request</option>
												<option value="script">Script Issue/Report a bug</option>
												<option value="other">Other</option>
											</select>
											</td>
										</tr>	
<?php
	}
	else {
		$subject = '';
		$msg = '';
		$type = $_POST['type'];
		if ($type == 'account') // Account related Question
		{
			$subject = 'Account Issue';
			$msg = "Your username: ".$username."
Issue that you are having: ";
		} elseif ($type == 'radmin') // Report an admin
		{
			$subject = 'Admin Report ';
			$msg = "Your username: ".$username."
Admin/s you are reporting: 
Date/Time of the incident(s): 
What happened?: 
Evidence (Video/Screenshots etc...): ";
		} elseif ($type == 'rplayer') // Report a player
		{
			$subject = 'Player Report ';
			$msg = "Your username: ".$username."
Player/s you are reporting: 
Date/Time of the incident(s): 
What happened?: 
Evidence (Video/Screenshots etc...): ";
		} elseif ($type == 'unban') // Unban request
		{
			$subject = 'Unban request';
			$msg = "Your username: ".$username."
Date of your ban: 
Admin who banned you: ".$isBannedBy."
Your Ban Reason: ".$isBannedReason."
Why do you feel we should unbann you: ";		
		} elseif ($type=='script') // Report a bug
		{
			$subject = 'Script Issue';
			$msg = "Your username: ".$username."
Character it happened on: 
How did it happen: 
Can you reproduce it - If so, how: ";			
		}
?>										<tr>
											<td width="20%" align="right" valign="top">Subject: </td>
											<td width="80%" align="left" valign="top"><input type="text" name="subject" size="80" value="<?php echo $subject; ?>"/></td>
										</tr>
										<tr>
											<td width="20%" align="right" valign="top">Message: </td>
											<td width="80%" align="left" valign="top"><textarea name="message" cols="60" rows="10" wrap="soft"><?php echo $msg; ?></textarea><br /></td>
										</tr>
										<tr>
											<td colspan="2" align="right"><input type="submit" value="Submit" /></td>
										</tr>
<?php
	}
?>									</table>
								</form>
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
				</div>
<?php
}
?>
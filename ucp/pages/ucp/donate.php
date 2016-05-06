<?php

$MySQLConn = @mysql_connect($Config['database']['hostname'], $Config['database']['username'], $Config['database']['password']);
if (!$MySQLConn) 
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}
$selectdb = @mysql_select_db($Config['database']['database'], $MySQLConn);

if (isset($_GET['param2']) and $_GET['param2'] == 'pp-ipn')
{
	$paypal = new paypal_class;
	if ($paypal->validate_ipn()) {
		if($paypal->ipn_data['payment_status']=='Completed' and ($paypal->ipn_data['receiver_email'] == "patrick@sitebar.nl" or $paypal->ipn_data['receiver_email'] == "donate@mta.vg"))
		{
			
			$amount = $paypal->ipn_data['mc_gross'];
			$realamount = $paypal->ipn_data['mc_gross'] - $paypal->ipn_data['mc_fee'];
			$insertQry = mysql_query("	INSERT INTO don_transactions (transaction_id,donator_email,username,amount,realamount,original_request,dt)
				VALUES (
					'".mysql_real_escape_string($paypal->ipn_data['txn_id'])."',
					'".mysql_real_escape_string($paypal->ipn_data['payer_email'])."',
					'".mysql_real_escape_string($paypal->ipn_data['custom'])."',
					".(float)$amount.",
					".(float)$realamount.",
					'".mysql_real_escape_string(http_build_query($_POST))." \r\n\r\n ".mysql_real_escape_string($paypal->ipn_response)." \r\n\r\n ".mysql_real_escape_string(http_build_query($paypal->ipn_data))."',
					NOW()
				)");
			$insertQryID = mysql_insert_id();
			$pointsUsername = mysql_real_escape_string($paypal->ipn_data['custom']);
			
			$statTransfers = 0;
			$vPoints = 0;
			
			switch ($amount)
			{
				case 400:
					$statTransfers = 5;
					$vPoints = 350;	
					break;
				case 5:
					$statTransfers = 1;
					$vPoints = 0;	
					break;
				case 10:
					$statTransfers = 1;
					$vPoints = 5;
					break;
				case 20:
					$statTransfers = 1;
					$vPoints = 12;
					break;
				case 50:
					$statTransfers = 2;
					$vPoints = 30;
					break;
				case 110:
					$statTransfers = 2;
					$vPoints = 70;
					break;
				case 175:
					$statTransfers = 3;
					$vPoints = 110;
					break;
				case 300:
					$statTransfers = 3;
					$vPoints = 200;
					break;
				case 375:
					$statTransfers = 4;
					$vPoints = 299;
					break;

				// Promotion package! 
				case 30:
					$statTransfers=3;
					$vPoints = 50;
					break;
					
				default:
					die();
					break;
			}
			echo 'hai';
			$fetchUserQry = mysql_query("SELECT `id` FROM `accounts` WHERE `username`='".$pointsUsername."'");
			if (mysql_num_rows($fetchUserQry) == 1)
			{
				$fetchArr = mysql_fetch_array($fetchUserQry);
				
				mysql_query("UPDATE `accounts` SET credits=credits+".$vPoints.", transfers=transfers+".$statTransfers." WHERE `id`=".$fetchArr['id']);
				mysql_query("UPDATE `don_transactions` SET `handled`=1 WHERE `id`='".mysql_real_escape_string($insertQryID)."'");
			}
		}
		else {
			mysql_query("	INSERT INTO don_transaction_failed (output, ip)
				VALUES (
					'FRAUD ".mysql_real_escape_string(http_build_query($_POST))." \r\n\r\n ".mysql_real_escape_string($paypal->ipn_response)." \r\n\r\n ".mysql_real_escape_string(http_build_query($paypal->ipn_data))."',
					'".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'
				)");
		}
	}
	else {
		mysql_query("	INSERT INTO don_transaction_failed (output, ip)
			VALUES (
				'".mysql_real_escape_string(http_build_query($_POST))." \r\n\r\n ".mysql_real_escape_string(http_build_query($paypal->ipn_data))."',
				'".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'
			)");
	}
	echo mysql_error();
	mysql_close($MySQLConn);
	die();
}

if (!isset($_SESSION['ucp_loggedin']) or !$_SESSION['ucp_loggedin'])
{
	header("Location: /ucp/login/");
	die();
}

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

// Application stuff
$playerUserName = $userRow['username'];
 // <!-- https://www.sandbox.paypal.com/cgi-bin/webscr -->

if (isset($_GET['param2']) and $_GET['param2'] == 'y')
{
require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Donation center</h2>
							<BR />
							Thank you for your donation, we really appriciate it. Your donatorperks should be added to your account within an hour. If it didn't add it to your account after one hour, please contact Mount on the forums.<BR />
							<BR />
							- MTA Head administration team
						</div>
					</div>
				</div>
<?php
require_once("includes/footer.php");
}
elseif (isset($_GET['param2']) and $_GET['param2'] == 'n')
{
require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Donation center</h2>
							<BR />
							Oops, we didn't recieve a vaild donation.
						</div>
					</div>
				</div>
<?php
require_once("includes/footer.php");
} else
{
require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Donation center</h2>
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_xclick" />
								<input type="hidden" name="business" value="donate@mta.vg" />
								<input type="hidden" name="item_name" value="Donation" />
								<input type="hidden" name="item_number" value="001" />
								<input type="hidden" name="notify_url" value="http://mta.vg/ucp/donate/pp-ipn/" />
								<input type="hidden" name="cancel_return" value="http://mta.vg/ucp/donate/f/" />
								<input type="hidden" name="return" value="http://mta.vg/ucp/donate/y/" />
								<input type="hidden" name="rm" value="2" />
								<input type="hidden" name="no_shipping" value="1" />
								
								Amount (in USD): <BR />
								<table>
									<TR>
										<TD>
											<input type="radio" name="amount" value="5" /> $ 5
										</TD>
										<TD>
											1 Perk transfer
										</TD>
										<TD>-</TD>
									</TR>
									<TR>
										<TD>
											<input type="radio" name="amount" value="10" /> $ 10
										</TD>
										<TD>
											1 Perk transfer
										</TD>
										<TD>5 vPoints to spend in our ingame donation system</TD>
									</TR>
									<TR>
										<TD>
											<input type="radio" name="amount" value="20" /> $ 20
										</TD>
										<TD>
											1 Perk transfer
										</TD>
										<TD>
											12 vPoints to spend in our ingame donation system
										</TD>
									</TR>

									<TR>
										<TD>
											<input type="radio" name="amount" value="50" /> $ 50
										</TD>
										<TD>
											2 Perk transfers
										</TD>
										<TD>
											30 vPoints to spend in our ingame donation system
										</TD>
									</TR>
									<TR>
										<TD>
											<input type="radio" name="amount" value="110" /> $ 110
										</TD>
										<TD>
											2 Perk transfers
										</TD>
										<TD>
											70 vPoints to spend in our ingame donation system
										</TD>
									</TR>
									<TR>
										<TD>
											<input type="radio" name="amount" value="175" /> $ 175
										</TD>
										<TD>
											3 Perk transfers
										</TD>
										<TD>
											110 vPoints to spend in our ingame donation system
										</TD>
									</TR>
									<TR>
										<TD>
											<input type="radio" name="amount" value="300" /> $ 300
										</TD>
										<TD>
											3 Perk transfers
										</TD>
										<TD>
											200 vPoints to spend in our ingame donation system
										</TD>
									</TR>
									<TR>
										<TD>
											<input type="radio" name="amount" value="375" /> $ 375
										</TD>
										<TD>
											4 Perk transfers
										</TD>
										<TD>
											299 vPoints to spend in our ingame donation system
										</TD>
									</TR>
								</TABLE>

								<input type="hidden" name="currency_code" value="USD" />
								<input type="hidden" name="lc" value="US" />
								Donation for the account: <input type="text" name="custom" value="<?php echo $playerUserName; ?>" />
											
								<input type="submit" name="I1" value="Donate" />
							</form>
						</div>
					</div>
				</div>
<?php
require_once("includes/footer.php");
}

?>
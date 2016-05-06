<?php
if (!isset($_SESSION['ucp_loggedin']) or !$_SESSION['ucp_loggedin'])
{
	if (!isset($_POST['register']))
	{
		require_once("includes/header.php");
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Register</h2>
							<div style="text-align:center;">
								<script type="text/javascript" src="/js/prototype/prototype.js"></script>
								<script type="text/javascript" src="/js/bramus/jsProgressBarHandler.js"></script>
								<script type="text/javascript">
								<!--
									function validate_form(thisform)
									{
										with (thisform)
										  {
											if (thisform.username.value==null || thisform.username.value==""  || thisform.username.value.length<3 || thisform.username.value.length>30 )
											{
												alert("Please ensure you have entered a valid username.\n\n Usernames must be between 3 and 30 characters long.");
												return false;
											}
											else if (thisform.password.value==null || thisform.password.value==""  || thisform.password.value.length<3 || thisform.password.value.length>30 )
											{
												alert("Please ensure you have entered a valid password.\n\n Passwords must be between 3 and 30 characters long.");
												return false;
											}
											else if (thisform.emailaddress.value==null || thisform.emailaddress.value==""  || thisform.emailaddress.value.length<3 || thisform.emailaddress.value.length>100 )
											{
												alert("Please ensure you have entered a valid e-mail address.\n\n Passwords must be between 3 and 100 characters long.");
												return false;
											}
											else if (thisform.password.value != thisform.password2.value)
											{
												alert("You didn't use the same password twice. Please correct this and try it again.");
												thisform.password.value = ''
												thisform.password2.value = ''
												return false;
											}
											else
											{
												return true;
											}
										  }
									 }

									Event.observe(window, 'load', function() {
									  securityPB = new JS_BRAMUS.jsProgressBar($('securitybar'), 0, {animate: false, width:120, height: 12});
									}, false);


									 function hasNumbers(t)
									 {
										var regex = /\d/g;
										return regex.test(t);
									 }
									 
									 function hasSpecialCharacters(t)
									 {
										 var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?~_"; 
										 for (var i = 0; i < t.length; i++) 
										 {
											if (iChars.indexOf(t.charAt(i)) != -1) 
												return true;
										 }
										 return false;
									  }
									  
									function isValidEmail(t)
									{
										for (var i = 0; i < t.length; i++) 
										{
											if ( t.charAt(i) == "@")
												return true;
										}
										return false;
									}
									 
									 function checkSecurity(field)
									 {
										 var value = field.value
										 var len = value.length
										 
										 if (hasNumbers(value))
											len = len * 2
											
										 if (hasSpecialCharacters(value))
											len = len * 2
										 
										 len = Math.round((len / 30) * 100, 0)
											
										 securityPB.setPercentage(len, false)
									 }
								 //-->
								 </script>
								 
								<form action="" method="post" onSubmit="return validate_form(this)">
									<table border="0">
										<tr>
											<td colspan="2">Please enter the details for your new account below.</td>
										</tr>
										<tr>
											<td align="left">Username</td>
											<td align="left"><input name="username" type="text" size="30" maxlength="30"> (Not your character name)</td>
										</tr>
										<tr>
											<td align="left">Password</td>
											<td align="left"><input name="password" type="password" size="30" maxlength="30" onKeyUp="checkSecurity(this)"></td>
										</tr>
										<tr>
											<td></td>
											<td align="left">Password Quality: <div class="securitybar" id="securitybar"></div></td>
										<tr>
											<td align="left">Repeat Password</td>
											<td align="left"><input name="password2" type="password" size="30" maxlength="30"></td>
										</tr>
										<tr>
											<td align="left">Email Address</td>
											<td align="left"><input name="emailaddress" type="emailaddress" size="30" maxlength="30"></td>
										</tr>
										<tr>
											<td></td>
											<td align="left"><input type="submit" name="register" id="register" value="Register"></td>
										</tr>
									</table>
								</form>
								 
								<?php
									if (isset($_SESSION["reg:errno"]))
										$errno = $_SESSION["reg:errno"];
									else
										$errno = 0;
										
									if ($errno==1)
										echo "<p>An account with this username already exists!</p><br />";
									elseif ($errno==2)
										echo "<p>An unknown error occured, please report this on the forums!</p><br />";
									elseif ($errno==3)
										echo "<p>An account already exists with that email address, please use Reset Password!</p><br />";
									elseif ($errno==4)
										echo "<p>You didn't fill in all the fields. All of those are required.</p><br />";
									elseif ($errno==5)
										echo "<p>The specified password don't match.</p><br />";
									elseif ($errno==6)
										echo "<p>The e-mail address used is not vaild.</p><br />";
									elseif ($errno==7)
										echo "<p>At the moment we're unable to reach our gameserver. Please try again later.</p><br />";
										
									unset($_SESSION["reg:errno"]);
								?>
							</div>
						</div>
					</div>
				</div>
<?php
		require_once("includes/footer.php");
	} else {
		if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password2']) and isset($_POST['emailaddress']))
		{
			if ($_POST['password'] != $_POST['password2'])
			{
				$_SESSION["reg:errno"] = 5;
				header("Location: /ucp/register/");
			}
			else { // passwords match
				if (check_email_address($_POST['emailaddress'])) // Is the mail address vailid?
				{
					$MySQLConn = @mysql_connect($Config['database']['hostname'], $Config['database']['username'], $Config['database']['password']);
					if (!$MySQLConn) {
						$_SESSION["reg:errno"] = 7;
						header("Location: /ucp/register/");
					}
					else {
						$selectdb = @mysql_select_db($Config['database']['database'], $MySQLConn);
						// Got a server connection
						
						// escape some stuff
						$username = mysql_real_escape_string($_POST['username'], $MySQLConn);
						$password = md5($Config['server']['hashkey'] . $_POST['password']);
						$emailaddress = mysql_real_escape_string($_POST['emailaddress'], $MySQLConn);
						$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR'], $MySQLConn);
						
						$mQuery1 = mysql_query("SELECT `id` FROM `accounts` WHERE `username`='" . $username . "' LIMIT 1", $MySQLConn);
						if (mysql_num_rows($mQuery1) == 0)
						{ // username is free
							$mQuery2 = mysql_query("SELECT `id` FROM `accounts` WHERE `username`='" . $username . "' LIMIT 1", $MySQLConn);
							if (mysql_num_rows($mQuery2) == 0)
							{ // e-mail address is not used yet
								// make the account
								$mQuery3 = mysql_query("INSERT INTO `accounts` SET `username`='" . $username . "', `password`='" . $password . "', email='" . $emailaddress. "', registerdate=NOW(), ip='" . $ip . "', country='SC', friendsmessage='Hi!'", $MySQLConn);
								
								// Welcome mail
								$smtp = new SMTP($Config['SMTP']['hostname'], 25, false, 5);
								$smtp->auth($Config['SMTP']['username'], $Config['SMTP']['password']);
								$smtp->mail_from($Config['SMTP']['from']);

								$smtp->send($emailaddress, 'Valhalla Gaming MTA Welcomes you!', 'Hi!
								
Thanks for joining the fastest growing MTA server available at the moment. Your user details are listed below:

Username: '.$username.'
Password: '.$_POST['password'].'
UCP URL: http://mta.vg/

Please store these details carefully, we\'re not able to recover them, as they\'re encrypted after this mail has been sent.
								
Kind Regards,
The ValhallaGaming MTA Administration Team');

								$_SESSION['errno'] = 7;
								header("Location: /ucp/login/");
							}
							else 
							{
								$_SESSION["reg:errno"] = 3;
								header("Location: /ucp/register/");
							}
						}
						else
						{
							$_SESSION["reg:errno"] = 1;
							header("Location: /ucp/register/");
						}
						
					}
				}
				else {
					$_SESSION["reg:errno"] = 6;
					header("Location: /ucp/register/");
				}
			}
		}
		else {
			$_SESSION["reg:errno"] = 4;
			header("Location: /ucp/register/");
		}
	}
}
else {
	header("Location: /ucp/main/");
}
?>
<?php
if (!isset($_POST['username']))
{
	require_once("includes/header.php");
?>				<!-- Middle Column - main content -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>User Control Panel - Login</h2>
							<div style="text-align:center;">
								<form action="" method="post">
									<?php
									if (isset($_SESSION["errno"]))
										$errno = $_SESSION["errno"];
									else
										$errno = 0;
										
									if (isset($_SESSION["loggedout"]))
										$_SESSION["loggedout"] = true;
									else
										$_SESSION["loggedout"] = false;
										
									if ($errno==2)
										echo "<span style='text-align:left;'><p>The UCP is currently unavailable!</p></span>";
									elseif ($errno==3)
										echo "<span style='text-align:left;'><p>Invalid Username / Password!</p><p>Please login using your vG MTA account details to continue.</p></span>";
									elseif ($errno==4)
										echo "<span style='text-align:left;'><p>You have used up your 3 login attempts. You are now locked out for 15 minutes.</p></span>";
									elseif ($errno==5)
										echo "<span style='text-align:left;'><p>Your username and new password has been emailed to you.</p></span>";
									elseif ($errno==6)
										echo "<span style='text-align:left;'><p>Invalid Username / Password!</p><p>Please login using your vG MTA account details to continue.</p></span>";	
									elseif ($errno==7)
										echo "<span style='text-align:left;'><p>Your account has been created successfully. Please login here.</p></span>";	
									elseif ($loggedout==true)
										echo "<span style='text-align:left;'><p>You are now logged out.</p><p>Please login using your vG MTA account details to continue.</p></span>";
									else
										echo "<span style='text-align:left;'><p>Please login using your vG MTA account details to continue.</p></span>";
										
									unset($_SESSION["errno"]);
									unset($_SESSION["loggedout"]);
?>									<br />
									<table border="0">
										<tr>
											<td>Username:</td>
											<td><input type="text" name="username" maxlength="32" style="width:150px;height:16px"/></td>
										</tr>
										<tr>
											<td>Password:</td>
											<td><input type="password" name="password" maxlength="32" style="width:150px;height:16px"/></td>
										</tr>
									</table>
									
									<br />
									<p><a href="/ucp/forgot-password/">Forgot your password?</a><strong> | </strong><a href="/ucp/register/">Want to register?</a></p>
									<input type="submit" value="Login"/>
								</form>
							</div>
						</div>
					</div>
				</div>
<?php
	require_once("includes/footer.php");
}
else {
	// login attempt
	if (!isset($_POST['username']) or !isset($_POST['password']))
	{
		$_SESSION['errno'] = 6;
		header("Location: /ucp/login/");
	}
	else {
		$MySQLConn = @mysql_connect($Config['database']['hostname'], $Config['database']['username'], $Config['database']['password']);
		if (!$MySQLConn) {
			$_SESSION['errno'] = 2;
			header("Location: /ucp/login/");
		}
		$selectdb = @mysql_select_db($Config['database']['database'], $MySQLConn);
		
		$username = mysql_real_escape_string($_POST['username'], $MySQLConn);
		$password = md5($Config['server']['hashkey'] . $_POST['password']);
		
		$result = mysql_query("SELECT `id`,`admin` FROM `accounts` WHERE `username`='" . $username . "' AND `password`='" . $password . "' LIMIT 1", $MySQLConn);

		if (!$result || mysql_num_rows($result) == 0)
		{
			$_SESSION['errno'] = 3;
			header("Location: /ucp/login/");
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			$_SESSION['ucp_loggedin'] = true;
			$_SESSION['ucp_username'] = $username;
			$_SESSION['ucp_userid'] = $row['id'];
			$_SESSION['ucp_adminlevel'] = $row['admin'];
			if (isset($_SESSION['returnurl_login']))
			{
				
				header("Location: ".$_SESSION['returnurl_login']);
				unset ($_SESSION['returnurl_login']);
			}
			else
				header("Location: /ucp/main/");
				
			
		}
	}
}
?>
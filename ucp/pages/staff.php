<?php

function showLevel($adminlevel)
{
	global $admins;
	if (!isset($admins[$adminlevel])) {
		echo "								<li>None</li>\r\n";
	} else {
		foreach ($admins[$adminlevel] as $admin)
			echo "								<li>".$admin."</li>\r\n";
	}
}

$MySQLConn = @mysql_connect($Config['database']['hostname'], $Config['database']['username'], $Config['database']['password']);

$selectdb = @mysql_select_db($Config['database']['database'], $MySQLConn);
echo mysql_error($MySQLConn);
if (true == false) {
?>
				<!-- Middle Column -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Server Error</h2>
							At the moment, we're unable to reach the gameserver. Please try again in a while. We're sorry for the inconvience.
						</div>
					</div>
				</div>
<?php
}
else {
	$admins = array();
	$mQuery1 = mysql_query("SELECT `username`, `admin` FROM `accounts` WHERE `admin` > 0 ORDER BY `username` ASC");
	while ($row = mysql_fetch_assoc($mQuery1))
	{
		if (!isset($admins[ $row['admin'] ]))
			$admins[ $row['admin'] ] = array();
			
		$admins[ $row['admin'] ][] = $row['username'];
	}
?>				<!-- Middle Column -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Server Staff</h2>

							<h3>Server Owner</h4>
							<ul>
<?php showLevel(6); ?>

							</ul>
							<h3>Head Administrators</h4>
							<ul>
<?php showLevel(5); ?>
							</ul>
							<h3>Lead Administrators</h4>		
							<ul>
<?php showLevel(4); ?>			
							</ul>
						</div>
					</div>
					<div class="content-box">
						<div class="content-holder">

							<h3>Super Administrators</h4>
							<ul>
<?php showLevel(3); ?>	
							</ul>
							<h3>Game Administrators</h4>
							<ul>
<?php showLevel(2); ?>	
							</ul>
							<h3>Trial Administrators</h4>
							<ul>
<?php showLevel(1); ?>
							</ul>
						</div>
					</div>
				</div>
				<!-- End of main content -->
<?php
}
?>

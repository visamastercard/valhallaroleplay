				<!-- Main content -->
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>What is FairPlay?</h2>
							<p>FairPlay is our unique anti-cheat system, written to protect the server from people trying to cheat and/or hack. FairPlay is also our third revision of our anti-cheat tools, and is 99% accurate. This ensures that you can play on our server without getting trouble from players who are trying to get an unfair advantage.</p>
						</div>
					</div>
					
					<div class="content-box">
						<div class="content-holder">
							<h2>Latest people caught</h2>
<?php
		$MySQLConn = @mysql_connect($Config['database']['hostname'], $Config['database']['username'], $Config['database']['password']);
		if (!$MySQLConn) {
			echo "							<p>Sorry, We're unable to contact the gameserver at this moment. Please try again later.</p>";
		}
		else {
			$selectdb = @mysql_select_db($Config['database']['database'], $MySQLConn);

			
			$mQuery1 = mysql_query("SELECT `username`, `ip`, `lastlogin`, `banned_reason`, DATEDIFF(NOW(), `lastlogin`) as 'daysago', `mtaserial` FROM `accounts` WHERE `banned_reason` LIKE '%FairPlay%' AND `banned`=1 ORDER BY `lastlogin` DESC LIMIT 5", $MySQLConn);
			echo"							<table border=\"0\">\r\n";
			echo"								<tr>\r\n";
			echo"									<th width=\"120\">Username</th>\r\n";
			echo"									<th width=\"250\">IP Address (4th Octet Hidden)</th>\r\n";
			echo"									<th width=\"250\">Ban Date & Time</th>\r\n";
			//echo"									<th width=\"150\">Serial Number</th>\r\n";
			echo"									<th width=\"120\">Ban Reason</th>\r\n";
			echo"								</tr>\r\n";
			
			
			while ($row = mysql_fetch_assoc($mQuery1))
			{
				$playerIP = explode(".", $row["ip"]);
				
				if ($row["daysago"] == 1) 
					$daystr = "day";
				else
					$daystr = "days";
				
				$playerIP = $playerIP[0] . "." . $playerIP[1] . "." . $playerIP[2] . ".*"; 
				echo"								<tr>\r\n";
				echo"									<td>".$row['username']."</td>\r\n";
				echo"									<td>".$playerIP."</td>\r\n";
				echo"									<td>".$row["lastlogin"]." (".$row["daysago"]." ".$daystr." ago)</td>\r\n";
				//echo"									<td>".$row["mtaserial"]."</td>\r\n";
				echo"									<td>".substr($row["banned_reason"], 11)."</td>\r\n";
				echo"								</tr>\r\n";			
			}
			echo"							</table>\r\n";
		}
?>
						</div>
					</div>
				</div>
				<!-- End of main content -->
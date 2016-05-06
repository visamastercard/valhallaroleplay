<?php
if (!isset($_SESSION['ucp_loggedin']) or !$_SESSION['ucp_loggedin'])
{
	$_SESSION['returnurl_login'] = '/ticketcenter/main/';
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
$mQuery1 = mysql_query("SELECT `username`, `admin` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
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
?>
				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Ticket Center - Main Page</h2>
							<div style="text-align:center;">										
								<?php
									if($admin <= 0)
									{
										?>
										<p><h2><span style="color:#FF6600;">Welcome to vG MTA Ticket Center.</span></h2><br />
										In order to assist our players with issues that they encounter regarding the server, we have developed this ticket center for you to use. Please use it accordingly and provide all necessary information.</p>
										<hr color="#ffffff" width="75%" size="1">
										<div style="float:left; width:50%; text-align:left;">
											<p><span style="color:#FF6600;">Open a Ticket</span><br />
											Please provide as much detail as possible so we can best assist you. To update a previously submitted ticket, please use the form to the right.</p>
											<br />
											<center><a href="/ticketcenter/new/">Create a New Ticket</a></center>
										</div>
										<div style="float:right; width:50%; text-align:left;">
											<p><span style="color:#FF6600;">Check Ticket Status</span><br />
											Please choose one of the following ticket numbers to view.</p>
											<br />
											<?php
												$tickets = mysql_query("SELECT * FROM tc_tickets WHERE creator='".$userID."'");
												$count = mysql_num_rows($tickets);
												
												function status($n)
												{
													$sN = array('New', 'Replied', 'Answered', 'Closed');
													return $sN[$n];
												}
												
												if($count == 0)
												{
													echo("<center>You do not have any tickets.</center>");
												}else{
													$x = 0;
													while($s = mysql_fetch_array($tickets))
													{
														echo("<a href='/ticketcenter/view/" . $s['id'] . "/'>#" . $s['id'] . " - " . $s['subject'] . "</a> (" . status($s['status']) . ")");
														
														++ $x;
														if($x < $count)
														{
															echo("<br/>");
														}
													}
												}
											?>
										</div>
										<div style="clear:both;"></div>
										<?php
									}else{
										?>
										<p><h2><span style="color:#FF6600;">Welcome to vG MTA Ticket Center.</span></h2><br />
										Please be sure to answer tickets accordingly. Do not close any tickets which are in relation to you and hear every player out. If you need help, contact a Senior Administrator.<br />
										<a href="/ticketcenter/new/">If you need to create a ticket, do so here</a>.</p>
										<hr color="#ffffff" width="75%" size="1">
										<p><a href="/ticketcenter/main/open/">OPENED TICKETS</a> &nbsp;&nbsp; | &nbsp;&nbsp; <a href="/ticketcenter/main/assigned/">MY TICKETS</a> &nbsp;&nbsp; | &nbsp;&nbsp; <a href="/ticketcenter/main/closed/">CLOSED TICKETS</a></p>
										<?php

										if(isset($_POST['form']) && $_POST['form'] == "true")
										{
											$tid = $_POST['delete_ticket'];
											if($tid > 0)
											{
												$count = mysql_query("SELECT * FROM tc_tickets");
												$count = mysql_num_rows($count);

												for($i=0;$i<$count;$i++)
												{
													$del_id = $_POST['delete_ticket'][$i];
													$del = mysql_query("UPDATE tc_tickets SET status='4' WHERE id='" . mysql_real_escape_string( $del_id) . "'");
												}
												
												if($del)
												{
													echo("Tickets were successfully deleted!<br /><br />");
												}
											}
										}
										
										if($_GET['view'] == "closed")
										{
											$tickets = mysql_query("SELECT * FROM tc_tickets WHERE status = '3' ORDER BY lastpost DESC");
										}elseif($_GET['view'] == "assigned"){
											$tickets = mysql_query("SELECT * FROM tc_tickets WHERE (assigned = '" . mysql_real_escape_string($userID) . "' OR creator = '" . mysql_real_escape_string($userID ). "') AND status < '3' ORDER BY lastpost ASC");
										}else{
											$tickets = mysql_query("SELECT * FROM tc_tickets WHERE status < '3' ORDER BY lastpost ASC");
										}
										$count = mysql_num_rows($tickets);
										
										function status($n)
										{
											$sN = array('New', 'Replied', 'Answered', 'Closed');
											return $sN[$n];
										}
										
										if($count == 0)
										{
											echo("<center>There are currently 0 tickets posted.</center>");
										}else{
											$c1 = "#000000";
											$c2 = "#000000";
											$row_count = 0;
											echo('<form name="delete_button" action="' . $PHP_SELF . '" method="POST">
											<table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
											<tr style="	border-top: 1px solid #FF6600;border-bottom: 1px solid #FF6600;">
												<td width="10%" valign="top">ID</td>
												<td width="20%" valign="top">Reporter</td>
												<td width="20%" valign="top">Subject</td>
												<td width="20%" valign="top">Posted On</td>
												<td width="20%" valign="top">Status</td>
												<td width="10%" valign="top">Delete</td>
											</tr>');
											while($s = mysql_fetch_array($tickets))
											{
												$date = date('m-d-Y', $s['posted']);
												$rc = ($row_count % 2) ? $c1 : $c2;
												?>
												<tr>
													<td width="10%" valign="top" bgcolor="<?php echo $rc; ?>"><a href="/ticketcenter/view/<?php echo $s['id']; ?>/">#<?php echo $s['id']; ?></a></td>
													<td width="20%" valign="top" bgcolor="<?php echo $rc; ?>"><a href="/ticketcenter/view/<?php echo $s['id']; ?>/"><?php echo getNameFromUserID($s['creator'], $MySQLConn); ?></a></td>
													<td width="20%" valign="top" bgcolor="<?php echo $rc; ?>"><a href="/ticketcenter/view/<?php echo $s['id']; ?>/"><?php echo $s['subject']; ?></a></td>
													<td width="20%" valign="top" bgcolor="<?php echo $rc; ?>"><a href="/ticketcenter/view/<?php echo $s['id']; ?>/"><?php echo $date; ?></a></td>
													<td width="20%" valign="top" bgcolor="<?php echo $rc; ?>"><img src="/images/tc/<?php echo $s['status']; ?>.png" alt="<?php echo status($s['status']); ?>" /><a href="/ticketcenter/view/<?php echo $s['id']; ?>/"><?php echo status($s['status']); ?></a></td>
													<td width="10%" valign="top" bgcolor="<?php echo $rc; ?>"><input type="checkbox" name="delete_ticket[]" id="delete_ticket[]" value="<?php echo $s['id']; ?>"></td>
												</tr>
												<?php
												$row_count++;
											}
											echo('</table><br />
											<input name="form" type="hidden" id="form" value="true">
											<p style="text-align:right;"><a href="#" onclick="document[\'delete_button\'].submit()">Delete Selected Tickets</a></span></form>');
										}
									}
								?>
							</div>
						</div>
					</div>
				</div>
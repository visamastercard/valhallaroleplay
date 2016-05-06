<?php
	$sid = mysql_real_escape_string($_GET['view']);
	$gti = mysql_query("SELECT * FROM tc_tickets WHERE id ='" . $sid . "'");
	$gti = mysql_fetch_array($gti);

	if($admin >= 1 && $username != $gti['assigned'])
	{
		$gpia = mysql_query("SELECT * FROM accounts WHERE id='" . $gti['creator'] . "'");
		$gpi = mysql_fetch_array($gpia);
		$smtp = new SMTP($Config['SMTP']['hostname'], 25, false, 5);
		$smtp->auth($Config['SMTP']['username'], $Config['SMTP']['password']);
		$smtp->mail_from($Config['SMTP']['from']);

		$smtp->send($gpi['email'], 'New Message on ticket #'.$_GET["view"].' - vG MTA Ticket Center', 'Hello ' . getNameFromUserID($gti["creator"],  $MySQLConn) . ',

You are receiving this e-mail because an Admin has posted a reply to your ticket. 

Poster: ' . $username . '
Ticket URL: http://mta.vg/ticketcenter/view/' . $_GET["view"] . '/

Kind Regards,
The ValhallaGaming MTA Administration Team');

	}else{
		$gpi = mysql_query("SELECT * FROM accounts WHERE id='" . $gti['assigned'] . "'");
		$gpi = mysql_fetch_array($gpi);

		$smtp = new SMTP($Config['SMTP']['hostname'], 25, false, 5);
		$smtp->auth($Config['SMTP']['username'], $Config['SMTP']['password']);
		$smtp->mail_from($Config['SMTP']['from']);

		$smtp->send($gpi['email'], 'New Message on ticket #'.$_GET["view"].' - vG MTA Ticket Center', 'Hello ' . getNameFromUserID($gti["assigned"],  $MySQLConn) . ',

You are receiving this e-mail because someone has posted a reply to your assigned ticket. 

Poster: ' . $username . '
Ticket URL: http://mta.vg/ticketcenter/view/' . $_GET["view"] . '/

Kind Regards,
The ValhallaGaming MTA Administration Team');
	}
?>
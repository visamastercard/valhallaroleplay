<HTML>
	<HEAD>
		<title>Valhalla Gaming: Multi Theft Auto</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="description" content="Website of the Valhalla Gaming Multi Theft Auto role play server." />
		<meta name="keywords" content="GTA, multi, grand, theft, auto, san, andreas, multiplayer, player, CJ, Carl, Johnson, Role, Play, RP, SAMP, IV, MTA, valhalla, gaming, vg"/>
		<meta name="author" content="valhallaGaming MTA Scripting Team" />
		<meta name="copyright" content="Valhalla Gaming" />
		<STYLE TYPE="text/css">
			body {
				font-family: Calibri, Verdana, Ariel, sans-serif;
				color: #FFFFFF;
				background-image:url('/images/bg.png');
				background-repeat: repeat-x;
				background-color: #000000;
			}
			
			p, h1, h2, h3, h4, h5, h6, td {
				font-family: Calibri, Verdana, Ariel, sans-serif;
				color: #FFFFFF;
			}
			 
			p, td {
				font-size: 12px;
			}
			 
			a {
				color: #8CF3FF;
				text-decoration:none;
			}
			 
			td
			{
				background-repeat:repeat-x;
			}
 
			#framework { 
				width: 900px;
				margin: 0 auto;
				overflow: hidden;
			}
			#header {
				font-family: Calibri, Verdana, Ariel, sans-serif;
				color: #FFFFFF;
				text-align: right;
				width: 900px;
				height: 200px;
				background-image:url(/images/MTASAWEBSITE_02.png);
			}
			
			#header-nav {
				width: 900px;
				background-image: url(/images/MTASAWEBSITE_07.png);
			}
			
			#main {
				background-image: url(/images/MTASAWEBSITE_21.png);
				background-repeat: repeat-x;
				width: 900px;
			}
			
			/* Target all Webkit browsers */ 
			@media screen and (-webkit-min-device-pixel-ratio:0) {
				#main {width: 1650px;}
			}
			
			/* Target all Firefox */ 
			@-moz-document url-prefix() {
				#main {width: 1650px;}
			} 


			#sidebarleft {
				width: 158px;
				margin: 0 auto;
				padding: 0;
				float: left;
			}
			
			#ticker {
				align: right;
				width:900px;
			}
			
			#content {
				float: left;
				
				padding: 0;
				width: 570px;
			}
			
			#content-box {
				margin: 0 auto;
				width: 555px;
				
				padding: 0px 5px;
			}
			
			#content-box {
				background-color: #000000;
			}
			
			#sidebarright {
				margin: 0 auto;
				width: 158px;
			}
			
			#sidebar-server-info {
				background-color: #000000;
				background-image: url(/images/MTASAWEBSITE_20.png);
				background-repeat: no-repeat;
				padding: 20px 10px;
			}
			
			#sidebar-donations {
				background-color: #000000;
				background-image: url(/images/MTASAWEBSITE_28.png);
				background-repeat: no-repeat;
				padding: 20px 10px;
			}
			
			#sidebar-news {
				background-color: #000000;
				background-image: url(/images/MTASAWEBSITE_23.png);
				background-repeat: no-repeat;
				padding: 20px 10px;
			}
			
			#heavy-stone {
				float: left;
				width: 900px;
				background-image: url(/images/MTASAWEBSITE_35.png);
				background-repeat: no-repeat;
			}
			
			
		</STYLE>
	</HEAD>
	<BODY>
		<div id="framework">
			<div id="header">
				<?php
if (!isset($_SESSION['ucp_loggedin']) or !$_SESSION['ucp_loggedin'])
{					
	echo"				<a href=\"/ucp/register/\">Register an account</a> | <a href=\"/ucp/login/\">Login into your account</a>\r\n";	
} else {
	echo"				<div class=\"nav-links-right\">Welcome back, ".$_SESSION['ucp_username']." | <a href=\"/ucp/main/\">Go to your UCP</a></div>\r\n";		
}	
?>
			</div>
			<div id="header-nav">
			
				<a href="/page/home/">
					<img border="0" src="/images/MTASAWEBSITE_04.png" width="113" height="35" alt="">
				</a>
		
				<a href="/page/about/">
					<img  border="0" src="/images/MTASAWEBSITE_06.png" width="225" height="35" alt="">
				</a>
				
				<a href="/page/staff/">
					<img  border="0" src="/images/MTASAWEBSITE_08.png" width="119" height="35" alt="">
				</a>

				<a href="/page/guides/">
					<img  border="0" src="/images/MTASAWEBSITE_10.png" width="118" height="35" alt="">
				</a>
				
				<a target="_blank" href="http://www.valhallagaming.net/forums/forumdisplay.php?446">
					<img  border="0" src="/images/MTASAWEBSITE_12.png" width="131" height="35" alt="">
				</a>

				<a href="/ticketcenter/main/">
					<img  border="0" src="/images/MTASAWEBSITE_14.png" width="172" height="35" alt="">
				</a>
			</div>
			<div id="ticker">&nbsp;</div>
			<div id="main">
				<div id="sidebarleft">
					<div id="sidebar-server-info">
						<p>MTA Server: <a href="mtasa://server.mta.vg:22003/">server.mta.vg:22003</a></p>
					</div>
					<div id="sidebar-donations">
						<p>Your donations go directly towards paying expenses relating to the upkeep of the Valhalla Gaming servers.</p>
						<h4><a href="http://www.valhallagaming.net/forums/showthread.php?80306-Donations-MTA-RP">More information</a></h4>
						<h4><a href="http://mta.vg/ucp/donate/">Donate Now</a></h4>
					</div>
				</div>
				<div id="content">
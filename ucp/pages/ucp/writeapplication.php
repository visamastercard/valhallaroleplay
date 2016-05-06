<?php

/*
appstate
0: no application made at all
1: pending
2: denied
3: accepted
*/
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
$mQuery1 = mysql_query("SELECT `username`, `appstate`, `appdatetime` > NOW() as 'noapply', HOUR(TIMEDIFF(NOW(), `appdatetime`)) as 'timehour', MINUTE(TIMEDIFF(NOW(), `appdatetime`)) as 'timeminute', `banned_reason` FROM `accounts` WHERE id='" . $userID . "' LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) == 0)
{
	$_SESSION['ucp_loggedin'] = false;
	$_SESSION['errno'] = 2;
	header("Location: /ucp/login/");
	die();
}

$userRow = mysql_fetch_assoc($mQuery1);
$noapply = $userRow['noapply'];
$appstate = $userRow['appstate'];

$mQuery2 = mysql_query("SELECT `adminAction` FROM `applications` WHERE `accountID`='".mysql_real_escape_string($userID, $MySQLConn)."' AND (`adminAction` = 1 or `adminAction` = 2) ORDER BY dateposted DESC LIMIT 1", $MySQLConn);
if (mysql_num_rows($mQuery1) != 0)
{
	$appRow = mysql_fetch_assoc($mQuery2);
	if ($appRow['adminAction'] > $appstate) 
		$appstate = $appRow['adminAction'];
}
foreach($userRow as $index => $value)
{
	$userRow[$index] = stripslashes(htmlentities($value));
}

if ($appstate == 3)
{
	header("Location: /ucp/main/");
	die();
}

$textSteps = array(		'1' => '
								<p>We aim to review all applications within a few hours of their submission. You will not be declined for being new to role play, infact we encourage new RolePlayers to join. This test avoids powergamers, metagamers, deathmatchers, etc from ruining the experience for others.	Before submitting your application please take the time to read <a target="_blank" href="http://www.valhallagaming.net/forums/showthread.php?43348-MTA-Server-Rules">the rules</a> and the guides available on the <a target="_blank" href="http://www.valhallagaming.net/forums/forumdisplay.php?f=489">Valhalla Gaming forum</a>.<p>
								<h3>Tips</h3>
								<ul>
									<li>Check your spelling, grammar and punctuation. This does not have to be perfect but this is an English speaking server and text is the main form of communication so we need to see that you will be able to keep up and that others will be able to understand you.</li>
									<li>Sentences begin with a capital letter!</li>
								</ul>
								<p>The following three application steps will test if you are capable for the high standard of roleplay we strive for in the server. Please answer your questions with full sentences if possible. Also, show us that you are putting effort in your application, this will give you a better chance of accepting the application.</p>',
						'2' => '<p>Okay, those were the first steps. Nice to meet you, good to know something from you. Now we are going to ask a few questions about role play in general.</p>',
						'3' => '<p>Last but not least we ask you to describe your future and first character. Also we\'re going to give you one of more cases. Please use common sense.</p>');

$requiredQuestions = array (
								'1' => array(	'What is your real-life age?',
												'Have you ever role-played before in another game or another server?',
												'Why do you want to play at the ValhallaGaming MTA Roleplay server?',
												'Describe common sense..',
												'What is your first language/mother tongue?',
												'What country are you from?',
								),
								'2' => array( 	'Explain what role play is:',
												'What is the difference between an RP server and a freeroam server?',
												'What is powergaming? (Include two detailed, non-generic examples of powergaming in it’s different forms such as being unrealistic physically, and being unrealistic in your age and character story.)',
												'What is metagaming? (Again, include two detailed, non-generic examples of metagaming.)',
												'When should you use a firearm/gun in game? (Provide a good and bad example. Again, try to not be generic about your answers.)',
												'Provide good examples of RP such as the usage /me and /do (Make sure they are well thought out and detailed /me‘s/do’s with adjectives and creatvitiy.).',
												'What is death matching?',
												'What is bunny hopping?',
												'What should you do if you see someone breaking the rules?',
												'Should you use PM or other messaging/communication devices to easily play with your friends and tell them where you\'re going ingame?',
												'What is passive RP and how often should you do it? If you\'re able to, please provide an example also.',
								),
								'3' => array(	'Write a short bio and personality assessment of what your first character will be like, what you plan to do with them/it and how you will do it:
(This should be the longest answer. This is usually called character development.):',
												'Think of this situation: Roger Stratton is driving around Downtown, when suddenly a yellow taxi misses a red light and smacks right into the left-rear side of Roger\'s vehicle. Roger quickly gets off his vehicle, extremely angry, and violently reaches into his coat, dragging out a firearm amd shooting 3 shots blindly towards the taxi driver, then he quickly rushes off away from the scene. From the previous situation: What is wrong with how it developed? What should Roger have done?'
								)
						);


// Start the design
require_once("includes/header.php");
echo "				<div id=\"content-middle\">\r\n";
echo "					<div class=\"content-box\">\r\n";
echo "						<div class=\"content-holder\">\r\n";
echo "							<h2>Application</h2>\r\n";
if( $noapply == 1 )
	echo "							<p>You need to wait ".(($timehour > 0)?($timehour . ' hours and '):('')) . $timeminute . " minutes before applying again.</p>";
elseif ($appstate == 1)
	echo "							<p><font color=\"yellow\">Your current application is still being processed. Please wait until it has been processed by one of our GameMasters or Admins.</font></p>";
else
{
	echo "								<FORM ACTION=\"\" METHOD=\"POST\">";
	if (!isset($_POST['step']) or isset($requiredQuestions[$_POST['step']]))
	{	
		
		if (!isset($_POST['step'])) 
		{
			$appStep = 1;
			$_SESSION['UCPapplicationQuestions'] = array();
			foreach ($requiredQuestions as $step => $steparray) {
				$_SESSION['UCPapplicationQuestions'][$step] = $steparray;
				shuffle($_SESSION['UCPapplicationQuestions'][$step]);
			}
			$_SESSION['UCPapplication'] = '';
		}
		else
		{
			$appStep = $_POST['step'];
			if (isset($_POST['question']) AND isset($_POST['answer']))
			{
				$error = false;
				$toAdd = '';
				foreach ($_POST['question'] as $ID => $question)
				{
					$answer = $_POST['answer'][$ID];
					if (strlen($answer) < 2)
						$error = true;
					else
					{
						$toAdd .= $question."\r\n";
						$toAdd .= $answer."\r\n\r\n";
					}
				}
				if (!$error) {
					$_SESSION['UCPapplication'] .= $toAdd;
				}
				else 
					if (isset($_SESSION['lastStep']))
						$appStep =  $_SESSION['lastStep'];
					else
						$appStep = 1;

			}
		}	
		
		echo $textSteps[$appStep];
		
		if ($error)
		{
			echo "<p><font color=\"#FF0000\">You didn't fill in all the fields! Please correct your mistakes below, then click next.</font></p>";
		}
		foreach ( $_SESSION['UCPapplicationQuestions'][$appStep] as $questionid => $question )
		{
			echo "							<P>\r\n";
			echo "								- ".$question."<BR />\r\n";
			echo "								<INPUT TYPE=\"HIDDEN\" NAME=\"question[]\" VALUE=\"".$question."\" />\r\n";
			echo "								<TEXTAREA NAME=\"answer[]\" STYLE=\"width:580px;height:100px\">";
			if (isset($_SESSION['lastStep']) and $_SESSION['lastStep'] == $appStep and $error) 
				echo $_POST['answer'][$questionid];
			
			echo "</TEXTAREA>\r\n";
			echo "							</P>\r\n";
		}
		$_SESSION['lastStep'] = $appStep;
		echo "								<P><INPUT TYPE=\"HIDDEN\" NAME=\"step\" VALUE=\"".($appStep+1)."\" /></P>\r\n";
		echo "								<P><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Next >>\" STYLE=\"color:#000;\"></P>\r\n";
		echo "								</FORM>";
	}
	else { // We completed all the steps
		if (!$_SESSION['UCPapplication'])
		{
			header("Location: /ucp/main/");
		}
		else {
			$appStep = $_POST['step'];
			if (isset($_POST['question']) AND isset($_POST['answer']))
			{
				$error = false;
				$toAdd = '';
				foreach ($_POST['question'] as $ID => $question)
				{
					$answer = $_POST['answer'][$ID];
					$toAdd .= $question."\r\n";
					$toAdd .= $answer."\r\n\r\n";
				}
				$_SESSION['UCPapplication'] .= $toAdd;
			}
			
			$escapedStr = mysql_real_escape_string($_SESSION['UCPapplication'], $MySQLConn);
			//echo $escapedStr;
			mysql_query("INSERT INTO `applications` (`accountID`, `dateposted`, `content`) VALUES ('".mysql_real_escape_string($userID)."', NOW(), '".$escapedStr."')", $MySQLConn);
			mysql_query("UPDATE `accounts` SET `appdatetime`=NOW(), `appstate`='1' WHERE id='" . mysql_real_escape_string($userID) . "' LIMIT 1", $MySQLConn);
			
			require_once("includes/header.php");
?>							<h2>Application Submitted</h2>
							<p> Your application has been submitted. You can view the status of your application at any time by logging into the Valhalla Gaming MTA UCP.</p>
							<p> We aim to process all applications within a few hours of them being submitted. While you wait you can find out more about the server on the site and our forums.</p>
							<br />
							<p><a href="/ucp/main/">Continue</a>
<?php
		}
	}
}

// End the design part
echo "						</div>\r\n";
echo "					</div>\r\n";
echo "				</div>\r\n";
require_once("includes/footer.php");
/*
if (!isset($_POST['submit']))
{
	
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Application</h2>
<?php
						if( $noapply == 1 )
						{
?>
							<p>You need to wait <?= (($timehour > 0)?($timehour . ' hours and '):('')) . $timeminute ?> minutes before applying again.</p>
<?php
						}
						else
						{
?>
							 <form action="" method="post" onSubmit="return validate_form(this)">
								
								<br />
								</textarea>
								<p>Your registration will be reviewed by a member of our administration team. Once your application has been accepted you will be able to login to the server and play.</p>
								<input type="submit" name="submit" id="submit" value="Submit" style="color:#FFF;">
							</form>
<?php
						}
?>						</div>
					</div>
				</div>
<?php 
	require_once("includes/footer.php"); 
}
else { // on submit
	foreach($_POST as $index => $value)
	{ // escaping everything
		$_POST[$index] = mysql_real_escape_string($value, $MySQLConn);
	}
	
	if (isset($_POST['gamingexperience']) AND isset($_POST['country']) AND isset($_POST['language']) AND isset($_POST['how'])  AND isset($_POST['why'])  AND isset($_POST['expectations'])  AND isset($_POST['definitions'])  AND isset($_POST['firstcharacter'])  AND isset($_POST['clarifications']))
	{
		$gamingexperience = $_POST["gamingexperience"];
		$country = $_POST["country"];
		$language = $_POST["language"];
		$how = $_POST["how"];
		$why = $_POST["why"];
		$expectations = $_POST["expectations"];
		$definitions = $_POST["definitions"];
		$firstcharacter = $_POST["firstcharacter"];
		$clarifications = $_POST["clarifications"]; 
		
		$userID = $_SESSION['ucp_userid'];
		
		require_once("includes/header.php");
?>				<div id="content-middle">
					<div class="content-box">
						<div class="content-holder">
							<h2>Application Submitted</h2>
							<p> Your application has been submitted. You can view the status of your application at any time by logging into the Valhalla Gaming MTA UCP.</p>
							<p> We aim to process all applications within a few hours of them being submitted. While you wait you can find out more about the server on the site and our forums.</p>
							<br />
							<p><a href="/ucp/main/">Continue</a>
						</div>
					</div>
				</div>
<?php
		require_once("includes/footer.php");
	}
	else {
		$_SESSION['ucp_loggedin'] = false;
		$_SESSION['errno'] = 2;
		header("Location: /ucp/login/");
		die();
	}
}
*/
?>
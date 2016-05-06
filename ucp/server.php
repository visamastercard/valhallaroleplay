<?php
session_start();

$latestdata = false;

//$ips = array('195.5.121.97', '46.4.175.150', '46.4.148.132'); //  '46.4.175.151',  '68.68.31.29',
$ips = array('87.238.175.150');
if (isset($_GET['exp']) and $_GET['exp'] == 'list')
{
	foreach($ips as $index => $value)
	{
		$ips[$index] = $value.";".mta_getServer($value, 22126);
	}
	echo implode($ips, "\r\n");
die();
}

if (isset($_GET['exp']) and $_GET['exp'] == 'xml')
{
 	header('Content-type: text/xml');
	echo "<root>\r\n";
	foreach($ips as $index => $value)
	{
		echo "	<server>\r\n";
		echo "		<IP>".$value."</IP>\r\n";
		echo "		<port>22003</port>\r\n";
		echo "		<queryport>22126</queryport>\r\n";
		$status = mta_getServer($value, 22126);
		if ($status == 1)
		{

			echo "		<status>Online</status>\r\n";
			echo "		<players>".$latestdata['currplayers']."</players>\r\n";
			//echo "		<maxplayers>".$latestdata['maxplayers']."</maxplayers>\r\n";
		}
		else
			echo "		<status>Offline</status>\r\n";
		
		echo "	</server>\r\n";
	}
echo "</root>\r\n";
	
die();
}



if (isset($_SESSION['tserverip']) and $_SESSION['tserverip'] != '46.4.175.151')
$s = $_SESSION['tserverip'];
else
$s = $ips[ rand(0, count($ips)-1)];

echo "<h1>The IP you're supposed to connect to is: ".$s.", port 22003</h1>";
//echo "Thanks, closed for now. check back later.";
$_SESSION['tserverip'] = $s;

function mta_getServer_process($dat) {
	$ndata = array();
        if (substr($dat,0,4)=="EYE1") {
                $dat=substr($dat,4);

                $i=0;
                while($dat!="") {
                        if (substr($dat,0,2)==chr(1)."?") {
                                $dat=substr($dat,2);
                        }
                        $l=ord(substr($dat,0,1));
                        $blks[$i]=substr($dat,1,$l-1);
                        $dat=substr($dat,$l);
                        $i++;
                }

                $ret->gameshort=$blks[0];
                $ret->port=$blks[1];
                $ret->name=$blks[2];
                $ret->rules['game']=$blks[3];
                $ret->map=$blks[4];
                $ret->rules['version']=$blks[5];
                if ($blks[6]=="0") {
                        $ret->public=1;
                } else {
                        $ret->public=0;
                }
                $ret->players=$blks[7];
	$ndata['currplayers'] = $blks[7];
	$ndata['maxplayers'] = $blks[8];
                $ret->maxplayers=$blks[8];
                $j=0;
                for ($i=11; $i<sizeof($blks)-2; $i=$i+5) {
                        $ret->player[$j]->name=$blks[$i];
                        $ret->player[$j]->score=$blks[$i+3];
                        $ret->player[$j]->time=0;
                        $j++;
                }
        }
        return $blks[7];
}

function mta_getServer($ip,$port) {
	global $latestdata;
        $fp=fsockopen("udp://$ip", $port, $errno, $errstr);
        if (!$fp) {
        } else {
                stream_set_timeout($fp,1,0);
                fwrite($fp,"s");
                $data=fread($fp,16384);
                fclose($fp);

                if (strlen($data) > 4)
	{
	$latestdata = mta_getServer_process($data); 
return 1;
}
	else
	return 0;
        }
}

?>
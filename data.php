<?php
error_reporting(0);
include 'functions.php';

$hostname = "aCC Server";
$refreshRate = "5000"; // ms
$version = "v1.2";
$rxlimit = "1280"; // kb/s
$txlimit = "128"; // kb/s

$workingdir = dirname($_SERVER['PHP_SELF']);
$host = $_SERVER['HTTP_HOST'];

//Refresh
if (isset($_GET['refresh'])) {
	$results = getdata();
	echo json_encode($results);
}

//Service start stop
if (isset($_GET['service'])) {
	$servicename = $_GET['service'];
	$saction = $_GET['saction'];
	service($servicename, $saction);
}

//list online
if (isset($_GET['listonline']))
{ 
	@exec("nmap -sP 192.168.1.1-50",$response);
	echo "<div class='modal-header'>
	<h3>Online Clients</h3>
	</div>
	<div class='modal-body'>";
	foreach ($response as $value) {
		echo $value . '<br/>';
	}
	echo "</div>
	<div class='modal-footer'>
	<a class='btn' data-dismiss='modal'>Close</a>
	</div>";
	exit;
}

// log read
if (isset($_GET['logread']))
{ 
	@exec("logread",$response);
	echo "<div class='modal-header'>
	<h3>Logs</h3>
	</div>
	<div class='modal-body'>";
	foreach ($response as $key => $value) {
		echo '<b>' . $key . '</b> ' . $value . '<br/>';
	}
	echo "</div>
	<div class='modal-footer'>
	<a class='btn' data-dismiss='modal'>Close</a>
	</div>";
	exit;
}

// dlna info
if (isset($_GET['dlna']))
{ 
	echo "<div class='modal-body'>";
	echo file_get_contents("http://192.168.1.1:8200");
	echo "</div>
	<div class='modal-footer'>
	<a class='btn' data-dismiss='modal'>Close</a>
	</div>";
	exit;
}

//Refresh
if (isset($_GET['refreshtoggle'])) {   
	if (!isset($_COOKIE['dynamicUpdates']) or $_COOKIE['dynamicUpdates'] == true) { 
		setcookie("dynamicUpdates", "0", time()+60*60*24*30, "/", ".".preg_replace('/^www\./','', $host)); 
		header("Location: ". $workingdir);
	}
	else { 
		setcookie("dynamicUpdates", "1", time()+60*60*24*30, "/", ".".preg_replace('/^www\./','', $host));
		header("Location: ". $workingdir);
	}
}
if (!isset($_COOKIE['dynamicUpdates']) or $_COOKIE['dynamicUpdates'] == true) { 
	$refreshtoggle = "<span class='label label-success'>On</span>"; 
}
else { 
	$refreshtoggle = "<span class='label label-inverse'>Off</span>";
	$refreshRate = "86400000";
}

?>

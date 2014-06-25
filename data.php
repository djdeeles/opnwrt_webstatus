<?php
//error_reporting(0);
require 'functions.php';
require 'config.php';

//Refresh
if (isset($_GET['refresh'])) {
	$datatime =  microtime(true);
	$results = getdata();
	$datatime = round((microtime(true) - $datatime), 2);
	array_push($results, $datatime);	
	echo json_encode($results);
}

//Service start stop
if (isset($_GET['service']) && $loggedin) {
	$servicename = $_GET['service'];
	$saction = $_GET['saction'];
	service($servicename, $saction);
}
elseif ($_GET['service']) {
	logger("Unauthenticated user for service");
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
if (isset($_GET['dlna'])) { 
	echo "<div class='modal-body'>";
	echo file_get_contents("http://192.168.1.1:8200");
	echo "</div>
	<div class='modal-footer'>
		<a class='btn' data-dismiss='modal'>Close</a>
	</div>";
	exit;
}

//Refresh
if (!isset($_COOKIE['dynamicUpdates']))	{ 
	if($loggedin) { 
		setcookie("dynamicUpdates", getoption($userid,"refresh")[0], time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $host)); 
	}
	else {
		setcookie("dynamicUpdates", 0, time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $host)); 
	}
	header("Location: ". $_SERVER['HTTP_REFERER']);
}
if (isset($_GET['refreshtoggle'])) {   
	if ($_COOKIE['dynamicUpdates'] == true) { 
		setoption($userid,"refresh",0);
		setcookie("dynamicUpdates", 0, time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $host));
	}
	else { 
		setoption($userid,"refresh",1);
		setcookie("dynamicUpdates", 1, time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $host));
	}
	header("Location: ". $_SERVER['HTTP_REFERER']);
}
if ( $_COOKIE['dynamicUpdates'] == true && $loggedin ) { $refreshtoggle = "<span class='label label-success'>On</span>"; }
elseif ( $_COOKIE['dynamicUpdates'] == true ) { $refreshtoggle = "<span class='label label-success'>On</span>"; $refreshRate = "60000"; }
else { $refreshtoggle = "<span class='label label-inverse'>Off</span>"; $refreshRate = "86400000"; }

?>

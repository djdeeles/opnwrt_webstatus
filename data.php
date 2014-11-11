<?php
//error_reporting(0);
require_once 'functions.php';
require_once 'config.php';

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

//Clean minidlna cache
if (isset($_GET['cleanminidlna']) && $loggedin) {
	rrmdir("/etc/minidlna");	
	header('Location: '.dirname($_SERVER['PHP_SELF'])."?service=minidlna&saction=restart");
}

//list online
if (isset($_GET['listonline']))
{ 

	$datatime =  microtime(true);
	$response = @shell_exec("nmap -sP 192.168.1.1-50");
	$clients = explode("Nmap scan report for ", get_string_between($response, "EET", " Nmap done"));
	$result = explode("Nmap done: ", $response);
	echo "<div class='modal-header'>
	<h3>Online Clients</h3>
</div>
<div class='modal-body'>";
	$clientid = "0";
	foreach ($clients as $client) {   
		if($clientid == "0" ) { $clientid++; continue; }
		$client = preg_replace('/\s+MAC.*$/', '', $client);
		echo '<b>'. $clientid . '.</b> ' . $client . '<br/>';
		$clientid++;
	}
	echo "<p style='margin:8px 0;text-align:right;'><b>" . (count($clients)-1) . " Hosts up.</b><br/><small>Scanned in " . round((microtime(true) - $datatime), 2) . " seconds.</small></p></div>
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

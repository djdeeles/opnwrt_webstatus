<?php
require_once 'functions.php';
require_once 'config.php';

if( session_id() == null ) { session_start(); }

//login
$loggedin = checklogin();
if (!empty($_POST)) {
	if (!empty($_POST)){
		$login = login($_POST["username"], sha1($_POST["password"]));
	}
}

//dynamic update
if (!isset($_COOKIE['dynamicUpdates']) && !$loggedin) { 
		setcookie("dynamicUpdates", 0, time()+60*60*24*30 , "/" , ".".$host); 
}
if (isset($_GET['refreshtoggle'])) {
	if ($_COOKIE['dynamicUpdates'] == true) { 
		setoption($_SESSION['user'][0],"refresh",0);
		setcookie("dynamicUpdates", 0, time()+60*60*24*30 , "/" , ".".$host);
	}
	else { 
		setoption($_SESSION['user'][0],"refresh",1);
		setcookie("dynamicUpdates", 1, time()+60*60*24*30 , "/" , ".".$host);
	}	
	header("Location: ". $_SERVER['HTTP_REFERER']);
}
if ( $_COOKIE['dynamicUpdates'] == true && $loggedin ) { $refreshtoggle = "<span class='label label-success'>On</span>"; }
elseif ( $_COOKIE['dynamicUpdates'] == true ) { $refreshtoggle = "<span class='label label-success'>On</span>"; $refreshRate = "60000"; }
else { $refreshtoggle = "<span class='label label-default'>Off</span>"; $refreshRate = "86400000"; }

//logout
if (isset($_GET['logout'])) {
	logger('logout');
	setcookie("authentication", null, time()-1 , "/" , ".".$host);
	session_destroy();
	$loggedin = false;
	header("Location: ". $_SERVER['HTTP_REFERER']);
}

//Refresh
if (isset($_GET['refresh'])) {
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
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
	$response = @shell_exec("nmap -sPn 192.168.1.1-255 -T5 --host-timeout 5s");
	$clients = explode("Nmap scan report for ", get_string_between($response, "EET", "Nmap done"));
	$result = explode("Nmap done: ", $response);
	echo "<div class='modal-header'>
	<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	<h2>Online Clients</h2>
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
		<a class='btn btn-default' data-dismiss='modal'>Close</a>
	</div>";
	exit;
}

// log read
if (isset($_GET['logread']))
{ 
	@exec("logread",$response);
	echo "<div class='modal-header'>
	<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
	<h2>Logs</h2>
</div>
<div class='modal-body'>";
	foreach ($response as $key => $value) {
		echo '<b>' . $key . '</b> ' . $value . '<br/>';
	}

	echo "</div>
	<div class='modal-footer'>
		<a class='btn btn-default' data-dismiss='modal'>Close</a>
	</div>";
	exit;
}

// dlna info
if (isset($_GET['dlna'])) { 
	echo "<div class='modal-header'></div>";
	echo "<div class='modal-body'>";
	echo file_get_contents("http://192.168.1.1:8200");
	echo "</div>
	<div class='modal-footer'>
		<a class='btn btn-default' data-dismiss='modal'>Close</a>
		<script type='text/javascript'>
			var myList = document.getElementById('myModal').getElementsByTagName('table');
			for (var i = 0; i < myList .length; i++) {
			    myList[i].className='table table-bordered table-hover table-striped';
			}
			$('.modal-body h2').appendTo('.modal-header');
			$('.modal-body title').remove();
			$('.modal-body div').remove();
		</script>
	</div>";
	exit;
}

?>

<?php
require_once 'conn.php';
require 'config.php';

function logger($action)
{
	$uri = $host.$_SERVER['REQUEST_URI'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$userid = $_SESSION['user'][0];
	mysql_query("INSERT INTO Logs (userid,action,uri,ip) VALUES ('$userid','$action','$uri','$ip')");
}
function checkuser($username, $password){
	$result = mysql_query("SELECT * FROM Users WHERE user='$username' AND password='$password' AND active=true");
	if(mysql_num_rows($result)) {
		$user = mysql_fetch_row($result);
		return $user;
	} 
	else {
		return 0;
	}
}
function checklogin() {
	if ($_SESSION['authenticated'] == true) {
		$loggedin = true;		
	}
	else {
		$logincookie = unserialize($_COOKIE["authentication"]);
		$user = checkuser($logincookie[1], $logincookie[2]);
		if ($user[0] > 0 && $user[0] != null && $user[0] != 0) {
			$_SESSION['authenticated'] = true;
			$_SESSION['user'] = $user;
			$loggedin = true;
			setcookie("dynamicUpdates", getoption($user[0],"refresh")[0], time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $GLOBALS['host']));
			header("Location: ". $_SERVER['HTTP_REFERER']);
		}
		else {
			$loggedin = false;
		}
	}
	return $loggedin;
}
function setoption($userid, $option, $value){
	$query = mysql_query("UPDATE Options SET $option=$value WHERE id=$userid");
	if(!$query){ return false; } else { return true; }
}
function getoption($userid, $option){
	$value = mysql_query("SELECT $option FROM Options WHERE userid=$userid");
	return mysql_fetch_row($value);  
}
function servicestate($process)
{
	@exec("pidof $process",$response);
	if ($response){ return true; } else	{ return false; }
}
function servicestatus($servicename) {
	$dir = '/etc/rc.d/';
	if( glob("{$dir}/***{$servicename}") ) {
		echo "<li><a href='?service=$servicename&saction=disable' title='disable' onclick=\"return confirm('Are you sure you want to disable service ?')\"><i class='icon-remove'></i>Disable service</a></li>";
	}
	else {
		echo "<li><a href='?service=$servicename&saction=enable' title='enable' onclick=\"return confirm('Are you sure you want to enable service ?')\"><i class='icon-ok'></i>Enable service</a></li>";
	}
}
function service($servicename, $saction)  
{ 	
	logger('service');
	@exec("/etc/init.d/$servicename $saction");
	header('Location: '.dirname($_SERVER['PHP_SELF']));
	exit;
}
function serviceControl($name, $servicename, $pid)  
{ 
	if (servicestate($pid)) { 
		echo "
		<a href='#' class='btn' data-toggle='dropdown'><span class='server'>$name: </span><font color='green'>Online</font><i class='icon-cog'></i></a>
		<ul class='dropdown-menu'>
			<li><a href='?service=$servicename&saction=stop' title='$saction' onclick=\"return confirm('Are you sure you want to stop service ?')\"><i class='icon-stop'></i>Stop service</a></li>
			<li><a href='?service=$servicename&saction=restart' title='restart' onclick=\"return confirm('Are you sure you want to restart service ?')\"><i class='icon-refresh'></i>Restart service</a></li>";
		servicestatus($servicename);
		echo "</ul>";
	} 
	else { echo "
		<a href='#' class='btn' data-toggle='dropdown'><span class='server'>$name: </span><font color='red'>Offline</font><i class='icon-cog'></i></a>
		<ul class='dropdown-menu'>
			<li><a href='?service=$servicename&saction=start' title='$saction' onclick=\"return confirm('Are you sure you want to start service ?')\"><i class='icon-play'></i>Start service</a></li>";
		servicestatus($servicename);
		echo "</ul>";
	}
} 
function ping($hostname, $host, $timeout ) {
	if ($timeout != null) { $timeoutsec = "0"; $timeoutms = $timeout;} else { $timeoutsec = "1"; $timeoutms = "0";}
	$package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
	$socket  = socket_create(AF_INET, SOCK_RAW, 1);
	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeoutsec, 'usec' => $timeoutms));
	socket_connect($socket, $host, null);
	$timer = microtime(1);
	socket_send($socket, $package, strlen($package), 0);
	if (socket_read($socket, 255)) {
		$result = round((microtime(1) - $timer) * 1000, 0);
		if ($result < 150){ $colorresult = "<span class='server'>$hostname: </span><font color='green'>$result ms</font>"; }
		else if ($result < 300){ $colorresult = "<span class='server'>$hostname: </span><font color='orange'>$result ms</font>"; }
		else if ($result > 300){ $colorresult = "<span class='server'>$hostname: </span><font color='red'>$result ms</font>"; }	
	}
	else {
		$colorresult = "<span class='server'>$hostname: </span><font color='red'>Offline</font>"; 
	}
	return $colorresult;
}
function color($percent, $field){
	if ( $field =="1" ) {
		switch (true){
			case ($percent > 85): $type = 'danger';
			break;
			case ($percent > 60): $type = 'warning';
			break;
			case ($percent > 25): $type = 'success';    
			break;
			default: $type = 'info';
		}
	}
	else if ( $field == "2" ) {
		switch (true){
			case ($percent > 85): $type = 'important';
			break;
			case ($percent > 60): $type = 'warning';
			break;
			case ($percent > 25): $type = 'success';    
			break;
			default: $type = 'info';
		}
	}
	return $type;
}
function formatSize($size){
	switch (true){
		case ($size > 1099511627776): $size /= 1099511627776; $suffix = ' TB';
		break;
		case ($size > 1073741824): $size /= 1073741824; $suffix = ' GB';
		break;
		case ($size > 1048576): $size /= 1048576; $suffix = ' MB';    
		break;
		case ($size > 1024): $size /= 1024; $suffix = ' KB';
		break;
		default: $suffix = ' B';
	}
	return round($size, 2).$suffix;
}
function formatMem($size){
	switch (true){
		case ($size > 1073741824): $size /= 1073741824; $suffix = ' TB';
		break;
		case ($size > 1048576): $size /= 1048576; $suffix = ' GB';
		break;
		default: $suffix = ' Kb';
	}
	return round($size, 2).$suffix;
}
function echoActiveClassIfRequestMatches($requestUri)
{
	$current_file_name = basename($_SERVER['REQUEST_URI']);
	if (/*strpos($current_file_name,$requestUri,null) !== false or*/ $current_file_name == $requestUri){
		echo 'class="active"';
	}
}
function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);  
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
function read_file($filename){
    $buffer = array();
    $source_file = fopen( $filename, "r" ) or die("Couldn't open $filename");
    while (!feof($source_file)) {
        $buffer[] = fread($source_file, 4096);  // use a buffer of 4KB
    }
    return $buffer;
}
function multiexplode ($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}
function vpninfo(){	
	$interface = get_string_between(@exec("ifconfig pptp-vpn,$vpn")[1], "inet addr:", "  ");
	return $interface;
}
function getdata() {
//Memory Info
	foreach(file("/proc/meminfo") as $ri)
		$m[strtok($ri, ':')] = strtok('');
	$totalMem = $m["MemTotal"];
	$availMem = $m["MemFree"] + $m["Buffers"] + $m["Cached"];
	$usedMem =  $totalMem - $availMem;
	$memPercent = round($usedMem/$totalMem*100, 0);
	$usedMem = formatMem($usedMem);
	$totalMem  = formatMem($totalMem);
	$availMem = formatMem($availMem);
//Swap Info
	$totalSwap = $m["SwapTotal"];
	$availSwap = $m["SwapFree"];
	$usedSwap =  $totalSwap - $availSwap;
	if ($totalSwap > 0 ) 	{ 
		$swapPercent = round($usedSwap/$totalSwap*100, 0); 
		$usedSwap = formatMem($usedSwap);
		$totalSwap  = formatMem($totalSwap);
		$availSwap = formatMem($availSwap);
	} 
	else { 
		$swapPercent = "0"; 
		$usedSwap ="N/A"; 
		$totalSwap="N/A"; 
		$availSwap="N/A";
	}
//Disk1 Info
	$totalDisk1 = disk_total_space("/overlay");
	$availDisk1 = disk_free_space("/overlay");
	$usedDisk1 =  $totalDisk1 - $availDisk1;
	$diskPercent1 = round($usedDisk1/$totalDisk1*100, 0);
	$totalDisk1 = formatSize($totalDisk1);
	$availDisk1 = formatSize($availDisk1);
	$usedDisk1 = formatSize($usedDisk1);
//Disk2 Info
	$totalDisk2 = disk_total_space("/mnt/data");
	$availDisk2 = disk_free_space("/mnt/data");
	$usedDisk2 =  $totalDisk2 - $availDisk2;
	$diskPercent2 = round($usedDisk2/$totalDisk2*100, 0);
	$totalDisk2 = formatSize($totalDisk2); 
	$availDisk2 = formatSize($availDisk2);
	$usedDisk2 = formatSize($usedDisk2);

	if ($usedDisk1 == $usedDisk2 ) 	{ 
		$totalDisk2 = "N/A"; 
		$availDisk2 = "N/A";
		$usedDisk2  = "N/A";
		$diskPercent2 = "0";
	} 
	
//Uptime Info
	$loadresult = @exec("uptime");
	preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs);
	$load1M = "$avgs[1]";
	$load5M = "$avgs[2]";
	$load15M = "$avgs[3]\n";
	$uptime = explode(' up ', $loadresult);
	$uptime = explode(',  load', $uptime[1]);
	$uptime = $uptime[0];
	$loadPercent = $avgs[1]*100;
	if( $loadPercent > 100){ $loadPercent = "100";}
//Connection Info
	$connresult = @exec("wc -l /proc/net/nf_conntrack");
	$connections = explode(" ", $connresult);
	$connections = $connections[0];
	$totalconnections = "16384";
	$connPercent = round($connections/$totalconnections*100, 0);
//Process Info
	$runningthreads = @exec("grep -s '^Threads' /proc/[0-9]*/status | awk '{ sum += $2; } END { print sum; }'");
//Transfer info
	$transfertime =  microtime(true);
	$WANrxstart = @exec("cat /sys/class/net/eth0.2/statistics/rx_bytes");
	$WANtxstart = @exec("cat /sys/class/net/eth0.2/statistics/tx_bytes");
	$LANrxstart = @exec("cat /sys/class/net/br-lan/statistics/rx_bytes");
	$LANtxstart = @exec("cat /sys/class/net/br-lan/statistics/tx_bytes");
	usleep(250000);
	$transfertime = microtime(true) - $transfertime;
	$WANrxend = @exec("cat /sys/class/net/eth0.2/statistics/rx_bytes");
	$WANtxend = @exec("cat /sys/class/net/eth0.2/statistics/tx_bytes");
	$LANrxend = @exec("cat /sys/class/net/br-lan/statistics/rx_bytes");
	$LANtxend = @exec("cat /sys/class/net/br-lan/statistics/tx_bytes");
	//wan
	$WANtx = round((($WANtxend - $WANtxstart) / $transfertime) / 1024, 2);
	$WANrx = round((($WANrxend - $WANrxstart) / $transfertime) / 1024, 2);
	$WANrxpercent = round($WANrx/$GLOBALS['WANrxlimit']*100,0);
	if( $WANrxpercent > 100){ $WANrxpercent = "100";}
	$WANtxpercent = round($WANtx/$GLOBALS['WANtxlimit']*100,0);
	if( $WANtxpercent > 100){ $WANtxpercent = "100";}
	//lan
	$LANtx = round((($LANtxend - $LANtxstart) / $transfertime) / 1024, 2);
	$LANrx = round((($LANrxend - $LANrxstart) / $transfertime) / 1024, 2);
	$LANrxpercent = round($WANrx/$GLOBALS['LANrxlimit']*100,0);
	if( $LANrxpercent > 100){ $LANrxpercent = "100";}
	$LANtxpercent = round($LANtx/$GLOBALS['LANtxlimit']*100,0);
	if( $LANtxpercent > 100){ $LANtxpercent = "100";}
//result
	$results = array($totalMem,
		$usedMem,
		$availMem,
		$memPercent,
		color($memPercent,"1"),
		color($memPercent,"2"),
		$totalSwap,
		$usedSwap,
		$availSwap,
		$swapPercent,
		color($swapPercent,"1"),
		color($swapPercent,"2"),
		$totalDisk1,
		$usedDisk1,
		$availDisk1,
		$diskPercent1,
		color($diskPercent1,"1"),
		color($diskPercent1,"2"),
		$totalDisk2,
		$usedDisk2,
		$availDisk2,
		$diskPercent2,
		color($diskPercent2,"1"),
		color($diskPercent2,"2"),
		$load1M,
		$load5M,
		$load15M,
		$loadPercent,
		color($loadPercent,"1"),
		color($loadPercent,"2"),
		$uptime,
		$connections,
		$connPercent,
		color($connPercent,"1"),
		color($connPercent,"2"),
		$runningthreads,
		$WANrx,
		$WANrxpercent,
		color($WANrxpercent,"1"),
		color($WANrxpercent,"2"),
		$WANtx,
		$WANtxpercent,
		color($WANtxpercent,"1"),
		color($WANtxpercent,"2"),
		$LANrx,
		$LANrxpercent,
		color($LANrxpercent,"1"),
		color($LANrxpercent,"2"),
		$LANtx,
		$LANtxpercent,
		color($LANtxpercent,"1"),
		color($LANtxpercent,"2"),
		//ping(VPN, vpninfo()),
		ping(US, "8.8.8.8",null),
		ping(EU, "194.236.188.144",null),
		ping(Gateway, "94.54.96.1",null),
		//ping(Ap, "192.168.1.2", "500000")
		"<span class='server'>Ap: </span><font color='green'>N/A</font>",
		ping(" ",$_SERVER["REMOTE_ADDR"],"500000")
		);
	return $results;
}

?>

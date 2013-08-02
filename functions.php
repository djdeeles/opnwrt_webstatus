<?php

function servicestate($process)
{
	exec("/bin/pidof $process",$response);
	if ($response){ return true; } else	{ return false; }
}
function service($servicename, $saction)  
{ 
	@exec("/etc/init.d/$servicename $saction");
	header('Location: '.dirname($_SERVER['PHP_SELF']));
	exit;
}
function serviceControl($name, $servicename, $pid)  
{ 
	if (servicestate($pid)) { 
		echo "<a href='#' class='btn' data-toggle='dropdown'><span class='server'>$name: </span><font color='green'>Online</font><i class='icon-cog'></i></a>
		<ul class='dropdown-menu'>
			<li><a href='?service=$servicename&saction=stop' title='$saction' onclick=\"return confirm('Are you sure you want to stop service ?')\"><i class='icon-stop'></i>Stop service</a></li>
			<li><a href='?service=$servicename&saction=restart' title='restart' onclick=\"return confirm('Are you sure you want to restart service ?')\"><i class='icon-refresh'></i>Restart service</a></li>
		</ul>";
	} 
	else { echo "<a href='#' class='btn' data-toggle='dropdown'><span class='server'>$name: </span><font color='red'>Offline</font><i class='icon-cog'></i></a>
		<ul class='dropdown-menu'>
			<li><a href='?service=$servicename&saction=start' title='$saction' onclick=\"return confirm('Are you sure you want to start service ?')\"><i class='icon-play'></i>Start service</a></li>
		</ul>";
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
		if ($result < 100){ $colorresult = "<span class='server'>$hostname: </span><font color='green'>$result ms</font>"; }
		else if ($result < 250){ $colorresult = "<span class='server'>$hostname: </span><font color='orange'>$result ms</font>"; }
		else if ($result > 250){ $colorresult = "<span class='server'>$hostname: </span><font color='red'>$result ms</font>"; }	
	}
	else {
		$colorresult = "<span class='server'>$hostname: </span><font color='red'>Offline</font>"; 
	}
	return $colorresult;
}
/*
function ping($hostname, $host) {
	exec("ping -W1 -c1 $host",$result);
	$result = (int)get_string_between($result[1], "time=", "ms");
	if ($result == 0) { $colorresult = "<span class='server'>$hostname: </span><font color='red'>Offline</font>"; }
	else if ($result < 100){ $colorresult = "<span class='server'>$hostname: </span><font color='green'>$result ms</font>"; }
	else if ($result < 250){ $colorresult = "<span class='server'>$hostname: </span><font color='orange'>$result ms</font>"; }
	else { $colorresult = "<span class='server'>$hostname: </span><font color='red'>$result ms</font>"; }

	return $colorresult;
}*/
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
		case ($size > 102400): $size /= 1024; $suffix = ' MB';
		break;
		default: $suffix = ' Kb';
	}
	return round($size, 2).$suffix;
}
function echoActiveClassIfRequestMatches($requestUri)
{
	$current_file_name = basename($_SERVER['REQUEST_URI']);
	if ($current_file_name == $requestUri)
		echo 'class="active"';
}
function get_string_between($string, $start, $end){
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);  
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
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
	if ($totalSwap > "0" ) { $swapPercent = round($usedSwap/$totalSwap*100, 0); } else { $swapPercent = "0"; }
	$usedSwap = formatMem($usedSwap);
	$totalSwap  = formatMem($totalSwap);
	$availSwap = formatMem($availSwap);
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
//Uptime Info
	$loadresult = @exec("uptime");
	preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs);
	$load1M = "$avgs[1]";
	$load5M = "$avgs[2]";
	$load15M = "$avgs[3]\n";
	$uptime = explode(' up ', $loadresult);
	$uptime = explode(',  load', $uptime[1]);
	$uptime = $uptime[0];
	$localtime = explode("up", $loadresult[1]);
	$localtime = date("H:i:s d/m/Y");
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
	exec("vnstat -i eth0.2 -tr 2",$out);
	$out = str_replace(' ', '', $out);
	$rout = explode("rx ", $out[3]);
	$tout = explode("rx ", $out[4]);
	$rx = (int)get_string_between($rout[0], "rx", "KB/s");
	$rxpercent = round($rx/$GLOBALS['rxlimit']*100,0);
	if( $rxpercent > 100){ $rxpercent = "100";}
	$tx = (int)get_string_between($tout[0], "tx", "KB/s");
	$txpercent = round($tx/$GLOBALS['txlimit']*100,0);
	if( $txpercent > 100){ $txpercent = "100";}
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
		$localtime,
		$connections,
		$connPercent,
		color($connPercent,"1"),
		color($connPercent,"2"),
		$runningthreads,
		$rx,
		$rxpercent,
		color($rxpercent,"1"),
		color($rxpercent,"2"),
		$tx,
		$txpercent,
		color($txpercent,"1"),
		color($txpercent,"2"),
		ping(US, "google-public-dns-a.google.com"),
		ping(EU, "bbc.co.uk"),
		ping(Gateway, "gateway"),
		ping(Ap, "192.168.1.2", "100000")
		);
	return $results;
}

?>
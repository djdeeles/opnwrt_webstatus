<?php 
$start =  microtime(true);
include_once 'data.php';
include_once 'login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$hostname?> Status</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="favicon.ico">
	<style type="text/css">
		@media (min-width: 980px) {
			body {
				padding-top: 42px;
				padding-bottom: 42px;
			}
		}
	</style>
	<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript">		
		$(document).ready(function() {
			$('[data-toggle="modal"]').click(function(e) {
				e.preventDefault();
				var url = $(this).attr('href');
				if (url.indexOf('#') == 0) {
					$(url).modal('open');
				} else {
					$.get(url, function(data) {
						$('<div class="modal hide fade">' + data + '</div>').modal();
					}).success(function() { $('input:text:visible:first').focus(); });
				}
			});
		});
		$(function() {
			setInterval(loadInfo, <?=$refreshRate?>);
			loadInfo();
		});
		function loadInfo() {
			$.getJSON("data.php?refresh", 
				function(result) {
			//mem
			var totalMem = result[0];
			$("#totalMem").html(totalMem);
			var usedMem = result[1];
			$("#usedMem").html(usedMem);
			var availMem = result[2];
			$("#availMem").html(availMem);
			var memPercent = result[3];
			$("#memPercent").html("(" + memPercent + "%)");
			$("#memPercentBar").css("width", memPercent + "%");
			var memPercentBarDiv = result[4];
			$('#memPercentBarDiv').removeClass().addClass("progress progress-" + memPercentBarDiv + " progress-striped active");		
			var memSpan = result[5];
			$('#memSpan1, #memSpan2, #memSpan3').removeClass().addClass("label label-" + memSpan);
			//swap
			var totalSwap = result[6];
			$("#totalSwap").html(totalSwap);
			var usedSwap = result[7];
			$("#usedSwap").html(usedSwap);
			var availSwap = result[8];
			$("#availSwap").html(availSwap);
			var swapPercent = result[9];
			$("#swapPercent").html("(" + swapPercent + "%)");
			$("#swapPercentBar").css("width", swapPercent + "%");
			var swapPercentBarDiv = result[10];
			$('#swapPercentBarDiv').removeClass().addClass("progress progress-" + swapPercentBarDiv + " progress-striped active");	
			var swapSpan = result[11];
			$('#swapSpan1, #swapSpan2, #swapSpan3').removeClass().addClass("label label-" + swapSpan);
			//disk1
			var totalDisk1 = result[12];
			$("#totalDisk1").html(totalDisk1);
			var usedDisk1 = result[13];
			$("#usedDisk1").html(usedDisk1);
			var availDisk1 = result[14];
			$("#availDisk1").html(availDisk1);
			var diskPercent1 = result[15];
			$("#diskPercent1").html("(" + diskPercent1 + "%)");
			$("#diskPercent1Bar").css("width", diskPercent1 + "%");
			var diskPercent1BarDiv = result[16];
			$('#diskPercent1BarDiv').removeClass().addClass("progress progress-" + diskPercent1BarDiv + " progress-striped active");			
			var disk1Span = result[17];
			$('#disk1Span1, #disk1Span2, #disk1Span3').removeClass().addClass("label label-" + disk1Span);
			//disk2
			var totalDisk2 = result[18];
			$("#totalDisk2").html(totalDisk2);
			var usedDisk2 = result[19];
			$("#usedDisk2").html(usedDisk2);
			var availDisk2 = result[20];
			$("#availDisk2").html(availDisk2);
			var diskPercent2 = result[21];
			$("#diskPercent2").html("(" + diskPercent2 + "%)");
			$("#diskPercent2Bar").css("width", diskPercent2 + "%");
			var diskPercent2BarDiv = result[22];
			$('#diskPercent2BarDiv').removeClass().addClass("progress progress-" + diskPercent2BarDiv + " progress-striped active");			
			var disk2Span = result[23];
			$('#disk2Span1, #disk2Span2, #disk2Span3').removeClass().addClass("label label-" + disk2Span);
			//load
			var load1M = result[24];
			$("#load1M").html(load1M);
			var load5M = result[25];
			$("#load5M").html(load5M);
			var load15M = result[26];
			$("#load15M").html(load15M);		
			var loadPercent = result[27];		
			$("#loadPercent").html("(" + loadPercent + "%)");
			$("#loadPercentBar").css("width", loadPercent + "%");
			var loadPercentBarDiv = result[28];
			$('#loadPercentBarDiv').removeClass().addClass("progress progress-" + loadPercentBarDiv + " progress-striped active");			
			var loadSpan = result[29];
			$('#loadSpan1, #loadSpan2, #loadSpan3').removeClass().addClass("label label-" + loadSpan);
	        //uptime and localtime                
	        var uptime = result[30];
	        $("#uptime").html(uptime); 
	        //connections
	        var connections = result[31];
	        $("#connections").html(connections);                
	        var connPercent = result[32];                                
	        $("#connPercent").html("(Connections " + connPercent + "%)");
	        $("#connPercentBar").css("width", connPercent + "%");
	        var connPercentBarDiv = result[33];
	        $('#connPercentBarDiv').removeClass().addClass("progress progress-" + connPercentBarDiv + " progress-striped active");
	        var connSpan = result[34];
	        $('#connSpan1, #connSpan2, #connSpan3, #connSpan4').removeClass().addClass("label label-" + connSpan);
	        //threads                
	        var runningthreads = result[35];
	        $("#runningthreads").html(runningthreads);
	        //WAN transfer
	        var WANrx = result[36];
	        $("#WANrx").html(WANrx + " Kb/s");
	        var WANrxPercent = result[37];
	        $("#WANrxPercentBar").css("width", WANrxPercent + "%");
	        var WANrxPercentBarDiv = result[38];
	        $('#WANrxPercentBarDiv').removeClass().addClass("progress progress-" + WANrxPercentBarDiv + " progress-striped active");
	        var WANRecieveSpan = result[39];
	        $('#WANRecieveSpan').removeClass().addClass("label label-" + WANRecieveSpan);
	        var WANtx = result[40];
	        $("#WANtx").html(WANtx + " Kb/s");
	        var WANtxPercent = result[41];
	        $("#WANtxPercentBar").css("width", WANtxPercent + "%");
	        var WANtxPercentBarDiv = result[42];
	        $('#WANtxPercentBarDiv').removeClass().addClass("progress progress-" + WANtxPercentBarDiv + " progress-striped active");
	        var WANSendSpan = result[43];
	        $('#WANSendSpan').removeClass().addClass("label label-" + WANSendSpan);	        
	        $("#WANPercent").html("(" + Math.round((WANtxPercent + WANrxPercent)/2) + "%)");
	        //LAN transfer
	        var LANrx = result[44];
	        $("#LANrx").html(LANrx + " Kb/s");
	        var LANrxPercent = result[45];
	        $("#LANrxPercentBar").css("width", LANrxPercent + "%");
	        var LANrxPercentBarDiv = result[46];
	        $('#LANrxPercentBarDiv').removeClass().addClass("progress progress-" + LANrxPercentBarDiv + " progress-striped active");
	        var LANRecieveSpan = result[47];
	        $('#LANRecieveSpan').removeClass().addClass("label label-" + LANRecieveSpan);
	        var LANtx = result[48];
	        $("#LANtx").html(LANtx + " Kb/s");
	        var LANtxPercent = result[49];
	        $("#LANtxPercentBar").css("width", LANtxPercent + "%");
	        var LANtxPercentBarDiv = result[50];
	        $('#LANtxPercentBarDiv').removeClass().addClass("progress progress-" + LANtxPercentBarDiv + " progress-striped active");
	        var LANSendSpan = result[51];
	        $('#LANSendSpan').removeClass().addClass("label label-" + LANSendSpan);	        
	        $("#LANPercent").html("(" + Math.round((LANtxPercent + LANrxPercent)/2) + "%)");
	        //ping
	        var usping = result[52];
	        $("#usping").html(usping);
	        var euping = result[53];
	        $("#euping").html(euping);
	        var gtping = result[54];
	        $("#gtping").html(gtping);
	        var apping = result[55];
	        $("#apping").html(apping);
	        var refreshtime = result[56];
	        $("#refreshtime").html(refreshtime);
	    });
}
</script>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="<?=$workingdir?>"><?=$hostname?></a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?=echoActiveClassIfRequestMatches("")?> ><a href="<?=$workingdir?>"><i class="icon-home"></i>Home</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-right"></i>Stats</a>
							<ul class="dropdown-menu">
								<li <?=echoActiveClassIfRequestMatches("stats")?> ><a href="content.php?iframe=<?=$host?>/stats/"><i class="icon-signal"></i>Network Stats</a></li>
								<li <?=echoActiveClassIfRequestMatches("lighttpd.php")?> ><a href="content.php?iframe=<?=$host?>/lighttpd.php"><i class="icon-globe"></i>Lighttpd Status</a></li>
								<li <?=echoActiveClassIfRequestMatches("apc.php")?> ><a href="content.php?iframe=<?=$host?>/apc.php"><i class="icon-align-left"></i>APC Status</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-chevron-down"></i>Downloads</a>
							<ul class="dropdown-menu">
								<li <?=echoActiveClassIfRequestMatches("public")?> ><a href="content.php?iframe=<?=$host?>/public"><i class="icon-folder-open"></i>Public Files</a></li>
								<li <?=echoActiveClassIfRequestMatches("file.php")?> ><a href="content.php?iframe=<?=$host?>/file.php"><i class="icon-folder-open"></i>File Manager</a></li>
								<li <?=echoActiveClassIfRequestMatches(":9091")?> ><a href="content.php?iframe=<?=$host?>:9091"><i class="icon-tasks"></i>Torrent</a></li>
								<li <?=echoActiveClassIfRequestMatches(":8000")?> ><a href="content.php?iframe=<?=$host?>:8000"><i class="icon-download-alt"></i>pyLoad</a></li>
							</ul>
						</li>						
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i>Management</a>
							<ul class="dropdown-menu">
								<li <?=echoActiveClassIfRequestMatches("router")?> ><a href="content.php?iframe=<?=$host?>/router"><i class="icon-cog"></i>Router</a></li>
								<li <?=echoActiveClassIfRequestMatches("shell.php")?> ><a href="content.php?iframe=<?=$host?>/shell.php"><i class="icon-align-left"></i>Shell</a></li>
								<li <?=echoActiveClassIfRequestMatches("adminer")?> ><a href="content.php?iframe=<?=$host?>/adminer"><i class="icon-list-alt"></i>Adminer</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav pull-right" data-no-collapse="true">
						<li><a href="?refreshtoggle">Auto Refresh: <?=$refreshtoggle?></a></li>
					</ul>
					<ul class="nav pull-right" data-no-collapse="true">
						<li class="dropdown">
							<?php if ($loggedin == true) { ?>
							<a href="?logout"><i class="icon-user"></i>Sign Out</a>
							<?php } else { ?>
							<a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-user"></i>Sign In<span class="caret"></span></a>                                                
							<div class="dropdown-menu" style="padding: 10px; padding-bottom: 0px;">
								<form method="post" id="login_form">
									<input style="margin-bottom: 10px;" type="text" placeholder="Username" id="username" name="username"><br/>
									<input style="margin-bottom: 10px;" type="password" placeholder="Password" id="password" name="password"><br/>
									<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember" value="yes"><label class="string optional" for="remember"> Remember me</label>
									<input class="btn btn-primary btn-block" type="submit" id="submit" name="Submit" value="Sign In">
									<div id="msgbox"><?=$error?></div>
								</form>                                        
							</div>
							<?php } ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="jumbotron">
			<img src="img/cc.png" width="100" alt="aCC Server">
			<h1>Status</h1>
			<h2 class="lead"><?=$hostname?></h2>        
		</div>
		<div class="jumbotron">
			<?php if ($loggedin == true) {
				echo "
				<div class='btn' id='usping' onclick='loadInfo()'><span class='server'>US: </span><font color='green'>___</font></div>
				<div class='btn' id='euping' onclick='loadInfo()'><span class='server'>EU: </span><font color='green'>___</font></div>
				<div class='btn' id='gtping' onclick='loadInfo()'><span class='server'>Gateway: </span><font color='green'>___</font></div>
				<div class='btn' id='apping' onclick='loadInfo()'><span class='server'>Ap: </span><font color='green'>___</font></div>
				<div class='btn-group'>", serviceControl(Http,lighttpd,lighttpd), "</div>
				<div class='btn-group'>", serviceControl(Ftp,vsftpd,vsftpd), "</div>
				<div class='btn-group'>", serviceControl(Dns,dnsmasq,dnsmasq), "</div>                                
				<div class='btn-group'>", serviceControl(SSH,dropbear,dropbear), "</div>
				<div class='btn-group'>", serviceControl(Ntpd,sysntpd,ntpd), "</div>
				<div class='btn-group'>", serviceControl(Cron,cron,crond), "</div>
				<div class='btn-group'>", serviceControl(Wifi,wifi,hostapd), "</div>
				<div class='btn-group'>", serviceControl(Stats,vnstat,vnstatd), "</div>
				<div class='btn-group'>", serviceControl(Vpn,pptpd,pptpd), "</div>
				<div class='btn-group'>", serviceControl(MySQL,mysqld,mysqld), "</div>
				<div class='btn-group'>", serviceControl(Samba,samba,smbd), "</div>
				<div class='btn-group'>", serviceControl(Torrent,transmission,'transmission-daemon'), "</div>
				<div class='btn-group'>", serviceControl(pyLoad,pyload,'python /usr/share/python/pyload/pyLoadCore.py'), "</div>
				<div class='btn-group'>", serviceControl(DLNa,minidlna,minidlna), "</div>
				";
			} else { echo "<h4>You need to login to see detailed info.</h4>"; } ?>                        
		</div>
		<div class="row-fluid">
			<div class="span6">
				<h2><b>Wan </b><span class="percentage" id="WANPercent"></span></h2>
				<span id="WANRecieveSpan" class="label label-inverse" style="float:left; margin-right:5px; width:120px;">Rx: <span id="WANrx"></span></span>
				<div id="WANrxPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="WANrxPercentBar"></div>
				</div>				
				<span id="WANSendSpan" class="label label-inverse" style="float:left; margin-right:5px; width:120px;">Tx: <span id="WANtx"></span></span>
				<div id="WANtxPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="WANtxPercentBar"></div>
				</div>				
			</div>
			<div class="span6">
				<h2><b>Lan </b><span class="percentage" id="LANPercent"></span></h2>
				<span id="LANRecieveSpan" class="label label-inverse" style="float:left; margin-right:5px; width:120px;">Rx: <span id="LANrx"></span></span>
				<div id="LANrxPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="LANrxPercentBar"></div>
					</div>
				<span id="LANSendSpan" class="label label-inverse" style="float:left; margin-right:5px; width:120px;">Tx: <span id="LANtx"></span></span>
				<div id="LANtxPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="LANtxPercentBar"></div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<h2><b>Load </b><span class="percentage" id="loadPercent"></span></h2>
				<div id="loadPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="loadPercentBar"></div>
				</div>
				<p><span id="loadSpan1" class="label label-inverse">Load 1 Minute: <span id="load1M"></span></span></p>
				<p><span id="loadSpan2" class="label label-inverse">Load 5 Minute: <span id="load5M"></span></span></p>
				<p><span id="loadSpan3" class="label label-inverse">Load 15 Minute: <span id="load15M"></span></span></p>
			</div>
			<div class="span4">
				<h2><b>Ram </b><span class="percentage" id="memPercent"></span></h2>
				<div id="memPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="memPercentBar"></div>
				</div>
				<p><span id="memSpan1" class="label label-inverse">Ram Total: <span id="totalMem"></span></span></p>
				<p><span id="memSpan2" class="label label-inverse">Ram Used: <span id="usedMem"></span></span></p>
				<p><span id="memSpan3" class="label label-inverse">Ram Available: <span id="availMem"></span></span></p>
			</div>
			<div class="span4">
				<h2><b>Swap </b><span class="percentage" id="swapPercent"></span></h2>
				<div id="swapPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="swapPercentBar"></div>
				</div>
				<p><span id="swapSpan1" class="label label-inverse">Swap Total: <span id="totalSwap"></span></span></p>
				<p><span id="swapSpan2" class="label label-inverse">Swap Used: <span id="usedSwap"></span></span></p>
				<p><span id="swapSpan3" class="label label-inverse">Swap Available: <span id="availSwap"></span></span></p>
			</div>
		</div>
		<div class="row-fluid">                                
			<div class="span4">
				<h2><b>Disk 1 </b><span class="percentage" id="diskPercent1"></span></h2>
				<div id="diskPercent1BarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="diskPercent1Bar"></div>
				</div>
				<p><span id="disk1Span1" class="label label-inverse">Disk Total: <span id="totalDisk1"></span></span></p>
				<p><span id="disk1Span2" class="label label-inverse">Disk Used: <span id="usedDisk1"></span></span></p>
				<p><span id="disk1Span3" class="label label-inverse">Disk Available: <span id="availDisk1"></span></span></p>
			</div>
			<div class="span4">
				<h2><b>Disk 2 </b><span class="percentage" id="diskPercent2"></span></h2>
				<div id="diskPercent2BarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="diskPercent2Bar"></div>
				</div>
				<p><span id="disk2Span1" class="label label-inverse">Disk Total: <span id="totalDisk2"></span></span></p>
				<p><span id="disk2Span2" class="label label-inverse">Disk Used: <span id="usedDisk2"></span></span></p>
				<p><span id="disk2Span3" class="label label-inverse">Disk Available: <span id="availDisk2"></span></span></p>
			</div>
			<div class="span4">
				<h2><b>Stats </b><span class="percentage" id="connPercent"></span></h2>
				<div id="connPercentBarDiv" class="progress progress-info progress-striped active">
					<div class="bar" style="width: 0%;" id="connPercentBar"></div>
				</div>
				<p><span id="connSpan3" class="label label-inverse">Connections: <span id="connections"></span></span></p>        
				<p><span id="connSpan1" class="label label-inverse">Uptime: <span id ="uptime"></span></span></p>                       
				<p><span id="connSpan4" class="label label-inverse">Threads: <span id="runningthreads"></span></span></p>
			</div>
		</div>
		<?php if ($loggedin == true) { ?>
		<hr> 
		<div class="row-fluid">
			<p class="span2"><a href="?logread" title="Read Logs" data-toggle="modal" class="btn">Read Logs</a></p>
			<p class="span2"><a href="?listonline" title="List Online" data-toggle="modal" class="btn">List Online</a></p>
			<p class="span2"><a href="?dlna" title="DLNA Info" data-toggle="modal" class="btn">DLNA Info</a></p>
		</div>
		<?php } ?>
		<hr>                
		<div class="footer">
			<div><small><b>Page generated in</b> <?php echo round((microtime(true) - $start), 2); ?> seconds. Refresh response time <span id="refreshtime"></span> seconds.
				<br><?php echo "Your IP Address: ". $_SERVER["REMOTE_ADDR"]; ?></small><br/>
				<small>Made by <a href="mailto:djdeeles@gmail.com">Çetin ÇÖNE</a>.<br/>aCC Stats <?=$version?></small></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
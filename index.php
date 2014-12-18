<?php  
$start =  microtime(true);
require_once 'login.php';
require_once 'data.php';
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
	<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/loader.js" type="text/javascript"></script>
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

			var url = window.location;
			//$('ul.nav a[href="' + this.location.pathname + '"]').parent().addClass('active');
			var active = $('ul.nav a').filter(function() {
			  return this.href == url;
			});
			active.parent().addClass('active');
			active.parent().parent().parent().addClass('active');

		});
		$(function() {
			setInterval(loadInfo, <?=$refreshRate?>);
			loadInfo();
		});
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
						<li><a href="<?=$workingdir?>"><i class="icon-home"></i>Home</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-right"></i>Stats</a>
							<ul class="dropdown-menu">
								<li><a href="content.php?iframe=<?=$host?>/stats/"><i class="icon-signal"></i>Network Stats</a></li>
								<li><a href="content.php?iframe=<?=$host?>/lighttpd.php"><i class="icon-globe"></i>Lighttpd Status</a></li>
								<li><a href="content.php?iframe=<?=$host?>/apc.php"><i class="icon-align-left"></i>APC Status</a></li>
								<li><a href="content.php?iframe=<?=$host?>/logreader.php"><i class="icon-inbox"></i>Log Reader</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-chevron-down"></i>Downloads</a>
							<ul class="dropdown-menu">
								<li><a href="content.php?iframe=<?=$host?>/public"><i class="icon-folder-open"></i>Public Files</a></li>
								<li><a href="content.php?iframe=<?=$host?>/file.php"><i class="icon-folder-open"></i>File Manager</a></li>
								<li><a href="content.php?iframe=<?=$host?>/torrent"><i class="icon-tasks"></i>Torrent</a></li>
								<li><a href="content.php?iframe=<?=$host?>:8000"><i class="icon-download-alt"></i>pyLoad</a></li>
							</ul>
						</li>						
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i>Management</a>
							<ul class="dropdown-menu">
								<li><a href="content.php?iframe=modem.<?=$host?>/RgSwInfo.asp"><i class="icon-globe"></i>Modem</a></li>
								<li><a href="content.php?iframe=<?=$host?>/router"><i class="icon-cog"></i>Router</a></li>
								<li><a href="content.php?iframe=ap.<?=$host?>"><i class="icon-filter"></i>Access Point</a></li>
								<li><a href="content.php?iframe=<?=$host?>/shell.php"><i class="icon-align-left"></i>Shell</a></li>
								<li><a href="content.php?iframe=<?=$host?>/adminer"><i class="icon-list-alt"></i>Adminer</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav pull-right" data-no-collapse="true">
						<li><a href="?refreshtoggle">Auto Refresh: <?=$refreshtoggle?></a></li>
					</ul>
					<ul class="nav pull-right" data-no-collapse="true">
						<li class="dropdown">
							<?php if ($loggedin == true) { ?>
							<a href="?logout"><i class="icon-user"></i>Sign Out(<?php echo $_SESSION['user'][1]; ?>)</a>
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
				<div class='btn' id='usping' onclick='loadInfo()'><span class='server'>US: </span><font color='green'>___</font></div>
				<div class='btn' id='euping' onclick='loadInfo()'><span class='server'>EU: </span><font color='green'>___</font></div>
				<div class='btn' id='gtping' onclick='loadInfo()'><span class='server'>Gateway: </span><font color='green'>___</font></div>
				<div class='btn' id='apping' onclick='loadInfo()'><span class='server'>Ap: </span><font color='green'>___</font></div>
			<?php if ($loggedin == true) { ?>
				<div class='btn-group'><?php serviceControl(Http,lighttpd,lighttpd); ?></div>
				<div class='btn-group'><?php serviceControl(Ftp,vsftpd,vsftpd); ?></div>
				<div class='btn-group'><?php serviceControl(Dns,dnsmasq,dnsmasq); ?></div>                                
				<div class='btn-group'><?php serviceControl(SSH,dropbear,dropbear); ?></div>
				<div class='btn-group'><?php serviceControl(Ntpd,sysntpd,ntpd); ?></div>
				<div class='btn-group'><?php serviceControl(Cron,cron,crond); ?></div>
				<div class='btn-group'><?php serviceControl(Wifi,wifi,hostapd); ?></div>
				<div class='btn-group'><?php serviceControl(Stats,vnstat,vnstatd); ?></div>
				<div class='btn-group'><?php serviceControl(Vpn,pptpd,pptpd); ?></div>
				<div class='btn-group'><?php serviceControl(MySQL,mysqld,mysqld); ?></div>
				<div class='btn-group'><?php serviceControl(Samba,samba,smbd); ?></div>
				<div class='btn-group'><?php serviceControl(Torrent,transmission,'transmission-daemon'); ?></div>
				<div class='btn-group'><?php serviceControl(pyLoad,pyload,'python /usr/share/python/pyload/pyLoadCore.py'); ?></div>
				<div class='btn-group'><?php serviceControl(DLNa,minidlna,minidlna); ?></div>
			<?php } ?>                        
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
				<br><?php echo "Your IP Address: " . $_SERVER["REMOTE_ADDR"]; ?> <span id="visitorlatency"></span></small><br/>
				<small>Made by <a href="mailto:djdeeles@gmail.com">Çetin ÇÖNE</a>.<br/>aCC Stats <?=$version?></small></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
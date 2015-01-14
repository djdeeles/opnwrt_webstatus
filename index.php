<?php  
$start =  microtime(true);
require_once 'data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?=$hostname?> Status</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="favicon.ico">
	<!--<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>-->
	<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
	<script type="text/javascript">		
		$(function() {
			setInterval(loadInfo, <?=$refreshRate?>);
			loadInfo();
		});
	</script>
	<style type="text/css">
		@media (min-width: 980px) {
			body {
				padding-top: 50px;
				padding-bottom: 42px;
			}
		}
	</style>
</head>
<body>
	<div class="navbar-nav navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">		
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand /*hidden-sm*/" href="<?=$workingdir?>"><img class="img-responsive" style="float:left; max-height:20px; vertical-align:middle; margin-right:5px;" src="img/cc.png" alt="aCC Server"><?=$hostname?></a>
			</div>
			<div class="collapse navbar-collapse" id="menu">
				<ul class="nav navbar-nav">
					<li><a href="<?=$workingdir?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" href="#">Stats<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="content.php?iframe=<?=$host?>/stats/"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>Network Stats</a></li>
							<li><a href="content.php?iframe=<?=$host?>/lighttpd.php"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span>Lighttpd Status</a></li>
							<li><a href="content.php?iframe=<?=$host?>/apc.php"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>APC Status</a></li>
							<li><a href="content.php?iframe=<?=$host?>/logreader.php"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>Log Reader</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" href="#">Downloads<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="content.php?iframe=<?=$host?>/public"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>Public Files</a></li>
							<li><a href="content.php?iframe=<?=$host?>/file.php"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>File Manager</a></li>
							<li><a href="content.php?iframe=<?=$host?>/torrent"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>Torrent</a></li>
							<li><a href="content.php?iframe=<?=$host?>:8000"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>pyLoad</a></li>
						</ul>
					</li>						
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" href="#">Management<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="content.php?iframe=<?=$host?>/shell.php"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>Shell</a></li>
							<li><a href="content.php?iframe=<?=$host?>/adminer"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>Adminer</a></li>
							<li class="divider"></li>
							<li><a href="content.php?iframe=modem.<?=$host?>/RgSwInfo.asp"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span>Modem</a></li>
							<li><a href="content.php?iframe=<?=$host?>/router"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>Router</a></li>
							<li><a href="content.php?iframe=ap.<?=$host?>"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>Access Point</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right" data-no-collapse="true">
					<li class="dropdown">
						<a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><?php if ($loggedin == true) { echo $_SESSION['username']; } else { echo "Sign In"; } ?><span class="caret"></span></a>
						<?php if ($loggedin == true) { ?>
						<ul class="dropdown-menu" role="menu">
							<li><a href="?refreshtoggle">Auto Refresh: <?=$refreshtoggle?></a></li>
							<li class="divider"></li>
							<li><a href="?logout">Sign Out</a></li>
						</ul>						
						<?php } else { ?>						
						<ul class="dropdown-menu" role="menu">
							<li><a href="?refreshtoggle">Auto Refresh: <?=$refreshtoggle?></a></li>
							<li class="divider"></li>
							<form class="form" method="post" id="login_form" onsubmit="return validate(this);" style="padding:10px 15px;">
								<?=$login?>
								<input class="form-control" style="margin-bottom: 10px;" type="text" placeholder="Username" id="username" name="username">
								<input class="form-control" style="margin-bottom: 10px;" type="password" placeholder="Password" id="password" name="password">
								<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember" value="yes"><label class="string optional" for="remember"> Remember me</label>	
								<input class="btn btn-default btn-primary btn-block" type="submit" id="submit" name="Submit" value="Sign In">
							</form>
						</ul>
						<?php } ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="jumbotron">
			<h1>Status</h1>
			<h2 class="lead"><?=$hostname?></h2>        
		</div>
		<div class="jumbotron">
			<div class='btn btn-default' id='usping' onclick='loadInfo()'><span class='server'>US: </span><font color='green'>___</font></div>
			<div class='btn btn-default' id='euping' onclick='loadInfo()'><span class='server'>EU: </span><font color='green'>___</font></div>
			<div class='btn btn-default' id='gtping' onclick='loadInfo()'><span class='server'>Gateway: </span><font color='green'>___</font></div>
			<div class='btn btn-default' id='apping' onclick='loadInfo()'><span class='server'>Ap: </span><font color='green'>___</font></div>
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
			<div class='btn-group'><?php serviceControl(pyLoad,pyload,'pyLoadCore'); ?></div>
			<div class='btn-group'><?php serviceControl(DLNa,minidlna,minidlna); ?></div>
			<div class='btn-group'><?php serviceControl(Proxy,polipo,polipo); ?></div>
			<div class='btn-group'><?php serviceControl(UsbOverIP,vhusb,vhusbdmipssf); ?></div>
			<div class='btn-group'><?php serviceControl(UPnP,miniupnpd,miniupnpd); ?></div>
			<?php } ?>                        
		</div>
		<div class="row">
			<div class="col-md-4">
				<h2><b>Wan </b><span class="percentage" id="WANPercent"></span></h2>
				<span id="WANRecieveSpan" class="label label-default" style="float:left; margin-right:5px; width:100px; text-align:left;">Rx: <span id="WANrx"></span></span>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="WANrxPercentBar"></div>
				</div>				
				<span id="WANSendSpan" class="label label-default" style="float:left; margin-right:5px; width:100px; text-align:left;">Tx: <span id="WANtx"></span></span>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="WANtxPercentBar"></div>
				</div>				
			</div>
			<div class="col-md-4">
				<h2><b>Lan </b><span class="percentage" id="LANPercent"></span></h2>
				<span id="LANRecieveSpan" class="label label-default" style="float:left; margin-right:5px; width:100px; text-align:left;">Rx: <span id="LANrx"></span></span>
				<div class="progress">
					<div class="progress-bar" style="width: 0%;" id="LANrxPercentBar"></div>
				</div>
				<span id="LANSendSpan" class="label label-default" style="float:left; margin-right:5px; width:100px; text-align:left;">Tx: <span id="LANtx"></span></span>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="LANtxPercentBar"></div>
				</div>
			</div>
			<div class="col-md-4">
				<h2><b>WLan </b><span class="percentage" id="WLANPercent"></span></h2>
				<span id="WLANRecieveSpan" class="label label-default" style="float:left; margin-right:5px; width:100px; text-align:left;">Rx: <span id="WLANrx"></span></span>
				<div class="progress">
					<div class="progress-bar" style="width: 0%;" id="WLANrxPercentBar"></div>
				</div>
				<span id="WLANSendSpan" class="label label-default" style="float:left; margin-right:5px; width:100px; text-align:left;">Tx: <span id="WLANtx"></span></span>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="WLANtxPercentBar"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<h2><b>Load </b><span class="percentage" id="loadPercent"></span></h2>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="loadPercentBar"></div>
				</div>
				<p><span id="loadSpan1" class="label label-default">Load 1 Minute: <span id="load1M"></span></span></p>
				<p><span id="loadSpan2" class="label label-default">Load 5 Minute: <span id="load5M"></span></span></p>
				<p><span id="loadSpan3" class="label label-default">Load 15 Minute: <span id="load15M"></span></span></p>
			</div>
			<div class="col-md-4">
				<h2><b>Ram </b><span class="percentage" id="memPercent"></span></h2>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="memPercentBar"></div>
				</div>
				<p><span id="memSpan1" class="label label-default">Ram Total: <span id="totalMem"></span></span></p>
				<p><span id="memSpan2" class="label label-default">Ram Used: <span id="usedMem"></span></span></p>
				<p><span id="memSpan3" class="label label-default">Ram Available: <span id="availMem"></span></span></p>
			</div>
			<div class="col-md-4">
				<h2><b>Swap </b><span class="percentage" id="swapPercent"></span></h2>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="swapPercentBar"></div>
				</div>
				<p><span id="swapSpan1" class="label label-default">Swap Total: <span id="totalSwap"></span></span></p>
				<p><span id="swapSpan2" class="label label-default">Swap Used: <span id="usedSwap"></span></span></p>
				<p><span id="swapSpan3" class="label label-default">Swap Available: <span id="availSwap"></span></span></p>
			</div>
		</div>
		<div class="row">                                
			<div class="col-md-4">
				<h2><b>Disk 1 </b><span class="percentage" id="diskPercent1"></span></h2>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="diskPercent1Bar"></div>
				</div>
				<p><span id="disk1Span1" class="label label-default">Disk Total: <span id="totalDisk1"></span></span></p>
				<p><span id="disk1Span2" class="label label-default">Disk Used: <span id="usedDisk1"></span></span></p>
				<p><span id="disk1Span3" class="label label-default">Disk Available: <span id="availDisk1"></span></span></p>
			</div>
			<div class="col-md-4">
				<h2><b>Disk 2 </b><span class="percentage" id="diskPercent2"></span></h2>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="diskPercent2Bar"></div>
				</div>
				<p><span id="disk2Span1" class="label label-default">Disk Total: <span id="totalDisk2"></span></span></p>
				<p><span id="disk2Span2" class="label label-default">Disk Used: <span id="usedDisk2"></span></span></p>
				<p><span id="disk2Span3" class="label label-default">Disk Available: <span id="availDisk2"></span></span></p>
			</div>
			<div class="col-md-4">
				<h2><b>Stats </b><span class="percentage" id="connPercent"></span></h2>
				<div class="progress">
					<div class="progress-bar" role="progressbar" style="width: 0%;" id="connPercentBar"></div>
				</div>
				<p><span id="connSpan3" class="label label-default">Connections: <span id="connections"></span></span></p>        
				<p><span id="connSpan1" class="label label-default">Uptime: <span id ="uptime"></span></span></p>                       
				<p><span id="connSpan4" class="label label-default">Threads: <span id="runningthreads"></span></span></p>
			</div>
		</div>
		<?php if ($loggedin == true) { ?>
		<hr> 
		<div class="row">
			<a href="?logread" title="Read Logs" data-toggle="modal" data-target="#myModal" class="btn btn-default">Read Logs</a>
			<a href="?listonline" title="List Online" data-toggle="modal" data-target="#myModal" class="btn btn-default">List Online</a>
			<a href="?dlna" title="DLNA Info" data-toggle="modal" data-target="#myModal" class="btn btn-default">DLNA Info</a>
		</div>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h2>Please wait</h2>
					</div>
					<div class="modal-body">
						Content is loading...
					</div>
					<div class="modal-footer">
						<a class="btn btn-default" data-dismiss="modal">Close</a>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<hr>                
		<div class="footer">
			<div><small><b>Page generated in</b> <?php echo round((microtime(true) - $start), 2); ?> seconds. Refresh response time <span id="refreshtime"></span> seconds.<br/>
				<a href="http://www.cetincone.com" target="_blank">aCC Stats <?=$version?></a></small>
			</div>
		</div>
	</div>
</body>
</html>
<?php include 'data.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?=$hostname?></title>
	<link rel="shortcut icon" href="favicon.ico">
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/iframe.css" type="text/css">
	<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
	<script src="js/iframe.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			var url = window.location;
		   //$('ul.nav a[href="' + this.location.pathname + '"]').parent().addClass('active');
		   var active = $('ul.nav a').filter(function() {
		   	return this.href == url;
		   });
		   active.parent().addClass('active');
		   active.parent().parent().parent().addClass('active');
		});
	</script>
</head>
<body>
	<div id="toolbar" class="navbar-nav navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">				
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand hidden-sm" href="<?=$workingdir?>"><img class="img-responsive" style="float:left; max-height:20px; vertical-align:middle; margin-right:5px;" src="img/cc.png" alt="aCC Server"><?=$hostname?></a>
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
							<li><a href="content.php?iframe=modem.<?=$host?>/RgSwInfo.asp"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span>Modem</a></li>
							<li><a href="content.php?iframe=<?=$host?>/router"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>Router</a></li>
							<li><a href="content.php?iframe=ap.<?=$host?>"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>Access Point</a></li>
							<li><a href="content.php?iframe=<?=$host?>/shell.php"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>Shell</a></li>
							<li><a href="content.php?iframe=<?=$host?>/adminer"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>Adminer</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right" data-no-collapse="true">
					<li class="dropdown">
						<?php if ($loggedin == true) { ?>
						<a href="?logout"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Sign Out(<?php echo $_SESSION['username']; ?>)</a>
						<?php } else { ?>
						<a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>Sign In<span class="caret"></span></a>                                                
						<div class="dropdown-menu" style="padding:17px;">
							<form class="form" method="post" id="login_form" onsubmit="return validate(this);"> 
								<?=$login?>
								<input class="form-control" style="margin-bottom: 10px;" type="text" placeholder="Username" id="username" name="username">
								<input class="form-control" style="margin-bottom: 10px;" type="password" placeholder="Password" id="password" name="password">
								<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember" value="yes"><label class="string optional" for="remember"> Remember me</label>	
								<input class="btn btn-default btn-primary btn-block" type="submit" id="submit" name="Submit" value="Sign In">
							</form>                              
						</div>
						<?php } ?>
					</li>					
					<li><a id="close" class="close" href="javascript:void(0);"><span aria-hidden='true'>&times;</span></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div id="iframe" name="iframe"><iframe></iframe>
	</div>
</body>
</html>
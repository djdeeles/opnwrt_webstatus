<?php include 'data.php'; include 'login.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>aCC Server</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/iframe.css" type="text/css">
	<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/iframe.js" type="text/javascript"></script>
</head>
<body>
	<div id="toolbar" class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="">aCC Server</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?=echoActiveClassIfRequestMatches("")?> ><a href="<?=$workingdir?>"><i class="icon-home"></i>Home</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-right"></i>Stats</a>
							<ul class="dropdown-menu">
								<li <?=echoActiveClassIfRequestMatches("stats")?> ><a href="content.php?iframe=<?=$host?>/stats/"><i class="icon-signal"></i>Network Stats</a></li>
								<li <?=echoActiveClassIfRequestMatches("lighttpd.php")?> ><a href="content.php?iframe=<?=$host?>/lighttpd.php"><i class="icon-globe"></i>Lighttpd Status</a></li>
								<li <?=echoActiveClassIfRequestMatches("apc.php")?> ><a href="content.php?iframe=<?=$host?>/apc.php"><i class="icon-align-left"></i>APC Status</a></li>
								<li <?=echoActiveClassIfRequestMatches("logreader.php")?> ><a href="content.php?iframe=<?=$host?>/logreader.php"><i class="icon-inbox"></i>Log Reader</a></li>
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
						<li class="dropdown">
							<?php if ($loggedin == true) { ?>
							<a href="?logout"><i class="icon-user"></i>Sign Out(<?php echo $_SESSION['user'][1]; ?>)</a>
							<?php } else { ?>
							<a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-user"></i>Sign In<span class="caret"></span></a>						
							<div class="dropdown-menu" style="padding: 10px; padding-bottom: 0px;">
								<form method="post" id="login_form">
									<input style="margin-bottom: 10px;" type="text" placeholder="Username" id="username" name="username"><br/>
									<input style="margin-bottom: 10px;" type="password" placeholder="Password" id="password" name="password"><br/>
									<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember-me" value="yes"><label class="string optional" for="remember"> Remember me</label>
									<input class="btn btn-primary btn-block" type="submit" id="submit" name="Submit" value="Sign In">
									<div id="msgbox"><?=$error?></div>
								</form>					
							</div>
							<?php } ?>
						</li>
						<li><a id="close" class="close" href="javascript:void(0);">X</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="iframe" name="iframe"><iframe></iframe>
	</div>
</body>
</html>
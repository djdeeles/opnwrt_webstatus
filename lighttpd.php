<?php require_once 'data.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- 
  Pretty automatically updating lighttpd server status by Markus Olsson 
  http://www.freakcode.com/projects/pretty-lighty-status-page
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Server status</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.ico">
	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>	
	<script src="js/lighttpd.js"></script>	
</head>
<body>
	<div class="container">
		<?php if ($loggedin == true) { ?>	
		<div id="header" class="section">
			<div id="last-update"></div>
			<div id="server-name">unknown</div>
			<div id="uptime"></div>
		</div>
		<div id="stats" class="section">
			<h2>Connection statistics</h2>
			<div class="innerSection">
				<h3>5s sliding average</h3>
				<div class="contents">
					<div class="valueContainer">
						<h4>Requests</h4>
						<div id="requests-avg-5s"></div>
					</div>
					<div class="valueContainer">
						<h4>Traffic</h4>
						<div id="traffic-avg-5s"></div>
					</div>
				</div>
			</div>
			<div class="innerSection">
				<h3>average</h3>
				<div class="contents">
					<div class="valueContainer">
						<h4>Requests</h4>
						<div id="requests-avg"></div>
					</div>
					<div class="valueContainer">
						<h4>Traffic</h4>
						<div id="traffic-avg"></div>
					</div>
				</div>
			</div>
			<div class="innerSection">
				<h3>total</h3>
				<div class="contents">
					<div class="valueContainer">
						<h4>Requests</h4>
						<div id="requests-total"></div>
					</div>
					<div class="valueContainer">
						<h4>Traffic</h4>
						<div id="traffic-total"></div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div id="connections" class="section">
			<h2>Connections</h2>
			<div class="table-responsive">
					<table id="connectionsTable" class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th>Client</th>
						<th>Time</th>
						<th>Host</th>
						<th>URI</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			</div>
		</div>
	<?php } else { include 'login.php'; } ?>	
	</div>
</body>
</html>

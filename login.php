<?php require_once 'data.php'; ?>
<script src="js/main.js"></script>	
<div class="row">
	<div class="col-md-6">		
		<h4><img class="img-responsive" src="img/cc.png" alt="aCC Server"></h4>
		<h4><b>Please Login</b></h4>
		<p><b>Staff:</b> Please login using the credentials supplied . If you do not have an account or need to reset your password, please send an email to the ServiceDesk.</p>
		<p><small>(Cookies must be enabled in your browser)</small></p>
	</div>
	<div class="col-md-6">
		<form class="form" method="post" id="login_form" onsubmit="return validate(this);">
			<h4><b>Login here using your username and password</b></h4>
			<?=$login?>
			<input class="form-control" style="margin-bottom: 10px;" type="text" placeholder="Username" id="username" name="username">
			<input class="form-control" style="margin-bottom: 10px;" type="password" placeholder="Password" id="password" name="password">
			<input style="float: left; margin-right: 10px;" type="checkbox" name="remember" id="remember" value="yes"><label class="string optional" for="remember"> Remember me</label>	
			<input class="btn btn-default btn-primary btn-block" type="submit" id="submit" name="Submit" value="Sign In">
		</form>
	</div>
</div>
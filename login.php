<?php
require_once 'functions.php'; 

if( session_id() == null ) { session_start(); }

if (isset($_GET['logout'])) {
	logger('logout');
	setcookie("authentication", null, time()-1 , "/" , ".".preg_replace('/^www\./','', $_SERVER['HTTP_HOST']));
	session_destroy();
	$loggedin = false;
	header("Location: ". $_SERVER['HTTP_REFERER']);
	return;
}

if (!empty($_POST)){
	$username = $_POST["username"];
	$password = sha1($_POST["password"]);
	$user = checkuser($username, $password);
	if ($user[0] > 0 && $user[0] != null && $user[0] != 0 ) {
		$_SESSION['authenticated'] = true;
		$_SESSION['user'] = $user; 
		$loggedin = true;
		logger('login');
		setcookie("dynamicUpdates", getoption($user[0],"refresh")[0], time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $_SERVER['HTTP_HOST'])); 
		if (isset($_POST['remember'])) { setcookie("authentication", serialize($user), time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $_SERVER['HTTP_HOST'])); }
		header("Location: ". $_SERVER['HTTP_REFERER']);
	}
	else {
		$error = 'Incorrect username or password';
		logger('wrong password');
		$loggedin = false ;
	}
}

$loggedin = checklogin();

?>
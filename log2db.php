<?php
include_once 'conn.php';
include_once 'functions.php';

// Open directory, and proceed to read its contents  
foreach(glob("/www/log/*.log") as $filename)  
{  
	if (filesize($filename) != 0) {

		switch ($filename) {
	    case "/www/log/lighttpd.log":
	        $logtype = "1";
	        $dateparse = ": (";
	        break;
	    case "/www/log/php_errors.log":
	        $logtype = "2";
	        $dateparse = array("[","] ");
	        break;
	    case "/www/log/minidlna.log":
	        $logtype = "3";
	        $dateparse = array("[","] ");
	        break;
	    case "/www/log/wifimanager.log":
	        $logtype = "4";
	        $dateparse = array("[","] ");
	        break;
	    case "/www/log/adblock.log":
	        $logtype = "5";
	        $dateparse = array("[","] ");
	        break;
	    default:
	    	continue;
		}
		
		foreach (file($filename) as $line) {
			if($logtype != 1) { 
				$line = multiexplode($dateparse,$line); 
				$errordate = date('Y-m-d H:i:s',strtotime($line[1]));
				$errorlog = mysql_real_escape_string($line[2]);
			} 
			else {
				$line = explode($dateparse,$line);
				$errordate = date('Y-m-d H:i:s',strtotime($line[0]));
				$errorlog = mysql_real_escape_string($line[1]);
			}
			$query = mysql_query("INSERT INTO System_Logs (logtype,log,errordate) VALUES ($logtype,'$errorlog','$errordate')") or $mysql_error = mysql_error();
		}
		
		if(!$query)	{ 
			echo "Fail <font color='red'>$filename </font><br/>
			<p>$mysql_error</p>";
		}
		else { 
			echo "Success <font color='green'>$filename </font><br/>";
			file_put_contents($filename, "");
		}
	}
} 
?>
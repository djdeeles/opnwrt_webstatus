<?php
require_once 'conn.php';
require_once 'functions.php';

$count=0;

function split_on($string, $num) {
  $length = strlen($string);
  $output[0] = substr($string, 0, $num);
  $output[1] = substr($string, $num, $length );
  return $output;
}

// Open directory, and proceed to read its contents  
foreach(glob("/www/log/*") as $filename)  
{  
  if (filesize($filename) != 0) {

    $cleanup = array('[',']');

    switch ($filename) {
      case "/www/log/lighttpd.log":
          $logtype = "1";
          $dateparse = 21;
          $cleanup = array('[',']',':');
          break;
      case "/www/log/php_errors.log":
          $logtype = "2";
          $dateparse = 39;
          break;
      case "/www/log/minidlna.log":
          $logtype = "3";
          $dateparse = 22;
          break;
      case "/www/log/wifimanager.log":
          $logtype = "4";
          $dateparse = 22;
          break;
      case "/www/log/adblock.log":
          $logtype = "5";
          $dateparse = 22;
          break;
      case "/www/log/system.log":
          $logtype = "6";
          $dateparse = 25;
          break;
      case "/www/log/log.txt":
          $logtype = "7";
          $dateparse = 20;
          break;
      default:
        $logtype = "0";
        break;
    }
    if($logtype != "0") {
      foreach (file($filename) as $line) {        
          $line = split_on($line, $dateparse);
          $logdate = date('Y-m-d H:i:s',strtotime(str_replace($cleanup, "", $line[0])));
          $log = mysql_real_escape_string($line[1]);
          $count++;
          $query = mysql_query("INSERT INTO System_Logs (logtype,log,logdate) VALUES ($logtype,'$log','$logdate')") or $mysql_error = mysql_error();
      }
      
      if(!$query) { 
        mysql_query("INSERT INTO System_Logs (logtype,log) VALUES ('8','$mysql_error')");
        echo "Fail <font color='red'>$filename </font><br/>
        <p>$mysql_error</p>";
      }
      else { 
        echo "$filename --> updated<br/>";
        file_put_contents($filename, "");
      }
    }
  }
}
if ($count != 0 ) {  
  echo "<b>Total $count enries added.</b>";
} else {
  echo "Nothing new :)";
}
?>
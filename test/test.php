<?php
  function test_1($count = 100) {
    $time_start = microtime(true);
    
    for ($i=0; $i < $count; $i++) {
      //fonksiyon
    }
    return number_format((microtime(true) - $time_start), 3);
  }  
  
  $total = 0;
  $functions = get_defined_functions();
  $line = str_pad("-",38,"-");
  echo "<pre>$line\n|".str_pad("PHP BENCHMARK SCRIPT",36," ",STR_PAD_BOTH)."|\n$line\nStart : ".date("Y-m-d H:i:s")."\nServer : {$_SERVER['SERVER_NAME']}@{$_SERVER['SERVER_ADDR']}\nPHP version : ".PHP_VERSION."\nPlatform : ".PHP_OS. "\n$line\n";
  foreach ($functions['user'] as $user) {
    if (preg_match('/^test_/', $user)) {
      $total += $result = $user();
            echo str_pad($user, 25) . " : " . $result ." sec.\n";
        }
  }
  echo str_pad("-", 38, "-") . "\n" . str_pad("Total time:", 25) . " : " . $total ." sec.</pre>";
?>
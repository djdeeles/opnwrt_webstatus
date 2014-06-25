<?php

$host = $_SERVER['HTTP_HOST'];
$workingdir = dirname($_SERVER['PHP_SELF']);
$userid = $_SESSION['user'][0];

$hostname = "aCC Server";
$refreshRate = "2500"; // ms
$version = "v1.3";
$interface = "eth0.2"; // interface to display data rate
$WANrxlimit = "1280"; // kb/s
$WANtxlimit = "128"; // kb/s
$LANrxlimit = "12500"; // kb/s
$LANtxlimit = "12500"; // kb/s

?>
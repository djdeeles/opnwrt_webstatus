<?php

    error_reporting(E_ALL | E_NOTICE);

    $locale = 'tr_TR.UTF-8';
    $language = 'en';
    $version = '2.0';

    // Set local timezone
    date_default_timezone_set("Europe/Istanbul");

    // list of network interfaces monitored by vnStat
    $iface_list = array('eth0.2', 'br-lan');

    //
    // optional names for interfaces
    // if there's no name set for an interface then the interface identifier
    // will be displayed instead
    //
    $iface_title['eth0.2'] = 'Wan';
    $iface_title['br-lan'] = 'Lan';

    $vnstat_bin = '/usr/bin/vnstat';
    $data_dir = './etc/vnstat';

?>

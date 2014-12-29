<?php
$start =  microtime(true);

    require 'config.php';
    require 'localize.php';
    require 'vnstat.php';

    validate_input();

    function write_menu()
    {
        global $iface, $page, $script;
        global $iface_list, $iface_title;
        global $page_list, $page_title;
        global $defaultpage;

        print "<div class=\"btn-group btn-block\" role=\"group\" aria-label=\"options\">";
        if (!$defaultpage) {   
            	print "<a class=\"btn btn-default\" href=\"$script\"><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></a>";
        } else {        	
            	print "<a class=\"btn btn-default active\" href=\"$script\"><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></a>";
        }
        foreach ($iface_list as $if)
        {
            if ($iface == $if && !$defaultpage) {
            	print "<a class=\"btn btn-default active\" href=\"$script?if=$if\">";
            } else {
            	print "<a class=\"btn btn-default\" href=\"$script?if=$if\">";
            }
            if (isset($iface_title[$if]))
            {
                print $iface_title[$if];
            }
            else
            {
                print $if;
            }
            print "</a>";            
        }
        print "</div>";
        if(!$defaultpage) {
            print "<div class=\"btn-group btn-block\" role=\"group\" aria-label=\"options\">";
            foreach ($page_list as $pg)
            {
            	if ($page == $pg) {
            		print "<a class=\"btn btn-default active\" href=\"$script?if=$iface&amp;page=$pg\">".$page_title[$pg]."</a>\n";
            	} else {
                	print "<a class=\"btn btn-default\" href=\"$script?if=$iface&amp;page=$pg\">".$page_title[$pg]."</a>\n";
            	}
            }
            print "</div>";        	
        }
    }

    function kbytes_to_string($kb)
    {
        $units = array('TB','GB','MB','KB');
        $scale = 1024*1024*1024;
        $ui = 0;

        while (($kb < $scale) && ($scale > 1))
        {
            $ui++;
            $scale = $scale / 1024;
        }
        return sprintf("%0.2f %s", ($kb/$scale),$units[$ui]);
    }

    function write_summary($topdays)
    {
        global $summary,$top,$day,$hour,$month;

        $trx = $summary['totalrx']*1024+$summary['totalrxk'];
        $ttx = $summary['totaltx']*1024+$summary['totaltxk'];

        //
        // build array for write_data_table
        //
        $sum[0]['act'] = 1;
        $sum[0]['label'] = T('This hour');
        $sum[0]['rx'] = $hour[0]['rx'];
        $sum[0]['tx'] = $hour[0]['tx'];

        $sum[1]['act'] = 1;
        $sum[1]['label'] = T('This day');
        $sum[1]['rx'] = $day[0]['rx'];
        $sum[1]['tx'] = $day[0]['tx'];

        $sum[2]['act'] = 1;
        $sum[2]['label'] = T('This month');
        $sum[2]['rx'] = $month[0]['rx'];
        $sum[2]['tx'] = $month[0]['tx'];

        $sum[3]['act'] = 1;
        $sum[3]['label'] = T('All time');
        $sum[3]['rx'] = $trx;
        $sum[3]['tx'] = $ttx;

        write_data_table(T('Summary'), $sum);
        if($topdays) { 
        	write_data_table(T('Top 10 days'), $top);
        }
    }

    function write_data_table($caption, $tab)
    {
        print "<div class='table-responsive'> <table class=\"table table-bordered table-hover table-striped\">\n";
        print "<tr>";
        print "<th class=\"active\">$caption</th>";
        print "<th class=\"active\">".T('In')."</th>";
        print "<th class=\"active\">".T('Out')."</th>";
        print "<th class=\"active\">".T('Total')."</th>";
        print "<th class=\"active ratio\">".T('Ratio')."</th>";
        print "</tr>\n";

        for ($i=0; $i<count($tab); $i++)
        {
            if ($tab[$i]['act'] == 1)
            {
                $t = $tab[$i]['label'];
                $rx = kbytes_to_string($tab[$i]['rx']);
                $tx = kbytes_to_string($tab[$i]['tx']);
                $total = kbytes_to_string($tab[$i]['rx']+$tab[$i]['tx']);
                $ratio = $tab[$i]['rx'] / ($tab[$i]['rx'] + $tab[$i]['tx']) * 100;
                
                print "<tr>";
                print "<td>$t</td>";
                print "<td>$rx</td>";
                print "<td>$tx</td>";
                print "<td>$total</td>";
                print "<td class=\"ratio\"><div class=\"ratio\"><div style=\"width: $ratio%;\"></div></div></td>";
                print "</tr>\n";
             }
        }
        print "</table></div>\n";
    }
    
    function write_graph_data($data)
    {
        $receivedData = [
            'className' => '.received',
            'data'      => [],
        ];
        $sentData = [
            'className' => '.sent',
            'data'      => [],
        ];
        foreach ($data as $line) {                  
            $receivedData['data'][] = ['x' => $line['img_label'], 'y' => $line['rx']*1024];
            $sentData['data'][] = ['x' => $line['img_label'], 'y' => $line['tx']*1024];
        }
        $receivedData['data'] = array_reverse($receivedData['data']);
        $sentData['data'] = array_reverse($sentData['data']);
        return json_encode($receivedData) . "," . json_encode($sentData); 
    }

    if (!$defaultpage) { 
    	get_vnstat_data();
    }

    //
    // html start
    //
    header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>aCC Server Statistics</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/vnstat.css" rel="stylesheet" type="text/css">
  <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/custom.css" rel="stylesheet" type="text/css">
  <script src="../js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="../js/bootstrap.min.js" type="text/javascript"></script>
  </style>
</head>
<body>
	<div class="container vnstat">
		<div class="row">
        <?php write_menu(); ?>
        </div>
		<div class="row">
		<?php if (!$defaultpage) { ?>
			<h2>
                <b><?php print T('Traffic data for')." $iface_title[$iface]";?></b>
            </h2>
			<?php                
			if ($page == 's') {
                    write_summary(true);
            } else { ?>
            <div class="graph table-bordered">
                <link href="css/xcharts.min.css" rel="stylesheet" />
                <script type="text/javascript" src="js/d3.min.js"></script>
                <script type="text/javascript" src="js/xcharts.min.js"></script>
                <figure id="chart"></figure>
            </div>            
            <?php				
				if ($page == 'h')
				{
					$graph_data = write_graph_data($hour);
					write_data_table(T('Last 24 hours'), $hour);
				}
				else if ($page == 'd')
				{
					$graph_data = write_graph_data($day);
					write_data_table(T('Last 30 days'), $day);
				}
				else if ($page == 'm')
				{
					$graph_data = write_graph_data($month);
					write_data_table(T('Last 12 months'), $month);
				}
            }
	     } else { 
			foreach ($iface_list as $iface) {
				get_vnstat_data();
				print "<h2><b>$iface_title[$iface]</b></h2>" ;
				write_summary(false);
			}
	     } ?>
		</div>
		<div class="row footer"><a href="http://www.cetincone.tk">aCC Stats</a> 2.0 <br/>
			<small><b>Page generated in</b> <?php echo round((microtime(true) - $start), 2); ?></small>
		</div>
        <?php if (isset($graph_data)) { ?>
		<script type="text/javascript">
            var chart = new xChart(
                'bar',
                {
                    "xScale": "ordinal", //ordinal,linear,time,exponential
                    "yScale": "linear", //ordinal,linear,time,exponential
                    "type": "line-dotted", //bar, cumulative,line,line-dotted
                    "main": [ <?php echo $graph_data; ?> ]
                },
                '#chart',
                {
                    "tickHintX": -24,
                    "tickHintY": 8,
                    "paddingTop" : "5",
                    "paddingBottom" : "15",
                    "paddingLeft" : "60",
                    "paddingRight" : "0",
                    "tickFormatY": function (y) {
                        var units = ['B', 'KiB', 'MiB', 'GiB', 'TiB'];
                        var pow   = Math.floor((y ? Math.log(y) : 0) / Math.log(1024));
                        pow = Math.min(pow, units.length - 1);
                        return (Math.round(y / (1 << (10 * pow)) * 10) / 10) + ' ' + units[pow];
                    }
                }
            );
        </script>        
        <?php } ?>
	</div>
</body>
</html>

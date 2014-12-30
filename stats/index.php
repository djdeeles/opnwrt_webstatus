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
            	print "<a class=\"btn btn-default\" href=\"$script\"><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></a>";
        } else {        	
            	print "<a class=\"btn btn-default active\" href=\"$script\"><span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span></a>";
        }
        foreach ($iface_list as $if)
        {	
        	$page = isset($_GET['page']) ? $_GET['page'] : 's';
            if ($iface == $if && !$defaultpage) {
            	print "<a class=\"btn btn-default active\" href=\"$script?if=$if&amp;page=$page\">";
            } else {
            	print "<a class=\"btn btn-default\" href=\"$script?if=$if&amp;page=$page\">";
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

    function write_summary()
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
    
    function write_graph_data($data,$graph,$type)
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
        	$x="";
        	is_numeric($line['img_label']) ? $x+=$line['img_label'] : $x=$line['img_label'];
            $receivedData['data'][] = ['x' => $x, 'y' => $line['rx']*1024];
            $sentData['data'][] = ['x' => $x, 'y' => $line['tx']*1024];
        }
        $receivedData['data'] = array_reverse($receivedData['data']);
        $sentData['data'] = array_reverse($sentData['data']);
        $graph_data = json_encode($receivedData) . "," . json_encode($sentData);
        return print "<div class='graph-wrapper table-bordered'><figure class='graph' id='$graph'></figure></div> 
    				   <script type='text/javascript'>
			            var chart = new xChart(
			                'bar',
			                {
			                    'xScale': 'ordinal', //ordinal,linear,time,exponential
			                    'yScale': 'linear', //ordinal,linear,time,exponential
			                    'type': '$type', //bar, cumulative,line,line-dotted
			                    'main': [ $graph_data ]
			                },
			                '#$graph',
			                {
			                    'tickHintX': -25,
			                    'paddingTop' : 5,
			                    'paddingBottom' : 15,
			                    'paddingLeft' : 60,
			                    'paddingRight' : 0,
			                    'tickFormatY': function (y) { 
									var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
								    if (y == 0) return '0 B';
								    var i = parseInt(Math.floor(Math.log(y) / Math.log(1024)));
								    if (i == 0) return bytes + ' ' + sizes[i]; 
								    return (y / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
						        }
			                }
			            );
			          </script>";
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
  <link href="css/xcharts.min.css" rel="stylesheet" />
  <script src="../js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="../js/bootstrap.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/d3.min.js"></script>
  <script type="text/javascript" src="js/xcharts.min.js"></script>
</head>
<body>
	<div class="container vnstat">
		<div class="row">
        <?php write_menu(); ?>
        </div>
		<div class="row">
		<?php if (!$defaultpage) { ?>
			<h2>
                <b><?php print T('Traffic data for')." $iface_title[$iface]"; ?></b>
            </h2>
			<?php                
			if ($page == 's') {
                    write_summary();
					print "<p><b>".T('Last 12 months')."</b></p>" ;
					write_graph_data($month,"month","cumulative");
					print "<p><b>".T('Last 30 days')."</b></p>" ;
					write_graph_data($day,"day","cumulative");
					print "<p><b>".T('Last 24 hours')."</b></p>" ;
					write_graph_data($hour,"hour","cumulative");
        			write_data_table(T('Top 10 days'), $top);
            } else { 		
				if ($page == 'h')
				{
					write_graph_data($hour,"hour","line-dotted");
					write_data_table(T('Last 24 hours'), $hour);
				}
				else if ($page == 'd')
				{
					$graph_data = write_graph_data($day,"day","line-dotted");
					write_data_table(T('Last 30 days'), $day);
				}
				else if ($page == 'm')
				{
					$graph_data = write_graph_data($month,"month","line-dotted");
					write_data_table(T('Last 12 months'), $month);
				}
            }
	     } else { 
			foreach ($iface_list as $iface) {
				get_vnstat_data();
				print "<h2><b>$iface_title[$iface]</b></h2>" ;
				write_summary();
			}
	     } ?>
		</div>
		<div class="row footer"><a href="http://www.cetincone.tk">aCC Stats 2.0</a><br/>
			<small><b>Page generated in</b> <?php echo round((microtime(true) - $start), 2); ?> seconds.</small>
		</div>
	</div>
</body>
</html>

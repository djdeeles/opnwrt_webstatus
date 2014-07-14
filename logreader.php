<?php 
$start =  microtime(true);
require_once 'conn.php';
require_once 'login.php';
require('data.php');
$per_page = 15; 
$logtype  = 0;
$page     = 1;
$sortby	  = "id";
$sort 	  = "desc";

if ($loggedin) {

	if ($_GET['page']) { $page= $_GET['page']; }
	if ($_GET['logtype']) { $logtype= $_GET['logtype']; }
	if ($_GET['sortby']) { $sortby= $_GET['sortby']; }
	if ($_GET['sort']) { $sort= $_GET['sort']; }
	if (isset($_GET['log2db'])) { 
	  	include('log2db.php');
		header("Location: ". $_SERVER['HTTP_REFERER']);
	}
}

function displaylogs() {
	global $page,$logtype,$per_page,$sortby,$sort;
	//getting table contents
    $start  = ($page-1)*$per_page;
    $result = mysql_query("select * from System_Logs where logtype=$logtype order by $sortby $sort limit $start,$per_page");

    if(mysql_num_rows($result) > 0) {
      while($row = mysql_fetch_array($result))
      {
        $id         = $row['id'];
        $log        = $row['log'];
        $logdate  	= $row['logdate'];

        $logs 		.= "<tr>
				          <td>$id</td>
				          <td>$log</td>
				          <td>$logdate</td>
				        </tr>";
      } //End while
    }
    echo $logs;
}
function pagination() {
	global $page,$logtype,$per_page,$sortby,$sort;
	//getting number of rows and calculating no of pages
	$result = mysql_query("select count(*) from System_Logs where logtype=$logtype");
	$count  = mysql_fetch_row($result);
	$pages  = ceil($count[0]/$per_page);
	if ($pages > 1) {              
		//show prev & first
		if ($page > 1) { $pagination = "<li class='prev'><a href='logreader.php?logtype=$logtype"."&page=1"."&sortby=$sortby"."&sort=$sort"."'>First</a></li>
		                       			<li class='prev'><a href='logreader.php?logtype=$logtype"."&page=".($page -1)."&sortby=$sortby"."&sort=$sort"."'>«</a></li>"; }
		//Show page links
		for($i=1; $i<=$pages; $i++)
		{
		  if( $i > ($page + 3) or $i < ($page - 3) ) { continue; }
		  elseif ($i == $page ) { $pagination .= '<li class="active"><a href="#">'.$i.'</a></li>'; }
		  else { $pagination .= "<li><a href='logreader.php?logtype=$logtype"."&page=$i"."&sortby=$sortby"."&sort=$sort"."'>".$i."</a></li>"; }
		}
		//show next & last
		if ($page < $pages) { $pagination .= "<li class='Next'><a href='logreader.php?logtype=$logtype"."&page=".($page +1)."&sortby=$sortby"."&sort=$sort"."'>»</a></li>
		                            		  <li class='prev'><a href='logreader.php?logtype=$logtype"."&page=$pages"."&sortby=$sortby"."&sort=$sort"."'>Last(".$pages.")</a></li>"; }
	}
	echo $pagination;
}
function sortdata($asortby,$sortname) {
	global $page,$logtype,$sort,$sortby;
	if ($sort=="desc"){	$asort = "asc"; } elseif ($sort=="asc") { $asort="desc"; }	
	if ($sortby==$asortby){ $headerstyle = "style='color:orange;'"; }
	$header = "<a $headerstyle href='logreader.php?logtype=$logtype"."&page=$page"."&sortby=$asortby"."&sort=$asort'>$sortname"."</a>";
	echo $header;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?=$hostname?> Logreader</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
  <link href="css/custom.css" rel="stylesheet" type="text/css">
  <link rel="shortcut icon" href="favicon.ico">
  <script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js" type="text/javascript"></script>  
</head>
<body> 
  <div class="container" style="margin-top:20px;">
    <div style="float:left;margin-bottom:10px;">
      <span style="font-weight:bold;">Select log type: </span>
      <select style="margin: 0;" onchange="if (this.value) window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?logtype=' + this.value">
        <option value="0"<?php echo $logtype == '0' ? ' selected="selected" disabled' : ' disabled'?>>select log type</option>
        <option value="1"<?php echo $logtype == '1' ? ' selected="selected"' : ''?>>lighttpd</option>
        <option value="2"<?php echo $logtype == '2' ? ' selected="selected"' : ''?>>php_errors</option>
        <option value="3"<?php echo $logtype == '3' ? ' selected="selected"' : ''?>>minidlna</option>
        <option value="4"<?php echo $logtype == '4' ? ' selected="selected"' : ''?>>wifimanager</option>
        <option value="5"<?php echo $logtype == '5' ? ' selected="selected"' : ''?>>adblock</option>
        <option value="6"<?php echo $logtype == '6' ? ' selected="selected"' : ''?>>log2db</option>
      </select>    
    </div>    
    <a style="float:right;" href="<?php echo $_SERVER['PHP_SELF']; ?>?log2db" title="Refresh Logs" data-toggle="modal" class="btn btn-small">Refresh Logs</a>
    <?php 	
    	if(!$loggedin) { echo "<p style='float:left;width:100%;font-weight:bold;'>Please Login</p>"; }
    	elseif($logtype != 0 && $loggedin) { 
    ?>
    <table class='table logs'>
      <tr>
        <th width="4%"><?php sortdata("id","Id"); ?></th>
        <th width="82%"><?php sortdata("log","Log"); ?></th>
        <th width="14%"><?php sortdata("logdate","Date"); ?></th>
      </tr>
      	<?php displaylogs(); ?>
    </table>
    <div class="pagination">  
      <ul>
      	<?php pagination(); ?>
      </ul> 
    </div>
    <?php }  else { echo "<p style='float:left;width:100%;font-weight:bold;'>No Result</p>"; } ?>    
    <p style="float:left;width:100%;"><small><b>Page generated in</b> <?php echo round((microtime(true) - $start), 2); ?> seconds.</small></p>
  </div>
</body>
</html>
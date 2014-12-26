<?php 

$start =  microtime(true);
require_once 'conn.php';
require_once 'login.php';
require('data.php');
$per_page = 20; 
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
		ob_start();
		include( 'log2db.php' );
		$alert = ob_get_contents();
		ob_end_clean();
	}

	if($_POST["checkbox"]) {
		$del = $_POST['checkbox'];
		$idsToDelete = implode($del, ', ');
		$result = mysql_query("DELETE FROM System_Logs WHERE id in ($idsToDelete)");
		$alert = count($del). " entries deleted.";
	}

	if(isset($_GET['cleanoldrec'])) {
		mysql_query("DELETE FROM System_Logs WHERE logdate < DATE_SUB(NOW(), INTERVAL 6 MONTH)");
		$alert = mysql_affected_rows() . " old entries deleted.";
	}

	if(isset($_POST['search'])) {
		$search = $_POST['search'];
	}
	elseif (isset($_GET['search'])) {
		$search = $_GET['search'];
	}
}

function displaylogs() {
	global $page,$logtype,$per_page,$sortby,$sort,$search;
	//getting table contents
	$start  = ($page-1)*$per_page;
	if ($search) {
		$result = mysql_query("select * from System_Logs where logtype=$logtype and log LIKE '%" . $search . "%' order by $sortby $sort limit $start,$per_page");
	} else {
		$result = mysql_query("select * from System_Logs where logtype=$logtype order by $sortby $sort limit $start,$per_page");
	}
	if( mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result))
		{
			$id         = $row['id'];
			$log        = $row['log'];
			$logdate  	= $row['logdate'];

			$logs 		.= "<tr>
			<td>$id</td>
			<td>$log</td>
			<td>$logdate</td>
			<td><input type='checkbox' name='checkbox[]' value='$id' style='margin:0;'></td>
		</tr>";
      } //End while
  } else {
  	$logs = "<tr><td colspan='4'>No Result</td></tr>";
  }
  echo $logs;
}

function pagination() {
	global $page,$logtype,$per_page,$sortby,$sort,$count,$search;
	//getting number of rows and calculating no of pages
	if ($search) {
		$result = mysql_query("select count(*) from System_Logs where logtype=$logtype and log LIKE '%" . $search . "%'");		
	} else {
		$result = mysql_query("select count(*) from System_Logs where logtype=$logtype");
	}
	$count  = mysql_fetch_row($result);
	$pages  = ceil($count[0]/$per_page);
	if ($search) { $ifsearch = "&search=$search"; }
	if ($pages > 1) {
		$pagination = "<ul class='pagination' style='float:left;'>";
		//show prev & first
		if ($page > 1) { $pagination .= "<li class='prev'><a href='logreader.php?logtype=$logtype"."&page=1"."&sortby=$sortby"."&sort=$sort".$ifsearch."'>First</a></li>
			<li class='prev'><a href='logreader.php?logtype=$logtype"."&page=".($page -1)."&sortby=$sortby"."&sort=$sort".$ifsearch."'>«</a></li>"; }
		//Show page links
		for($i=1; $i<=$pages; $i++)
		{
			if( $i > ($page + 3) or $i < ($page - 3) ) { continue; }
			elseif ($i == $page ) { $pagination .= '<li class="active"><a href="#">'.$i.'</a></li>'; }
			else { $pagination .= "<li><a href='logreader.php?logtype=$logtype"."&page=$i"."&sortby=$sortby"."&sort=$sort".$ifsearch."'>".$i."</a></li>"; }
		}
		//show next & last
		if ($page < $pages) { $pagination .= "<li class='Next'><a href='logreader.php?logtype=$logtype"."&page=".($page +1)."&sortby=$sortby"."&sort=$sort".$ifsearch."'>»</a></li>
			<li class='prev'><a href='logreader.php?logtype=$logtype"."&page=$pages"."&sortby=$sortby"."&sort=$sort".$ifsearch."'>Last(".$pages.")</a></li>"; }
		$pagination .= "</ul>";
	}
	echo $pagination;
}

function sortdata($asortby,$sortname) {
	global $page,$logtype,$sort,$sortby;
	if ($sort=="desc"){	
		$asort = "asc"; 
		if ($sortby==$asortby){ $headerstyle = "style='color:orange;'"; $carret = "<span class='caret caret'></span>"; }
	} elseif ($sort=="asc") { 
		$asort="desc"; 		
		if ($sortby==$asortby){ $headerstyle = "style='color:orange;'"; $carret = "<span class='caret caret-reversed'></span>"; }
	}	
	$header = "<a $headerstyle href='logreader.php?logtype=$logtype"."&page=$page"."&sortby=$asortby"."&sort=$asort'>$sortname $carret"."</a>";
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
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="favicon.ico">
	<!--<script src="http://code.jquery.com/jquery.min.js" type="text/javascript"></script>-->
	<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
			$('#select-all').click(function(event) {   
				if(this.checked) {
	            // Iterate each checkbox
	            $(':checkbox').each(function() {
	            	this.checked = true;                        
	            });
	        }
	        if(!this.checked) {
	            // Iterate each checkbox
	            $(':checkbox').each(function() {
	            	this.checked = false;                        
	            });
	        }
	    });
		});

		function validate() {
			obj = document.search;
			if (obj.search.value.length < 4) {
				alert("Minimum 4 characters needed.");
				return false;
			} else {
				return true;
			}
		}
	</script>
</head>
<body> 
	<div class="container" style="margin-top:20px;">
		<?php if($alert) { echo "<div class='alert alert-warning alert-dismissible' role='alert'>$alert <button type='button' class='close' aria-label='Close' data-dismiss='alert'><span aria-hidden='true'>&times;</span></button></div>"; } ?>
		<div style="float:left;margin-bottom:10px;">
			<select class="form-control" onchange="if (this.value) window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?logtype=' + this.value">
				<option value="0"<?php echo $logtype == '0' ? ' selected="selected" disabled' : ' disabled'?>>Select log type</option>
				<option value="6"<?php echo $logtype == '6' ? ' selected="selected"' : ''?>>system</option>
				<option value="1"<?php echo $logtype == '1' ? ' selected="selected"' : ''?>>lighttpd</option>
				<option value="2"<?php echo $logtype == '2' ? ' selected="selected"' : ''?>>php_errors</option>
				<option value="3"<?php echo $logtype == '3' ? ' selected="selected"' : ''?>>minidlna</option>
				<option value="4"<?php echo $logtype == '4' ? ' selected="selected"' : ''?>>wifimanager</option>
				<option value="5"<?php echo $logtype == '5' ? ' selected="selected"' : ''?>>adblock</option>
				<option value="7"<?php echo $logtype == '7' ? ' selected="selected"' : ''?>>pyload</option>
				<option value="8"<?php echo $logtype == '8' ? ' selected="selected"' : ''?>>log2db</option>
			</select>    
		</div>
		<?php if(!$loggedin) { ?> 
		<p style='float:left;width:100%;font-weight:bold;'>Please Login</p>
		<?php } elseif($loggedin && $logtype != 0) { ?>
		<form action='<? echo "logreader.php?logtype=$logtype"; ?>' name="search" method='POST' onsubmit="return validate()" class="col-lg-4">
			<div class="input-group">
				<input class="form-control" aria-label="Search" type="text" name="search" class="input-medium" value="<?php echo $search; ?>">
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default">Search</button>
					<a href="<?php echo "logreader.php?logtype=$logtype" ?>" title="X" data-toggle="modal" class="btn btn-default" ><span aria-hidden='true'>&times;</span></a>
				</div>
			</div>
		</form>
		<form action='<? echo "logreader.php?logtype=$logtype"."&page=$page"."&sortby=$sortby"."&sort=$sort"; ?>' method='POST'>			
			<div class="btn-group" class="col-lg-4" style="float:right;" >
				<a href="<?php echo "logreader.php?logtype=$logtype"."&page=$page"."&sortby=$sortby"."&sort=$sort&log2db" ?>" title="Refresh Logs" class="btn btn-default" onclick="return confirm('Are you sure you want to refresh logs ?')">Refresh Logs</a>
				<a href="<?php echo "logreader.php?cleanoldrec&logtype=$logtype"."&page=$page"."&sortby=$sortby"; ?>" title="Clear Old Logs" class="btn btn-default" onclick="return confirm('Are you sure you want to delete entries older than 6 months ?')">Delete Older Than 6 Months</a>
				<input type='submit' value='Delete Selected' class="btn btn-default" onclick="return confirm('Are you sure you want to delete selected entries ?')">
			</div>
			<table class='table logs' style="float:left;">
				<tr>
					<th width="4%"><?php sortdata("id","Id"); ?></th>
					<th width="79%"><?php sortdata("log","Log"); ?></th>
					<th width="14%"><?php sortdata("logdate","Date"); ?></th>
					<th width="3%" align="center"><input type='checkbox' name='select-all' id='select-all' style='margin:0;'></th>
				</tr>
				<?php displaylogs(); ?>
			</table>
		</form>
		<?php pagination(); ?>
		<div class="pagination" style="float:right;">  
			Total records: <?php echo $count[0]; ?>
		</div>
		<?php } ?>    
		<p style="float:left;width:100%;"><small><b>Page generated in</b> <?php echo round((microtime(true) - $start), 2); ?> seconds.</small></p>
	</div>
</body>
</html>
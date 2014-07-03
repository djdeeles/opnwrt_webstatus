<?php 
include('conn.php');
include('data.php');
$per_page = 10; 
$logtype  = 2; // bu biryerden seçilecek
$page     = 1;

if ($_GET['page']) { $page= $_GET['page']; }
if ($_GET['logtype']) { $logtype= $_GET['logtype']; }
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
  <div class="container">
    <div class="jumbotron" id="content">
    <select onchange="if (this.value) window.location.href='logreader.php?logtype=' + this.value">
          <option value="1"<?php echo $logtype == '1' ? 'selected' : ''?>>lighttpd</option>
          <option value="2"<?php echo $logtype == '2' ? 'selected' : ''?>>php_errors</option>
          <option value="3"<?php echo $logtype == '3' ? 'selected' : ''?>>minidlna</option>
          <option value="4"<?php echo $logtype == '4' ? 'selected' : ''?>>wifimanager</option>
          <option value="5"<?php echo $logtype == '5' ? 'selected' : ''?>>adblock</option>
        </select>
    <table id='tbl' class='table'>
      <th><b>Id</b></th>
      <th><b>Error</b></th>
      <th><b>Date</b></th>
      <?php
        //getting table contents
        $start  = ($page-1)*$per_page;
        $result = mysql_query("select * from System_Logs where logtype=$logtype order by id limit $start,$per_page");

        while($row = mysql_fetch_array($result))
        {
          $id         = $row['id'];
          $log        = $row['log'];
          $errordate  = $row['errordate'];

          echo "
          <tr>
            <td>$id</td>
            <td>$log</td>
            <td>$errordate</td>
          </tr>";
        } //End while
      ?>
      </table>
    </div>
    <div class="pagination">  
      <ul id="pagination">
        <?php
          //getting number of rows and calculating no of pages
          $result = mysql_query("select count(*) from System_Logs where logtype=$logtype");
          $count  = mysql_fetch_row($result);
          $pages  = ceil($count[0]/$per_page);
          
          //show prev & first
          if ($page > 1) { echo '<li class="prev"><a href="logreader.php?logtype='.$logtype.'&page=1">First</a></li>
                                 <li class="prev"><a href="logreader.php?logtype='.$logtype.'&page='.($page -1).'">Prev</a></li>'; }
          
          //Show page links
          for($i=1; $i<=$pages; $i++)
          {
            if( $i > ($page + 3) or $i < ($page - 3) ) { continue; }
            elseif ($i == $page ) { echo '<li class="active"><a href="#">'.$i.'</a></li>'; }
            else { echo '<li><a href="logreader.php?logtype='.$logtype.'&page='.$i.'">'.$i.'</a></li>'; }
          }

          //show next & last
          if ($page < $pages) { echo '<li class="Next"><a href="logreader.php?logtype='.$logtype.'&page='.($page +1).'">Next</a></li>
                                      <li class="prev"><a href="logreader.php?logtype='.$logtype.'&page='.$pages.'">Last('.$pages.')</a></li>'; }
        ?>
      </ul> 
    </div>
    <div id="loading"></div>
  </div>
</div>
</body>
</html>
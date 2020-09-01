<?php

$connect = new PDO("mysql:host=localhost;dbname=php-ajax", "root", "");

$start_date_error = '';
$end_date_error = '';

if(isset($_POST["export"]))
{
 if(empty($_POST["start_date"]))
 {
  $start_date_error = '<label class="text-danger">Start Date is required</label>';
 }
 else if(empty($_POST["end_date"]))
 {
  $end_date_error = '<label class="text-danger">End Date is required</label>';
 }
 else
 {
  $file_name = 'Order Data.csv';
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename=$file_name");
  header("Content-Type: application/csv;");

  $file = fopen('php://output', 'w');

  $header = array("ID", "SYMBOL", "COMPANY NAME", "ANNOUNCEMENT DATE", "ANNOUNCEMENT TIME", "BSE ANNOUNCEMENT TIME", "ANNOUNCEMENT TYPE", "SHORT DESCRIPTION", "ANNOUNCEMENT");

  fputcsv($file, $header);

  $query = "
  SELECT * FROM nsedata 
  WHERE ANNOUNCEMENT_DATE >= '".$_POST["start_date"]."' 
  AND ANNOUNCEMENT_DATE <= '".$_POST["end_date"]."' 
  ORDER BY ANNOUNCEMENT_DATE DESC
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
   $data = array();
   $data[] = $row["ID"];
   $data[] = $row["SYMBOL"];
   $data[] = $row["COMPANY_NAME"];
   $data[] = $row["ANNOUNCEMENT_DATE"];
   $data[] = $row["ANNOUNCEMENT_TIME"];
   $data[] = $row["BSE_ANNOUNCEMENT_TIME"];
   $data[] = $row["ANNOUNCEMENT_TYPE"];
   $data[] = $row["SHORT_DESCRIPTION"];
   $data[] = $row["ANNOUNCEMENT_TEXT"];
   fputcsv($file, $data);
  }
  fclose($file);
  exit;
 }
}

$query = "SELECT * FROM nsedata ORDER BY ANNOUNCEMENT_DATE DESC;";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<html>
 <head>
  <title>Daterange Mysql Data Export to CSV in PHP</title>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
 </head>
 <body>
  <div class="container box">
   <h1 align="center">Daterange Mysql Data Export to CSV in PHP</h1>
   <br />
   <div class="table-responsive">
    <br />
    <div class="row">
     <form method="post">
      <div class="input-daterange">
       <div class="col-md-4">
        From: <input type="text" name="start_date" class="form-control" readonly />
        <?php echo $start_date_error; ?>
       </div>
       <div class="col-md-4">
        To: <input type="text" name="end_date" class="form-control" readonly />
        <?php echo $end_date_error; ?>
       </div>
      </div>
      <div class="col-md-2">
       <input type="submit" name="export" value="Export" class="btn btn-info" />
      </div>
     </form>
    </div>
    <br />
    <table class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>ID</th> 
       <th>SYMBOL</th>
       <th>COMPANY NAME</th>
       <th>ANNOUNCEMENT DATE</th>
       <th>ANNOUNCEMENT TIME</th>
       <th>BSE ANNOUNCEMENT TIME</th>
       <th>ANNOUNCEMENT TYPE</th>
       <th>SHORT DESCRIPTION</th>
       <th>ANNOUNCEMENT</th>
      </tr>
     </thead>
     <tbody>
      <?php
      foreach($result as $row)
      {
       echo '
       <tr>
        <td>'.$row["ID"].'</td>
        <td>'.$row["SYMBOL"].'</td>
        <td>'.$row["COMPANY_NAME"].'</td>
        <td>'.$row["ANNOUNCEMENT_DATE"].'</td>
        <td>'.$row["ANNOUNCEMENT_TIME"].'</td>
        <td>'.$row["BSE_ANNOUNCEMENT_TIME"].'</td>
        <td>'.$row["ANNOUNCEMENT_TYPE"].'</td>
        <td>'.$row["SHORT_DESCRIPTION"].'</td>
        <td>'.$row["ANNOUNCEMENT_TEXT"].'</td>
       </tr>
       ';
      }
      ?>
     </tbody>
    </table>
    <br />
    <br />
   </div>
  </div>
 </body>
</html>

<script>

$(document).ready(function(){
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 });
});

</script>
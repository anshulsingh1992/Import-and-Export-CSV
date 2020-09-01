<?php

$symbol = $_POST["sym_bol"];
$companyname = $_POST["company_name"];
$announcementdate = $_POST["announcement_date"];
$announcementtime = $_POST["announcement_time"];
$bseannouncementtime = $_POST["bse_announcement_time"];
$announcementtype = $_POST["announcement_type"];
$shortdescription = $_POST["short_description"];
$announcement = $_POST["announce_ment"];

$conn = mysqli_connect("localhost","root","","php-ajax") or die("Connection Failed");

$sql = "INSERT INTO nsedata(SYMBOL, COMPANY_NAME, ANNOUNCEMENT_DATE, ANNOUNCEMENT_TIME, BSE_ANNOUNCEMENT_TIME, ANNOUNCEMENT_TYPE, SHORT_DESCRIPTION, ANNOUNCEMENT_TEXT) VALUES ('{$symbol}','{$companyname}','{$announcementdate}','{$announcementtime}','{$bseannouncementtime}','{$announcementtype}','{$shortdescription}','{$announcement}')";

if(mysqli_query($conn, $sql)){
  echo 1;
}else{
  echo 0;
}


?>

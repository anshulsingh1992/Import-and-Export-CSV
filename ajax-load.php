<?php

$conn = mysqli_connect("localhost","root","","php-ajax") or die("Connection Failed");

$sql = "SELECT * FROM nsedata";
$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");
$output = "";
if(mysqli_num_rows($result) > 0 ){
  $output = '<table border="1" width="100%" cellspacing="0" cellpadding="10px">
              <tr>
                <th width="60px">ID</th>
                <th>SYMBOL</th>
                <th>COMPANY NAME</th>
                <th>ANNOUNCEMENT DATE</th>
                <th>ANNOUNCEMENT TIME</th>
                <th>BSE ANNOUNCEMENT TIME</th>
                <th>ANNOUNCEMENT TYPE</th>
                <th>SHORT DESCRIPTION</th>
                <th>ANNOUNCEMENT</th>
                <th width="90px">Edit</th>
                <th width="90px">Delete</th>
              </tr>';

              while($row = mysqli_fetch_assoc($result)){
                $output .= "<tr><td align='center'>{$row["ID"]}</td><td>{$row["SYMBOL"]}</td> <td>{$row["COMPANY_NAME"]}</td><td>{$row["ANNOUNCEMENT_DATE"]}</td><td>{$row["ANNOUNCEMENT_TIME"]}</td><td>{$row["BSE_ANNOUNCEMENT_TIME"]}</td><td>{$row["ANNOUNCEMENT_TYPE"]}</td><td>{$row["SHORT_DESCRIPTION"]}</td><td>{$row["ANNOUNCEMENT_TEXT"]}</td><td align='center'><button class='edit-btn' data-eid='{$row["ID"]}'>Edit</button></td><td align='center'><button Class='delete-btn' data-id='{$row["ID"]}'>Delete</button></td></tr>";
              }
    $output .= "</table>";

    mysqli_close($conn);

    echo $output;
}else{
    echo "<h2>No Record Found.</h2>";
}
?>

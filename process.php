<?php

//process.php

$connect = new PDO("mysql:host=localhost; dbname=php-ajax", "root", "");

$query = "SELECT * FROM nsedata";

$statement = $connect->prepare($query);

$statement->execute();

echo $statement->rowCount();

?>
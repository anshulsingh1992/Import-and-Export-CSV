<?php

//import.php

header('Content-type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

set_time_limit(0);

ob_implicit_flush(1);

session_start();

if(isset($_SESSION['csv_file_name']))
{
	$connect = new PDO("mysql:host=localhost; dbname=php-ajax", "root", "");

	$file_data = fopen('file/' . $_SESSION['csv_file_name'], 'r');

	fgetcsv($file_data);

	while($row = fgetcsv($file_data))
	{
		$data = array(
			':SYM'	=>	$row[0],
			':COMPANY'	=>	$row[1],
			':DATE'	=>	$row[2],
			':TIME'	=>	$row[3],
			':BSE'	=>	$row[4],
			':TYPE'	=>	$row[5],
			':DESCRIPTION'	=>	$row[6],
			':TEXT'	=>	$row[7]
			
			
		);

		$query = "
		INSERT INTO nsedata (SYMBOL, COMPANY_NAME,ANNOUNCEMENT_DATE,ANNOUNCEMENT_TIME, BSE_ANNOUNCEMENT_TIME,ANNOUNCEMENT_TYPE,SHORT_DESCRIPTION,ANNOUNCEMENT_TEXT) 
    	VALUES (:SYM, :COMPANY, :DATE, :TIME, :BSE, :TYPE,:DESCRIPTION, :TEXT)
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		sleep(1);

		if(ob_get_level() > 0)
		{
			ob_end_flush();
		}
	}

	unset($_SESSION['csv_file_name']);
}

?>
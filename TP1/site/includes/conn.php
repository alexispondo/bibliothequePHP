<?php
	try
	{
		$connect = new PDO("mysql:host=localhost; dbname=tp1", "root", "");
	}catch(Exception $error)
	{
		die("UNE ERREURE:" .$error->getMessage());
	}

?>
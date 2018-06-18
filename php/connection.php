<?php 
	try{
		$handler = new PDO('mysql:host=127.0.0.1;dbname=ubicatecbd','root',''); //Localhost
		$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}	

	include_once 'ubicatec.class.php';
	$ObjectUbicatec = new ubicatec($handler);
?>
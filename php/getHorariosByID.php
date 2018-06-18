<?php 
	include_once ("connection.php");
	if(isset($_POST['idLoncheria'])) {		
		$ObjectUbicatec->getHorariosModal($_POST['idLoncheria']);
	} 
?>
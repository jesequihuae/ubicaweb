<?php 
	include_once ("connection.php");
	if(isset($_POST['idEdificio'])) {		
		$ObjectUbicatec->changeBuildingState($_POST['estado'], $_POST['idEdificio']);
	} else if (isset($_POST['idDepartamento'])) {
		$ObjectUbicatec->changeDepartmentState($_POST['estado'], $_POST['idDepartamento']);
	} else if (isset($_POST['idLoncheria'])) {
		$ObjectUbicatec->changeLoncheriaState($_POST['estado'], $_POST['idLoncheria']);
	}
?>
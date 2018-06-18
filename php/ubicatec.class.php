<?php 

class ubicatec {

	private $CONNECTION;

	public function __construct($BD){
		$this->CONNECTION = $BD;
	}

	public function login($Datos) {
		try {
			$SQLStatement = $this->CONNECTION->prepare("SELECT idUsuario, nombre FROM tblusuarios WHERE usuario = :usuario AND contrasena = :contrasena");
			$SQLStatement->bindParam(":usuario",$Datos['user']);
			$SQLStatement->bindParam(":contrasena",$Datos['password']);
			$SQLStatement->execute(); 

			if($SQLStatement->rowCount() > 0) {
				$Usuario = $SQLStatement->fetch(PDO::FETCH_ASSOC);
				@session_start();
			    $_SESSION['username'] = $Usuario['nombre'];
		 		header('Location: view/main.php');
			} else {
				echo '<div class="alert alert-dismissable alert-danger">Lo sentimos, usuario y/o contraseña no coinciden!
							<button type="button" class="close" data-dismiss="alert">x</button>
					  	 </div>';
			}	
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}	
	}

	public function logout() {
		@session_start();
		session_unset($_SESSION['username']);
		session_destroy();
		header('Location: ../index.php');
	}

	#START-BUILDINGS
	public function newBuilding($Edificio, $Imagen) {
		try {			
			$Usuario 	  = 'Ricardo Beltran';
			$Fecha        = date("Y-m-d H:i:s");
			// $Coordenadas  = '20.00000,-12.0000';

			if($Imagen['name'] == "") {
				$rutaImagen  = '../img/buildings/default.png';
			} else {				
				$nombreImagen = uniqid().$Imagen['name'];
				$rutaImagen   = '../img/buildings/'.$nombreImagen;
			}

			$SQL = $this->CONNECTION->prepare("INSERT INTO tbledificios (vNombre, vCoordenadas, bEdificioLoncheria, bActivo, vUsuarioCreacion, dFechaCreacion, tInformacion, vImgEdificio) VALUES (:Edificio, :Coordenadas, 1, 1, :UsuarioCreacion, :FechaCreacion, :Informacion, :ImagenRuta)");
			$SQL->bindParam(":Edificio",        $Edificio['vEdificio']);
			$SQL->bindParam(":Coordenadas",     $Edificio['vCoordenadas']);
			$SQL->bindParam(":UsuarioCreacion", $Usuario); #SESSION
			$SQL->bindParam(":FechaCreacion",   $Fecha);
			$SQL->bindParam(":Informacion",     $Edificio['vInformacion']);
			$SQL->bindParam(":ImagenRuta",      $rutaImagen);
			$SQL->execute();

			if($Imagen['name'] != "")
				move_uploaded_file($Imagen['tmp_name'], $rutaImagen);

			echo '<div class="alert alert-dismissable alert-success">Edificio registrado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch(PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function updateBuilding($Edificio, $Imagen) {
		try {
			$Usuario 	  = 'Ricardo Beltran';
			$Fecha        = date("Y-m-d H:i:s");

			$SQLFOTO = $this->CONNECTION->prepare("SELECT vImgEdificio FROM tbledificios WHERE idEdificio = :idEdificio");
			$SQLFOTO->bindParam(":idEdificio",$Edificio['idEdificio']);
			$SQLFOTO->execute();
			$RutaAnterior = $SQLFOTO->fetch(PDO::FETCH_ASSOC);

			if($Imagen['name'] != "") {
				if($RutaAnterior['vImgEdificio'] != "../img/buildings/default.png") {					
					@unlink($RutaAnterior['vImgEdificio']);
				}
				$nuevaImagen = uniqid().$Imagen['name'];
				$rutaImagen = '../img/buildings/'.$nuevaImagen;
				move_uploaded_file($Imagen['tmp_name'], $rutaImagen);
			}

			$SQL = $this->CONNECTION->prepare("UPDATE tbledificios 
										SET vNombre = :Edificio,
											vCoordenadas = :Coordenadas,
											tInformacion = :Informacion,
											vImgEdificio = :ImagenRuta,
											vUsuarioModificacion = :UsuarioModificacion,
											dFechaModificacion = :FechaModificacion
										WHERE idEdificio = :idEdificio");
			$SQL->bindParam(":Edificio",    $Edificio['vEdificio']);
			$SQL->bindParam(":Coordenadas", $Edificio['vCoordenadas']);
			$SQL->bindParam(":Informacion", $Edificio['vInformacion']);
			if($Imagen['name'] == ""){				
				$SQL->bindParam(":ImagenRuta", $RutaAnterior['vImgEdificio']);
			}else {
				$SQL->bindParam(":ImagenRuta", $rutaImagen);
			}
			$SQL->bindParam(":idEdificio", $Edificio['idEdificio']);
			$SQL->bindParam(":UsuarioModificacion", $Usuario);
			$SQL->bindParam(":FechaModificacion", $Fecha);
			$SQL->execute();

			echo '<div class="alert alert-dismissable alert-success">Edificio actualizado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getBuildings() {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT 
					idEdificio, 
					vNombre, 
					vCoordenadas, 
					bEdificioLoncheria, 
					bActivo, 
					vUsuarioCreacion, 
					dFechaCreacion, 
					tInformacion, 
					vImgEdificio
				FROM tbledificios
				WHERE bEdificioLoncheria = 1
				ORDER BY idEdificio DESC");
			$SQL->execute();

			while($Building = $SQL->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>
						<td>'.$Building['idEdificio'].'</td>
						<td>'.$Building['vNombre'].'</td>
						<td>'.($Building['bActivo']  == 1 ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Inactivo</span>').'</td>
						<td><button type="button" class="verImagen btn btn-info btn-sm" data-ruta="'.$Building['vImgEdificio'].'"><i class="fa fa-image"></i> Ver Imagen</button></td>
						<td><button type="button" class="verEnMapa btn btn-info btn-sm" data-coords="'.$Building['vCoordenadas'].'"><i class="fa fa-map-marker"></i> Ver en Mapa</button></td>
						<td align="right"><button type="button" data-id="'.$Building['idEdificio'].'" class="editarEdificio btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></button></td>
						<td>'.($Building['bActivo'] == 1 ? '<button type="button" data-id="'.$Building['idEdificio'].'" class="desactivar btn btn-danger btn-sm" title="Desactivar"><i class="fa fa-close"></i></button>' : '<button type="button" data-id="'.$Building['idEdificio'].'" class="activar btn btn-success btn-sm" title="Activar"><i class="fa fa-check"></i></button>').'</td>
					  </tr>';
			}

		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getBuildingByID($id) {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT 
					idEdificio, 
					vNombre, 
					vCoordenadas, 
					bEdificioLoncheria, 
					bActivo, 
					vUsuarioCreacion, 
					dFechaCreacion, 
					tInformacion, 
					vImgEdificio
				FROM tbledificios
				WHERE idEdificio = :id");
			$SQL->bindParam(":id", $id);
			$SQL->execute();
			$Building = $SQL->fetch(PDO::FETCH_ASSOC);
			echo $Building['vNombre'].'|'.$Building['tInformacion'].'|'.$Building['vCoordenadas'];
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function changeBuildingState($state, $idEdificio) {
		try {
			$Usuario 	  = 'Ricardo Beltran';
			$Fecha        = date("Y-m-d H:i:s");

			$SQL = $this->CONNECTION->prepare("UPDATE tbledificios
											SET bActivo = :Activo,
												vUsuarioModificacion = :UsuarioModificacion,
												dFechaModificacion = :FechaModificacion
											WHERE idEdificio = :idEdificio");
			$SQL->bindParam(":Activo", $state);
			$SQL->bindParam(":idEdificio", $idEdificio);
			$SQL->bindParam(":UsuarioModificacion", $Usuario);
			$SQL->bindParam(":FechaModificacion", $Fecha);
			$SQL->execute();

			echo '<div class="alert alert-dismissable alert-success">Estado modificado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	#END-BUILDINGS

	#START-DEPARMENTS
	public function newDepartment($Departamento, $Imagen) {
		try {
			if($Imagen['name'] == "") {
				$rutaImagen  = '../img/departments/default.png';
			} else {				
				$nombreImagen = uniqid().$Imagen['name'];
				$rutaImagen   = '../img/departments/'.$nombreImagen;
			}

			$SQL = $this->CONNECTION->prepare("INSERT INTO
											tbldepartamentos (tInformacion, vCorreoElectronico, vTelefono, vResponsable, vImgResponsable, idEdificio, bActivo, vDepartamento)
											VALUES (:Informacion, :CorreoElectronico, :Telefono, :Responsable, :ImagenResponsable, :idEdificio, 1, :Departamento)");
			$SQL->bindParam(":Informacion", $Departamento['vInformacion']);
			$SQL->bindParam(":CorreoElectronico", $Departamento['vCorreoElectronico']);
			$SQL->bindParam(":Telefono", $Departamento['vTelefono']);
			$SQL->bindParam(":Responsable", $Departamento['vResponsable']);
			$SQL->bindParam(":ImagenResponsable", $rutaImagen);
			$SQL->bindParam(":idEdificio", $Departamento['idEdificio']);
			$SQL->bindParam(":Departamento", $Departamento['vDepartamento']);
			$SQL->execute();

			if($Imagen['name'] != "")
				move_uploaded_file($Imagen['tmp_name'], $rutaImagen);

			echo '<div class="alert alert-dismissable alert-success">Departamento registrado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function updateDepartment($Departamento, $Imagen) {
		try {
			$SQLFOTO = $this->CONNECTION->prepare("SELECT vImgResponsable FROM tbldepartamentos WHERE idDepartamento = :idDepartamento");
			$SQLFOTO->bindParam(":idDepartamento",$Departamento['idDepartamento']);
			$SQLFOTO->execute();
			$RutaAnterior = $SQLFOTO->fetch(PDO::FETCH_ASSOC);

			if($Imagen['name'] != "") {
				if($RutaAnterior['vImgResponsable'] != "../img/departments/default.png") {					
					@unlink($RutaAnterior['vImgResponsable']);
				}
				$nuevaImagen = uniqid().$Imagen['name'];
				$rutaImagen = '../img/departments/'.$nuevaImagen;
				move_uploaded_file($Imagen['tmp_name'], $rutaImagen);
			}

			$SQL = $this->CONNECTION->prepare("UPDATE tbldepartamentos
										SET tInformacion = :Informacion,
										vCorreoElectronico = :CorreoElectronico,
										vTelefono = :Telefono,
										vResponsable = :Responsable,
										vImgResponsable = :ImagenResponsable,
										idEdificio = :idEdificio,
										vDepartamento = :Departamento
										WHERE idDepartamento = :idDepartamento");

			$SQL->bindParam(":Informacion", $Departamento['vInformacion']);
			$SQL->bindParam(":CorreoElectronico", $Departamento['vCorreoElectronico']);
			$SQL->bindParam(":Telefono", $Departamento['vTelefono']);
			$SQL->bindParam(":Responsable", $Departamento['vResponsable']);
			if($Imagen['name'] == ""){				
				$SQL->bindParam(":ImagenResponsable", $RutaAnterior['vImgResponsable']);
			}else {
				$SQL->bindParam(":ImagenResponsable", $rutaImagen);
			}
			$SQL->bindParam(":idEdificio", $Departamento['idEdificio']);
			$SQL->bindParam(":Departamento", $Departamento['vDepartamento']);
			$SQL->bindParam(":idDepartamento", $Departamento['idDepartamento']);

			$SQL->execute();
			echo '<div class="alert alert-dismissable alert-success">Edificio actualizado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getDepartments() {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT
										D.idDepartamento,
										D.tInformacion,
										D.vCorreoElectronico,
										D.vTelefono, 
										D.vResponsable,
										D.vImgResponsable,
										E.vNombre,
										D.bActivo, 
										D.vDepartamento
										FROM tbldepartamentos AS D
										INNER JOIN tbledificios AS E
										ON D.idEdificio = E.idEdificio
										ORDER BY D.idDepartamento DESC");
			$SQL->execute();
			while($Department = $SQL->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>
						<td>'.$Department['idDepartamento'].'</td>
						<td>'.$Department['vDepartamento'].'</td>
						<td>'.$Department['vNombre'].'</td>
						<td>'.($Department['bActivo']  == 1 ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Inactivo</span>').'</td>
					 	<td>'.$Department['vResponsable'].'</td>
					 	<td><button type="button" class="verImagen btn btn-info btn-sm" data-responsable="'.$Department['vResponsable'].'" data-ruta="'.$Department['vImgResponsable'].'"><i class="fa fa-image"></i> Ver Imagen</button></td>
					  	<td>'.$Department['vTelefono'].'</td>
					  	<td>'.$Department['vCorreoElectronico'].'</td>
					  	<td><button type="button" class="verInformacion btn btn-info btn-sm" data-departamento="'.$Department['vDepartamento'].'" data-informacion="'.$Department['tInformacion'].'"><i class="fa fa-comment-o"></i> Ver Información</button></td>
					  	<td align="right"><button type="button" data-id="'.$Department['idDepartamento'].'" class="editarDepartamento btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></button></td>
						<td>'.($Department['bActivo'] == 1 ? '<button type="button" data-id="'.$Department['idDepartamento'].'" class="desactivar btn btn-danger btn-sm" title="Desactivar"><i class="fa fa-close"></i></button>' : '<button type="button" data-id="'.$Department['idDepartamento'].'" class="activar btn btn-success btn-sm" title="Activar"><i class="fa fa-check"></i></button>').'</td>
					  </tr>';
			}
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getDepartmentByID($id) {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT
									idDepartamento,
									tInformacion,
									vCorreoElectronico,
									vTelefono,
									vResponsable,
									idEdificio,
									vDepartamento
									FROM tbldepartamentos
									WHERE idDepartamento = :id");
			$SQL->bindParam(":id", $id);
			$SQL->execute();
			$Department = $SQL->fetch(PDO::FETCH_ASSOC);
			echo $Department['idDepartamento'].'|'.$Department['tInformacion'].'|'.$Department['vCorreoElectronico'].'|'.$Department['vTelefono'].'|'.$Department['vResponsable'].'|'.$Department['idEdificio'].'|'.$Department['vDepartamento'];
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function changeDepartmentState($state, $idDepartamento) {
		try {
			$SQL = $this->CONNECTION->prepare("UPDATE tbldepartamentos
											SET bActivo = :Activo
											WHERE idDepartamento = :idDepartamento");
			$SQL->bindParam(":Activo", $state);
			$SQL->bindParam(":idDepartamento", $idDepartamento);
			$SQL->execute();

			echo '<div class="alert alert-dismissable alert-success">Estado modificado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}
	#END-DEPARTMENTS

	#START-FOODBUILDING
	public function newFoodbuilding($Foodbuilding, $Imagen) {
		
			$Usuario 	  = 'Ricardo Beltran';
			$Fecha        = date("Y-m-d H:i:s");
			// $Coordenadas  = '20.00000,-12.0000';

			if($Imagen['name'] == "") {
				$rutaImagen  = '../img/foodbuilding/default.png';
			} else {				
				$nombreImagen = uniqid().$Imagen['name'];
				$rutaImagen   = '../img/foodbuilding/'.$nombreImagen;
			}

			$SQL = $this->CONNECTION->prepare("INSERT INTO tbledificios (vNombre, vCoordenadas, bEdificioLoncheria, bActivo, vUsuarioCreacion, dFechaCreacion, vImgEdificio) VALUES (:Edificio, :Coordenadas, 0, 1, :UsuarioCreacion, :FechaCreacion, :ImagenRuta)");
		try {
			$this->CONNECTION->beginTransaction();
			$SQL->bindParam(":Edificio",        $Foodbuilding['vLoncheria']);
			$SQL->bindParam(":Coordenadas",     $Foodbuilding['vCoordenadas']);
			$SQL->bindParam(":UsuarioCreacion", $Usuario); #SESSION
			$SQL->bindParam(":FechaCreacion",   $Fecha);
			$SQL->bindParam(":ImagenRuta",      $rutaImagen);
			$SQL->execute();			
			$IDLONCHERIA = $this->CONNECTION->lastInsertId();

			if($Imagen['name'] != "")
				move_uploaded_file($Imagen['tmp_name'], $rutaImagen);

			$SQL2 = $this->CONNECTION->prepare("INSERT INTO tblHorarios (tHoraInicial, tHoraFinal, idEdificio, idDiaSemana) VALUES (:tHoraInicial, :tHoraFinal, :idEdificio, :idDiaSemana)");

			$h = explode("?",$Foodbuilding['jsonDias']);
			foreach ($h as $value) {
				$f = explode("|", $value);
				// echo $f[0]."--".$f[1]."--".$f[2];
				$arrayHorario = array(
						':idDiaSemana'=>$f[0],
						':tHoraInicial'=>$f[1],
						':idEdificio'=>$IDLONCHERIA,
						':tHoraFinal'=>$f[2]
					);		

				if($SQL2->execute($arrayHorario)) {
					// echo 'agregado';
				} else {
					// echo $SQL2->errorCode();
				}	
			}

			$this->CONNECTION->commit();	
			echo '<div class="alert alert-dismissable alert-success">Lonchería registrada exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';		
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
			$this->CONNECTION->rollback();
		}
	}

	public function updateFoodbuilding($Foodbuilding, $Imagen) {
		try {
			$Usuario 	  = 'Ricardo Beltran';
			$Fecha        = date("Y-m-d H:i:s");

			$SQLFOTO = $this->CONNECTION->prepare("SELECT vImgEdificio FROM tbledificios WHERE idEdificio = :idEdificio");
			$SQLFOTO->bindParam(":idEdificio",$Foodbuilding['idLoncheria']);
			$SQLFOTO->execute();
			$RutaAnterior = $SQLFOTO->fetch(PDO::FETCH_ASSOC);

			if($Imagen['name'] != "") {
				if($RutaAnterior['vImgEdificio'] != "../img/foodbuilding/default.png") {					
					@unlink($RutaAnterior['vImgEdificio']);
				}
				$nuevaImagen = uniqid().$Imagen['name'];
				$rutaImagen = '../img/foodbuilding/'.$nuevaImagen;
				move_uploaded_file($Imagen['tmp_name'], $rutaImagen);
			}

			$SQL = $this->CONNECTION->prepare("UPDATE tbledificios 
										SET vNombre = :Loncheria,
											vCoordenadas = :Coordenadas,
											vImgEdificio = :ImagenRuta,
											vUsuarioModificacion = :UsuarioModificacion,
											dFechaModificacion = :FechaModificacion
										WHERE idEdificio = :idEdificio");
			$SQL->bindParam(":Loncheria",    $Foodbuilding['vLoncheria']);
			$SQL->bindParam(":Coordenadas", $Foodbuilding['vCoordenadas']);
			if($Imagen['name'] == ""){				
				$SQL->bindParam(":ImagenRuta", $RutaAnterior['vImgEdificio']);
			}else {
				$SQL->bindParam(":ImagenRuta", $rutaImagen);
			}
			$SQL->bindParam(":idEdificio", $Foodbuilding['idLoncheria']);
			$SQL->bindParam(":UsuarioModificacion", $Usuario);
			$SQL->bindParam(":FechaModificacion", $Fecha);
			$SQL->execute();

			$SQLDELETE = $this->CONNECTION->prepare("DELETE FROM tblHorarios WHERE idEdificio = :idLoncheria");
			$SQLDELETE->bindParam(":idLoncheria", $Foodbuilding['idLoncheria']);
			$SQLDELETE->execute();

			$SQL2 = $this->CONNECTION->prepare("INSERT INTO tblHorarios (tHoraInicial, tHoraFinal, idEdificio, idDiaSemana) VALUES (:tHoraInicial, :tHoraFinal, :idEdificio, :idDiaSemana)");

			$h = explode("?",$Foodbuilding['jsonDias']);
			foreach ($h as $value) {
				$f = explode("|", $value);
				// echo $f[0]."--".$f[1]."--".$f[2];
				$arrayHorario = array(
						':idDiaSemana'=>$f[0],
						':tHoraInicial'=>$f[1],
						':idEdificio'=>$Foodbuilding['idLoncheria'],
						':tHoraFinal'=>$f[2]
					);		

				if($SQL2->execute($arrayHorario)) {
					// echo 'agregado';
				} else {
					// echo $SQL2->errorCode();
				}	
			}

			echo '<div class="alert alert-dismissable alert-success">Edificio actualizado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getFoodbuildings() {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT 
					idEdificio, 
					vNombre, 
					vCoordenadas, 
					bEdificioLoncheria, 
					bActivo, 
					vUsuarioCreacion, 
					vImgEdificio
				FROM tbledificios
				WHERE bEdificioLoncheria = 0
				ORDER BY idEdificio DESC");
			$SQL->execute();

			while($Foodbuilding = $SQL->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>
						<td>'.$Foodbuilding['idEdificio'].'</td>
						<td>'.$Foodbuilding['vNombre'].'</td>
						<td>'.($Foodbuilding['bActivo']  == 1 ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Inactivo</span>').'</td>
						<td><button type="button" class="verImagen btn btn-info btn-sm" data-ruta="'.$Foodbuilding['vImgEdificio'].'"><i class="fa fa-image"></i> Ver Imagen</button></td>
						<td><button type="button" class="verEnMapa btn btn-info btn-sm" data-coords="'.$Foodbuilding['vCoordenadas'].'"><i class="fa fa-map-marker"></i> Ver en Mapa</button></td>
						<td><button type="button" class="btn btn-info btn-sm verHorarios" data-nombre="'.$Foodbuilding['vNombre'].'" data-id="'.$Foodbuilding['idEdificio'].'">Ver Horarios</button></td>
						<td align="right"><button type="button" data-id="'.$Foodbuilding['idEdificio'].'" class="editarLoncheria btn btn-info btn-sm" title="Editar"><i class="fa fa-pencil"></i></button></td>
						<td>'.($Foodbuilding['bActivo'] == 1 ? '<button type="button" data-id="'.$Foodbuilding['idEdificio'].'" class="desactivar btn btn-danger btn-sm" title="Desactivar"><i class="fa fa-close"></i></button>' : '<button type="button" data-id="'.$Foodbuilding['idEdificio'].'" class="activar btn btn-success btn-sm" title="Activar"><i class="fa fa-check"></i></button>').'</td>
					  </tr>';
			}
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} 
	}

	public function getFoodbuildingById($id) {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT 
					idEdificio, 
					vNombre, 
					vCoordenadas
				FROM tbledificios
				WHERE idEdificio = :id");
			$SQL->bindParam(":id", $id);
			$SQL->execute();
			$Foodbuilding = $SQL->fetch(PDO::FETCH_ASSOC);
			echo $Foodbuilding['vNombre'].'|'.$Foodbuilding['vCoordenadas'];
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getHorariosModal($idLoncheria) {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT 
									h.tHoraInicial, 
									h.tHoraFinal, 
									h.idEdificio, 
									h.idDiaSemana,
									ds.vDia
									FROM tblhorarios AS h
									INNER JOIN tbldiassemana AS ds
									ON ds.idDiaSemana = h.idDiaSemana
									WHERE idEdificio = :idLoncheria
									ORDER BY idDiaSemana ASC");
			$SQL->bindParam(":idLoncheria", $idLoncheria);
			$SQL->execute();
			while($Horario = $SQL->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>
					    <td align="center">'.$Horario['vDia'].'</td>
					    <td align="center">'.$Horario['tHoraInicial'].'</td>
					    <td align="center">'.$Horario['tHoraFinal'].'</td>
					  </tr>';
			}
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getHorariosEdit($idLoncheria) {
		try {
			$Horario = "";
			$SQL = $this->CONNECTION->prepare("SELECT 
									tHoraInicial, 
									tHoraFinal, 
									idEdificio, 
									idDiaSemana
									FROM tblhorarios AS h
									WHERE idEdificio = :idLoncheria
									ORDER BY idDiaSemana ASC");
			$SQL->bindParam(":idLoncheria", $idLoncheria);
			$SQL->execute();
			while($Horarios = $SQL->fetch(PDO::FETCH_ASSOC)) {
				 $Horario .= '<tr>';
                 $Horario .= '<td align="center">
                 					<select class="form-control idDia" style="width: 100%;" name="idDia">
                 						<option '.($Horarios['idDiaSemana'] == 1 ? 'selected' : '' ).' value="1">Lunes</option>
                 						<option '.($Horarios['idDiaSemana'] == 2 ? 'selected' : '' ).' value="2">Martes</option>
                 						<option '.($Horarios['idDiaSemana'] == 3 ? 'selected' : '' ).' value="3">Miercoles</option>
                 						<option '.($Horarios['idDiaSemana'] == 4 ? 'selected' : '' ).' value="4">Jueves</option>
                 						<option '.($Horarios['idDiaSemana'] == 5 ? 'selected' : '' ).' value="5">Viernes</option>
                 						<option '.($Horarios['idDiaSemana'] == 6 ? 'selected' : '' ).' value="6">Sabado</option>
                 						<option '.($Horarios['idDiaSemana'] == 7 ? 'selected' : '' ).' value="7">Domingo</option>
                 					</select>
                 			  </td>';                 
                 $Horario .= '<td align="center"><div class="bootstrap-timepicker"><div class="form-group"><div class="input-group"><input type="text" class="form-control timepicker horaInicio" value="'.$Horarios['tHoraInicial'].'"></div></div></div></td>';
                 $Horario .= '<td align="center"><div class="bootstrap-timepicker"><div class="form-group"><div class="input-group"><input type="text" class="form-control timepicker horaFinal"  value="'.$Horarios['tHoraFinal'].'"></div></div></div></td>';
                 $Horario .= '<td><a type="button" class="btn btn-danger eliminarHorario">Eliminar</a></td> ';
                 $Horario .= '</tr>';
			}
			echo $Horario;
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function changeLoncheriaState($state, $idLoncheria) {
		try {
			$SQL = $this->CONNECTION->prepare("UPDATE tbledificios
											SET bActivo = :Activo
											WHERE idEdificio = :idLoncheria");
			$SQL->bindParam(":Activo", $state);
			$SQL->bindParam(":idLoncheria", $idLoncheria);
			$SQL->execute();

			echo '<div class="alert alert-dismissable alert-success">Estado modificado exitosamente!
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}
	#END-FOODBUILDING

	public function getBuildingsDDL() {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT idEdificio, vNombre FROM tbledificios WHERE bEdificioLoncheria = 1 AND bActivo = 1");
			$SQL->execute();
			while($Building = $SQL->fetch(PDO::FETCH_ASSOC)) {
				echo '<option value="'.$Building['idEdificio'].'">'.$Building['vNombre'].'</option>';
			}
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}

	public function getDiasDDL() {
		try {
			$SQL = $this->CONNECTION->prepare("SELECT idDiaSemana, vDia FROM tbldiassemana");
			$SQL->execute();
			while($Dias = $SQL->fetch(PDO::FETCH_ASSOC)) {
				echo '<option value="'.$Dias['idDiaSemana'].'">'.$Dias['vDia'].'</option>';
			}
		} catch (PDOException $e) {
			echo '<div class="alert alert-dismissable alert-danger">Ocurrió un error: '.$e->getMessage().'
					<button type="button" class="close" data-dismiss="alert">x</button>
				  </div>';
		}
	}
}

?>
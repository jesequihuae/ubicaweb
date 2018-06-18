<!DOCTYPE html>
<?php  @session_start(); if(!isset($_SESSION['username'])){ echo '<script language = javascript> self.location = "javascript:history.back(-1);" </script>'; exit;  } ?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>UbicaTEC</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../librerias/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../librerias/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../librerias/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../librerias/dist/css/skins/skin-blue.min.css">
 </head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper"> <!-- Contenedor -->
  <!-- HEADER (Administrador y Icono para abrir/cerrar Aside) -->
  <?php include '../mod/headerSection.php'; ?>
  <!-- Aside (logo y opciones) -->
  <?php include '../mod/asideSection.php'; ?>

  <!-- Contenido principal -->
  <div class="content-wrapper">
    <section class="content">
      <div id="alerta"></div>
      <?php         
        if(isset($_POST['guardarDepartamento'])) {
          include_once '../php/connection.php';
          if($_POST['idDepartamento'] == 0) {
              $ObjectUbicatec->newDepartment($_POST, $_FILES['fImagenResponsable']);
          } else {
              $ObjectUbicatec->updateDepartment($_POST, $_FILES['fImagenResponsable']);
          }
        } 
      ?>

      <section id="nuevoDepartamento">
          <div class="row">
            <div class="col-lg-offset-2 col-lg-8">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-plus"></i><i class="fa fa-building"></i>
                  <h3 class="box-title"><div id="headerFormulario"></div></h3>
                </div>
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                  <input type="hidden" id="idDepartamento" name="idDepartamento" value="0">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="idEdificio" class="col-lg-3 control-label">Edificio*:</label>
                      <div class="col-lg-9">
                        <select class="form-control select2" style="width: 100%;" id="idEdificio" name="idEdificio">
                            <?php include_once '../php/connection.php'; $ObjectUbicatec->getBuildingsDDL();?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="vDepartamento" class="col-lg-3 control-label">Departamento*:</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" id="vDepartamento" name="vDepartamento" placeholder="Departamento" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="vInformacion" class="col-lg-3 control-label">Información*:</label>
                      <div class="col-lg-9">
                        <textarea class="form-control" rows="3" placeholder="Información" id="vInformacion" name="vInformacion" required></textarea>
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="vResponsable" class="col-lg-3 control-label">Responsable*:</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" id="vResponsable" name="vResponsable" placeholder="Responsable" required>
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="vCorreoElectronico" class="col-lg-3 control-label">Correo Electrónico*:</label>
                      <div class="col-lg-9">
                        <input type="email" class="form-control" id="vCorreoElectronico" name="vCorreoElectronico" placeholder="Correo Electrónico" required>
                      </div>
                    </div>  
                    <div class="form-group">
                      <label for="vTelefono" class="col-lg-3 control-label">Teléfono:</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" id="vTelefono" name="vTelefono" placeholder="Telefono">
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="fImagenResponsable" class="col-lg-3 control-label">Imagen de Responsable:</label>
                        <div class="col-lg-9">
                          <input type="file" id="fImagenResponsable" name="fImagenResponsable">
                          <p class="help-block"><span id="msgImagen"></span></p>
                        </div>                      
                    </div>
                  </div>
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" id="cancelarRegistro"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="submit" class="btn btn-info pull-right" name="guardarDepartamento"><i class="fa fa-paper-plane"></i> Guardar</button>
                  </div>
                </form>                
              </div>
            </div>
          </div>
      </section>

      <section>
          <div class="row">
            <div class="col-lg-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-archive"></i><i class="fa fa-building"></i>
                  <h3 class="box-title"> Departamentos registrados</h3>
                  <button type="button" class="btn btn-primary pull-right" id="btnNuevoDepartamento"><i class="fa fa-plus"></i> Nuevo Departamento</button>
                </div>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>ID</th>
                      <th>Departamento</th>
                      <th>Edificio</th>
                      <th>Estatus</th>
                      <th>Responsable</th>
                      <th>Imagen</th>
                      <th>Teléfono</th>
                      <th>Correo Electrónico</th>
                      <th>Información</th>
                      <th colspan="2"><center>Operaciones</center></th>
                    </tr>                    
                    <?php 
                      include_once '../php/connection.php';
                      $ObjectUbicatec->getDepartments();
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </section>

    </section>
  </div>
  </div><!-- Contenedor -->

  <!-- MODAL IMAGEN -->
  <div class="modal" id="modalImagen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><span id="nombreResponsable"></span></h4>
        </div>
        <div class="modal-body">
          <center><img id="imgResponsable" src="" class="img-responsive"></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- MODAL INFORMACION -->
  <div class="modal" id="modalInformacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><span id="nombreDepartamento"></span></h4>
        </div>
        <div class="modal-body">
          <center><div id="informacion"></div></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- MODAL ACTIVAR/DESACTIVAR -->
  <div class="modal" id="modalActivarDesactivar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Atención!</h4>
        </div>
        <div class="modal-body" align="center">
          <input type="hidden" id="idDepartmentChangeState">
          <h2 id="hdrActDes"></h2>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">No</button>
          <button type="button" class="btn btn-outline" id="btnActivarDesactivar" data-dismiss="modal">Sí</button>&nbsp;&nbsp;&nbsp;
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


  <script src="../librerias/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="../librerias/bootstrap/js/bootstrap.min.js"></script>
  <script src="../librerias/dist/js/app.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        $("#nuevoDepartamento").hide();
    });

    $("#btnNuevoDepartamento").on('click', function(e) {
      $("#nuevoDepartamento").hide(100);
      $("#idDepartamento").val("0");
      $("#vDepartamento").val("");
      $("#vInformacion").val("");
      $("#vResponsable").val("");
      $("#vCorreoElectronico").val("");
      $("#vTelefono").val("");
      $("#nuevoDepartamento").show(200);      
      $("#headerFormulario").text("Registro de Departamento");
      $("#msgImagen").text("Si no se selecciona imagen se asignará una por defecto.");
      e.preventDefault();
    });

    $("#cancelarRegistro").on('click', function(e) {
      $("#nuevoDepartamento").hide(100);
      $("#idDepartamento").val("0");
      $("#vDepartamento").val("");
      $("#vInformacion").val("");
      $("#vResponsable").val("");
      $("#vCorreoElectronico").val("");
      $("#vTelefono").val("");
    });

    $(".verImagen").on('click', function(e) {
      $("#imgResponsable").attr('src',$(this).data("ruta"));
      $("#nombreResponsable").text($(this).data("responsable"));
      $("#modalImagen").modal('show');
    });

    $(".verInformacion").on('click', function(e) {
       $("#nombreDepartamento").text($(this).data("departamento"));
       $("#informacion").text($(this).data("informacion"));
       $("#modalInformacion").modal('show');
    });

    $('.editarDepartamento').on('click', function(e) { 
        $("#nuevoDepartamento").hide();
        $("#headerFormulario").text("Edición de Departamento");
        $("#msgImagen").text("Si no se selecciona una imagen se tomará la existente.");
        var id = $(this).data("id");
        $("#idDepartamento").val(id);

        $.ajax({
          type: 'POST',
          url: '../php/getDepartmentByID.php',
          data: {id:id},
          success: function(data) {
            // console.log(data);   
            var INFORMACION = data.split('|');
            $("#idDepartamento").val(INFORMACION[0]);            
            $("#vInformacion").val(INFORMACION[1]);
            $("#vCorreoElectronico").val(INFORMACION[2]);
            $("#vTelefono").val(INFORMACION[3]);
            $("#vResponsable").val(INFORMACION[4]);
            $("#idEdificio").val(parseInt(INFORMACION[5]));
            $("#vDepartamento").val(INFORMACION[6]);
            $("#nuevoDepartamento").show(200);
          }
        });
    });

    $(".activar").on('click', function(e) {
        $("#hdrActDes").text("¿Está seguro de activar el departamento?");
        if($("#modalActivarDesactivar").hasClass('modal-danger')) {
          $("#modalActivarDesactivar").removeClass('modal-danger')
        }
        $("#idDepartmentChangeState").val($(this).data("id"));
        $("#modalActivarDesactivar").addClass("modal-success");
        $("#modalActivarDesactivar").modal('show');
    });

    $(".desactivar").on('click', function(e) {
      $("#hdrActDes").text("¿Está seguro de desactivar el departamento?");
       if($("#modalActivarDesactivar").hasClass('modal-success')) {
          $("#modalActivarDesactivar").removeClass('modal-success')
        }
      $("#idDepartmentChangeState").val($(this).data("id"));
      $("#modalActivarDesactivar").addClass("modal-danger");
      $("#modalActivarDesactivar").modal('show');
    });

     $("#btnActivarDesactivar").on('click', function(e) {
        var estado = 0;
        if($("#modalActivarDesactivar").hasClass('modal-success')) {
          estado = 1;
        }
        if($("#modalActivarDesactivar").hasClass('modal-danger')) {
          estado = 0;
        }

        $.ajax({
          type: 'POST',
          url: '../php/changeStateByID.php',
          data: { 
                  estado:estado, 
                  idDepartamento: $("#idDepartmentChangeState").val()
                },
          success: function(data) {
            $("#alerta").html(data);
          }
        });
    });
  </script>
</body>
</html>

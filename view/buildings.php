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
  <style>
      #map {
        height: 450px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
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
          if(isset($_POST) && isset($_POST['guardarEdificio'])) {  
            include_once '../php/connection.php';
            if($_POST['idEdificio'] == 0) {
              $ObjectUbicatec->newBuilding($_POST,$_FILES['fImagenEdificio']);
            } else {
              $ObjectUbicatec->updateBuilding($_POST,$_FILES['fImagenEdificio']);
            }
          }
        ?>

        <section id="nuevoEdificio">
          <div class="row">
            <div class="col-lg-offset-2 col-lg-8">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-plus"></i><i class="fa fa-building"></i>
                  <h3 class="box-title"><div id="headerFormulario"></div></h3>
                </div>
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                  <input type="hidden" id="idEdificio" name="idEdificio" value="0">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="vEdificio" class="col-lg-2 control-label">Edificio:</label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" id="vEdificio" name="vEdificio" placeholder="Edificio" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="vInformacion" class="col-lg-2 control-label">Información:</label>
                      <div class="col-lg-10">
                        <textarea class="form-control" rows="3" placeholder="Información" id="vInformacion" name="vInformacion" required></textarea>
                      </div>
                    </div>                    
                    <div class="form-group">
                        <label for="<!-- vCoordenadas -->" class="col-lg-2 control-label">Coordenadas:</label>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" id="vCoordenadas" placeholder="Coordenadas" disabled required>
                          <input type="hidden" id="vCoordenadasForm" name="vCoordenadas">
                          <p class="help-block">Seleccione la posición con el marcador en el mapa.</p>
                        </div>   
                        <div class="col-lg-2">
                          <button class="btn btn-info" id="verMapa"><i class="fa fa-map-marker"></i> Ver Mapa</button>
                        </div>                   
                    </div>
                    <div class="form-group">
                        <label for="fImagenEdificio" class="col-lg-2 control-label">Imagen:</label>
                        <div class="col-lg-10">
                          <input type="file" id="fImagenEdificio" name="fImagenEdificio">
                          <p class="help-block"><span id="msgImagen"></span></p>
                        </div>                      
                    </div>
                  </div>
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" id="cancelarRegistro"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="submit" class="btn btn-info pull-right" name="guardarEdificio"><i class="fa fa-paper-plane"></i> Guardar</button>
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
                  <h3 class="box-title"> Edificios registrados</h3>
                  <button type="button" class="btn btn-primary pull-right" id="btnNuevoEdificio"><i class="fa fa-plus"></i> Nuevo Edificio</button>
                </div>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>ID</th>
                      <th>Edificio</th>
                      <th>Estatus</th>
                      <th>Imagen</th>
                      <th>Ubicación</th>
                      <th colspan="2"><center>Operaciones</center></th>
                    </tr>                    
                    <?php 
                      include_once '../php/connection.php';
                      $ObjectUbicatec->getBuildings();
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

  <!-- MODAL MAPA -->
  <div class="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Mapa</h4>
        </div>
        <div class="modal-body">
          <div id="map"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

   <!-- MODAL VER EN MAPA -->
  <div class="modal" id="modalMapa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Mapa</h4>
        </div>
        <div class="modal-body">
          <div id="map2"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
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
          <input type="hidden" id="idEdificioChangeState">
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

  <!-- MODAL IMAGEN -->
  <div class="modal" id="modalImagen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Imagen de Edificio</h4>
        </div>
        <div class="modal-body">
         <center><img id="imgEdificio" src="" class="img-responsive"></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
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
    var map;
    var markerDraggable;
    var markerStatic; 

    $(document).ready(function(){
        $("#nuevoEdificio").hide();
    });

    $('.editarEdificio').on('click', function(e) {
        $("#nuevoEdificio").hide();
        $("#headerFormulario").text("Edición de Edificio");
        $("#msgImagen").text("Si no se selecciona una imagen se tomará la existente.");
        var id = $(this).data("id");
        $("#idEdificio").val(id);

        $.ajax({
          type: 'POST',
          url: '../php/getBuildingByID.php',
          data: {id:id},
          success: function(data) {
            // console.log(data);
            var INFORMACION = data.split('|');
            $("#vEdificio").val(INFORMACION[0]);
            $("#vInformacion").val(INFORMACION[1]);
            $("#vCoordenadas").val(INFORMACION[2]); 
            $("#vCoordenadasForm").val(INFORMACION[2]);           
            $("#nuevoEdificio").show(200);
          }
        });
    });

    $('#btnNuevoEdificio').on('click', function(e) {
        $("#nuevoEdificio").hide();
        $("#vEdificio").val("");
        $("#vInformacion").val("");
        $("#vCoordenadas").val("");
        $("#idEdificio").val("0");
        $("#headerFormulario").text("Registro de Edificio");
        $("#msgImagen").text("Si no se selecciona imagen se asignará una por defecto.");
        $("#nuevoEdificio").show(200);
        e.preventDefault();
    });

    $('#cancelarRegistro').on('click', function(e) {
      $("#nuevoEdificio").hide(100);
      $("#vEdificio").val("");
      $("#vInformacion").val("");
      $("#vCoordenadas").val("");
      e.preventDefault();
    });

    $(".activar").on('click', function(e) {
        $("#hdrActDes").text("¿Está seguro de activar el registro?");
        if($("#modalActivarDesactivar").hasClass('modal-danger')) {
          $("#modalActivarDesactivar").removeClass('modal-danger')
        }
        $("#idEdificioChangeState").val($(this).data("id"));
        $("#modalActivarDesactivar").addClass("modal-success");
        $("#modalActivarDesactivar").modal('show');
    });

    $(".desactivar").on('click', function(e) {
       $("#hdrActDes").text("¿Está seguro de desactivar el edificio?");
       if($("#modalActivarDesactivar").hasClass('modal-success')) {
          $("#modalActivarDesactivar").removeClass('modal-success')
        }
       $("#idEdificioChangeState").val($(this).data("id"));
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
                  idEdificio: $("#idEdificioChangeState").val()
                },
          success: function(data) {
            $("#alerta").html(data);
          }
        });
    });

    $(".verImagen").on('click', function(e) {
        $("#imgEdificio").attr('src',$(this).data("ruta"));
        $("#modalImagen").modal('show');
    });

    $('#verMapa').on('click', function(e) {  
        addDraggableMarker();
        $("#modal").modal('show');             
        e.preventDefault();
    });

    $(".verEnMapa").on('click', function(e) {
      if(markerStatic !== undefined)
          markerStatic.setMap(null);

      if(markerDraggable !== undefined)
          markerDraggable.setMap(null);

      var latlong = $(this).data("coords").split(',');
      var lati = latlong[0];
      var lngi = latlong[1];

      markerStatic = new google.maps.Marker({
        map: map,
        position: {lat: parseFloat(lati), lng: parseFloat(lngi)},
      });

      $("#modal").modal('show');
    });

   function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 19.093320, lng: -102.405536},
        zoom: 20
      });

      map.setMapTypeId('satellite');
    }

   function addDraggableMarker() {

        if(markerDraggable !== undefined)
          markerDraggable.setMap(null);

        if(markerStatic !== undefined)
          markerStatic.setMap(null);

        var vLat = 19.093320;
        var vLong = -102.405536;

        markerDraggable = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: {lat: vLat, lng: vLong},
        });

        markerDraggable.addListener('click', function() {
          if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
          } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
          }
        });

        markerDraggable.addListener( 'dragend', function (event){
            document.getElementById("vCoordenadas").value = this.getPosition().lat()+","+ this.getPosition().lng();
            document.getElementById("vCoordenadasForm").value = this.getPosition().lat() + "," + this.getPosition().lng();
        });
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXlTBt3sgaOLv2G-oMV5aILOK5UwnvDDY&callback=initMap"
    async defer></script>
</body>
</html>

<!DOCTYPE html>
<?php  @session_start(); if(isset($_SESSION['username'])){ echo '<script language = javascript> self.location = "javascript:history.back(-1);" </script>'; exit;  } ?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="librerias/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="librerias/font-awesome/css/font-awesome.min.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
  <link rel="stylesheet" href="librerias/dist/css/AdminLTE.min.css">
  <!-- Se puede utilizar otro color, aquí está el blue y más abajo hay una tabla de los colores  -->
  <link rel="stylesheet" href="librerias/dist/css/skins/skin-blue.min.css">
</head>
<body class="hold-transition login-page" style="background:url('img/bg2.png') fixed; background-size: cover; padding: 0; margin: 0;">
  <div class="login-box">
    <?php 
      if(isset($_POST) && isset($_POST['login'])) {
        include_once 'php/connection.php';
        $ObjectUbicatec->login($_POST);
      }
      // print_r($_POST);
    ?>
    <form  method="post">
    <div class="login-box-body">
      <div class="login-logo">
        <img src="img/logoITSA.png" width="150">
      </div>
      <p class="login-box-msg">Ingresa los datos para iniciar sesión</p>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Correo electrónico" name="user">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Contraseña" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">Iniciar Sesión <i class="fa fa-sign-in"></i></button>
        </div>
      </div>      
    </div> 
   </form>
  </div>

  <script src="librerias/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="librerias/bootstrap/js/bootstrap.min.js"></script>
  <script src="librerias/dist/js/app.min.js"></script>
</body>
</html>

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
  <!-- TimePciker -->
  <!-- <link href="../librerias/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> -->
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper"> <!-- Contenedor -->
  <!-- HEADER (Administrador y Icono para abrir/cerrar Aside) -->
  <?php include '../mod/headerSection.php'; ?>
  <!-- Aside (logo y opciones) -->
  <?php include '../mod/asideSection.php'; ?>

  <!-- Contenido principal -->
  <div class="content-wrapper">
    <section class="content" align="center">
      <!-- <img src="../img/abejita.gif" width="100"> -->
      <img src="../img/logoITSA.png" alt="">
    </section>
  </div>
  </div><!-- Contenedor -->

  <script src="../librerias/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="../librerias/bootstrap/js/bootstrap.min.js"></script>
  <script src="../librerias/dist/js/app.min.js"></script>
</body>
</html>

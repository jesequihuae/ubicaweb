<?php 
  $URL =  $_SERVER["REQUEST_URI"]; 
  $URL = explode('/', $URL); 
  $URL = $URL[sizeof($URL)-1];
  // $URL = explode('.', $URL[4]); 
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../img/users/userundefined.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php @session_start(); echo $_SESSION['username']; ?></p>
        </div><br><br>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header"><font size="3"><center>Opciones</center></font></li>
        <!-- <li><a href="#"><i class="fa fa-users" aria-hidden="true"></i><span> Usuarios</span></a></li> -->
        <li ><a href="buildings" ><i class="fa fa-building"></i><span> Edificios</span></a></li>
        <li><a href="foodbuildings"><i class="fa fa-cutlery"></i><span> Loncherias</span></a></li>
        <li><a href="departments"><i class="fa fa-puzzle-piece"></i><span> Departamentos</span></a></li>
        <li><a href="../php/logout.php"><i class="fa fa-sign-out"></i>Salir</a></li>
      </ul>
    </section>
  </aside>

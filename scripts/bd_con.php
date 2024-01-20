<?php
  $bd = new mysqli("localhost","root","","tp_web2");


  if ($bd -> connect_errno) {
    echo "Error al conectar a MySQL: " . $bd -> connect_error;
    exit();
  }


?> 
<?php
if(isset($_GET['op'])){
  $op = $_GET['op'];
} else {
  $op = 0;
}
switch ($op) {
  case 1:
    include("templates/qs.php");
    break;
  case 2:
    include("templates/socios.php");
    break;
  case 3:
    include("templates/productos.php");
    break;
    case 4:
      include("templates/contactos.php");
      break;
  default:
    include("templates/splash.php");
    break;
}
?>

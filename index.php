<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="static/css/formato.css">
  <script src="static/js/eventos.js" charset="utf-8"></script>
  <title>Document</title>
</head>
<body>
  <div id="contenido">
    <header>
    </header>
    <nav>
      <ul>
          <li><a href="index.php?op=3">productos</a></li>
          <li><a href="index.php?op=2">Socios</a></li>
          <li><a href="index.php?op=4">contactanos</a></li>
          <li><a href="index.php?op=1">Nosotros</a></li>
      </ul>
    </nav>
    <article>
      <?php
      include("templates/controlador.php");
      ?>
    </article>
    <footer>
    </footer>
  </div>
</body>
</html>

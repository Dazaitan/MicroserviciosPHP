<h1><center><strong>Tabla</strong></center></h1>
<table>
  <tr>
    <td>elemtos</td><td>orden</td><td>registro</td>
  </tr>
  <?php
    for($i=0;$i<=20;$i++) {
      ?>
      <tr>
        <td>elemento<?php echo $i;?></td>
        <td><?php echo $i;?></td>
        <td><?php echo $i;?></td>
      </tr>
      <?php
    }
    ?>
</table>

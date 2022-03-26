<?php
header("Content-Type: application/json");
include("../bd/conexion.php");
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
    if (isset($_GET['categorias'])!="") {
      $vag = $_GET['categorias'];
      //obtenemos el valor de "vagon" que se define en la url
      if ($vag=='all') {
          $consultaCat = "SELECT * FROM res_categorias;";
          $reg = mysqli_query($ap, $consultaCat);
          $arr_cat =array();
          $i=0;
          while ($filas=mysqli_fetch_assoc($reg)){
            $arr_cat[$i] = $filas;
            $i++;
          }
          $resultado["Platos"] = $arr_cat;
      } else if($vag !='all') {
          $consultaBusq = "SELECT * FROM res_categorias WHERE cat_id=".$vag.";";
          $busq = mysqli_query($ap, $consultaBusq);
          $arr_cat = array();
          $i = 0;
            while ($filas = mysqli_fetch_assoc($busq)) {
              $arr_cat[$i] = $filas;
              $i++;
            }
        $resultado["Categorias"] = $arr_cat;
    }
    }else if(isset($_GET['plato'])!= "") {
        // el metodo isset verifica si la variable existe y la evalua para verficar si no esta vacia
        $vag = $_GET['plato'];
        //aqui esta obteniendo el valor asignado a "platos"
        if ($vag =='all'){
          $consultaPlato = "SELECT * FROM res_productos;";
          $reg = mysqli_query($ap, $consultaPlato,);
          //el metodo mysqli_query esta ejecutando las variables que contienen la conexion con la base de datos, y la consulta para realizar
          $arr_pro = array();
          //definimos una variable array donde se va a guardar los datos recuperados
          $i = 0;
            while ($filas = mysqli_fetch_assoc($reg)) {
              $arr_pro[$i] = $filas;
              $i++;
              //aqui estamos almacenando los datos recuperados de la consulta en la variable $arr_pro
            }
            $resultado["plato"] = $arr_pro;
        }else if ($vag == 'busq'){
          $pro_id = $_GET['busq'];
          $selectProId = "SELECT * FROM res_productos WHERE pro_id=".$pro_id;
          $busqPro = mysqli_query($ap, $selectProId);
          $recuperado = array();
          while ($filas = mysqli_fetch_assoc($busqPro)) {
            $recuperado = $filas;
          }
          $resultado["plato"] = $recuperado;
        }
      }
    break;
    case 'POST':
      $_POST = json_decode(file_get_contents('php://input'), true);
      if(isset($_POST['op'])&& $_POST['op']=='insert cat'){
        $cat_nombre = $_POST['cat_nombre'];
        $cat_imagen = $_POST['cat_imagen'];
        $cat_descripcion = $_POST['cat_descripcion'];
        $cat_fecha = date("Y-m.d");
        $cat_hora = date("H:i:s");
        $sqlInsertcat= "Insert Into res_categorias(cat_id, cat_nombre, cat_imagen, cat_descripcion, cat_fecha, cat_hora)
                VALUES(Null, '".$cat_nombre."','".$cat_imagen."','".$cat_descripcion."','".$cat_fecha."','".$cat_hora."');";
        mysqli_query($ap, $sqlInsertcat);
        $resultado['Mensaje'] = "registro guardado con exito";
      } else if(isset($_POST['op']) && $_POST['op']=='insert pro') {
        $pro_codigo = $_POST['pro_codigo'];
        $pro_nombre = $_POST['pro_nombre'];
        $pro_imagen = $_POST['pro_imagen'];
        $pro_descripcion = $_POST['pro_descripcion'];
        $pro_cantidad = $_POST['pro_cantidad'];
        $pro_ingredientes = $_POST['pro_ingredientes'];
        $pro_obs = $_POST['pro_obs'];
        $insertPro = "INSERT INTO res_productos(pro_codigo,pro_nombre,pro_imagen,pro_descripcion,pro_cantidad,pro_ingredientes,pro_obs)
                VALUES('".$pro_codigo."','".$pro_nombre."','".$pro_imagen."','".$pro_descripcion."','".$pro_cantidad."','".$pro_ingredientes."','".$pro_obs."');";
        mysqli_query($ap, $insertPro);
        $resultado['Mensaje'] = "producto guardado con exito";
      }
      break;
    case 'PUT':
      $_PUT = json_decode(file_get_contents('php://input'), true);
      $cat_id = $_PUT['cat_id'];
      $consultaExiste = "SELECT count(*) nr FROM res_categorias WHERE cat_id='".$cat_id."';";
      $rer = mysqli_query($ap, $consultaExiste);
      $row = mysqli_fetch_array($rer);
      if ($row['nr']>0) {
        $cat_activo = $_PUT['cat_activo'];
        if ($cat_activo === 1 || $cat_activo === 0) {
          $consultaActivo = "UPDATE res_categorias SET cat_activo=".$cat_activo." WHERE cat_id=".$cat_id.";";
          mysqli_query($ap, $consultaActivo);
          $resultado['Mensaje'] = "Actividad actualizada";
        } else {
          $cat_nombre = $_PUT['cat_nombre'];
          $cat_img = $_PUT['cat_imagen'];
          $cat_descripcion = $_PUT['cat_descripcion'];
          $cat_fecha =date("y-m-d");
          $cat_hora =date("H:t:s");
          $consAct = "UPDATE res_categorias SET cat_nombre='".$cat_nombre."', cat_imagen='".$cat_img."', cat_descripcion='".$cat_descripcion."', cat_fecha='".$cat_fecha."', cat_hora='".$cat_hora."' WHERE cat_id=".$cat_id.";";
          mysqli_query($ap, $consAct);
          $resultado['Mensaje'] = "Registro actualizado con exito!!";
        }
      } else {
        $resultado['Mensaje']= "no se pudo bb";
      }
      break;
    case 'DELETE':
      break;
  }
echo json_encode($resultado);
?>

<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
include("../static/bd/conexion.php");
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
          $resultado["Categorias"] = $arr_cat;
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
            if(isset($_POST['op']) && $_POST['op']=='cat'){
                $cat_nombre = $_POST['cat_nombre'];
                $cat_imagen = $_POST['cat_imagen'];
                $cat_descripcion = $_POST['cat_descripcion'];
                $cat_fecha = date("Y-m-d");
                $cat_hora = date("H:i:s");
                $sqlInserCat = "Insert Into res_categorias(cat_id,cat_nombre,cat_imagen,cat_descripcion,cat_fecha,cat_hora) 
                VALUES(Null,'".$cat_nombre."','".$cat_imagen."','".$cat_descripcion."','".$cat_fecha."','".$cat_hora."');";
                mysqli_query($ap, $sqlInserCat);
                $resultado['Mensaje'] = "Registro Guardado con éxito!";
            }elseif(isset($_POST['op']) && $_POST['op']=='mesa'){
                //$mes_id = $_POST['mes_id'];
                $identificacion = $_POST['mes_identificacion'];
                $consMesa = "SELECT * FROM mesa WHERE mes_identificacion=".$identificacion." AND mes_fecha=Current_Date() AND mes_ocupado=1;";
                $recordset =mysqli_query($ap, $consMesa);
                $registro = mysqli_fetch_array($recordset);
                $fecha = date("Y-m-d");
                $hora = date("H:i:s");
                if($registro['mes_id'] !='') {
                    $pro_id = $_POST['pro_id'];
                    $consultaCatpro = "SELECT * FROM cat_pro WHERE pro_id=".$pro_id;
                    $recordidp = mysqli_query($ap, $consultaCatpro);
                    $cp_id = mysqli_fetch_array($recordidp);
                    $mes_id = $registro['mes_id'];
                    $pro_cant = $_POST['pro_cant'];
                    $mes_mesa= $_POST['mes_mesa'];
                    if ($cp_id['cp_id']!=0) {
                        $pro_ocupado = 1;
                        $pro_obs = "";
                        $sqlInserPed = "INSERT INTO pedido (cp_id, mes_id, ped_fecha, ped_hora, ped_cant) VALUES ('".$cp_id['cp_id']."','".$mes_id."','".$fecha."','".$hora."','".$pro_cant."');";
                        mysqli_query($ap, $sqlInserPed);
                        $resultado['Mensaje'] = "Registro guardado";
                    } else {
                        $resultado['Mensaje'] = "Producto no existen";
                    }
                }else {
                    $mes_identificacion = $_POST['mes_identificacion'];
                    $mes_ocupado = 1;
                    $mes_mesa= $_POST['mes_mesa'];
                    $mes_obs = $_POST['mes_obs'];
                    $mes_puestos = $_POST['mes_puestos'];
                    $sqlInsertMesa = "INSERT INTO mesa(mes_id, mes_identificacion, mes_obs, mes_puestos, mes_mesa, mes_ocupado, mes_fecha, mes_hora)VALUES(Null,'".$mes_identificacion."','".$mes_obs."','".$mes_puestos."','".$mes_mesa."','".$mes_ocupado."','".$fecha."','".$hora."');";
                    mysqli_query($ap, $sqlInsertMesa);
                    $pro_id = $_POST['pro_id'];
                    $consultaCatpro = "SELECT * FROM cat_pro WHERE pro_id=".$pro_id;
                    $recordidp = mysqli_query($ap, $consultaCatpro);
                    $cp_id = mysqli_fetch_array($recordidp);
                    $resultado['Mensaje'] = "Registro Guardado";
                    $mes_id = $registro['mes_id'];
                    $pro_cant = $_POST['pro_cant'];
                    if ($cp_id['cp_id']!=0) {
                        $pro_ocupado = 1;
                        $pro_obs = "";
                        $sqlInserPed = "INSERT INTO pedido(cp_id, mes_id, ped_fecha, ped_hora, ped_cant) VALUES ('".$cp_id['cp_id']."','".$mes_id."','".$fecha."','".$hora."','".$pro_cant."');";
                        mysqli_query($ap, $sqlInserPed);
                        $resultado['Mensaje'] = "Registro Guardado";
                    } else {
                        $resultado['Mensaje'] = "Producto no existen";
                    }
                }
            }
          break;
    case 'PUT':
      $_PUT = json_decode(file_get_contents('php://input'), true);
      if (isset($_PUT['op']) && $_PUT['op'] == 'editar cat') {
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
      }else if (isset($_PUT['op']) && $_PUT['op'] == 'editar producto') {
        $pro_id = $_PUT['pro_id'];
        $consultaExiste = "SELECT count(*) nr FROM res_productos WHERE pro_id='".$pro_id."';";
        $rer = mysqli_query($ap, $consultaExiste);
        $row = mysqli_fetch_array($rer);
        if ($row['nr']>0) {
          $pro_activo = $_PUT['pro_activo'];
          if ($pro_activo === 0 || $pro_activo === 1) {
            $consultaActivo = "UPDATE res_productos SET pro_activo=".$pro_activo." WHERE pro_id=".$pro_id.";";
            mysqli_query($ap, $consultaActivo);
            $resultado['MENSAJE'] = "Actividad actualizada";
          } else {
            $pro_codigo = $_PUT['pro_codigo'];
            $pro_nombre = $_PUT['pro_nombre'];
            $pro_imagen = $_PUT['pro_imagen'];
            $pro_descripcion = $_PUT['pro_descripcion'];
            $pro_cantidad = $_PUT['pro_cantidad'];
            $pro_ingredientes = $_PUT['pro_ingredientes'];
            $pro_obs = $_PUT['pro_obs'];
            $pro_fecha =date("y-m-d");
            $pro_hora = date("H:t:s");
            $actualizarRe = "UPDATE res_categorias SET pro_codigo='".$pro_codigo."', pro_nombre='".$pro_nombre."', pro_imagen='".$pro_imagen."', pro_descripcion='".$pro_descripcion."', pro_cantidad='".$pro_cantidad."', pro_ingredientes='".$pro_ingredientes."', pro_obs='".$pro_obs."', pro_fecha='".$pro_fecha."', pro_hora='".$pro_hora."' WHERE pro_id='".$pro_id."';";
            mysqli_query($ap, $actualizarRe);
            $resultado['MENSAJE'] = "registro actualizado";
          }
        } else {
          $resultado['MENSAJE'] ="no pudimos :c";
        }
      }
      break;
    case 'DELETE':
      break;
  }
echo json_encode($resultado);
?>

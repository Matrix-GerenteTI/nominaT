<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "lista":{
			$array = array();
			$tabla = 'p'.$_POST['id'];
			$nip = $_POST['nip'];
			//Obtenemos el id del contrato activo
			$query1 = "SELECT * FROM pcontrato WHERE nip='".$nip."' AND status=1";
			$sql1 = $conexion->query($query1);
			$row1 =$sql1->fetch_assoc();
			$contrato = $row1['id'];
			$inner = "";
			$select = "";
			//Sacamos los campos de la tabla a consultar para poder hacer las relaciones
			$query2 = "SHOW COLUMNS FROM ".$tabla;
			$sql2 = $conexion->query($query2);
			while($row2 =$sql2->fetch_assoc()){
				if($row2['Field']!='status' && $row2['Field']!='idcontrato'){
					if(strlen($row2['Field'])>2){
						if(strpos($row2['Field'],'id') === false){
							$select.= $tabla.".".$row2['Field']." as ".$row2['Field'].",";
						}else{
							if(strpos($row2['Field'],'id')>1){
								$select.= $tabla.".".$row2['Field']." as ".$row2['Field'].",";
							}else{
								$tablainner = substr($row2['Field'],2,strlen($row2['Field']));
								$select.= "c".substr($row2['Field'],2,strlen($row2['Field'])).".descripcion as ".substr($row2['Field'],2,strlen($row2['Field'])).", ";
								$inner.= " INNER JOIN c".$tablainner." ON ".$tabla.".".$row2['Field']."=c".$tablainner.".id";
							}
						}
					}else{
						$select.= $tabla.".".$row2['Field']." as ".$row2['Field'].",";
					}
				}
			}
			
			$select = substr($select,0,-1);
			
			$query = "SELECT 	".$select." 
					  FROM 		".$tabla." 
					  ".$inner."
					  WHERE 	".$tabla.".idcontrato='".$contrato."' AND ".$tabla.".status=1";
			$sql = $conexion->query($query);
			//echo $query;
			//die();
			while($row =$sql->fetch_assoc()){
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case "add":{
			$tabla = "p".$_POST['id'];
			$nip = $_POST['nip'];
			//Obtenemos el id del contrato activo
			$query1 = "SELECT * FROM pcontrato WHERE nip='".$nip."' AND status=1";
			$sql1 = $conexion->query($query1);
			$row1 =$sql1->fetch_assoc();
			$contrato = $row1['id'];
			//creamos el string para el insert de los campos enviados
			$campos = "";
			$valores = "";
			foreach($_POST as $clave => $valor){
				if($clave!='id' && $clave!='nip'){
					$campos.= $clave.",";
					$valores.= "'".$valor."',";
				}
			}
			//Hacemos el insert
			$campos = substr($campos,0,-1);
			$valores = substr($valores,0,-1);
			$query = "INSERT INTO ".$tabla." (status,idcontrato,".$campos.") VALUES (1,".$contrato.",".$valores.")";
			$sql = $conexion->query($query);
			if(!$sql){
				echo 0;
			}else{
				echo 1;
			}
			break;
		}
		
		case "delete":{
			$tabla = "p".$_POST['id'];
			$id = $_POST['item'];
			$query = "UPDATE ".$tabla." SET status=0 WHERE id='".$id."'";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
	}

function CalculaEdad( $fecha ) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function CalculaAntiguedadSAT( $fecha ) {
    $fecha1 = new DateTime($fecha);
	$fecha2 = new DateTime(date('Y-m-d'));
	$fecha = $fecha1->diff($fecha2);
	return "P".$fecha->y."Y".$fecha->m."M".$fecha->d."D";
}

function formateaFechaSLASH($fecha){
	$arr = explode("/",$fecha);
	$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
	return $fechaNueva;	
}

function formateaFechaGUION($fecha){
	$arr = explode("-",$fecha);
	$fechaNueva = $arr[2]."/".$arr[1]."/".$arr[0];
	return $fechaNueva;	
}

function calculaIMC($peso,$estatura){
	if($estatura>0 && $peso>0)
		$imcnum = $peso/($estatura*$estatura);
	else
		return "";
	if($imcnum<18)
		return "PESO BAJO";
	if($imcnum>=18 && $imcnum<25)
		return "NORMAL";
	if($imcnum>=25 && $imcnum<27)
		return "SOBREPESO";
	if($imcnum>=27)
		return "OBESIDAD";
}
?>
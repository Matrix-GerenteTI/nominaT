<?php
	session_start();
	require_once("mysql.php");
	conexion();
	$op = $_GET['op'];
	switch($op){
		case "listaPacientes":{
			$array = array();
			$query = "SELECT * FROM expedientes GROUP BY pnombre";
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql)){
				$array[] = $row;	
			}
			echo json_encode($array);
			break;
		}
		
		case "guardar":{
			$query = "INSERT INTO recibos  (idexpediente,
											paciente,
											concepto,
											fecha,
											hora,
											monto,
											idusuario)
									VALUES ('".$_POST['expediente']."',
											'".$_POST['paciente']."',
											'".$_POST['concepto']."',
											'".formateaFecha($_POST['fecha'])."',
											NOW(),
											'".$_POST['monto']."',
											'".$_SESSION['userid']."')";
			$sql = mysql_query($query);
			$id = mysql_insert_id();
			if(!$sql)
				echo 0;
			else
				echo $id;
			break;
		}
		
		case "cancelar":{
			$folio = $_POST['folio'];
			$query = "UPDATE recibos SET status=99 WHERE folio=".$folio;
			$sql = mysql_query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
	}

function formateaFecha($fecha){
	$pos = strpos($fecha,"/");
	if($pos>0){
		$arr = explode("/",$fecha);
		$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
	}
	else{
		$arr = explode("-",$fecha);
		$fechaNueva = $arr[2]."/".$arr[1]."/".$arr[0];
	}
	return $fechaNueva;	
}

?>
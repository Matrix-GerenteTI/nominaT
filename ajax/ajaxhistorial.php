<?php
	session_start();
	require_once("mysql.php");
	conexion();
	$op = $_GET['op'];
	switch($op){
		case "lista":{
			$filtro = $_POST['filtro'];
			$_POST['fecIni']==""?$fecIni="2000-01-01":$fecIni=formateaFecha($_POST['fecIni']);
			$_POST['fecFin']==""?$fecFin=date("Y-m-d"):$fecFin=formateaFecha($_POST['fecFin']);
			$array = array();
			$query = "SELECT e.folio as folio,
							 u.username as username,
							 e.pnombre as pnombre,
							 u.equipo as equipo,
							 e.fecha as fecha 
					  FROM   expedientes e,
							 usuarios u
					  WHERE  e.idusuario=u.username
					  AND 	 e.status>0
					  AND	 (e.pnombre LIKE '%".$filtro."%' OR u.nombre LIKE '%".$filtro."%')
					  AND	 e.fecha>='".$fecIni."' AND e.fecha<='".$fecFin."'";
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql)){
				$array[] = $row;	
			}
			echo json_encode($array);
			break;
		}
		
		case "eliminar":{
			$folio = $_POST['folio'];
			$query = "UPDATE expedientes SET status=0 WHERE folio='".$folio."'";
			$sql = mysql_query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "verExpediente":{
			$_SESSION['folio'] = $_POST['folio'];
			$_SESSION['userid'] = $_POST['folio'];
			break;
		}
		
		case "registrardia":{
			$pesoact = $_POST['pesoact'];
			$pesobaj = $_POST['pesobaj'];
			$semana = $_POST['semana'];
			$dia = $_POST['dia'];
			$reto = $_POST['reto'];
			
			$n=0;
			$query = "SELECT * FROM usuario_reto WHERE idusuario='".$_SESSION['userid']."' AND idreto='".$reto."' AND status<>99";
			$sql = mysql_query($query);
			while($row = mysql_fetch_array($sql)){
				$n = $row['id'];
			}
			
			if($n>0){
				$query = "UPDATE usuario_reto SET peso=".$pesoact.",bajado=".$pesobaj." WHERE id=".$n;
				$sql = mysql_query($query);
				if($sql){
					echo 1;	
				}
			}else{
				$query = "INSERT INTO usuario_reto (idusuario,idreto,peso,bajado,status) VALUES ('".$_SESSION['userid']."',".$reto.",".$pesoact.",".$pesobaj.",1)";
				$sql = mysql_query($query);
				if($sql){
					echo 1;	
				}
			}
			
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
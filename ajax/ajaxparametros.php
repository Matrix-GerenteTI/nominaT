<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "carga":{
			$n = 0;
			$query = "SELECT 	*
					  FROM 		cparametrosasistencia p
					  WHERE 	p.idpuesto=".$_POST['puesto']."
					  AND 		p.status=1";
			$sql = $conexion->query($query);
			$row1 = $sql->fetch_assoc();
			echo json_encode($row1);
			break;
		}
		
		case "guardar":{
			$n = 0;
			$q = "SELECT * FROM cparametrosasistencia WHERE idpuesto='".$_POST['puesto']."'";		
			$s =  $conexion->query($q);
			while($r = $s->fetch_assoc()){
				$n++;
			}
			if($n>0){
				$query1 = "UPDATE cparametrosasistencia SET entrada='".$_POST['entrada']."',
															entradai='".$_POST['entradai']."',
															salidai='".$_POST['salidai']."',
															salida='".$_POST['salida']."',
															tolerancia='".$_POST['tolerancia']."',
															retardospfalta='".$_POST['retardos']."',
															corrido='".$_POST['corrido']."',
															faltaspdescuento='".$_POST['faltas']."'
													  WHERE idpuesto='".$_POST['puesto']."'";
				$sql1 = $conexion->query($query1);
				if(!$sql1){
					echo 0;
				}else{
					echo 1;
				}
			}else{
				$query1 = "INSERT INTO cparametrosasistencia (entrada,
															  entradai,
															  salidai,
															  salida,
															  tolerancia,
															  retardospfalta,
															  faltaspdescuento,
															  corrido,
															  idpuesto,
															  status)
													VALUES 	 ('".$_POST['entrada']."',
															  '".$_POST['entradai']."',
															  '".$_POST['salidai']."',
															  '".$_POST['salida']."',
															  '".$_POST['tolerancia']."',
															  '".$_POST['retardos']."',
															  '".$_POST['faltas']."',
															  '".$_POST['corrido']."',
															  '".$_POST['puesto']."',
															  1)";
				$sql1 = $conexion->query($query1);
				if(!$sql1){
					echo 0;
				}else{
					echo 1;
				}
			}
			
			break;
		}
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
?>
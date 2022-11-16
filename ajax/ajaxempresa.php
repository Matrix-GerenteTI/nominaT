<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "comboSelected":{
			$catalogo = $_POST['catalogo'];
			//$scatalogo = $_POST['scatalogo'];
			$echo = "";
			if(isset($_POST['padre']))
				$query = "SELECT * FROM c".$catalogo." WHERE id".$_POST['padre']."=".$_POST['valorpadre']." AND status=1";
			else
				$query = "SELECT * FROM c".$catalogo." WHERE status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc())
				if($_POST['valor']==$row['id'])
					$echo.= '<option value="'.$row['id'].'" selected>'.strtoupper($row['descripcion']).'</option>';
				else
					$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
			echo $echo;
			break;
		}
		
		case "listaDeptos":{
			$query = "SELECT 	*								
					  FROM 		cdepartamento
					  WHERE 		status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case "listaPuestos":{
			$query = "SELECT 	p.id as id,
								p.descripcion as puesto,
								d.descripcion as departamento
					  FROM 		cpuesto p
					  INNER JOIN cdepartamento d ON p.iddepartamento=d.id
					  AND 		p.status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case "guardaDepto":{
			$departamento = $_POST['descripcion'];
			$query = "INSERT INTO cdepartamento (idpatron,descripcion,status,idpadre) VALUES (1,'".$departamento."',1,0)";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "guardaPuesto":{
			$departamento = $_POST['iddepartamento'];
			$puesto = $_POST['descripcion'];
			$query = "INSERT INTO cpuesto (iddepartamento,descripcion,status,idpadre) VALUES ('".$departamento."','".$puesto."',1,0)";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "eliminaItem":{
			$tabla = $_POST['tabla'];
			$id = $_POST['id'];
			$query = "UPDATE c".$tabla." SET status=99 WHERE id='".$id."'";
			$sql = $conexion->query($query);
			if(!$sql)
				echo 0;
			else
				echo 1;
			break;
		}
		
		case "carga":{
			$query = "SELECT 	*, s.id as estado
					  FROM 		ppatron e
					  INNER JOIN pdireccion d ON d.id=e.iddireccion
					  INNER JOIN cestado s ON s.id=d.idestado
					  WHERE 	e.status=1";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			echo json_encode($row);
			break;
		}
		
		case "guardar":{			
			$query1 = "UPDATE ppatron SET 	rfc='".$_POST['rfc']."',
											nombre_razsoc='".$_POST['nombre']."',
											curp='".$_POST['curp']."',
											registropatronal='".$_POST['registropatronal']."',
											idregimenfiscal='".$_POST['regimenfiscal']."',
											telefono='".$_POST['telefono']."',
											email='".$_POST['email']."'
									  WHERE id='1'";
			$sql1 = $conexion->query($query1);
			if(!$sql1){
				echo 0;
			}else{
				$query2 = "UPDATE pdireccion SET 	calle='".$_POST['calle']."',
													numext='".$_POST['numext']."',
													numint='".$_POST['numint']."',
													colonia='".$_POST['colonia']."',
													municipio='".$_POST['municipio']."',
													cp='".$_POST['cp']."',
													idestado='".$_POST['estado']."'
											  WHERE id='1'";
				$sql2 = $conexion->query($query2);
				if(!$sql2){
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
<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		case "lista":{
			$array = array();
			$query = "SELECT * FROM pusuarios WHERE status>0";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;	
			}
			echo json_encode($array);
			break;
		}
		
		case "cargar":{
			$id = $_POST['id'];
			$query = "SELECT * FROM pusuarios WHERE username='".$id."'";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc($sql);
			echo json_encode($row);
			break;
		}
		
		case "guardar":{
			$id = $_POST['username'];
			$query = "SELECT COUNT(*) as registros FROM pusuarios WHERE username='".$id."'";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			if($row['registros']>0){
				$query = "UPDATE pusuarios SET password='".$_POST['password']."',
											  tipo='".$_POST['tipo']."',
											  nombre='".$_POST['nombre']."',
											  email='".$_POST['email']."'
										WHERE username='".$id."'";
				$sql = $conexion->query($query);
				if(!$sql)
					echo 0;
				else
					echo 1;
			}else{
				$query = "INSERT INTO pusuarios (username,
											    password,
											    tipo,
											    nombre,
											    email)
										VALUES ('".$id."',
												'".$_POST['password']."',
												'".$_POST['tipo']."',
												'".$_POST['nombre']."',
												'".$_POST['email']."')";
				$sql = $conexion->query($query);
				if(!$sql)
					echo 0;
				else
					echo 1;
			}
			break;
		}
		
		case "eliminar":{
			$id = $_POST['username'];
			$query = "SELECT COUNT(*) as registros FROM pusuarios WHERE username='".$id."'";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			if($row['registros']>0){
				$query2 = "UPDATE pusuarios SET status=0 WHERE username='".$id."'";
				$sql2 = $conexion->query($query2);
				if(!$sql2)
					echo 1;
				else
					echo 2;
			}else
				echo 0;
			break;
		}
	}
?>
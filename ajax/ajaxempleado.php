<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		case "calculaTiempo":{
			$fecha = formateaFechaSLASH($_POST['fecha']);
			if($_POST['tipo']=="edad"){
				$result = CalculaEdad($fecha);
			}
			if($_POST['tipo']=="antiguedadSAT"){
				$result = CalculaAntiguedadSAT($fecha);
			}
			echo $result;
			break;
		}
		
		case "buslista":{
			$departamento = $_POST['departamento'];
			$nombre = $_POST['nombre'];
			$array = array();
			$query = "SELECT 	e.nip as nip,
								e.nombre as nombre,
								d.descripcion as departamento
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  WHERE 	e.nombre LIKE '%".$nombre."%' 
					  AND 		d.id LIKE '%".$departamento."%'
					  AND		e.status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;	
			}
			echo json_encode($array);
			break;
		}
		
		case "comboCatalogo":{
			$catalogo = $_POST['catalogo'];
			//$scatalogo = $_POST['scatalogo'];
			$prefijo = '';
			if(isset($_POST['prefijo']))
				$prefijo = $_POST['prefijo'];
			
			if($_POST['tipo']==3)
				$echo = "<option value=''>TODOS...</option>";
			else
				$echo = "";
			if(isset($_POST['scatalogo'])){
				$query = "SELECT * FROM c".$_POST['scatalogo']." WHERE id".$catalogo."=".$_POST['id']." AND status=1";
			}else{
				$query = "SELECT * FROM c".$catalogo." WHERE status=1";
			}
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				if($prefijo=='percepciones_'){
					if($row['id']!='019' && $row['id']!='022' && $row['id']!='023' && $row['id']!='025' && $row['id']!='039' && $row['id']!='044'){
						$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
					}
				}
				if($prefijo=='jubilaciones_'){
					if($row['id']=='039' || $row['id']=='044'){
						$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
					}
				}
				if($prefijo=='separaciones_'){
					if($row['id']=='022' || $row['id']=='023' || $row['id']=='025'){
						$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
					}
				}
				if($prefijo!='percepciones_' && $prefijo!='jubilaciones_' && $prefijo!='separaciones_'){
					$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
				}
			}
			echo $echo;
			break;
		}
		
		case "cargar":{
			$nip = $_POST['nip'];
			$query = "SELECT 	*,
								e.nip as nip,
								d.idestado as estado,
								a.descripcion as departamento,
								p.descripcion as puesto
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN pdireccion d ON e.nip=d.nip 
					  INNER JOIN cdepartamento a ON a.id=c.iddepartamento 
					  INNER JOIN cpuesto p ON p.id=c.idpuesto
					  WHERE 	e.nip='".$nip."'";
			$sql = $conexion->query($query);
			$row =$sql->fetch_assoc();
			echo json_encode($row);
			break;
		}
		
		case "guardar":{
			$nip = $_POST['nip'];
			$conexion->autocommit(false);
			try{
				$query = "SELECT COUNT(*) as registros FROM pempleado WHERE nip='".$nip."'";
				$sql = $conexion->query($query);
				$row = $sql->fetch_assoc();
				if($row['registros']>0){
					$query1 = "UPDATE pempleado SET rfc='".$_POST['rfc']."',
													nombre='".$_POST['nombre']."',
													curp='".$_POST['curp']."',
													nss='".$_POST['nss']."',
													fechanac='".formateaFechaSLASH($_POST['fecnac'])."',
													edocivil='".$_POST['edocivil']."',
													sexo='".$_POST['sexo']."',
													email='".$_POST['email']."',
													telefono='".$_POST['telefono']."',
													celular='".$_POST['celular']."'
											  WHERE nip='".$nip."'";
					$sql1 = $conexion->query($query1);
					
					$query2 = "UPDATE pdireccion SET 	calle='".$_POST['calle']."',
														numext='".$_POST['numext']."',
														numint='".$_POST['numint']."',
														colonia='".$_POST['colonia']."',
														municipio='".$_POST['municipio']."',
														cp='".$_POST['cp']."',
														idestado='".$_POST['estado']."'
												  WHERE nip='".$nip."'";
					$sql2 = $conexion->query($query2);
					
					$query3 = "UPDATE pcontrato SET	 	 iddepartamento='".$_POST['departamento']."',
														 idpuesto='".$_POST['puesto']."',
														 idtipocontrato='".$_POST['tipocontrato']."',
														 idtipojornada='".$_POST['tipojornada']."',
														 fechaterminolab='".formateaFechaSLASH($_POST['terminolaboral'])."',
														 fechainiciolab='".formateaFechaSLASH($_POST['iniciolaboral'])."',
														 sindicalizado='".$_POST['sindicalizado']."',
														 idtiporegimen='".$_POST['tiporegimen']."',
														 idriesgopuesto='".$_POST['riesgopuesto']."',
														 idperiodicidadpago='".$_POST['periodicidadpago']."',
														 salariobase='".$_POST['salariobase']."',
														 salariodiario='".$_POST['salariodiario']."',
														 idbanco='".$_POST['banco']."',
														 cuentabancaria='".$_POST['cuentabancaria']."',
														 subrfc='".$_POST['subrfc']."',
														 subporcentaje='".$_POST['subporcentaje']."'
												 WHERE 	 nip='".$nip."'";
					$sql3 = $conexion->query($query3);
					
				}else{
					$query = "INSERT INTO pempleado   (rfc,
													   nombre,
													   curp,
													   nss,
													   fechanac,
													   edocivil,
													   sexo,
													   email,
													   telefono,
													   celular,
													   idpatron,
													   status)
												VALUES ('".$_POST['rfc']."',
													   '".$_POST['nombre']."',
													   '".$_POST['curp']."',
													   '".$_POST['nss']."',
													   '".formateaFechaSLASH($_POST['fecnac'])."',
													   '".$_POST['edocivil']."',
													   '".$_POST['sexo']."',
													   '".$_POST['email']."',
													   '".$_POST['telefono']."',
													   '".$_POST['celular']."',
													   1,
													   1)";
					$sql = $conexion->query($query);
					
					$nip = $conexion->insert_id;
					$query2 = "INSERT INTO pdireccion 	(nip,
														 calle,
														 numext,
														 numint,
														 colonia,
														 municipio,
														 cp,
														 idestado,
														 status)
												 VALUES ('".$nip."',
														 '".$_POST['calle']."',
														 '".$_POST['numext']."',
														 '".$_POST['numint']."',
														 '".$_POST['colonia']."',
														 '".$_POST['municipio']."',
														 '".$_POST['cp']."',
														 '".$_POST['estado']."',
														 1)";
					$sql2 = $conexion->query($query2);
						
					$direccion = $conexion->insert_id;
					$query3 = "INSERT INTO pcontrato 	(nip,
														 iddireccion,
														 iddepartamento,
														 idpuesto,
														 idtipocontrato,
														 idtipojornada,
														 fechainiciolab,
														 fechaterminolab,
														 sindicalizado,
														 idtiporegimen,
														 idriesgopuesto,
														 idperiodicidadpago,
														 salariobase,
														 salariodiario,
														 idbanco,
														 cuentabancaria,
														 subrfc,
														 subporcentaje,
														 status)
												 VALUES ('".$nip."',
														 '".$direccion."',
														 '".$_POST['departamento']."',
														 '".$_POST['puesto']."',
														 '".$_POST['tipocontrato']."',
														 '".$_POST['tipojornada']."',
														 '".formateaFechaSLASH($_POST['iniciolaboral'])."',
														 '".formateaFechaSLASH($_POST['terminolaboral'])."',
														 '".$_POST['sindicalizado']."',
														 '".$_POST['tiporegimen']."',
														 '".$_POST['riesgopuesto']."',
														 '".$_POST['periodicidadpago']."',
														 '".$_POST['salariobase']."',
														 '".$_POST['salariodiario']."',
														 '".$_POST['banco']."',
														 '".$_POST['cuentabancaria']."',
														 '".$_POST['subrfc']."',
														 '".$_POST['subporcentaje']."',
														 1)";
					$sql3 = $conexion->query($query3);	
				}
				$conexion->commit();
				echo $nip;
			}catch(Exception $e){
				$conexion->rollback();
				echo 0;
			}
			break;
		}
		
		case "eliminar":{
			$nip = $_POST['nip'];
			$query = "SELECT COUNT(*) as registros FROM pempleado WHERE nip='".$nip."'";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			if($row['registros']>0){
				$query = "UPDATE pempleado SET status=99 WHERE nip='".$nip."'";
				$sql = $conexion->query($query);
				if(!$sql)
					echo 0;
				else
					echo 1;
			}else
				echo 2;
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
	if ( sizeof( $arr) > 1 ) {
		$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
		return $fechaNueva;	
	}
	return $fecha;
}

function formateaFechaGUION($fecha){
	$arr = explode("-",$fecha);
	if ( sizeof( $arr ) > 1) {
		$fechaNueva = $arr[2]."/".$arr[1]."/".$arr[0];
		return $fechaNueva;	
	}

	return $fecha;
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
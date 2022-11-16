<?php
	session_start();
	require_once("mysql.php");
	conexion();
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
		
		case "calculaIMC":{
			$peso = $_POST['peso'];
			$talla = $_POST['talla'];
			$imc = calculaIMC($peso,$talla);
			echo $imc;
			break;
		}
		
		case "obtenerFolio":{
			$query = "SELECT AUTO_INCREMENT as folio FROM information_schema.TABLES where TABLE_SCHEMA='nomina' and TABLE_NAME='pempleado'";
			$sql = mysql_query($query);
			$row = mysql_fetch_assoc($sql);
			echo $row['folio'];
			break;
		}
		
		case "comboCatalogo":{
			$catalogo = $_POST['catalogo'];
			//$scatalogo = $_POST['scatalogo'];
			$echo = "";
			if(isset($_POST['scatalogo'])){
				$query = "SELECT * FROM c".$_POST['scatalogo']." WHERE id".$catalogo."=".$_POST['id']." AND status=1";
			}else{
				$query = "SELECT * FROM c".$catalogo." WHERE status=1";
			}
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql))
				$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['descripcion']).'</option>';
			echo $echo;
			break;
		}
		
		case "comboDoctor":{
			$echo = "";
			$query = "SELECT * FROM usuarios WHERE tipo='DOCTOR' AND status=1";
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql))
				$echo.= '<option value="'.$row['nombre'].'">'.strtoupper($row['nombre']).'</option>';
			echo $echo;
			break;
		}
		
		case "comboCirugia":{
			$echo = "";
			$query = "SELECT * FROM cirugias WHERE status=1";
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql))
				$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['cirugia']).'</option>';
			echo $echo;
			break;
		}
		
		case "comboDoctor2":{
			$echo = "<option value='%'>TODOS</option>";
			$query = "SELECT * FROM usuarios WHERE tipo='DOCTOR' AND status=1";
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql))
				$echo.= '<option value="'.$row['username'].'">'.strtoupper($row['nombre']).'</option>';
			echo $echo;
			break;
		}
		
		case "comboCirugia2":{
			$echo = "<option value='%'>TODOS</option>";
			$query = "SELECT * FROM cirugias WHERE status=1";
			$sql = mysql_query($query);
			while($row = mysql_fetch_assoc($sql))
				$echo.= '<option value="'.$row['id'].'">'.strtoupper($row['cirugia']).'</option>';
			echo $echo;
			break;
		}
		
		case "cargar":{
			$usuario = $_SESSION['userid'];
			$query = "SELECT *,e.status as status FROM expedientes e,datosmedicos m WHERE e.folio=m.idexpediente AND e.pemail='".$usuario."'";
			$sql = mysql_query($query);
			$row = mysql_fetch_assoc($sql);
			echo json_encode($row);
			break;
		}
		
		case "guardar":{
			$folio = $_POST['folio'];
			$query = "SELECT COUNT(*) as registros FROM expedientes WHERE folio='".$folio."'";
			$sql = mysql_query($query);
			$row = mysql_fetch_assoc($sql);
			if($row['registros']>0){
				$query1 = "UPDATE expedientes SET doctor='".$_POST['doctor']."',
											  	 cirugia='".$_POST['cirugia']."',
											  	 pnombre='".$_POST['pnombre']."',
												 fecha='".formateaFechaSLASH($_POST['fecha'])."',
											  	 pfecnac='".formateaFechaSLASH($_POST['pfecnac'])."',
												 pedad='".$_POST['pedad']."',
												 pedocivil='".$_POST['pedocivil']."',
												 psexo='".$_POST['psexo']."',
												 pdomicilio='".$_POST['pdomicilio']."',
												 ptelparticular='".$_POST['ptelparticular']."',
												 pteltrabajo='".$_POST['pteltrabajo']."',
												 pcelular='".$_POST['pcelular']."',
												 pemail='".$_POST['pemail']."',
												 pfacebook='".$_POST['pfacebook']."',
												 ptwitter='".$_POST['ptwitter']."',
												 rnombre='".$_POST['rnombre']."',
												 rtelefono='".$_POST['rtelefono']."',
												 avisara='".$_POST['avisara']."',
												 avisartelefonos='".$_POST['avisartelefonos']."',
												 peso='".$_POST['peso']."',
												 talla='".$_POST['talla']."',
												 imc='".$_POST['imc']."',
												 operyproc='".$_POST['operyproc']."'
										   WHERE folio='".$folio."'";
				$sql1 = mysql_query($query1);
				if(!$sql1)
					echo 0;
				else{
					$query2 = "UPDATE datosmedicos SET 	ultimoexamenfisico='".$_POST['ultimoexamenfisico']."',
													 	ultimaradiografia='".$_POST['ultimaradiografia']."',
													  	ultimoelectrocardiograma='".$_POST['ultimoelectrocardiograma']."',
													  	anestesiaraquia='".$_POST['anestesiaraquia']."',
														anestesiageneral='".$_POST['anestesiageneral']."',
														anestesialocal='".$_POST['anestesialocal']."',
														anestesiareacciones='".$_POST['anestesiareacciones']."',
														anestesiafiebre='".$_POST['anestesiafiebre']."',
														usteddientesflojos='".$_POST['usteddientesflojos']."',
														usteddientespostizos='".$_POST['usteddientespostizos']."',
														ustedcubiertosporcelana='".$_POST['ustedcubiertosporcelana']."',
														ustedabrirboca='".$_POST['ustedabrirboca']."',
														ustedpestaniaspostizas='".$_POST['ustedpestaniaspostizas']."',
														ustedlentescontacto='".$_POST['ustedlentescontacto']."',
														usteddefectosfisicos='".$_POST['usteddefectosfisicos']."',
														medantidepresivos='".$_POST['medantidepresivos']."',
														medantidepresivoscual='".$_POST['medantidepresivoscual']."',
														medantihipertensivos='".$_POST['medantihipertensivos']."',
														medantihipertensivoscual='".$_POST['medantihipertensivoscual']."',
														medanticuagulantes='".$_POST['medanticuagulantes']."',
														medanticuagulantescual='".$_POST['medanticuagulantescual']."',
														medanticuagulantesdosis='".$_POST['medanticuagulantesdosis']."',
														meddiabetes='".$_POST['meddiabetes']."',
														meddiabetescual='".$_POST['meddiabetescual']."',
														medotro='".$_POST['medotro']."',
														medotrocual1='".$_POST['medotrocual1']."',
														medotrodosis1='".$_POST['medotrodosis1']."',
														medotrocual2='".$_POST['medotrocual2']."',
														medotrodosis2='".$_POST['medotrodosis2']."'
											   	  WHERE idexpediente='".$folio."'";
					$sql2 = mysql_query($query2);
					if(!$sql2)
						echo 2;
					else
						echo 1;
				}
			}else{
				$query = "INSERT INTO expedientes (doctor,
											  	   cirugia,
											  	   pnombre,
											  	   pfecnac,
												   pedad,
												   pedocivil,
												   psexo,
												   pdomicilio,
												   ptelparticular,
												   pteltrabajo,
												   pcelular,
												   pemail,
												   pfacebook,
												   ptwitter,
												   rnombre,
												   rtelefono,
												   avisara,
												   avisartelefonos,
												   peso,
												   talla,
												   imc,
												   operyproc,
												   fecha,
												   hora,
												   idusuario)
										   VALUES ('".$_POST['doctor']."',
										   		   '".$_POST['cirugia']."',
												   '".$_POST['pnombre']."',
												   '".formateaFechaSLASH($_POST['pfecnac'])."',
												   '".$_POST['pedad']."',
												   '".$_POST['pedocivil']."',
												   '".$_POST['psexo']."',
												   '".$_POST['pdomicilio']."',
												   '".$_POST['ptelparticular']."',
												   '".$_POST['pteltrabajo']."',
												   '".$_POST['pcelular']."',
												   '".$_POST['pemail']."',
												   '".$_POST['pfacebook']."',
												   '".$_POST['ptwitter']."',
												   '".$_POST['rnombre']."',
												   '".$_POST['rtelefono']."',
												   '".$_POST['avisara']."',
												   '".$_POST['avisartelefonos']."',
												   '".$_POST['peso']."',
												   '".$_POST['talla']."',
												   '".$_POST['imc']."',
												   '".$_POST['operyproc']."',
												   '".formateaFechaSLASH($_POST['fecha'])."',
												   NOW(),
												   '".$_SESSION['userid']."')";
				$sql = mysql_query($query);
				if(!$sql)
					echo 0;
				else{
					$idexpediente = mysql_insert_id();
					$query2 = "INSERT INTO datosmedicos (idexpediente,
														 ultimoexamenfisico,
													 	 ultimaradiografia,
													  	 ultimoelectrocardiograma,
													  	 anestesiaraquia,
														 anestesiageneral,
														 anestesialocal,
														 anestesiareacciones,
														 anestesiafiebre,
														 usteddientespostizos,
														 usteddientesflojos,
														 ustedcubiertosporcelana,
														 ustedabrirboca,
														 ustedpestaniaspostizas,
														 ustedlentescontacto,
														 usteddefectosfisicos,
														 medantidepresivos,
														 medantidepresivoscual,
														 medantihipertensivos,
														 medantihipertensivoscual,
														 medanticuagulantes,
														 medanticuagulantescual,
														 medanticuagulantesdosis,
														 meddiabetes,
														 meddiabetescual,
														 medotro,
														 medotrocual1,
														 medotrodosis1,
														 medotrocual2,
														 medotrodosis2)
											   	 VALUES ('".$idexpediente."',
												 		 '".$_POST['ultimoexamenfisico']."',
														 '".$_POST['ultimaradiografia']."',
														 '".$_POST['ultimoelectrocardiograma']."',
														 '".$_POST['anestesiaraquia']."',
														 '".$_POST['anestesiageneral']."',
														 '".$_POST['anestesialocal']."',
														 '".$_POST['anestesiareacciones']."',
														 '".$_POST['anestesiafiebre']."',
														 '".$_POST['usteddientespostizos']."',
														 '".$_POST['usteddientesflojos']."',
														 '".$_POST['ustedcubiertosporcelana']."',
														 '".$_POST['ustedabrirboca']."',
														 '".$_POST['ustedpestaniaspostizas']."',
														 '".$_POST['ustedlentescontacto']."',
														 '".$_POST['usteddefectosfisicos']."',
														 '".$_POST['medantidepresivos']."',
														 '".$_POST['medantidepresivoscual']."',
														 '".$_POST['medantihipertensivos']."',
														 '".$_POST['medantihipertensivoscual']."',
														 '".$_POST['medanticuagulantes']."',
														 '".$_POST['medanticuagulantescual']."',
														 '".$_POST['medanticuagulantesdosis']."',
														 '".$_POST['meddiabetes']."',
														 '".$_POST['meddiabetescual']."',
														 '".$_POST['medotro']."',
														 '".$_POST['medotrocual1']."',
														 '".$_POST['medotrodosis1']."',
														 '".$_POST['medotrocual2']."',
														 '".$_POST['medotrodosis2']."')";
					$sql2 = mysql_query($query2);
					if(!$sql2)
						echo 2;
					else
						echo 1;
				}
			}
			break;
		}
		
		case "eliminar":{
			$folio = $_POST['folio'];
			$query = "SELECT COUNT(*) as registros FROM expedientes WHERE folio='".$folio."'";
			$sql = mysql_query($query);
			$row = mysql_fetch_assoc($sql);
			if($row['registros']>0){
				$query = "UPDATE expedientes SET status=0 WHERE folio='".$folio."'";
				$sql = mysql_query($query);
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
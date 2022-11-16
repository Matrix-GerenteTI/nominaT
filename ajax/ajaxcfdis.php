<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "lista":{
			$departamento = $_POST['depto'];
			$nombre = $_POST['nombre'];
			$fecini = $_POST['fecIni'];
			$fecfin = $_POST['fecFin'];
			$inicio = $_POST['inicio'];
			$cantidad = $_POST['cantidad'];
			$status = $_POST['status'];
			$array = array();
			$query = "SELECT 	e.nip as nip,
								e.nombre as nombre,
								d.descripcion as departamento,
								c.id as idcontrato,
								t.serie,
								t.folio,
								t.wsuuid,
								t.fechaPago								
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  INNER JOIN ptimbrado t ON c.id=t.idcontrato
					  WHERE 	e.nombre LIKE '%".$nombre."%' 
					  AND 		d.id LIKE '%".$departamento."%'";
			if($fecini!="" && $fecfin!="")
				$query.= "AND		t.fechaPago>='".formateaFechaSLASH($fecini)."' AND t.fechaPago<='".formateaFechaSLASH($fecfin)."'";	
			$query.= "AND		e.status>0
					  AND		t.status=".$status."
					  ORDER BY t.folio ASC
					  -- LIMIT ".$inicio.",".$cantidad;
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[] = $row;
			}
			echo json_encode($array);
			break;
		}
		
		case "listaP":{
			$departamento = $_POST['depto'];
			$nombre = $_POST['nombre'];
			$fecini = $_POST['fecIni'];
			$fecfin = $_POST['fecFin'];
			$array = array();
			$query = "SELECT 	COUNT(*) as cantidad								
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  INNER JOIN ptimbrado t ON c.id=t.idcontrato
					  WHERE 	e.nombre LIKE '%".$nombre."%' 
					  AND 		d.id LIKE '%".$departamento."%'";
			if($fecini!="" && $fecfin!="")
				$query.= "AND		t.fechaPago>='".formateaFechaSLASH($fecini)."' AND t.fechaPago<='".formateaFechaSLASH($fecfin)."'";	
			$query.= "AND		e.status>0
					  AND		t.status<>0";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			echo $row['cantidad'];
			break;
		}
		
		case "cancelar":{
			date_default_timezone_set('America/Mexico_City');
			
			
			//satxmlsv($xml,$EmisorRFC,$serie,$folio);
			$validacion = satxmlsv_valida($xml);
				
			if($validacion=='ok'){
				$cfd = satxmlsv($xml,$EmisorRFC,$serie,$folio);
				//echo $cfd;
				//$xmlUTF8 = utf8_encode($xml);
				/*
				$zip = new ZipArchive(); 
				$filename = "./send/".$EmisorRFC."/".$serie."".$folio.".zip";
				 
				if($zip->open($filename,ZIPARCHIVE::CREATE)===true) {
						$zip->addFile("./send/".$EmisorRFC."/".$serie."".$folio.".xml",$serie."".$folio.".xml");
						$zip->close();
						//echo 'Creado '.$filename;
				}
				else {
						echo 'Error creando '.$filename;
						//die();
				}
				¨*/
				$invoice_path = "./send/".$EmisorRFC."/".$serie."".$folio.".xml";
				$xml_file = fopen($invoice_path, "rb");
				$xml_content = fread($xml_file, filesize($invoice_path));
				fclose($xml_file);
				
				$xmlZip = base64_encode($xml_content);				
				
				#DEMO
				//$url = 'http://devcfdi.sifei.com.mx:8080/SIFEI/SIFEI';
				$username = 'sergio@xiontecnologias.com';
				$password = 'P4ssw0rd+';				
				
				#PRODUCCION
				//$url = 'https://facturacion.finkok.com/servicios/soap/stamp.wsdl';
				
				/* Tiempo límite de espera entre la conexión de 10 segundos */
				//$timeout = stream_context_create(array('http' => array('timeout' => 50))); 
				/* Verifica si la url existe */
				//if(@file_get_contents($url, 0, $timeout)){
				# Consuming the stamp service
				$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
				
				$client = new SoapClient($url, array('trace' => 1));
				 
				$params = array(
					  "xml" => $xml_content,
					  "username" => $username,
					  "password" => $password
					);				
				 /*
				$params = array(
				  "Usuario" => 'GODR770917KK6',
				  "Password" => 'Kwc6021709@',
				  "archivoXMLZip" => $xmlZip,
				  "IdEquipo" => 'OWM3N2U3MmQtZGU0ZC1jNjQ4LTE2MjAtNGZkY2FhNWE2ZTNk',
				  "Serie" => ''
				);
				*/
				$arrResp = array('status'=>3,
				 'mensaje'=>'',
				 'UUID'=>0,
				 'NoCertificadoSAT'=>0,
				 'Fecha'=>0,
				 'SatSeal'=>0,
				 'selloCFD'=>0,
				 'RfcProvCertif'=>0,
				 'serie'=>$serie,
				 'folio'=>$folio);
				$response = $client->__soapCall("stamp", array($params));
				$lastRequest = $client->__getLastRequest();
				# Guardar el XML de la ultima peticion SOAP
				$response_path = "send/".$EmisorRFC."/LastRequest".$serie."".$folio.".xml";
				$xml_out_file = fopen($response_path, "w");
				fwrite($xml_out_file, $lastRequest);
				fclose($xml_out_file);
				
				//print_r($response);
				$xmlResp = $response->stampResult->xml;
				if(trim($xmlResp)!=""){
					if(is_string($xmlResp)){
						if(strlen($xmlResp)>200){
							$arrResp['status'] = 0;
							$arrResp['UUID'] = $response->stampResult->UUID;
							$arrResp['NoCertificadoSAT'] = $response->stampResult->NoCertificadoSAT;
							$arrResp['Fecha'] = $response->stampResult->Fecha;
							$arrResp['SatSeal'] = $response->stampResult->SatSeal;
							$arrResp['selloCFD'] = '';
							
							$sxe = new SimpleXMLElement($xmlResp);
							$ns = $sxe->getNamespaces(true);
							$sxe->registerXPathNamespace('c', $ns['cfdi']);
							$sxe->registerXPathNamespace('t', $ns['tfd']);
							$selloCFD = "";
							$rfcPAC = "";
							$versionTD = "";
							foreach ($sxe->xpath('//t:TimbreFiscalDigital') as $tfd) {
								$selloCFD = $tfd['SelloCFD'];
								$rfcPAC = $tfd['RfcProvCertif'];
								$rfcPAC = $tfd['RfcProvCertif'];
								$versionTD = $tfd['Version'];
							}
							
							$arrResp['selloCFD'] = "".$selloCFD;
							$arrResp['RfcProvCertif'] = "".$rfcPAC;
							
							$cadenaTFD = '||'.$versionTD.'|'.$arrResp['UUID'].'|'.$arrResp['Fecha'].'|'.$arrResp['RfcProvCertif'].'|'.$arrResp['selloCFD'].'|'.$arrResp['NoCertificadoSAT'].'||';
							
							//ACTUALIZA EL RegistroPatronal
							$qws = "UPDATE ptimbrado SET status=1,
														 wsuuid='".$arrResp['UUID']."',
														 wsnoCertificado='".$arrResp['NoCertificadoSAT']."',
														 wsfecha='".$arrResp['Fecha']."',
														 wssellosat='".$arrResp['SatSeal']."',
														 wssellocfd='".$arrResp['selloCFD']."',
														 wsrfc='".$arrResp['RfcProvCertif']."',
														 cadenaOriginal='".$cadenaTFD."',
														 sello='".$cfd['sello']."',
														 certificado='".$cfd['certificado']."',
														 status=1
												WHERE    folio=".$folio." AND serie='".$serie."'";
							$sws = $conexion->query($qws);
							
							//echo $qws;
							
							# Guardar el XML timbrado
							$response_path = "CFDIS/".$EmisorRFC."/".$serie."".$folio.".xml";
							$xml_out_file = fopen($response_path, "w");
							fwrite($xml_out_file, $xmlResp);
							fclose($xml_out_file);
						}
						else{
							$arrResp['status'] = 1920;//RESPUESTA DE ERROR DEL PACMensajeIncidencia
							$arrResp['mensaje'] = "Error en STRLEN, contacte a soporte o intente de nuevo";
						}
					}else{
						$arrResp['status'] = 1900;//RESPUESTA DE ERROR DEL PACMensajeIncidencia
						$arrResp['mensaje'] = "La respuesta no es un STRING, contacte a soporte o intente de nuevo";
					}
				}else{
					$arrResp['status'] = 1902;//RESPUESTA DE ERROR DEL PACMensajeIncidencia
					$arrResp['mensaje'] = $response->stampResult->Incidencias->Incidencia->MensajeIncidencia;
				}
				
			}else{
				$arrResp['status'] = 1901;
				$arrResp['mensaje'] = $validacion;	
			}
			echo json_encode($arrResp);
			break;
		}
	}

function CalculaEdad( $fecha ) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function CalculaAntiguedadSAT( $fecha, $fechaFinal ) {
    $fecha1 = new DateTime($fecha);
	$fecha2 = new DateTime($fechaFinal);
	$fecha = $fecha1->diff($fecha2);
	$anio = $fecha->y;
	$mes = $fecha->m;
	$dia = $fecha->d;
	$return = "P";
	if($anio>0)
		$return.= $anio."Y";
	if($mes>0)
		$return.= $mes."M";
	$return.= $dia."D";
	return $return;
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

function formateaFecha($fecha){
	$arr = explode("-",$fecha);
	$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
	return $fechaNueva;	
}

?>
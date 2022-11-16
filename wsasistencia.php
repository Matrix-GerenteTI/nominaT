<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
require_once("ajax/mysql.php");
//conexion();
	
$departamento = "";
$nombre = "";


$diahoy = date('d');
if($diahoy<=15){
	$fecini = date('01/m/Y');
}else{
	$fecini = date('16/m/Y');
}
$fecfin = date('d/m/Y', strtotime('+1 day'));
$puesto = "";

$dias = calculaDias($fecini,$fecfin);

$date = str_replace("/","-",$fecini);
$rangoFechas[0] = $date;
for($d=0;$d<$dias;$d++){
	$mod_date = strtotime($date."+ 1 days");
	$date = date("d-m-Y",$mod_date);
	$rangoFechas[count($rangoFechas)] = $date;
}

$array = array();
$query = "SELECT 	e.nip as nip,
					e.nombre as nombre,
					d.descripcion as departamento,
					p.descripcion as puesto,
					c.id as idcontrato,
					r.timecheck,
					YEAR(r.timecheck) as anio,
					MONTH(r.timecheck) as mes,
					DAY(r.timecheck) as dia,
					HOUR(r.timecheck) as hora,
					MINUTE(r.timecheck) as minuto,
					pa.entrada,
					pa.entradai,
					pa.salidai,
					pa.salida,
					pa.tolerancia,
					pa.retardospfalta,
					pa.corrido,
					pa.faltaspdescuento,
					s.descripcion as sucursal
		  FROM 		pregistros r  
		  INNER JOIN pempleado e ON r.idempleado=e.nip 
		  INNER JOIN pcontrato c ON e.nip=c.nip 
		  INNER JOIN cpuesto p ON c.idpuesto=p.id 
		  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
		  INNER JOIN cparametrosasistencia pa ON p.id=pa.idpuesto 
		  INNER JOIN csucursal s ON r.idreloj=s.idreloj
		  WHERE 	e.nombre LIKE '%".$nombre."%' 
		  AND 		p.id LIKE '%".$puesto."%' ";
if($fecini!="" && $fecfin!="")
	$query.= "AND		r.timecheck>='".formateaFechaSLASH($fecini)."' AND r.timecheck<='".formateaFechaSLASH($fecfin)."' ";	
$query.= "AND		e.status=1 
		  GROUP BY  d.descripcion,p.descripcion,e.nombre,anio,mes,dia
		  ORDER BY d.descripcion,p.descripcion,e.nombre,r.timecheck ASC";
//echo $query."</br></br>";
//die();

$sql = $conexion->query($query);
$i = 0;
while($row = $sql->fetch_assoc()){
	$array[$i] = array('sucursal'=>'','departamento'=>'','puesto'=>'','nombre'=>'','entrada'=>'','salidai'=>'','entradai'=>'','salida'=>'','marcado'=>'');
	$array[$i]['sucursal'] = $row['sucursal'];
	$array[$i]['departamento'] = $row['departamento'];
	$array[$i]['puesto'] = $row['puesto'];
	$array[$i]['nombre'] = $row['nombre'];
	$squery = "SELECT 	e.nip as nip,
						e.nombre as nombre,
						d.descripcion as departamento,
						p.descripcion as puesto,
						c.id as idcontrato,
						r.timecheck,
						YEAR(r.timecheck) as anio,
						MONTH(r.timecheck) as mes,
						DAY(r.timecheck) as dia,
						HOUR(r.timecheck) as hora,
						MINUTE(r.timecheck) as minuto,
						pa.entrada,
						pa.entradai,
						pa.salidai,
						pa.salida,
						pa.tolerancia,
						pa.retardospfalta,
						pa.faltaspdescuento 
			  FROM 		pregistros r  
			  INNER JOIN pempleado e ON r.idempleado=e.nip 
			  INNER JOIN pcontrato c ON e.nip=c.nip 
			  INNER JOIN cpuesto p ON c.idpuesto=p.id 
			  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
			  INNER JOIN cparametrosasistencia pa ON p.id=pa.idpuesto 
			  WHERE 	e.nip=".$row['nip'];
	if($fecini!="" && $fecfin!="")
		$squery.= " AND		r.timecheck>='".formateaFechaSLASH($fecini)."' AND r.timecheck<='".formateaFechaSLASH($fecfin)."' ";	
	$squery.= "AND		e.status=1 
			  AND		YEAR(r.timecheck)=".$row['anio']." 
			  AND		MONTH(r.timecheck)=".$row['mes']." 
			  AND		DAY(r.timecheck)=".$row['dia']." 
			  ORDER BY d.descripcion,p.descripcion,e.nombre,r.timecheck ASC";
	//echo $squery."</br></br>";
	//die();
	
	$ssql = $conexion->query($squery);
	$it = 0;
	$e1 = "";
	$e2 = "";
	$e3 = "";
	$e4 = "";
	while($srow = $ssql->fetch_assoc()){					
		if($it==0){
			$e1 = formateaDigitos($srow['dia']).'-'.formateaDigitos($srow['mes']).'-'.$srow['anio'].' '.formateaDigitos($srow['hora']).':'.formateaDigitos($srow['minuto']);
		}
		if($it==1){
			$e2 = formateaDigitos($srow['dia']).'-'.formateaDigitos($srow['mes']).'-'.$srow['anio'].' '.formateaDigitos($srow['hora']).':'.formateaDigitos($srow['minuto']);
		}
		if($it==2){
			$e3 = formateaDigitos($srow['dia']).'-'.formateaDigitos($srow['mes']).'-'.$srow['anio'].' '.formateaDigitos($srow['hora']).':'.formateaDigitos($srow['minuto']);
		}
		if($it==3){
			$e4 = formateaDigitos($srow['dia']).'-'.formateaDigitos($srow['mes']).'-'.$srow['anio'].' '.formateaDigitos($srow['hora']).':'.formateaDigitos($srow['minuto']);
		}
		$it++;
	}
	
	if($it==1){
		$array[$i]['entrada'] = $e1;
		$horaChk = explode(" ",$e1);
		$minT = calculaMinutos($row['entrada'],$horaChk[1]);
		if($minT>$row['tolerancia'])
			$array[$i]['marcado'] = 'RETARDO';
		else
			$array[$i]['marcado'] = 'OK';
	}
	if($it==2){
		$array[$i]['entrada'] = $e1;
		$horaChk = explode(" ",$e1);
		$minT = calculaMinutos($row['entrada'],$horaChk[1]);
		if($minT>$row['tolerancia'])
			$array[$i]['marcado'] = 'RETARDO';
		else
			$array[$i]['marcado'] = 'OK';
		$array[$i]['salida'] = $e2;
	}
	if($it==3){
		$array[$i]['entrada'] = $e1;
		$horaChk = explode(" ",$e1);
		$minT = calculaMinutos($row['entrada'],$horaChk[1]);
		if($minT>$row['tolerancia'])
			$array[$i]['marcado'] = 'RETARDO';
		else
			$array[$i]['marcado'] = 'OK';
		$array[$i]['salidai'] = $e2;
		$array[$i]['salida'] = $e3;
	}
	if($it>3){
		$ret = 0;
		$array[$i]['entrada'] = $e1;
		$horaChk = explode(" ",$e1);
		$minT = calculaMinutos($row['entrada'],$horaChk[1]);
		//echo "<<".$minT.">>";
		if($minT>$row['tolerancia'])
			$ret++;
		$array[$i]['salidai'] = $e2;
		$array[$i]['entradai'] = $e3;
		$horaChk = explode(" ",$e3);
		$minT = calculaMinutos($row['entradai'],$horaChk[1]);
		//echo "<<".$minT.">>";
		if($minT>$row['tolerancia'] && $row['corrido']!=1)
			$ret++;
		$array[$i]['salida'] = $e4;
		if($ret>0){
			if($ret==1)
				$array[$i]['marcado'] = '1 RETARDO';
			else	
				$array[$i]['marcado'] = '2 RETARDOS';
		}else{
			$array[$i]['marcado'] = 'OK';
		}
		//echo $minT;
	}
	
	$i++;
	
}		

$arrSucursales = array();
$it = 0;
foreach($array as $indice => $rw){
	if(!isset($arrSucursales[$rw['sucursal']])){
		$arrSucursales[$rw['sucursal']] = array();
	}
	if(!isset($arrSucursales[$rw['sucursal']][$rw['departamento']])){
		$arrSucursales[$rw['sucursal']][$rw['departamento']] = array();
	}
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['puesto'] = $rw['puesto'];
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['nombre'] = $rw['nombre'];
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['entrada'] = $rw['entrada'];
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['salidai'] = $rw['salidai'];
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['entradai'] = $rw['entradai'];
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['salida'] = $rw['salida'];
	$arrSucursales[$rw['sucursal']][$rw['departamento']][$it]['marcado'] = $rw['marcado'];
	$it++;
}

//print_r($arrSucursales);

//echo json_encode($array);


//Comienza la escritura en Excel
/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

// Create new PHPExcel object
echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Matrix");
$objPHPExcel->getProperties()->setLastModifiedBy("Matrix");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Inventario actualizado");

$header_style= array('font' => array('bold' => true),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$border_style= array('font' => array('bold' => true),'borders' => array('bottom' => array('style' =>PHPExcel_Style_Border::BORDER_THICK,'color' => array('rgb' => '000000'),)));
$sheetIndex = 0;
$letrasImg = array('E','F','G','H','I','J','K','L');
foreach($arrSucursales as $idxsucursales => $valsucursales){
	
	//if($sheetIndex>0)
	//	continue;
	// Add some data
	echo date('H:i:s') . " Add some data\n";
	if($sheetIndex > 0){
		$objPHPExcel->createSheet();
		$sheet = $objPHPExcel->setActiveSheetIndex($sheetIndex);
		$sheet->setTitle($idxsucursales);
		$sheet->mergeCells('A1:J1');
		$sheet->mergeCells('A2:J2');
		$sheet->mergeCells('A3:J3');		
		//Do you want something more here
	}else{
		$objPHPExcel->setActiveSheetIndex(0)->setTitle($idxsucursales);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:G3');
	}
	$sheetIndex++;
	
	
	$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
	$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'REPORTE DE ASISTENCIA DEL '.date('d/m/Y', strtotime('-1 day')));
	$objPHPExcel->getActiveSheet()->SetCellValue('A3', '"NO EXISTEN LOS PRETEXTOS, SI QUIERES HACER ALGO HAZLO O POR LO MENOS INTENTALO"');
	$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($header_style);
	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($header_style);
	$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($header_style);
	$objDrawing1 = new PHPExcel_Worksheet_Drawing();
	$objDrawing1->setName('Logo');
	$objDrawing1->setDescription('Logo');
	$objDrawing1->setPath('./logoFWhite.png');
	$objDrawing1->setCoordinates('B1');	 
	$objDrawing1->setOffsetX(430);
	$objDrawing1->setHeight(47);
	$objDrawing1->setWidth(100);					
	$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet());
	
	$n = 4;
	$n++;
	
	foreach($valsucursales as $idxdepartamento => $valdepartamento){
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$n, strtoupper($idxdepartamento));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$n)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$n)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$n)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$n)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$n)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$n)->applyFromArray($border_style);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$n)->applyFromArray($border_style);
		$n++;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$n, 'PUESTO');
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$n, 'NOMBRE');
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$n, 'ENTRADA');
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$n, 'SALIDA I');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$n, 'ENTRADA I');
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$n, 'SALIDA');
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$n, 'MARCADO');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$n)->applyFromArray($header_style);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$n)->applyFromArray($header_style);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$n)->applyFromArray($header_style);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$n)->applyFromArray($header_style);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$n)->applyFromArray($header_style);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$n)->applyFromArray($header_style);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$n)->applyFromArray($header_style);
		$n++;
		print_r($valdepartamento);
		
		foreach($valdepartamento as $valrow){
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$n, $valrow['puesto']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$n, $valrow['nombre']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$n, $valrow['entrada']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$n, $valrow['salidai']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$n, $valrow['entradai']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$n, $valrow['salida']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$n, $valrow['marcado']);
			
			$n++;
		}
		
		$n = $n + 2;
		//}
	}

	foreach(range('A','G') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
}
// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
//enviaEmail();


function enviaEmail(){
	// primero hay que incluir la clase phpmailer para poder instanciar 
	//un objeto de la misma
	require "phpmailer/class.phpmailer.php";

	//instanciamos un objeto de la clase phpmailer al que llamamos 
	//por ejemplo mail
	$mail = new phpmailer();

	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	//$mail->SMTPSecure = 'ssl';
	$mail->Port = 25;
	//Asignamos a Host el nombre de nuestro servidor smtp
	$mail->Host = "mail.matrix.com.mx";

	//Le decimos cual es nuestro nombre de usuario y password
	$mail->Username = "no-responder@matrix.com.mx";
	$mail->Password = "M@tr1x2017";

	//Indicamos cual es nuestra dirección de correo y el nombre que 
	//queremos que vea el usuario que lee nuestro correo
	$mail->From = "no-responder@matrix.com.mx";

	$mail->FromName = "Asistencia";

	//Asignamos asunto y cuerpo del mensaje
	//El cuerpo del mensaje lo ponemos en formato html, haciendo 
	//que se vea en negrita
	$mail->Subject = "Asistencia del personal ";
	$mail->Body = "<p>...</p>";

	//Definimos AltBody por si el destinatario del correo no admite 
	//email con formato html
	$mail->AltBody ="...";

	//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
	//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120  
	$mail->Timeout=10;

	//Indicamos el fichero a adjuntar si el usuario seleccionó uno en el formulario
	if(is_file("wsasistencia.xlsx")	)
		$mail->AddAttachment("wsasistencia.xlsx");

	//Indicamos cuales son las direcciones de destino del correo y enviamos 
	//los mensajes
	$mail->AddAddress('sestrada@matrix.com.mx');
	$mail->AddAddress('admon@matrix.com.mx');
	$mail->AddAddress('tesoreria@matrix.com.mx');

	//se envia el mensaje, si no ha habido problemas la variable $success 
	//tendra el valor true
	$exito = $mail->Send();

	//La clase phpmailer tiene un pequeno bug y es que cuando envia un mail con
	//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho 
	//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
	if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
	   $exito=true;
	 }
		
	if(!$exito)
	{
	   echo "[".$mail->ErrorInfo."] - Problemas enviando correo electrónico a ";
	}
	else
	{
	   echo "Enviado";
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

function calculaDias($fechaI, $fechaF)
{
    $fecha1= explode("/",$fechaI); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
    $dia1=$fecha1[0]; // día del mes en número
    $mes1=$fecha1[1]; // número del mes de 01 a 12
    $anio1=$fecha1[2];
    
    $fecha2= explode("/",$fechaF); // convierte la fecha de formato mm/dd/yyyy a marca de tiempo
    $dia2=$fecha2[0]; // día del mes en número
    $mes2=$fecha2[1]; // número del mes de 01 a 12
    $anio2=$fecha2[2];
    
    $fecha1a=mktime(0,0,0,$mes1,$dia1,$anio1);
    $fecha2a=mktime(0,0,0,$mes2,$dia2,$anio2);
 
    $diferencia = $fecha2a - $fecha1a;
    $dias=$diferencia/(60*60*24);
    $dias=floor($dias);
    
    return $dias; 
}

function calculaMinutos($hora1,$hora2){ 
    $h1=explode(':',$hora1); 
    $h2=explode(':',$hora2); 

	$h1 = ($h1[0]*60)+$h1[1]; 
	$h2 = ($h2[0]*60)+$h2[1]; 
	$total_minutos_trasncurridos = $h2 - $h1; 
	return($total_minutos_trasncurridos); 
}

function formateaDigitos($numero){
	if($numero<10)
		return '0'.$numero;
	else
		return $numero;
}

?>
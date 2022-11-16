<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "lista":{
			$departamento = $_POST['departamento'];
			$nombre = $_POST['nombre'];
			$fecini = $_POST['fecIni'];
			$fecfin = $_POST['fecFin'];
			$inicio = $_POST['inicio'];
			$cantidad = $_POST['cantidad'];
			$puesto = $_POST['puesto'];
			
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
								pa.faltaspdescuento 
					  FROM 		pregistros r  
					  INNER JOIN pempleado e ON r.idempleado=e.nip 
					  INNER JOIN pcontrato c ON e.nip=c.nip 
					  INNER JOIN cpuesto p ON c.idpuesto=p.id 
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  INNER JOIN cparametrosasistencia pa ON p.id=pa.idpuesto 
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
				$array[$i] = array('departamento'=>'','puesto'=>'','nombre'=>'','entrada'=>'','salidai'=>'','entradai'=>'','salida'=>'','marcado'=>'');
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
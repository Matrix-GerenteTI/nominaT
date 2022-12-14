<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	session_start();
	require_once("mysql.php");
	require_once('generaPDFI.php');
	//conexion();
	$op = $_GET['op'];
	switch($op){
		
		case "buslistaTimbrado":{
			$departamento = $_POST['departamento'];
			$nombre = $_POST['nombre'];
			$array = array();
			$n=0;
			$query = "SELECT 	e.nip as nip,
								e.nombre as nombre,
								d.descripcion as departamento,
								c.id as idcontrato
					  FROM 		pempleado e 
					  INNER JOIN pcontrato c ON c.nip=e.nip
					  INNER JOIN cdepartamento d ON c.iddepartamento=d.id  
					  WHERE 	e.nombre LIKE '%".$nombre."%' 
					  AND 		d.id LIKE '%".$departamento."%'
					  AND		e.status=1";
			$sql = $conexion->query($query);
			while($row = $sql->fetch_assoc()){
				$array[$n] = $row;
				$q1 = "SELECT SUM(gravado) as gravado, SUM(excento) as excento FROM ppercepciones WHERE idcontrato=".$row['idcontrato']." AND status=1 GROUP BY idcontrato";
				$s1 = $conexion->query($q1);
				$r1 = $s1->fetch_assoc();
				$percepcion = $r1['gravado'] + $r1['excento'];
				if($percepcion>0)
					$array[$n]['estado'] = 'listo';
				else
					$array[$n]['estado'] = 'noinfo';
				$n++;
			}
			echo json_encode($array);
			break;
		}
		

		case "cargaPatron":{
			 $queryPatron = "SELECT * FROM  ppatron";
			 $exePatron = $conexion->query( $queryPatron );

			 echo json_encode( $exePatron->fetch_all( MYSQLI_ASSOC ) );
			break;
		}

		case "timbrar":{
			
			
			
			require_once("satxmlsv.php"); 
			require_once("generaPDFI.php");
			date_default_timezone_set('America/Mexico_City');
			//echo date_default_timezone_get();
			$idcontrato = $_POST['idcontrato'];
			
			//DATOS DE LA Nomina
			$NominaTipoNomina = $_POST['tiponomina'];
			$NominaFechaInicialPago = formateaFechaSLASH($_POST['fechainicial']);
			$NominaFechaFinalPago =formateaFechaSLASH( $_POST['fechafinal']);
			$NominaDiasPagados = $_POST['diaspagados'];
			$NominaFechaPago = formateaFechaSLASH($_POST['fechapago']);
			$NominaUUID = $_POST['uuidanterior'];
			$NominaRegistroPat = $_POST['registropatronal'];
			
			$NominaEmisorRfcOrigen = '';
			
			$fecha = date("Y-m-d")."T".date("h:i:s");
			
			//DATOS DEL CONTRATO y se aprovecha para sacar el nip
			$q1 = "SELECT 	*,
							e.descripcion as departamento,
							u.descripcion as puesto,
							c.nip as nip
				   FROM 	pcontrato c
				   INNER JOIN pdireccion d ON c.iddireccion=d.id
				   INNER JOIN cdepartamento e ON c.iddepartamento=e.id
				   INNER JOIN cpuesto u ON c.idpuesto=u.id
				   WHERE 	c.id=".$idcontrato;
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$nip = $r1['nip'];
			$NominaReceptorBanco = $r1['idbanco'];
			$NominaReceptorCuenta = $r1['cuentabancaria'];
			$NominaReceptorDepartamento = $r1['departamento'];
			$NominaReceptorEstado = $r1['idestado'];
			$NominaReceptorInicioLab = $r1['fechainiciolab'];
			$NominaReceptorAntiguedad = CalculaAntiguedadSAT($NominaReceptorInicioLab,$NominaFechaFinalPago);
			$NominaReceptorPeriodicidad = $r1['idperiodicidadpago'];
			$NominaReceptorPuesto = $r1['puesto'];
			$NominaReceptorRiesgo = $r1['idriesgopuesto'];
			$NominaReceptorSalarioIntegrado = $r1['salariodiario'];
			$NominaReceptorSalariobase = $r1['salariobase'];
			$NominaReceptorSindicalizado = $r1['sindicalizado'];
			$NominaReceptorTipoContrato = $r1['idtipocontrato'];
			$NominaReceptorTipoJornada = $r1['idtipojornada'];
			$NominaReceptorTipoRegimen = $r1['idtiporegimen'];
			
			//Obtenemos Serie y NoCertificado
			$q1 = "SELECT * FROM cgeneral WHERE id='noCertificado'";
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$noCertificado = "00001000000505572166";
			
			$q1 = "SELECT * FROM cgeneral WHERE id='serie'";
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$serie = $r1['valor'];
			
			//Moneda
			$Moneda = 'MXN';
			
			//UUID Relacionado
			$RelacionadoUUID = '';
			
			//Datos de Emisor
			$q1 = "SELECT * FROM ppatron p INNER JOIN pdireccion d ON p.iddireccion=d.id WHERE p.id= $NominaRegistroPat";
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$EmisorRFC = $r1['rfc'];
			$EmisorNombre = $r1['nombre_razsoc'];
			$EmisorCP = $r1['cp'];
			$LugarExpedicion = $r1['cp'];
			$EmisorRegimen = $r1['idregimenfiscal'];
			$NominaEmisorCurp = $r1['curp'];
			$NominaEmisorRegistro = $r1['registropatronal'];
			
			//Datos del Receptor
			$q1 = "SELECT * FROM pempleado WHERE nip=".$nip;
			$s1 = $conexion->query($q1);
			$r1 = $s1->fetch_assoc();
			$ReceptorRFC = $r1['rfc'];
			$ReceptorNombre = $r1['nombre'];
			$NominaReceptorCurp = $r1['curp'];
			$NominaReceptorNSS = $r1['nss'];
			$NominaReceptorNumEmpleado = $nip;	
			
			//Datos de las Percepciones sin contar Horas Extra
			$TotalImporteGravado = 0;
			$TotalImporteExento = 0;
			$TotalSeparacionIndemnizacion = 0;
			$TotalJubilacionPensionRetiro = 0;
			$TotalSueldos = 0;
			$arrPercepcion = array();
			$q1 = "SELECT 	p.idtipopercepcion as TipoPercepcion,
							p.idtipopercepcion as Clave,
							tp.descripcion as Concepto,
							p.gravado as ImporteGravado,
							p.excento as ImporteExento,
							p.valormercado as ValorMercado,
							p.preciootorgarse as PrecioAlOtorgarse
				   FROM 	ppercepciones p
				   INNER JOIN ctipopercepcion tp ON p.idtipopercepcion=tp.id
				   WHERE 	p.idcontrato=".$idcontrato." 
				   AND 		p.status=1";
			$s1 = $conexion->query($q1);
			while($r1 = $s1->fetch_assoc()){
				$arrPercepcion[] = $r1;
				if($r1['TipoPercepcion']!='022' && $r1['TipoPercepcion']!='023' && $r1['TipoPercepcion']!='025' && $r1['TipoPercepcion']!='039' && $r1['TipoPercepcion']!='044'){
					$TotalSueldos = $TotalSueldos + $r1['ImporteGravado'] + $r1['ImporteExento'];
				}
				if($r1['TipoPercepcion']=='022' && $r1['TipoPercepcion']=='023' && $r1['TipoPercepcion']=='025'){
					$TotalSeparacionIndemnizacion = $TotalSeparacionIndemnizacion + $r1['ImporteGravado'] + $r1['ImporteExento'];
				}
				if($r1['TipoPercepcion']=='039' && $r1['TipoPercepcion']=='044'){
					$TotalJubilacionPensionRetiro = $TotalJubilacionPensionRetiro + $r1['ImporteGravado'] + $r1['ImporteExento'];
				}
				$TotalImporteGravado = $TotalImporteGravado + $r1['ImporteGravado'];
				$TotalImporteExento = $TotalImporteExento + $r1['ImporteExento'];
			}
			
			$sumImporteGravado = 0;
			$sumImporteExento = 0;
			//Datos de las horas extra sin contar Horas Extra
			$arrHorasExtra = array();
			$q2 = "SELECT 	idtipohoras as TipoHoras,
							dias as Dias,
							horasextra as HorasExtra,
							importepagado as ImportePagado,
							exento as ImporteExento,
							gravado as ImporteGravado
				   FROM 	phorasextra 
				   WHERE 	idcontrato=".$idcontrato." 
				   AND 		status=1";
			$s2 = $conexion->query($q2);
			while($r2 = $s2->fetch_assoc()){
				$arrHorasExtra[] = $r2;
				$sumImporteGravado = $sumImporteGravado + $r2['ImporteGravado'];
				$sumImporteExento =  $sumImporteGravado + $r2['ImporteExento'];
			}
			
			$TotalImporteExento = $TotalImporteExento + $sumImporteExento;
			$TotalImporteGravado = $TotalImporteGravado + $sumImporteGravado;
			$TotalSueldos = $TotalSueldos + $sumImporteExento + $sumImporteGravado;
			
			//DEDUCCIONES
			$TotalOtrasDeducciones = 0;
			$TotalImpuestosRetenidos = 0;
			$arrDeducciones = array();
			$q3 = "SELECT 	d.idtipodeduccion as TipoDeduccion,
							d.idtipodeduccion as Clave,
							td.descripcion as Concepto,
							d.importe as Importe
				   FROM 	pdeducciones d
				   INNER JOIN ctipodeduccion td ON d.idtipodeduccion=td.id
				   WHERE 	d.idcontrato=".$idcontrato." 
				   AND 		d.status=1";
			$s3 = $conexion->query($q3);
			while($r3 = $s3->fetch_assoc()){
				$arrDeducciones[] = $r3;
				if($r3['TipoDeduccion']=='002'){
					$TotalImpuestosRetenidos = $TotalImpuestosRetenidos + $r3['Importe'];
				}else{
					$TotalOtrasDeducciones = $TotalOtrasDeducciones + $r3['Importe'];
				}
			}
			
			//OTROS PAGOS
			$TotalOtrosPagos = 0;
			$arrOtrosPagos = array();
			$q5 = "SELECT 	d.idtipootropago as TipoOtroPago,
							d.idtipootropago as Clave,
							td.descripcion as Concepto,
							d.importe as Importe,
							d.saldofavor as SaldoAFavor,
							d.anio as Anio,
							d.remanente as RemanenteSalFav,
							d.subsidiocausado as SubsidioCausado
				   FROM 	potrospagos d
				   INNER JOIN ctipootropago td ON d.idtipootropago=td.id
				   WHERE 	d.idcontrato=".$idcontrato." 
				   AND 		d.status=1";
			$s5 = $conexion->query($q5);
			while($r5 = $s5->fetch_assoc()){
				$arrOtrosPagos[] = $r5;
				$TotalOtrosPagos = $TotalOtrosPagos + $r5['Importe'];
			}
			
			//JUBILACION
			$arrJubilacion = array();
			$q6 = "SELECT 	*,
							p.descripcion as Concepto,
							j.idtipopercepcion as Clave,
							j.idtipopercepcion as TipoPercepcion,
							j.gravado as ImporteGravado,
							j.exento as ImporteExento
				   FROM 	pjubilaciones j
				   INNER JOIN ctipopercepcion p ON j.idtipopercepcion=p.id
				   WHERE 	j.idcontrato=".$idcontrato." 
				   AND 		j.status=1";
			$s6 = $conexion->query($q6);
			while($r6 = $s6->fetch_assoc()){
				$arrJubilacion[] = $r6;
			}
			
			//SEPARACION
			$arrSeparacion = array();
			$q7 = "SELECT 	*,
							p.descripcion as Concepto,
							s.idtipopercepcion as TipoPercepcion,
							s.idtipopercepcion as Clave,
							s.gravado as ImporteGravado,
							s.exento as ImporteExento
				   FROM 	pseparaciones s
				   INNER JOIN ctipopercepcion p ON s.idtipopercepcion=p.id
				   WHERE 	s.idcontrato=".$idcontrato." 
				   AND 		s.status=1";
			$s7 = $conexion->query($q7);
			while($r7 = $s7->fetch_assoc()){
				$arrSeparacion[] = $r7;
			}
			
			//INCAPACIDADES
			$arrIncapacidades = array();
			$q8 = "SELECT 	d.idtipoincapacidad as TipoIncapacidad,
							d.dias as Dias,
							d.importe as Importe
				   FROM 	pincapacidades d
				   INNER JOIN ctipoincapacidad td ON d.idtipoincapacidad=td.id
				   WHERE 	d.idcontrato=".$idcontrato." 
				   AND 		d.status=1";
			$s8 = $conexion->query($q8);
			while($r8 = $s8->fetch_assoc()){
				$arrIncapacidades[] = $r8;
			}
			
			$TPercepciones = $TotalSueldos + $TotalJubilacionPensionRetiro + $TotalSeparacionIndemnizacion;
			$subtotal = $TPercepciones + $TotalOtrosPagos;
			$descuento = $TotalImpuestosRetenidos + $TotalOtrasDeducciones;
			$total = $subtotal - $descuento;
			$NominaTotalDeducciones = $descuento;
			$NominaTotalPercepciones = $TPercepciones;
			$NominaTotalOtrosPagos = $TotalOtrosPagos;
									
			$qF = "SELECT valor FROM cgeneral WHERE id='folio'";
			$sF = $conexion->query($qF);
			$folio = $sF->fetch_assoc();
			$folio = $folio['valor'];
			//echo $folio."<br/>";
			//$folio = 999;
			//die();
			
			if($NominaTipoNomina=='E')
				$NominaReceptorPeriodicidad = '99';
			
			
			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			$xml.= '<cfdi:Comprobante  xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:nomina12="http://www.sat.gob.mx/nomina12" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd http://www.sat.gob.mx/nomina12 http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd" Version="3.3" Serie="NOM" ';
			$xml.= '	Folio="'.$folio.'" Fecha="'.$fecha.'" Sello="" FormaPago="99" NoCertificado="'.$noCertificado.'" Certificado="" SubTotal="'.number_format($subtotal,2,'.','').'" Descuento="'.number_format($descuento,2,'.','').'" Moneda="MXN" Total="'.number_format($total,2,'.','').'" TipoDeComprobante="N" MetodoPago="PUE" LugarExpedicion="'.$LugarExpedicion.'">';
			$xml.= '	<cfdi:Emisor Nombre="'.$EmisorNombre.'" Rfc="'.$EmisorRFC.'" RegimenFiscal="'.$EmisorRegimen.'" />';
			$xml.= '	<cfdi:Receptor Rfc="'.$ReceptorRFC.'" Nombre="'.$ReceptorNombre.'" UsoCFDI="P01" />';
			$xml.= '	<cfdi:Conceptos>';
			$xml.= '		<cfdi:Concepto ClaveProdServ="84111505" Cantidad="1" ClaveUnidad="ACT" Descripcion="Pago de n??mina" ValorUnitario="'.number_format($subtotal,2,'.','').'" Importe="'.number_format($subtotal,2,'.','').'" Descuento="'.number_format($descuento,2,'.','').'"/>';
			$xml.= '	</cfdi:Conceptos>';
			//$xml.= '	<cfdi:Impuestos />';
			$xml.= '	<cfdi:Complemento>';
			if($NominaTotalDeducciones>0 && $NominaTotalOtrosPagos>0 && $NominaTotalPercepciones>0){
				$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.number_format($NominaTotalPercepciones,2,'.','').'" TotalDeducciones="'.number_format($NominaTotalDeducciones,2,'.','').'" TotalOtrosPagos="'.number_format($NominaTotalOtrosPagos,2,'.','').'">';
			}else{
				if($NominaTotalDeducciones>0 && $NominaTotalPercepciones>0){
					$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.number_format($NominaTotalPercepciones,2,'.','').'" TotalDeducciones="'.number_format($NominaTotalDeducciones,2,'.','').'">';
				}else{
					if($NominaTotalOtrosPagos>0 && $NominaTotalPercepciones>0){
						$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.number_format($NominaTotalPercepciones,2,'.','').'" TotalOtrosPagos="'.number_format($NominaTotalOtrosPagos,2,'.','').'">';
					}else{
						if($NominaTotalOtrosPagos>0 && $NominaTotalDeducciones>0){
							$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalDeducciones="'.number_format($NominaTotalDeducciones,2,'.','').'" TotalOtrosPagos="'.number_format($NominaTotalOtrosPagos,2,'.','').'">';
						}else{
							if($NominaTotalOtrosPagos>0){
								$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalOtrosPagos="'.number_format($NominaTotalOtrosPagos,2,'.','').'">';
							}else{
								if($NominaTotalDeducciones>0){
									$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalDeducciones="'.number_format($NominaTotalDeducciones,2,'.','').'">';
								}else{
									$xml.= '		<nomina12:Nomina Version="1.2" TipoNomina="'.$NominaTipoNomina.'" FechaPago="'.$NominaFechaPago.'" FechaInicialPago="'.$NominaFechaInicialPago.'" FechaFinalPago="'.$NominaFechaFinalPago.'" NumDiasPagados="'.$NominaDiasPagados.'" TotalPercepciones="'.number_format($NominaTotalPercepciones,2,'.','').'">';
								}
							}
						}
					}
				}
			}
			if($NominaEmisorRfcOrigen!=''){
				if($NominaEmisorCurp!=''){
					$xml.= '			<nomina12:Emisor Curp="'.$NominaEmisorCurp.'" RegistroPatronal="'.$NominaEmisorRegistro.'" RfcPatronOrigen="'.$NominaEmisorRfcOrigen.'">';
				}else{
					$xml.= '			<nomina12:Emisor RegistroPatronal="'.$NominaEmisorRegistro.'" RfcPatronOrigen="'.$NominaEmisorRfcOrigen.'">';
				}
			}else{
				if($NominaEmisorCurp!=''){
					$xml.= '			<nomina12:Emisor Curp="'.$NominaEmisorCurp.'" RegistroPatronal="'.$NominaEmisorRegistro.'">';
				}else{
					$xml.= '			<nomina12:Emisor RegistroPatronal="'.$NominaEmisorRegistro.'">';
				}
			}
			//$xml.= '				<nomina12:EntidadSNCF OrigenRecurso="" MontoRecursoPropio=""/>';
			$xml.= '			</nomina12:Emisor>';
			if($NominaReceptorBanco!='' && $NominaReceptorCuenta!=''){
				$xml.= '			<nomina12:Receptor Curp="'.$NominaReceptorCurp.'" NumSeguridadSocial="'.$NominaReceptorNSS.'" FechaInicioRelLaboral="'.$NominaReceptorInicioLab.'" TipoContrato="'.$NominaReceptorTipoContrato.'" Antig??edad="'.$NominaReceptorAntiguedad.'" Sindicalizado="'.$NominaReceptorSindicalizado.'" TipoJornada="'.$NominaReceptorTipoJornada.'" TipoRegimen="'.$NominaReceptorTipoRegimen.'" NumEmpleado="'.$NominaReceptorNumEmpleado.'" Departamento="'.$NominaReceptorDepartamento.'" Puesto="'.$NominaReceptorPuesto.'" RiesgoPuesto="'.$NominaReceptorRiesgo.'" PeriodicidadPago="'.$NominaReceptorPeriodicidad.'" Banco="'.$NominaReceptorBanco.'" CuentaBancaria="'.$NominaReceptorCuenta.'" SalarioBaseCotApor="'.number_format($NominaReceptorSalariobase,2,'.','').'" SalarioDiarioIntegrado="'.number_format($NominaReceptorSalarioIntegrado,2,'.','').'" ClaveEntFed="'.$NominaReceptorEstado.'">';
			}else{
				$xml.= '			<nomina12:Receptor Curp="'.$NominaReceptorCurp.'" NumSeguridadSocial="'.$NominaReceptorNSS.'" FechaInicioRelLaboral="'.$NominaReceptorInicioLab.'" TipoContrato="'.$NominaReceptorTipoContrato.'" Antig??edad="'.$NominaReceptorAntiguedad.'" Sindicalizado="'.$NominaReceptorSindicalizado.'" TipoJornada="'.$NominaReceptorTipoJornada.'" TipoRegimen="'.$NominaReceptorTipoRegimen.'" NumEmpleado="'.$NominaReceptorNumEmpleado.'" Departamento="'.$NominaReceptorDepartamento.'" Puesto="'.$NominaReceptorPuesto.'" RiesgoPuesto="'.$NominaReceptorRiesgo.'" PeriodicidadPago="'.$NominaReceptorPeriodicidad.'" SalarioBaseCotApor="'.number_format($NominaReceptorSalariobase,2,'.','').'" SalarioDiarioIntegrado="'.number_format($NominaReceptorSalarioIntegrado,2,'.','').'" ClaveEntFed="'.$NominaReceptorEstado.'">';
			}
				
			//$xml.= '			<nomina12:SubContratacion RfcLabora="" PorcentajeTiempo=""/>';
			$xml.= '			</nomina12:Receptor>';
			if(count($arrPercepcion)>0){
				if($TotalSeparacionIndemnizacion>0 && $TotalJubilacionPensionRetiro>0){
					$xml.= '			<nomina12:Percepciones TotalSueldos="'.number_format($TotalSueldos,2,'.','').'" TotalSeparacionIndemnizacion="'.number_format($TotalSeparacionIndemnizacion,2,'.','').'" TotalJubilacionPensionRetiro="'.number_format($TotalJubilacionPensionRetiro,2,'.','').'" TotalGravado="'.number_format($TotalImporteGravado,2,'.','').'" TotalExento="'.number_format($TotalImporteExento,2,'.','').'">';
				}else{
					if($TotalSeparacionIndemnizacion>0){
						$xml.= '			<nomina12:Percepciones TotalSueldos="'.number_format($TotalSueldos,2,'.','').'" TotalSeparacionIndemnizacion="'.number_format($TotalSeparacionIndemnizacion,2,'.','').'" TotalGravado="'.number_format($TotalImporteGravado,2,'.','').'" TotalExento="'.number_format($TotalImporteExento,2,'.','').'">';
					}else{
						if($TotalSeparacionIndemnizacion>0)
							$xml.= '			<nomina12:Percepciones TotalSueldos="'.number_format($TotalSueldos,2,'.','').'" TotalJubilacionPensionRetiro="'.number_format($TotalJubilacionPensionRetiro,2,'.','').'" TotalGravado="'.number_format($TotalImporteGravado,2,'.','').'" TotalExento="'.number_format($TotalImporteExento,2,'.','').'">';
						else
							$xml.= '			<nomina12:Percepciones TotalSueldos="'.number_format($TotalSueldos,2,'.','').'"  TotalGravado="'.number_format($TotalImporteGravado,2,'.','').'" TotalExento="'.number_format($TotalImporteExento,2,'.','').'">';
					}
				}
				
				
				foreach($arrPercepcion as $row1){
					$xml.= '				<nomina12:Percepcion TipoPercepcion="'.$row1['TipoPercepcion'].'" Clave="'.$row1['Clave'].'" Concepto="'.$row1['Concepto'].'" ImporteGravado="'.number_format($row1['ImporteGravado'],2,'.','').'" ImporteExento="'.number_format($row1['ImporteExento'],2,'.','').'">';
					if($row1['ValorMercado']>0 && $row1['PrecioAlOtorgarse']>0)
						$xml.= '					<nomina12:AccionesOTitulos ValorMercado="" PrecioAlOtorgarse=""/>';
					$xml.= '				</nomina12:Percepcion>';
				}
				foreach($arrJubilacion as $row8){
					$xml.= '				<nomina12:Percepcion TipoPercepcion="'.$row8['TipoPercepcion'].'" Clave="'.$row8['Clave'].'" Concepto="'.$row8['Concepto'].'" ImporteGravado="'.number_format($row8['ImporteGravado'],2,'.','').'" ImporteExento="'.number_format($row8['ImporteExento'],2,'.','').'"></nomina12:Percepcion>';
				}
				foreach($arrSeparacion as $row9){
					$xml.= '				<nomina12:Percepcion TipoPercepcion="'.$row9['TipoPercepcion'].'" Clave="'.$row9['Clave'].'" Concepto="'.$row9['Concepto'].'" ImporteGravado="'.number_format($row9['ImporteGravado'],2,'.','').'" ImporteExento="'.number_format($row9['ImporteExento'],2,'.','').'"></nomina12:Percepcion>';
				}
				
				if(count($arrHorasExtra)>0){
					$xml.= '				<nomina12:Percepcion TipoPercepcion="019" Clave="019" Concepto="Horas Extra" ImporteGravado="'.number_format($sumImporteGravado,2,'.','').'" ImporteExento="'.number_format($sumImporteExento,2,'.','').'">';
					foreach($arrHorasExtra as $row2){
						$xml.= '					<nomina12:HorasExtra Dias="'.$row2['Dias'].'" TipoHoras="'.$row2['TipoHoras'].'" HorasExtra="'.$row2['HorasExtra'].'" ImportePagado="'.number_format($row2['ImportePagado'],2,'.','').'"/>';
					}
					$xml.= '				</nomina12:Percepcion>';
				}
				
				foreach($arrJubilacion as $row3){
					$xml.= '				<nomina12:JubilacionPensionRetiro TotalUnaExhibicion="'.number_format($row3['unaexhibicion'],2,'.','').'" TotalParcialidad="'.number_format($row3['parcialidades'],2,'.','').'" MontoDiario="'.number_format($row3['diario'],2,'.','').'" IngresoAcumulable="'.number_format($row3['acumulable'],2,'.','').'" IngresoNoAcumulable="'.number_format($row3['noacumulable'],2,'.','').'"/>';
				}
				foreach($arrSeparacion as $row4){
					$xml.= '				<nomina12:SeparacionIndemnizacion TotalPagado="'.number_format($row4['pagado'],2,'.','').'" NumA??osServicio="'.$row4['anios'].'" UltimoSueldoMensOrd="'.number_format($row4['sueldo'],2,'.','').'" IngresoAcumulable="'.number_format($row4['acumulable'],2,'.','').'" IngresoNoAcumulable="'.number_format($row4['noacumulable'],2,'.','').'"/>';
				}
				$xml.= '			</nomina12:Percepciones>';
			}
			
			if(count($arrDeducciones)>0){
				if($TotalImpuestosRetenidos>0)
					$xml.= '			<nomina12:Deducciones TotalOtrasDeducciones="'.number_format($TotalOtrasDeducciones,2,'.','').'" TotalImpuestosRetenidos="'.number_format($TotalImpuestosRetenidos,2,'.','').'">';
				else
					$xml.= '			<nomina12:Deducciones TotalOtrasDeducciones="'.number_format($TotalOtrasDeducciones,2,'.','').'">';
				foreach($arrDeducciones as $row5){
					$xml.= '				<nomina12:Deduccion TipoDeduccion="'.$row5['TipoDeduccion'].'" Clave="'.$row5['Clave'].'" Concepto="'.$row5['Concepto'].'" Importe="'.number_format($row5['Importe'],2,'.','').'"/>';
				}
				$xml.= '			</nomina12:Deducciones>';
			}
			
			if(count($arrOtrosPagos)>0){
				$xml.= '			<nomina12:OtrosPagos>';
				foreach($arrOtrosPagos as $row6){
					$xml.= '				<nomina12:OtroPago TipoOtroPago="'.$row6['TipoOtroPago'].'" Clave="'.$row6['Clave'].'" Concepto="'.$row6['Concepto'].'" Importe="'.number_format($row6['Importe'],2,'.','').'">';
					if($row6['SubsidioCausado']>0)
						$xml.= '					<nomina12:SubsidioAlEmpleo SubsidioCausado="'.number_format($row6['SubsidioCausado'],2,'.','').'"/>';
					if($row6['SaldoAFavor']>0)
						$xml.= '					<nomina12:CompensacionSaldosAFavor SaldoAFavor="'.number_format($row6['SaldoAFavor'],2,'.','').'" A??o="'.$row6['Anio'].'" RemanenteSalFav="'.number_format($row6['RemanenteSalFav'],2,'.','').'"/>';
					$xml.= '				</nomina12:OtroPago>';
				}
				$xml.= '			</nomina12:OtrosPagos>';
			}
			
			if(count($arrIncapacidades)>0){
				$xml.= '			<nomina12:Incapacidades>';
				foreach($arrIncapacidades as $row7){
					$xml.= '				<nomina12:Incapacidad DiasIncapacidad="'.$row7['DiasIncapacidad'].'" TipoIncapacidad="'.$row7['TipoIncapacidad'].'" ImporteMonetario="'.number_format($row7['ImporteMonetario'],2,'.','').'"/>';
				}
				$xml.= '			</nomina12:Incapacidades>';
			}
			
			$xml.= '		</nomina12:Nomina>';
			$xml.= '	</cfdi:Complemento>';
			$xml.= '</cfdi:Comprobante>';
			
			/*
			$response_path = "send/".$EmisorRFC."/".$serie."".$folio.".xml";
			$xml_out_file = fopen($response_path, "w");
			fwrite($xml_out_file, $xml);
			fclose($xml_out_file);
			
			$url = "http://localhost:1234/RocencranService/CreateXML/C:|wamp|www|nomina|ajax|send|".$EmisorRFC."|".$serie."".$folio.".xml/C:|wamp|www|nomina|ajax|CFDIS|".$EmisorRFC."|/C:|FinalServiceQR|".$EmisorRFC."|";
			//abrir conexi??n
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//Si lo deseamos podemos recuperar la salida de la ejecuci??n de la URL
			$resultado = curl_exec($ch);
			//cerrar conexi??n
			curl_close($ch);
			
			//$responseService12 = file_get_contents("http://");
			
			*/
			
			
			satxmlsv($xml,$EmisorRFC,$serie,$folio);
			# Username and Password, assigned by FINKOK
			$username = 'sergio@xiontecnologias.com';
			$password = 'Serygra300107+-';

			# Read the xml file and encode it on base64
			$invoice_path = "./send/".$EmisorRFC."/".$serie."".$folio.".xml";
			$xml_file = fopen($invoice_path, "rb");
			$xml_content = fread($xml_file, filesize($invoice_path));
			fclose($xml_file);
			
			# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
			#$xml_content = base64_encode($xml_content);

			$arrResp = array('status'=>3,
							'mensaje'=>'',
							'UUID'=>0,
							'NoCertificadoSAT'=>0,
							'Fecha'=>0,
							'SatSeal'=>0,
							'selloCFD'=>0);
			
			$cfd = array('sello' => '',
						 'certificado' => '');
			#DEMO
			//$url = 'http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl';

			#PRODUCCION
			$url = 'https://facturacion.finkok.com/servicios/soap/stamp.wsdl';

			/* Tiempo l???mite de espera entre la conexi???n de 10 segundos */
			$timeout = stream_context_create(array('http' => array('timeout' => 10))); 
			/* Verifica si la url existe */
			if(@file_get_contents($url, 0, $timeout)){
				# Consuming the stamp service   
				//$url = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
				$client = new SoapClient($url, array('trace' => 1));
				
				$params = array(
				"xml" => $xml_content,
				"username" => $username,
				"password" => $password
				);
				$response = $client->__soapCall("stamp", array($params));
				$xmlResp = $response->stampResult->xml;

				# Saving the Last Request
				$response_path = "send/".$rowEmisor['rfc']."/LastRequest_".$serie."".$folio.".xml";
				$xml_out_file = fopen($response_path, "w");
				fwrite($xml_out_file, $client->__getLastRequest());
				fclose($xml_out_file);
				
				if($xmlResp!=""){
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
						$versionTD = $tfd['Version'];
						$selloCFD = $tfd['SelloCFD'];
						$arrResp['UUID'] = $tfd['UUID'];
						$arrResp['NoCertificadoSAT'] = $tfd['NoCertificadoSAT'];
						$arrResp['Fecha'] = $tfd['FechaTimbrado'];
						$arrResp['SatSeal'] = $tfd['SelloSAT'];
						$arrResp['selloCFD'] = $tfd['SelloCFD'];
						$arrResp['RfcProvCertif'] = $tfd['RfcProvCertif'];
					}
					
					$arrResp['selloCFD'] = "".$selloCFD;
					
					# Guardar el XML timbrado
					$response_path = "CFDI/".$EmisorRFC."/".$serie."".$folio.".xml";
					$xml_out_file = fopen($response_path, "w");
					fwrite($xml_out_file, $xmlResp);
					fclose($xml_out_file);

					$comprobante = $sxe->xpath('//c:Comprobante');
					$cfd['sello'] = $comprobante[0]['Sello'];
					$cfd['certificado'] = $comprobante[0]['Certificado'];
					
					$cadenaTFD = '||'.$versionTD.'|'.$arrResp['UUID'].'|'.$arrResp['Fecha'].'|'.$arrResp['RfcProvCertif'].'|'.$arrResp['selloCFD'].'|'.$arrResp['NoCertificadoSAT'].'||';
					
					//INSERTAMOS EL REGISTRO EN LA TABLA DE TIMBRADO

					$q2 = "INSERT INTO ptimbrado (serie,
												folio,
												fecha,
												hora,
												idcontrato,
												fechaIni,
												fechaFin,
												fechaPago,
												idtiponomina,
												diasPagados,
												totalOtrosPagos,
												totalDeducciones,
												totalPercepciones,
												subtotal,
												descuento,
												total,
												noCertificado,
												uuidanterior,
												wsuuid,
												wsnoCertificado,
												wsfecha,
												wssellosat,
												wssellocfd,
												wsrfc,
												cadenaOriginal,
												sello,
												certificado,
												status)
										VALUES	 ('".$serie."',
												'".$folio."',
												NOW(),
												NOW(),
												".$idcontrato.",
												'".$NominaFechaInicialPago."',
												'".$NominaFechaFinalPago."',
												'".$NominaFechaPago."',
												'".$NominaTipoNomina."',
												".$NominaDiasPagados.",
												".$NominaTotalOtrosPagos.",
												".$NominaTotalDeducciones.",
												".$NominaTotalPercepciones.",
												".$subtotal.",
												".$descuento.",
												".$total.",
												'".$noCertificado."',
												'".$NominaUUID."',
												'".$arrResp['UUID']."',
												'".$arrResp['NoCertificadoSAT']."',
												'".$arrResp['Fecha']."',
												'".$arrResp['SatSeal']."',
												'".$arrResp['selloCFD']."',
												'".$arrResp['RfcProvCertif']."',
												'".$cadenaTFD."',
												'".$cfd['sello']."',
												'".$cfd['certificado']."',
												1)";
					$s2 = $conexion->query($q2);
					
					$qF2 = "UPDATE cgeneral SET valor=valor+1 WHERE id='folio'";
					$sF2 = $conexion->query($qF2);
					generarPDF($serie,$folio);
					//echo $qws;
					
					# Guardar el XML timbrado
					/*$response_path = "CFDIS/".$EmisorRFC."/".$serie."".$folio.".xml";
					$xml_out_file = fopen($response_path, "w");
					fwrite($xml_out_file, $xmlResp);
					fclose($xml_out_file);	*/					
					
				}else{
					$arrResp['status'] = 1;//RESPUESTA DE ERROR DEL PACMensajeIncidencia
					$arrResp['mensaje'] = $response->stampResult->Incidencias->Incidencia->MensajeIncidencia;
				}
			}else{
				$arrResp['status'] = 2; //SERVIDOR DE PAC FUERA DE LINEA
			}
			
			echo json_encode($arrResp);
			break;
		}
		
		case "enviar":{
			$serie = $_POST['serie'];
			$folio = $_POST['folio'];			
			generarPDF($serie,$folio);
			$query = "SELECT * FROM ppatron WHERE id=1";
			$sql = $conexion->query($query);
			$rowEmisor = $sql->fetch_assoc();

			$query = "SELECT * FROM ptimbrado WHERE serie='".$_POST['serie']."' AND folio='".$_POST['folio']."'";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			$query = "SELECT 	e.nombre as nombre,
								e.email as email
					  FROM 		pcontrato c
					  INNER JOIN pempleado e ON c.nip=e.nip
					  WHERE 	e.nip='".$row['idcontrato']."'";
			$sql = $conexion->query($query);
			$rowReceptor = $sql->fetch_assoc();
			$arrResp = array('status'=>0,'mensaje'=>'');
			if($rowReceptor['email']!=""){

				// primero hay que incluir la clase phpmailer para poder instanciar 
				//un objeto de la misma
				require "class.phpmailer.php";
				
				//instanciamos un objeto de la clase phpmailer al que llamamos 
				//por ejemplo mail
				$mail = new phpmailer();
				
				$mail->IsSMTP(); // enable SMTP
				$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
				$mail->SMTPAuth = true;  // authentication enabled
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				//Asignamos a Host el nombre de nuestro servidor smtp
				$mail->Host = "llantasyrinesmatrix.com";
				
				//Le decimos cual es nuestro nombre de usuario y password
				$mail->Username = "no-responder@llantasyrinesmatrix.com";
				$mail->Password = "P4ssw0rd";
				
				//Indicamos cual es nuestra direcci??n de correo y el nombre que 
				//queremos que vea el usuario que lee nuestro correo
				$mail->From = "no-responder@llantasyrinesmatrix.com";
				
				$mail->FromName = $rowEmisor['nombre_razsoc'];
				
				//Asignamos asunto y cuerpo del mensaje
				//El cuerpo del mensaje lo ponemos en formato html, haciendo 
				//que se vea en negrita
				$mail->Subject = "RECIBO DE NOMINA";
				$mail->Body = "<b>Buen d&iacute;a ".$rowReceptor['nombre']."</b><p>Le enviamos su Recibo de N&oacute;mina ".$row['serie']."-".$row['folio'].". Cualquier aclaraci&oacute;n comunicarse al tel&eacute;fono ".$rowEmisor['telefono']."</p>";
				
				//Definimos AltBody por si el destinatario del correo no admite 
				//email con formato html
				$mail->AltBody ="Buen dia ".$rowReceptor['nombre'].". Le enviamos su Recibo de Nomina ".$row['serie']."-".$row['folio'].". Cualquier aclaracion comunicarse al telefono ".$rowEmisor['telefono'].".";
				
				//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
				//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120  
				$mail->Timeout=10;
				
				//Indicamos el fichero a adjuntar si el usuario seleccion?? uno en el formulario
				if(is_file("CFDIS/".$rowEmisor['rfc']."/".$row['serie']."".$row['folio'].".xml")	)
					$mail->AddAttachment("CFDIS/".$rowEmisor['rfc']."/".$row['serie']."".$row['folio'].".xml");
				if(is_file("CFDIS/".$rowEmisor['rfc']."/".$row['serie']."".$row['folio'].".xml"))
					$mail->AddAttachment("CFDIS/".$rowEmisor['rfc']."/".$row['serie']."".$row['folio'].".pdf");
				
				//Indicamos cuales son las direcciones de destino del correo y enviamos 
				//los mensajes
				$mail->AddAddress($rowReceptor['email']);

				//se envia el mensaje, si no ha habido problemas la variable $success 
				//tendra el valor true
				$exito = $mail->Send();

				//La clase phpmailer tiene un pequeno bug y es que cuando envia un mail con
				//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho 
				//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
				if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
				   $exito=true;
				 }
					
				if(!$exito){
					$arrResp['status'] = 1;
					$arrResp['mensaje'] = "[".$mail->ErrorInfo."] - Problemas enviando correo electr??nico a ".$rowReceptor['email'];
				}else{
					$arrResp['status'] = 0;
				}
			}
			else{
				$arrResp['status'] = 2;
				$arrResp['mensaje'] = "Cliente sin correo!, no olvide enviar los archivos al cliente";
			}
			echo json_encode($arrResp);
			break;
		}
		
		case "cancelar":{
			$serie = $_POST['serie'];
			$folio = $_POST['folio'];	
			
			$query = "SELECT * FROM ppatron WHERE id=1";
			$sql = $conexion->query($query);
			$rowEmisor = $sql->fetch_assoc();

			$query = "SELECT * FROM ptimbrado WHERE serie='".$serie."' AND folio='".$folio."'";
			$sql = $conexion->query($query);
			$row = $sql->fetch_assoc();
			$query = "SELECT 	e.nombre as nombre,
								e.email as email
					  FROM 		pcontrato c
					  INNER JOIN pempleado e ON c.nip=e.nip
					  WHERE 	e.nip='".$row['idcontrato']."'";
			$sql = $conexion->query($query);
			$rowReceptor = $sql->fetch_assoc();
			$UUID = $row['wsuuid'];
			$SerieFolio = $serie."".$folio;

			$arreglo = array('status'=>0,'mensaje'=>0);
			$errores = array(201=>'Folio cancelado exitosamente',
							 202=>'Folio cancelado previamente',
							 203=>'No corresponde el RFC del Emisor y de quien solicita la cancelacion',
							 205=>'Folio inexistente',
							 501=>'Autenticacion no valida',
							 702=>'No ha registrado el RFC emisor bajo la cuenta del PAC',
							 703=>'Cuenta en el PAC suspendida',
							 704=>'Error en la contrasena de la llave privada',
							 708=>'No se pudo conectar al SAT',
							 709=>'No se pudo generar la cancelacion intente mas tarde, si ya es la segunda vez que ve este mensaje contacte a Soporte Tecnico',
							 711=>'Error en el certificado al cancelar');

			if($UUID!=""){
				
				# Username and Password, assigned by FINKOK
				#DEMO
				//$url = "http://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl";
				//$username = 'sergio@xiontecnologias.com';
				//$password = 'P4ssw0rd+';	
				
				#PRODUCCION
				$url = "https://facturacion.finkok.com/servicios/soap/cancel.wsdl";
				$username = 'nomina@llantasyrinesmatrix.com';
				$password = 'M@tr1x2017';
				
				# Consuming the cancel service
				# Read the x509 certificate file on PEM format and encode it on base64
				$cer_path = "certificados/".$rowEmisor['rfc'].".cer.pem";
				$cer_file = fopen($cer_path, "r");
				$cer_content = fread($cer_file, filesize($cer_path));
				fclose($cer_file);
				# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
				//$cer_content = base64_encode($cer_content);
				
				# Read the Encrypted Private Key (des3) file on PEM format and encode it on base64
				$key_path = "certificados/".$rowEmisor['rfc'].".enc.key";
				$key_file = fopen($key_path, "r");
				$key_content = fread($key_file,filesize($key_path));
				fclose($key_file);
				# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
				//$key_content = base64_encode($key_content);
				
				//echo $cer_content."<br>".$key_content;
				
				$taxpayer_id = $rowEmisor['rfc']; # The RFC of the Emisor
				$invoices = array("".$UUID.""); # A list of UUIDs
				
				$arreglo = Array('status'=>0,'mensaje'=>'');
				
				$client = new SoapClient($url,array("soap_version" => SOAP_1_1,"trace" => 1));
				$params = array(
				  "UUIDS" => array('uuids' => $invoices),
				  "username" => $username,
				  "password" => $password,
				  "taxpayer_id" => $taxpayer_id,
				  "cer" => $cer_content,
				  "key" => $key_content
				);
				$response = $client->__soapCall("cancel", array($params));
				//print_r($response);
					
				if(count(get_object_vars($response->cancelResult))>1)
					$status = $response->cancelResult->Folios->Folio->EstatusUUID;
				else
					$status = 709;
				
				
				if($status == 201){
					/*
					if($rowReceptor['email']!=""){
						
						// primero hay que incluir la clase phpmailer para poder instanciar 
						//un objeto de la misma
						require "phpmailer/class.phpmailer.php";
						
						//instanciamos un objeto de la clase phpmailer al que llamamos 
						//por ejemplo mail
						$mail = new phpmailer();
				
						$mail->IsSMTP(); // enable SMTP
						$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
						$mail->SMTPAuth = true;  // authentication enabled
						$mail->SMTPSecure = 'ssl';
						$mail->Port = 465;
						//Asignamos a Host el nombre de nuestro servidor smtp
						$mail->Host = "p3plcpnl0180.prod.phx3.secureserver.net";
						
						//Le decimos cual es nuestro nombre de usuario y password
						$mail->Username = "no-responder@xiontecnologias.com";
						$mail->Password = "Serygra300107+-";
						
						//Indicamos cual es nuestra direcci??n de correo y el nombre que 
						//queremos que vea el usuario que lee nuestro correo
						$mail->From = "no-responder@xiontecnologias.com";
						
						$mail->FromName = $rE['nombre'];
						
						//Asignamos asunto y cuerpo del mensaje
						//El cuerpo del mensaje lo ponemos en formato html, haciendo 
						//que se vea en negrita
						if($_POST['tipo']==1){
							$mail->Subject = "Cancelacion Factura";
							$mail->Body = "<b>Buen d&iacute;a ".$rowReceptor['nombre']."</b><p>Le informamos que la factura ".$rF['serie']."-".$rF['folio']." ha sido cancelada. Cualquier aclaraci&oacute;n comunicarse al tel&eacute;fono ".$rE['telefono']."</p>";
							
							//Definimos AltBody por si el destinatario del correo no admite 
							//email con formato html
							$mail->AltBody ="Buen dia ".$rowReceptor['nombre'].". Le informamos que la factura ".$row['serie']."-".$row['folio']." ha sido cancelada. Cualquier aclaracion comunicarse al telefono ".$rE['telefono'].".";
						}
						if($_POST['tipo']==3){
							$mail->Subject = "Cancelacion Nota de Credito";
							$mail->Body = "<b>Buen d&iacute;a ".$rowReceptor['nombre']."</b><p>Le informamos que la Nota de Credito ".$rF['serie']."-".$rF['folio']." ha sido cancelada. Cualquier aclaraci&oacute;n comunicarse al tel&eacute;fono ".$rE['telefono']."</p>";
							
							//Definimos AltBody por si el destinatario del correo no admite 
							//email con formato html
							$mail->AltBody ="Buen dia ".$rowReceptor['nombre'].". Le informamos que la Nota de Credito ".$row['serie']."-".$row['folio']." ha sido cancelada. Cualquier aclaracion comunicarse al telefono ".$rE['telefono'].".";
						}
						
						//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
						//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120  
						$mail->Timeout=10;
						
						//Indicamos cuales son las direcciones de destino del correo y enviamos 
						//los mensajes
						$mail->AddAddress($rowReceptor['email']);
					
						//se envia el mensaje, si no ha habido problemas la variable $success 
						//tendra el valor true
						$exito = $mail->Send();
					
						//La clase phpmailer tiene un pequeno bug y es que cuando envia un mail con
						//attachment la variable ErrorInfo adquiere el valor Data not accepted, dicho 
						//valor no debe confundirnos ya que el mensaje ha sido enviado correctamente
						if ($mail->ErrorInfo=="SMTP Error: Data not accepted") {
						   $exito=true;
						 }
					}*/
					
					//ACTUALIZA EL RegistroPatronal
					$qws = "UPDATE ptimbrado SET status=99
										WHERE    folio=".$folio." AND serie='".$serie."'";
					$sws = $conexion->query($qws);	
					
					$arreglo['status'] = $status;
					$arreglo['mensaje'] = $errores[$status];
					$response_path = "cancelaciones/".$taxpayer_id."/Cancel_".$SerieFolio."_".$UUID.".xml";
					$xml_out_file = fopen($response_path, "w");
					fwrite($xml_out_file, utf8_encode($response->cancelResult->Acuse));
					fclose($xml_out_file);
				}
				else{
					$arreglo['status'] = $status;
					$arreglo['mensaje'] = $errores[$status];
				}
			}
			else{
				$arreglo['status'] = 205;
				$arreglo['mensaje'] = $errores[205];
			}

			echo json_encode($arreglo);
			break;
		}
	}

function CalculaEdad( $fecha ) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function CalculaAntiguedadSAT( $fecha, $fechaFinal ) {
	$exf1 = explode("-",$fecha);
	$exf2 = explode("-",$fechaFinal);
    $fInicio = $exf1[2]."/".$exf1[1]."/".$exf1[0];
	$fFinal = $exf2[2]."/".$exf2[1]."/".$exf2[0];
	$AInicio = $exf2[0];
	$AFinal = $exf2[0];
	$sumadiasBis = 0;
	for ($i = $AInicio; $i <= $AFinal; $i++) {
	(($i % 4) == 0) ? $bis = 86400 : $bis = 0;
	$sumadiasBis += $bis;
	}
	//echo "Fecha de Inicio " .$fInicio. "<br />Fecha Final " .$fFinal. "<br /><br />Restan<br />";
	// Calculamos los segundos entre las dos fechas
	$fechaInicio = mktime(0,0,0,($exf1[1] * 1),($exf1[2] * 1),$exf1[0]);
	$fechaFinal = mktime(0,0,0,($exf2[1] * 1),($exf2[2] * 1),$exf2[0]);
	$segundos = ($fechaFinal - $fechaInicio);
	$anyos = floor(($segundos-$sumadiasBis)/31536000);
	$semanas = floor(($segundos-$sumadiasBis)/604800);
	//echo $anyos. " a&ntilde;os<br />";
	$segundosRestante = ($segundos-$sumadiasBis)%(31536000);
	$meses = floor($segundosRestante/2592000);
	//echo $meses. " meses<br />";
	$segundosRestante = ($segundosRestante%2592000); // Suma un d??a mas por cada a??os bisiesto
	//$segundosRestante = (($segundosRestante-$sumadiasBis)%2592000); // No suma un d??a mas por cada a??o bisiesto
	$dias = abs(floor($segundosRestante/86400));
	//echo $dias. " d&iacute;as<br />";
	
	if($semanas>0){
		$return = "P".$semanas."W";
		
	}else{
		$return = "P";
		if($anyos>0)
			$return.= $anyos."Y";
		if($meses>0)
			$return.= $meses."M";
		$return.= ($dias + 1)."D";
	}
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
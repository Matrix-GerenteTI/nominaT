<?php
require('fpdf/fpdf.php');
require("config.inc.php");
//require("control.php");
//conexion();
$GLOBALS['n'] = 0;
$GLOBALS['paginas'] = 1;
$GLOBALS['h'] = 0;
//GENERAR EL PDF YA QUE SI SE TIMBRÓ	
class PDF extends FPDF{
	
	var $widths;
	var $heightL;
	var $aligns;
	var $fonts;
	
	var $javascript;
	var $n_js;

	function IncludeJS($script) {
		$this->javascript=$script;
	}

	function _putjavascript() {
		$this->_newobj();
		$this->n_js=$this->n;
		$this->_out('<<');
		$this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
		$this->_out('>>');
		$this->_out('endobj');
		$this->_newobj();
		$this->_out('<<');
		$this->_out('/S /JavaScript');
		$this->_out('/JS '.$this->_textstring($this->javascript));
		$this->_out('>>');
		$this->_out('endobj');
	}

	function _putresources() {
		parent::_putresources();
		if (!empty($this->javascript)) {
			$this->_putjavascript();
		}
	}

	function _putcatalog() {
		parent::_putcatalog();
		if (!empty($this->javascript)) {
			$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
		}
	}


	function AutoPrint($dialog=false)
	{
		//Open the print dialog or start printing immediately on the standard printer
		$param=($dialog ? 'true' : 'false');
		$script="print($param);";
		$this->IncludeJS($script);
	}
	
	function AutoPrintToPrinter($server, $printer, $dialog=false)
	{
		//Print on a shared printer (requires at least Acrobat 6)
		$script = "var pp = getPrintParams();";
		if($dialog)
			$script .= "pp.interactive = pp.constants.interactionLevel.full;";
		else
			$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
		$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
		$script .= "print(pp);";
		$this->IncludeJS($script);
	}
	
	function truncateFloat($number, $digitos)
	{
		$raiz = 10;
		$multiplicador = pow ($raiz,$digitos);
		$resultado = ((int)($number * $multiplicador)) / $multiplicador;
		return number_format($resultado, $digitos);
	 
	}
	
	function num2letras($num, $fem = false, $dec = true) { 
	   $matuni[2]  = "dos"; 
	   $matuni[3]  = "tres"; 
	   $matuni[4]  = "cuatro"; 
	   $matuni[5]  = "cinco"; 
	   $matuni[6]  = "seis"; 
	   $matuni[7]  = "siete"; 
	   $matuni[8]  = "ocho"; 
	   $matuni[9]  = "nueve"; 
	   $matuni[10] = "diez"; 
	   $matuni[11] = "once"; 
	   $matuni[12] = "doce"; 
	   $matuni[13] = "trece"; 
	   $matuni[14] = "catorce"; 
	   $matuni[15] = "quince"; 
	   $matuni[16] = "dieciseis"; 
	   $matuni[17] = "diecisiete"; 
	   $matuni[18] = "dieciocho"; 
	   $matuni[19] = "diecinueve"; 
	   $matuni[20] = "veinte"; 
	   $matunisub[2] = "dos"; 
	   $matunisub[3] = "tres"; 
	   $matunisub[4] = "cuatro"; 
	   $matunisub[5] = "quin"; 
	   $matunisub[6] = "seis"; 
	   $matunisub[7] = "sete"; 
	   $matunisub[8] = "ocho"; 
	   $matunisub[9] = "nove"; 
	
	   $matdec[2] = "veint"; 
	   $matdec[3] = "treinta"; 
	   $matdec[4] = "cuarenta"; 
	   $matdec[5] = "cincuenta"; 
	   $matdec[6] = "sesenta"; 
	   $matdec[7] = "setenta"; 
	   $matdec[8] = "ochenta"; 
	   $matdec[9] = "noventa"; 
	   $matsub[3]  = 'mill'; 
	   $matsub[5]  = 'bill'; 
	   $matsub[7]  = 'mill'; 
	   $matsub[9]  = 'trill'; 
	   $matsub[11] = 'mill'; 
	   $matsub[13] = 'bill'; 
	   $matsub[15] = 'mill'; 
	   $matmil[4]  = 'millones'; 
	   $matmil[6]  = 'billones'; 
	   $matmil[7]  = 'de billones'; 
	   $matmil[8]  = 'millones de billones'; 
	   $matmil[10] = 'trillones'; 
	   $matmil[11] = 'de trillones'; 
	   $matmil[12] = 'millones de trillones'; 
	   $matmil[13] = 'de trillones'; 
	   $matmil[14] = 'billones de trillones'; 
	   $matmil[15] = 'de billones de trillones'; 
	   $matmil[16] = 'millones de billones de trillones'; 
	   
	   //Zi hack
	   $float=explode('.',$num);
	   $num=$float[0];
	
	   $num = trim((string)@$num); 
	   if ($num[0] == '-') { 
		  $neg = 'menos '; 
		  $num = substr($num, 1); 
	   }else 
		  $neg = ''; 
	   while ($num[0] == '0') $num = substr($num, 1); 
	   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
	   $zeros = true; 
	   $punt = false; 
	   $ent = ''; 
	   $fra = ''; 
	   for ($c = 0; $c < strlen($num); $c++) { 
		  $n = $num[$c]; 
		  if (! (strpos(".,'''", $n) === false)) { 
			 if ($punt) break; 
			 else{ 
				$punt = true; 
				continue; 
			 } 
	
		  }elseif (! (strpos('0123456789', $n) === false)) { 
			 if ($punt) { 
				if ($n != '0') $zeros = false; 
				$fra .= $n; 
			 }else 
	
				$ent .= $n; 
		  }else 
	
			 break; 
	
	   } 
	   $ent = '     ' . $ent; 
	   if ($dec and $fra and ! $zeros) { 
		  $fin = ' coma'; 
		  for ($n = 0; $n < strlen($fra); $n++) { 
			 if (($s = $fra[$n]) == '0') 
				$fin .= ' cero'; 
			 elseif ($s == '1') 
				$fin .= $fem ? ' una' : ' un'; 
			 else 
				$fin .= ' ' . $matuni[$s]; 
		  } 
	   }else 
		  $fin = ''; 
	   if ((int)$ent === 0) return 'Cero ' . $fin; 
	   $tex = ''; 
	   $sub = 0; 
	   $mils = 0; 
	   $neutro = false; 
	   while ( ($num = substr($ent, -3)) != '   ') { 
		  $ent = substr($ent, 0, -3); 
		  if (++$sub < 3 and $fem) { 
			 $matuni[1] = 'una'; 
			 $subcent = 'as'; 
		  }else{ 
			 $matuni[1] = $neutro ? 'un' : 'uno'; 
			 $subcent = 'os'; 
		  } 
		  $t = ''; 
		  $n2 = substr($num, 1); 
		  if ($n2 == '00') { 
		  }elseif ($n2 < 21) 
			 $t = ' ' . $matuni[(int)$n2]; 
		  elseif ($n2 < 30) { 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  }else{ 
			 $n3 = $num[2]; 
			 if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
			 $n2 = $num[1]; 
			 $t = ' ' . $matdec[$n2] . $t; 
		  } 
		  $n = $num[0]; 
		  if ($n == 1) { 
			 $t = ' ciento' . $t; 
		  }elseif ($n == 5){ 
			 $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
		  }elseif ($n != 0){ 
			 $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
		  } 
		  if ($sub == 1) { 
		  }elseif (! isset($matsub[$sub])) { 
			 if ($num == 1) { 
				$t = ' mil'; 
			 }elseif ($num > 1){ 
				$t .= ' mil'; 
			 } 
		  }elseif ($num == 1) { 
			 $t .= ' ' . $matsub[$sub] . '?n'; 
		  }elseif ($num > 1){ 
			 $t .= ' ' . $matsub[$sub] . 'ones'; 
		  }   
		  if ($num == '000') $mils ++; 
		  elseif ($mils != 0) { 
			 if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
			 $mils = 0; 
		  } 
		  $neutro = true; 
		  $tex = $t . $tex; 
	   } 
	   $tex = $neg . substr($tex, 1) . $fin; 
	   //Zi hack --> return ucfirst($tex);
	   $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
	   return $end_num; 
	}
	
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetFonts($b)
	{
		//Set the array of column widths
		$this->Ffonts=$b;
	}
	
	function SetBorders($b)
	{
		//Set the array of column widths
		$this->Bborders=$b;
	}
	
	function SetHeights($h)
	{
		//Set the array of column heights
		$this->heightL=$h;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$f=$this->Ffonts[$i];
			//print_r($f);
			$h=$this->heightL;
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			$br=isset($this->Bborders[$i]) ? $this->Bborders[$i] : 0;
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->SetFont((string)$f[0],(string)$f[1],$f[2]);
			//$this->Rect($x,$y,$w,$h);
			$this->MultiCell($w,$h,$data[$i],(string)$br,$a,false);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function Row2($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x, $y, $w, $h);
			//Print the text
			$this->SetFillColor(255,255,255); 
			$this->MultiCell($w, 5, $data[$i], 0, $a, true);
			//Put the position to the right of the cell
			$this->SetXY($x+$w, $y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>($this->PageBreakTrigger-83))
		{
			$this->AddPage($this->CurOrientation);
			$this->SetMargins(6,20,20);
			$this->Ln(12);
			$GLOBALS['paginas']++;
		}
	}
	
	function NbLines($w, $txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r", '', $txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	function mes($m){
		$m = $m*1;
		$mesLetras = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
		return $mesLetras[$m];
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
	
	function formateaFecha($fecha){
		$arr = explode("-",$fecha);
		$fechaNueva = $arr[2]."-".$arr[1]."-".$arr[0];
		return $fechaNueva;	
	}
	
	function Header()
	{
		require_once("phpqrcode/qrlib.php");
		$conexion = new mysqli(HOST,USER,PASSWORD,BD);
		$this->SetMargins(10,10,10);
		$this->SetFont('Arial','',10);
		//$this->Text(20,14,'',0,'C', 0);
		$query = "SELECT * FROM ptimbrado WHERE serie='".$_GET['serie']."' AND folio='".$_GET['folio']."'";
		$sql = $conexion->query($query);
		$row = $sql->fetch_assoc();
		$idcontrato = $row['idcontrato'];
		
		$NominaTipoNomina = $row['idtiponomina'];
		$NominaFechaInicialPago = $row['fechaIni'];
		$NominaFechaFinalPago = $row['fechaFin'];
		$NominaDiasPagados = $row['diasPagados'];
		$NominaFechaPago = $row['fechaPago'];
		$NominaUUID = $row['uuidanterior'];
		
		
		//echo $query;
		$idcontrato = $row['idcontrato'];
		//DATOS DEL CONTRATO y se aprovecha para sacar el nip
		$q1 = "SELECT 	*,
						e.descripcion as departamento,
						u.descripcion as puesto
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
		$NominaReceptorAntiguedad = $this->CalculaAntiguedadSAT($NominaReceptorInicioLab,$NominaFechaFinalPago);
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
		$noCertificado = $r1['valor'];
		
		$q1 = "SELECT * FROM cgeneral WHERE id='serie'";
		$s1 = $conexion->query($q1);
		$r1 = $s1->fetch_assoc();
		$serie = $r1['valor'];
		
		//Moneda
		$Moneda = 'MXN';
		
		//UUID Relacionado
		$RelacionadoUUID = '';
		
		//Datos de Emisor
		$q1 = "SELECT * FROM ppatron p 
			   INNER JOIN pdireccion d ON p.iddireccion=d.id 
			   INNER JOIN cregimenfiscal r ON p.idregimenfiscal=r.id 
			   WHERE p.id=1";
		$s1 = $conexion->query($q1);
		$rEmisor = $s1->fetch_assoc();
		$EmisorRFC = $rEmisor['rfc'];
		$EmisorNombre = $rEmisor['nombre_razsoc'];
		$EmisorCP = $rEmisor['cp'];
		$LugarExpedicion = $rEmisor['cp'];
		$EmisorRegimen = $rEmisor['descripcion'];
		$NominaEmisorCurp = $rEmisor['curp'];
		$NominaEmisorRegistro = $rEmisor['registropatronal'];
		
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
		
		
		$thread_id = $conexion->thread_id;
		/* Poner fin a la conexión */
		$conexion->kill($thread_id);
		$conexion->close();
		
		$TPercepciones = $TotalSueldos + $TotalJubilacionPensionRetiro + $TotalSeparacionIndemnizacion;
		$subtotal = $TPercepciones + $TotalOtrosPagos;
		$descuento = $TotalImpuestosRetenidos + $TotalOtrasDeducciones;
		$total = $subtotal - $descuento;
		$NominaTotalDeducciones = $descuento;
		$NominaTotalPercepciones = $TPercepciones;
		$NominaTotalOtrosPagos = $TotalOtrosPagos;
		
		//INSERTAMOS EL LOGO
		$this->Image('logos/logo.jpg',10,10,40); //LOGO
		
		$totalTMP = number_format($total,6,".","");
		$totalTMP = explode(".",$totalTMP);
		$enteros = "";
		$decimales = $totalTMP[1];
		
		$tamanioStr = 10 - strlen($totalTMP[0]);
		for($i=1;$i<=$tamanioStr;$i++){
			$enteros.= "0";
		}
		
		$enteros.= $totalTMP[0];
		$totalCBB = $enteros.".".$decimales;
		
		$cadenaCodigoBarras = "?re=".trim(str_replace("-","",$EmisorRFC))."&rr=".trim(str_replace("-","",$ReceptorRFC))."&tt=".$totalCBB."&id=".$row['wsuuid'];
		$tmpImg = QRcode::png($cadenaCodigoBarras, 'CBB.png', 'L', 4, 2);
		$this->Image("CBB.png",10,230,30);
		
		if($GLOBALS['n']==0){
			$this->SetY(10);
			
		}
		else
			$this->SetY(9);
		
		#DATOS DEL CFDI							
		$this->SetWidths(array(21,20, 84, 70));
		$this->SetAligns(array('C','C','L','R'));
		$this->SetFonts(array(array('Arial','',8),array('Arial','b',8),array('Arial','b',11),array('Arial','b',11)));
		$this->Row(array('','', 'RECIBO DE NOMINA', $row['serie'].'-'.$row['folio']));
		$this->Ln(2);
		$this->SetAligns(array('C','L','L','R'));
		$this->SetHeights(3);
		$this->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','b',8)));
		$this->Row(array('','', strtoupper($EmisorNombre), 'No. de Certificado SAT'));
		$this->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8)));
		$this->Row(array('','', $EmisorRFC, $row['noCertificado']));
		$this->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','b',8)));
		$this->Row(array('','', strtoupper($rEmisor['calle'].' '.$rEmisor['numext'].', '.$rEmisor['colonia'].' C.P. '.$rEmisor['cp'].'.'), 'Fecha y Hora de Emision'));
		$this->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8)));
		$this->Row(array('','', strtoupper($rEmisor['municipio'].', '.$rEmisor['idestado']), $row['fecha'].'T'.$row['hora']));
		$this->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','b',8)));
		$this->Row(array('','', 'Registro Patronal: '.$NominaEmisorRegistro, 'Lugar de Expedicion'));
		$this->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8)));
		$this->Row(array('','', 'Regimen: '.$EmisorRegimen, $rEmisor['municipio']));
		$this->Ln(3);
		if($GLOBALS['n']==0){
			$this->Ln(2);
			$GLOBALS['n']++;
		}
		/*else
			$this->Ln(3);*/
		
		#Expedido en
		/*$this->Ln(5);
		$this->SetTextColor(255);
		$this->SetWidths(array(56,7, 130, 40));
		$this->SetHeights(1);
		$this->SetTextColor(0);
		$this->SetAligns(array('C','C','L','C'));
		$this->Row(array(strtoupper(utf8_decode($row['leyenda'])),'', '', ''));*/
		
		
		
		
	}
	
	function Footer()
	{
		$conexion = new mysqli(HOST,USER,PASSWORD,BD);
		$query = "SELECT * FROM ptimbrado WHERE serie='".$_GET['serie']."' AND folio='".$_GET['folio']."'";
		$sql = $conexion->query($query);
		$row = $sql->fetch_assoc();
		$idcontrato = $row['idcontrato'];
		
		//DATOS DEL CONTRATO y se aprovecha para sacar el nip
		$q1 = "SELECT 	*,
						e.descripcion as departamento,
						u.descripcion as puesto
			   FROM 	pcontrato c
			   INNER JOIN pdireccion d ON c.iddireccion=d.id
			   INNER JOIN cdepartamento e ON c.iddepartamento=e.id
			   INNER JOIN cpuesto u ON c.idpuesto=u.id
			   WHERE 	c.id=".$idcontrato;
		$s1 = $conexion->query($q1);
		$r1 = $s1->fetch_assoc();
		$nip = $r1['nip'];
		
		//Datos de Emisor
		$q1 = "SELECT * FROM ppatron p INNER JOIN pdireccion d ON p.iddireccion=d.id WHERE p.id=1";
		$s1 = $conexion->query($q1);
		$rEmisor = $s1->fetch_assoc();
		$EmisorRFC = $rEmisor['rfc'];
		$EmisorNombre = $rEmisor['nombre_razsoc'];
		$EmisorCP = $rEmisor['cp'];
		$LugarExpedicion = $rEmisor['cp'];
		$EmisorRegimen = $rEmisor['idregimenfiscal'];
		$NominaEmisorCurp = $rEmisor['curp'];
		$NominaEmisorRegistro = $rEmisor['registropatronal'];
		
		//Datos del Receptor
		$q1 = "SELECT * FROM pempleado WHERE nip=".$nip;
		$s1 = $conexion->query($q1);
		$r1 = $s1->fetch_assoc();
		$ReceptorRFC = $r1['rfc'];
		$ReceptorNombre = $r1['nombre'];
		$NominaReceptorCurp = $r1['curp'];
		$NominaReceptorNSS = $r1['nss'];
		$NominaReceptorNumEmpleado = $nip;	
		
		$strConsulta = "SELECT 	d.idtipodeduccion as TipoDeduccion,
						d.idtipodeduccion as Clave,
						td.descripcion as Concepto,
						d.importe as Importe
			   FROM 	pdeducciones d
			   INNER JOIN ctipodeduccion td ON d.idtipodeduccion=td.id
			   WHERE 	d.idcontrato=".$row['idcontrato']." 
			   AND 		d.status=1";
		$s3 = $conexion->query($strConsulta);
		
		$lineas = 0;
		$rw = array();
		while($fila = $s3->fetch_assoc()){
			$rw[] = $fila;
		}
		
		foreach($rw as $ro){
			$nb=0;
			#for($k=0;$k<25;$k++){
				$nb = $this->NbLines(187, strtoupper(''));			
				//$pdf->Cell(18,5,$nb,0,0,'C');
				$lineas = $lineas + $nb;
			#}
		}
		
		$thread_id = $conexion->thread_id;
		/* Poner fin a la conexión */
		$conexion->kill($thread_id);
		$conexion->close();
		
		$paginas = ceil($lineas/17);
		
		$this->SetY(-57);
		$this->SetMargins(10,10,10);
		
		$this->Line(10,221,205,221);
		$this->SetFont('Arial','b',7);
		$this->Cell(57,5,"Folio Fiscal",0,0,'L');
		$this->Cell(50,5,"Certificado SAT",0,0,'L');
		$this->Cell(50,5,"Fecha de Certificacion",0,0,'L');
		$this->Cell(50,5,"RFC del PAC",0,0,'L');
		$this->Ln(3);
		$this->SetFont('Arial','',7);
		$this->Cell(57,5,$row['wsuuid'],0,0,'L');
		$this->Cell(50,5,$row['wsnoCertificado'],0,0,'L');
		$this->Cell(50,5,$row['wsfecha'],0,0,'L');
		$this->Cell(50,5,$row['wsrfc'],0,0,'L');
		$this->Ln(5);
		$this->SetFont('Arial','b',6);
		$this->Cell(29,5,"",0,0,'L');
		$this->Cell(107,5,"Sello del CFD:",0,0,'L');	
		$this->Ln(4);
		$this->SetFont('Arial','',6);
		$this->Cell(29,2,"",0,0,'L');
		$this->MultiCell(107,2,$row['wssellocfd'],0,'L');
		$this->SetFont('Arial','b',6);
		$this->Cell(29,3,"",0,0,'L');
		$this->Cell(107,3,"Sello del SAT:",0,0,'L');	
		$this->Ln(3);
		$this->SetFont('Arial','',6);
		$this->Cell(29,2,"",0,0,'L');
		$this->MultiCell(107,2,$row['wssellosat'],0,'L');
		$this->SetFont('Arial','b',6);
		$this->Cell(29,3,"",0,0,'L');
		$this->Cell(107,3,"Cadena Original:",0,0,'L');
		$this->Ln(3);
		$this->SetFont('Arial','',6);
		$this->MultiCell(136,2,utf8_decode($row['cadenaOriginal']),0,'L');		
		$this->Ln(2);
		$this->SetFont('Arial','b',8);
		$this->Cell(100,5,"Este documento es una representacion impresa de un CFDI",0,0,'L');
		$this->Cell(95,5,'Pag. '.$GLOBALS['paginas'].'/'.$paginas,0,0,'R');
		
		$this->SetXY(145,-45);
		$this->SetFont('Arial','b',8);
		$this->MultiCell(62,3,'RECIBÍ DE '.strtoupper($EmisorNombre).' EL TOTAL NETO SEÑALADO Y ESTOY DE ACUERDO CON LO INDICADO.',0,'C');
		$this->Line(151,261,201,261);
		$this->SetXY(145,-17);
		$this->MultiCell(62,3,'FIRMA DEL EMPLEADO',0,'C');
	}
	
	}
		$conexion = new mysqli(HOST,USER,PASSWORD,BD);
		$pdf=new PDF('P','mm','Letter');
		$pdf->Open();
		$pdf->AddPage();
		$pdf->SetMargins(10,10,10);	
		$pdf->SetAutoPageBreak(true);	
		
		$query = "SELECT * FROM ptimbrado WHERE serie='".$_GET['serie']."' AND folio='".$_GET['folio']."'";
		$sql = $conexion->query($query);
		$row = $sql->fetch_assoc();
		$idcontrato = $row['idcontrato'];
		
		$NominaTipoNomina = $row['idtiponomina'];
		$NominaFechaInicialPago = $row['fechaIni'];
		$NominaFechaFinalPago = $row['fechaFin'];
		$NominaDiasPagados = $row['diasPagados'];
		$NominaFechaPago = $row['fechaPago'];
		$NominaUUID = $row['uuidanterior'];
		
		
		//echo $query;
		$idcontrato = $row['idcontrato'];
		//DATOS DEL CONTRATO y se aprovecha para sacar el nip
		$q1 = "SELECT 	*,
						e.descripcion as departamento,
						u.descripcion as puesto,
						a.descripcion as banco,
						b.descripcion as estado,
						h.descripcion as periodicidadpago,
						j.descripcion as riesgopuesto,
						f.descripcion as tipocontrato,
						g.descripcion as tipojornada,
						i.descripcion as tiporegimen
			   FROM 	pcontrato c
			   INNER JOIN pdireccion d ON c.iddireccion=d.id
			   INNER JOIN cdepartamento e ON c.iddepartamento=e.id
			   INNER JOIN cpuesto u ON c.idpuesto=u.id
			   INNER JOIN cbanco a ON c.idbanco=a.id
			   INNER JOIN cestado b ON d.idestado=b.id
			   INNER JOIN cperiodicidadpago h ON c.idperiodicidadpago=h.id
			   INNER JOIN criesgopuesto j ON c.idriesgopuesto=j.id
			   INNER JOIN ctipocontrato f ON c.idtipocontrato=f.id
			   INNER JOIN ctipojornada g ON c.idtipojornada=g.id
			   INNER JOIN ctiporegimen i ON c.idtiporegimen=i.id
			   WHERE 	c.id=".$idcontrato;
		$s1 = $conexion->query($q1);
		$r1 = $s1->fetch_assoc();
		$nip = $r1['nip'];
		$NominaReceptorBanco = $r1['banco'];
		$NominaReceptorCuenta = $r1['cuentabancaria'];
		$NominaReceptorDepartamento = $r1['departamento'];
		$NominaReceptorEstado = $r1['estado'];
		$NominaReceptorInicioLab = $r1['fechainiciolab'];
		$NominaReceptorAntiguedad = $pdf->CalculaAntiguedadSAT($NominaReceptorInicioLab,$NominaFechaFinalPago);
		$NominaReceptorPeriodicidad = $r1['periodicidadpago'];
		$NominaReceptorPuesto = $r1['puesto'];
		$NominaReceptorRiesgo = $r1['riesgopuesto'];
		$NominaReceptorSalarioIntegrado = $r1['salariodiario'];
		$NominaReceptorSalariobase = $r1['salariobase'];
		$NominaReceptorSindicalizado = $r1['sindicalizado'];
		$NominaReceptorTipoContrato = $r1['tipocontrato'];
		$NominaReceptorTipoJornada = $r1['tipojornada'];
		$NominaReceptorTipoRegimen = $r1['tiporegimen'];
		
		//Obtenemos Serie y NoCertificado
		$q1 = "SELECT * FROM cgeneral WHERE id='noCertificado'";
		$s1 = $conexion->query($q1);
		$r1 = $s1->fetch_assoc();
		$noCertificado = $r1['valor'];
		
		$q1 = "SELECT * FROM cgeneral WHERE id='serie'";
		$s1 = $conexion->query($q1);
		$r1 = $s1->fetch_assoc();
		$serie = $r1['valor'];
		
		//Moneda
		$Moneda = 'MXN';
		
		//UUID Relacionado
		$RelacionadoUUID = '';
		
		//Datos de Emisor
		$q1 = "SELECT * FROM ppatron p INNER JOIN pdireccion d ON p.iddireccion=d.id WHERE p.id=1";
		$s1 = $conexion->query($q1);
		$rEmisor = $s1->fetch_assoc();
		$EmisorRFC = $rEmisor['rfc'];
		$EmisorNombre = $rEmisor['nombre_razsoc'];
		$EmisorCP = $rEmisor['cp'];
		$LugarExpedicion = $rEmisor['cp'];
		$EmisorRegimen = $rEmisor['idregimenfiscal'];
		$NominaEmisorCurp = $rEmisor['curp'];
		$NominaEmisorRegistro = $rEmisor['registropatronal'];
		
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
		$q2 = "SELECT 	a.dias as Dias,
						a.horasextra as HorasExtra,
						a.importepagado as ImportePagado,
						a.exento as ImporteExento,
						a.gravado as ImporteGravado,
						b.descripcion as TipoHoras
			   FROM 	phorasextra a
			   INNER JOIN ctipohoras b ON a.idtipohoras=b.id
			   WHERE 	a.idcontrato=".$idcontrato." 
			   AND 		a.status=1";
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
		
		$strConsulta = "SELECT 	d.idtipodeduccion as TipoDeduccion,
						d.idtipodeduccion as Clave,
						td.descripcion as Concepto,
						d.importe as Importe
			   FROM 	pdeducciones d
			   INNER JOIN ctipodeduccion td ON d.idtipodeduccion=td.id
			   WHERE 	d.idcontrato=".$row['idcontrato']." 
			   AND 		d.status=1";
		$s3 = $conexion->query($strConsulta);
		
		
		$numfilas = $s3->num_rows;
		
		$pdf->SetFont('Arial','',7);
		//$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);
		$pdf->SetWidths(array(18, 29, 94, 30, 30));
		$pdf->SetAligns(array('C','C','L','R','R'));
		$arr = array();
		while($rowt = $s3->fetch_assoc()){		
			$arr[] = $rowt;
		}
		
		$thread_id = $conexion->thread_id;
		/* Poner fin a la conexión */
		$conexion->kill($thread_id);
		$conexion->close();
		//DATOS DEL EMPLEADO
		$pdf->SetFont('Arial','b',8);
		$pdf->MultiCell(194,5,'DATOS DEL EMPLEADO',1,'C');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"Nombre:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$ReceptorNombre,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Num. Empleado:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$nip,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Entidad Fed.:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorEstado,0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"R.F.C:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$ReceptorRFC,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Antigüedad:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorAntiguedad,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Inicio Laboral:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$pdf->formateaFecha($NominaReceptorInicioLab),0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"CURP:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$NominaReceptorCurp,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Periodicidad:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorPeriodicidad,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Salario Diario:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorSalarioIntegrado,0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"Depto.:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$NominaReceptorDepartamento,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Riesgo Puesto:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorRiesgo,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Salario Base:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorSalariobase,0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"Puesto:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$NominaReceptorPuesto,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Tipo Regimen:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorTipoRegimen,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Banco:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorBanco,0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"NSS:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$NominaReceptorNSS,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Tipo Jornada:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorTipoJornada,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Cuenta:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorCuenta,0,0,'L');
		$pdf->Ln(4);
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(15,5,"Sindicato:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(55,5,$NominaReceptorSindicalizado,0,0,'L');
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"Tipo Contrato:",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,$NominaReceptorTipoContrato,0,0,'L');		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(25,5,"",0,0,'L');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(37,5,"",0,0,'L');
		$pdf->Ln(6);
		
		if(count($arrPercepcion)>0){
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(194,5,"PERCEPCIONES",1,1,'C');
			$pdf->Cell(20,5,"CLAVE",'L',0,'C');
			$pdf->Cell(20,5,"TIPO",0,0,'C');
			$pdf->Cell(114,5,"CONCEPTO",0,0,'C');
			$pdf->Cell(20,5,"GRAVADO",0,0,'C');	
			$pdf->Cell(20,5,"EXENTO",'R',1,'C');
			
			foreach($arrPercepcion as $row1){
				$pdf->SetWidths(array(20,20,114,20,20));
				$pdf->SetHeights(5);
				$pdf->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8)));
				$pdf->SetBorders(array('L',0,0,0,'R'));
				$pdf->Row(array($row1['Clave'],$row1['TipoPercepcion'],$row1['Concepto'], number_format($row1['ImporteGravado'],2,'.',','),number_format($row1['ImporteExento'],2,'.',',')));
			}
			foreach($arrJubilacion as $row8){
				$pdf->Row(array($row8['Clave'],$row8['TipoPercepcion'],$row8['Concepto'], number_format($row8['ImporteGravado'],2,'.',','),number_format($row8['ImporteExento'],2,'.',',')));
			}
			foreach($arrSeparacion as $row9){
				$pdf->Row(array($row9['Clave'],$row9['TipoPercepcion'],$row9['Concepto'], number_format($row9['ImporteGravado'],2,'.',','),number_format($row9['ImporteExento'],2,'.',',')));
			}
			
			if(count($arrHorasExtra)>0){
				$pdf->Row(array('019','019','Horas Extra', number_format($sumImporteGravado,2,'.',','),number_format($sumImporteExento,2,'.',',')));
				foreach($arrHorasExtra as $row2){
					$pdf->Row(array('','','Tipo de Horas: '.$row2['TipoHoras'].', Días: '.$row2['Dias'].', Horas: '.$row2['HorasExtra'], '',''));
				}
			}
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(20,5,"",'LBT',0,'C');
			$pdf->Cell(20,5,"",'BT',0,'C');
			$pdf->Cell(114,5,"",'BT',0,'C');
			$pdf->Cell(20,5,number_format($TotalImporteGravado,2,'.',','),'BT',0,'R');	
			$pdf->Cell(20,5,number_format($TotalImporteExento,2,'.',','),'RBT',1,'R');
		}
		$pdf->Ln(3);
		if(count($arrDeducciones)>0){
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(194,5,"DEDUCCIONES",1,1,'C');
			$pdf->Cell(20,5,"CLAVE",'L',0,'C');
			$pdf->Cell(20,5,"TIPO",0,0,'C');
			$pdf->Cell(134,5,"CONCEPTO",0,0,'C');
			$pdf->Cell(20,5,"IMPORTE",'R',1,'C');
			foreach($arrDeducciones as $row5){
				$pdf->SetWidths(array(20,20,134,20));
				$pdf->SetHeights(5);
				$pdf->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8)));
				$pdf->SetBorders(array('L',0,0,'R'));
				$pdf->Row(array($row5['Clave'],$row5['TipoDeduccion'],$row5['Concepto'], number_format($row5['Importe'],2,'.',',')));
			}
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(20,5,"",'LBT',0,'C');
			$pdf->Cell(20,5,"",'BT',0,'C');
			$pdf->Cell(134,5,"",'BT',0,'C');	
			$pdf->Cell(20,5,number_format($NominaTotalDeducciones,2,'.',','),'RBT',1,'R');
		}
		$pdf->Ln(3);
		if(count($arrOtrosPagos)>0){
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(194,5,"OTROS PAGOS",1,1,'C');
			$pdf->Cell(20,5,"CLAVE",'L',0,'C');
			$pdf->Cell(20,5,"TIPO",0,0,'C');
			$pdf->Cell(134,5,"CONCEPTO",0,0,'C');
			$pdf->Cell(20,5,"IMPORTE",'R',1,'C');
			foreach($arrOtrosPagos as $row6){
				$pdf->SetWidths(array(20,20,134,20));
				$pdf->SetHeights(5);
				$pdf->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8),array('Arial','',8)));
				$pdf->SetBorders(array('L',0,0,'R'));
				$pdf->Row(array($row6['Clave'],$row6['TipoOtroPago'],$row6['Concepto'], number_format($row6['Importe'],2,'.',',')));
			}
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(20,5,"",'LBT',0,'C');
			$pdf->Cell(20,5,"",'BT',0,'C');
			$pdf->Cell(134,5,"",'BT',0,'C');	
			$pdf->Cell(20,5,number_format($NominaTotalDeducciones,2,'.',','),'RBT',1,'R');
		}
		$pdf->Ln(3);
		$TotalIncidencias = 0;
		if(count($arrIncapacidades)>0){
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(194,5,"INCAPACIDADES",1,1,'C');
			$pdf->Cell(20,5,"CLAVE",'L',0,'C');
			$pdf->Cell(154,5,"CONCEPTO",0,0,'C');
			$pdf->Cell(20,5,"IMPORTE",'R',1,'C');
			foreach($arrIncapacidades as $row7){
				$pdf->SetWidths(array(20,154,20));
				$pdf->SetHeights(5);
				$pdf->SetFonts(array(array('Arial','',8),array('Arial','',8),array('Arial','',8)));
				$pdf->SetBorders(array('L',0,0,'R'));
				$pdf->Row(array($row7['DiasIncapacidad'],$row7['TipoIncapacidad'], number_format($row7['ImporteMonetario'],2,'.',',')));
			}
			$pdf->SetFont('Arial','b',8);
			$pdf->Cell(20,5,"",'LBT',0,'C');
			$pdf->Cell(154,5,"",'BT',0,'C');	
			$pdf->Cell(20,5,number_format($TotalIncidencias,2,'.',','),'RBT',1,'R');
		}
		
		$pdf->SetFont('Arial','b',8);
		$pdf->Cell(154,5,"Observaciones:",'LTR',0,'L');
		$pdf->Cell(20,5,"Subtotal:",'T',0,'R');	
		$pdf->Cell(20,5,number_format($subtotal,2,'.',','),'TR',0,'R');
		$pdf->Ln(4);
		$pdf->Cell(154,5,"",'LR',0,'R');
		$pdf->Cell(20,5,"Descuento:",'',0,'R');	
		$pdf->Cell(20,5,number_format($descuento,2,'.',','),'R',0,'R');
		$pdf->Ln(4);
		$pdf->Cell(154,5,"",'LBR',0,'R');
		$pdf->Cell(20,5,"Total:",'B',0,'R');	
		$pdf->Cell(20,5,number_format($total,2,'.',','),'RB',0,'R');
		$pdf->Ln(6);
		$pdf->Cell(194,5,strtoupper($pdf->num2letras(number_format($total,2,".",""))),0,0,'L');
		
		
	//$pdf->AutoPrint(true);
	$pdf->Output();
	
	
?>
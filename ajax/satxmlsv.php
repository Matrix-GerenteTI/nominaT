<?php
//
// +---------------------------------------------------------------------------+
// | satxmlsv22.php Procesa el arreglo asociativo de intercambio y genera un   |
// |               mensaje XML con los requisitos del SAT de la version 3.2    |
// |               publicada en el DOF del ? de Diciembre del 2011.            |
// |                                                                           |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2011  Fabrica de Jabon la Corona, SA de CV                  |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software               |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA|
// +---------------------------------------------------------------------------|
// | Autor: Fernando Ortiz <fortiz@lacorona.com.mx>                            |
// +---------------------------------------------------------------------------+
// |19/dic/2011  Se toma como base el programa de la version 2.0 se agregan los|
// |             nuevos nodos, pero se usa xslt para formar la cadena original |
// +---------------------------------------------------------------------------+
//
error_reporting(E_ALL);
ini_set('display_errors', '1');
function satxmlsv($string, $emisor, $serie, $folio, $edidata=false) {
	global $xml, $cadena_original, $conn, $sello, $texto, $ret;
	error_reporting(E_ALL & ~(E_WARNING | E_NOTICE));
	satxmlsv_genera_xml($string,$edidata);
	satxmlsv_genera_cadena_original();
	$ret = satxmlsv_sella($string, $emisor,$serie,$folio,'./send/'.$emisor.'/');
	//$ret = satxmlsv_termina($serie,$folio);
	return $ret;
}


function satxmlsv_genera_xml($string){
	global $xml, $ret;
	$xml = new DOMdocument("1.0","UTF-8");
	$xml->loadXML($string);
}

// }}}
// {{{ genera_cadena_original
function satxmlsv_genera_cadena_original() {
	global $xml, $cadena_original;
	$paso = new DOMDocument;
	$paso->loadXML($xml->saveXML());
	$xsl = new DOMDocument;
	$file="xslt/cadenaoriginal_3_3.xslt";      // Ruta al archivo
	$xsl->load($file);
	$proc = new XSLTProcessor;
	$proc->importStyleSheet($xsl);
	$cadena_original = $proc->transformToXML($paso);
	
	//echo utf8_decode($cadena_original);
	//die();
	#return $cadena_original;
}
// }}}
// {{{ Calculo de sello
function satxmlsv_sella($xml,$emisor,$serie, $folio,$dir) {
    global $cadena_original, $sello;
	$element = simplexml_load_string($xml);
	//$root = $xml->getElementsByTagName("cfdi:Comprobante");
	$certificado = "".$emisor."";
	$file="certificados/".$emisor.".key.pem";      // Ruta al archivo
	// Obtiene la llave privada del Certificado de Sello Digital (CSD),
	//    Ojo , Nunca es la FIEL/FEA
	
	//Numero de ccertificado
	/*$cert = file_get_contents("./certificados/".$emisor.".cer");
	$data=openssl_x509_parse($cert,true);
	$serial_number= $arrInfo['subject']['OU'];*/
	
	//$element->attributes()->noCertificado = $serial_number;
	
	$pkeyid = openssl_get_privatekey(file_get_contents($file));
	openssl_sign($cadena_original, $crypttext, $pkeyid, OPENSSL_ALGO_SHA256);
	openssl_free_key($pkeyid);
	
	//echo $pkeyid;
	/*
	// fetch private key from file and ready it
	$fp = fopen($file, "r");
	$priv_key = fread($fp, 8192);
	fclose($fp);
	$pkeyid = openssl_get_privatekey($priv_key);

	// compute signature with SHA-512
	openssl_sign($data, $signature, $pkeyid, "sha256");

	// free the key from memory
	openssl_free_key($pkeyid);
	*/
	 
	$sello = base64_encode($crypttext);      // lo codifica en formato base64
	//$atrSello = $root->createAttribute('name');
	//satxmlsv_cargaAtt("sello",$sello);
	//echo "SELLO: ".$sello;
    //die();
	$element->attributes()->Sello = $sello;
	
	$file="certificados/".$emisor.".cer.pem";      // Ruta al archivo de Llave publica
	$datos = file($file);
	$certificado = ""; $carga=false;
	for ($i=0; $i<sizeof($datos); $i++) {
		if (strstr($datos[$i],"END CERTIFICATE")) $carga=false;
		if ($carga) $certificado .= trim($datos[$i]);
		if (strstr($datos[$i],"BEGIN CERTIFICATE")) $carga=true;
	}
	// El certificado como base64 lo agrega al XML para simplificar la validacion
	//$root->setAttribute("certificado",$certificado);
	$element->attributes()->Certificado = $certificado;
	$nufa = $serie.$folio;
	//echo $dir.$nufa."xml";
	$element->asXML($dir.$nufa.".xml");
	$respArr = array('cadena'=>$cadena_original,'sello'=>$sello,'certificado'=>$certificado);
	return $respArr;
}
// }}}
// {{{ Termina, graba en edidata o genera archivo en el disco
function satxmlsv_termina($serie, $folio,$dir) {
	global $xml, $conn;
	$xml->formatOutput = true;
	$todo = $xml->saveXML();
	$nufa = $serie.$folio;    // Junta el numero de factura   serie + folio
	$xml->save($dir.$nufa.".xml");
	$paso = $todo;
	return($todo);
}
// {{{ Funcion que carga los atributos a la etiqueta XML
function satxmlsv_cargaAtt(&$nodo, $attr) {
	$quitar = array('sello'=>1,'noCertificado'=>1,'certificado'=>1);
	foreach ($attr as $key => $val) {
		$val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
		$val = trim($val);                           // Regla 5b
		if (strlen($val)>0) {   // Regla 6
			$val = utf8_encode(str_replace("|","/",$val)); // Regla 1
			$nodo->setAttribute($key,$val);
		}
	}
}
 

// {{{ valida que el xml coincida con esquema XSD
function satxmlsv_valida($xmlstr) {
	libxml_use_internal_errors(true);

	$doc = simplexml_load_string($xmlstr);
	$xml = explode("\n", $xmlstr);

	if (!$doc) {
		return "INCIDENCIA 1002: No es un documento XML valido".$doc;
	}else{
		$paso = new DOMDocument;
		$paso->loadXML($xmlstr);
		$schema = "xsd/cfdv33.xsd";
		libxml_use_internal_errors(true);
		if (!$paso->schemaValidate($schema)) {
			return libxml_display_errors();
		} else {
			return "ok"; 
		}
	}
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
	$strerr = "";
	$n=0;
    foreach ($errors as $error) {
		if($n==0)
        	$strerr.= libxml_display_error($error);
		$n++;
    }
    libxml_clear_errors();
	return $strerr;
}

function libxml_display_error($error)
{
    $return = " ";
    switch ($error->level) {
       // case LIBXML_ERR_WARNING:
       //     $return .= "<b>Warning $error->code</b>: ";
       //     break;
        case LIBXML_ERR_ERROR:
            $return .= "INCIDENCIA $error->code: ";
            break;
       // case LIBXML_ERR_FATAL:
       //     $return .= "<b>Fatal Error $error->code</b>: ";
       //     break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        //$return .=    " in <b>$error->file</b>";
    }
   // $return .= " on line <b>$error->line</b>\n";

    return $return;
}
 
// }}}
?>
<?php
require_once("mysql.php");
//GENERAMOS EL ARCHIVO ZIP
require_once('pclzip.lib.php');
require_once('generaPDFI.php');

$query = "SELECT * FROM ppatron WHERE id=1";
$sql = $conexion->query($query);
$rowEmisor = $sql->fetch_assoc();
$rutaArchivos = 'CFDIS/'.$rowEmisor['rfc'].'/';
$ListaArchivos = "";
$thread_id = $conexion->thread_id;
$conexion->kill($thread_id);
$conexion->close();

$ids = $_POST['ids'];
$rows = explode(",",$ids);
foreach($rows as $factura){
	$sf = explode('|',$factura);
	$serie = $sf[0];
	$folio = $sf[1];
	$serieFolio = $serie."".$folio;
	generarPDF($serie,$folio);
	$ListaArchivos.= $rutaArchivos.''.$serieFolio.".pdf,".$rutaArchivos.''.$serieFolio.".xml,";
}

$ListaArchivos = substr($ListaArchivos,0,-1);

//echo $rutaArchivos;

$nombre_zip = 'Recibos.zip';
$archivo_zip = new PclZip($nombre_zip);
if ($archivo_zip->create($ListaArchivos, PCLZIP_OPT_REMOVE_PATH, $rutaArchivos) == 0) {
		 die('Error : '.$archivo_zip->errorInfo(true));
}

echo $nombre_zip;



?>
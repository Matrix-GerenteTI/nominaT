<?php

$nombre_zip = $_GET['nombre_zip'];

$tam = filesize($nombre_zip);
header('Content-type: application/zip');
header('Content-Length: ' . $tam);
header('Content-Disposition: attachment; filename='.$nombre_zip);
readfile($nombre_zip);


//if (file_exists($GLOBALS['fileXML']) && is_writable($GLOBALS['fileXML']))
//	unlink($GLOBALS['fileXML']);

if (file_exists($nombre_zip) && is_writable($nombre_zip))
	unlink($nombre_zip);

?>
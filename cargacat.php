<?php
	$conexion = mysql_connect("localhost","root","");
	$selectdb = mysql_select_db("nomina",$conexion);
	$catalogo = "tipopercepcion";
	$c = 0;
	$file = file("c".$catalogo.".csv");
	foreach($file as $linea){
		$campos = explode(",",$linea);
		$insert = "";
		foreach($campos as $campo){
			$insert.= "'".trim(utf8_encode($campo))."',";
		}
		$insert = substr($insert,0,-1);
		$query = "INSERT INTO c".$catalogo." VALUES (".$insert.")";
		$mysql = mysql_query($query);
		echo $query."</br>";
		if($mysql)
			$c++;
	}
	echo "Se cargaron ".$c." registros a la tabla c".$catalogo;
?>
<?php
session_start();

if(!isset($_SESSION['username'])){
	header("Location: login.php");
	die();
}

if(isset($_GET['closeSesion'])){
	session_destroy();
	header("Location: ../login.php");	
}

?>

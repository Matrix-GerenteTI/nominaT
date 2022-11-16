<?php
	session_start();
	require_once("mysql.php");
	//conexion();
	$op = $_GET['op'];
	switch($op){
		case "validaUsuario":{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$query = "SELECT * FROM pusuarios WHERE username='".$username."' AND password='".$password."'";
			$sql = $conexion->query($query);
			$existe = 0;
			while($row = $sql->fetch_assoc()){				
				if($row['status']==0)
					$existe = 2;
				else{
					$existe = 1;
					$_SESSION['userid'] = $row['username'];
					$_SESSION['username'] = $row['nombre'];
					$_SESSION['usertype'] = $row['tipo'];
				}
			}
			
			echo $existe;
			break;
		}
		
		case "registro":{
			$respuesta = 3;
			$username = $_POST['username'];
			$password = $_POST['password'];
			$nombre = $_POST['nombre'];
			$peso = $_POST['peso'];
			$altura = $_POST['altura'];
			$equipo = "NO APLICA";
			$imc = $peso / ($altura * $altura);
			
			if($imc>=25 && $imc<30)
				$equipo = "AZUL";
			if($imc>=30 && $imc<35)
				$equipo = "VERDE";
			if($imc>=35 && $imc<40)
				$equipo = "AMARILLO";
			if($imc>=40 && $imc<50)
				$equipo = "NARANJA";
			if($imc>=50)
				$equipo = "ROJO";
			if($equipo != "NO APLICA"){
				$query = "SELECT * FROM pusuarios WHERE username='".$username."' AND password='".$password."'";
				$sql = $conexion->query($query);
				while($row = $sql->fetch_assoc()){	
					$respuesta=0;
				}
				if($respuesta==3){
					$code = md5($username.date("dmYhis"));
					$query = "INSERT INTO pusuarios (username,
													password,
													nombre,
													peso,
													altura,
													equipo,
													email,
													status,
													codigo) 
										VALUES 	   ('".$username."',
													'".$password."',
													'".$nombre."',
													'".$peso."',
													'".$altura."',
													'".$equipo."',
													'".$username."',
													0,
													'".$code."')";
					$sql = $conexion->query($query);
					if(!$sql){
						$respuesta = 3;
					}else{
						$respuesta = 1;					
						enviarMail($username,$code);
					}
				}
			}else{
				if($imc>=18.5 && $imc<25)
					$respuesta = 2;
				else
					$respuesta = 4;
			}
			
			echo $respuesta;
			break;
		}
	}
	
function enviarMail($email,$code){
	require "../phpmailer/class.phpmailer.php";
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
	
	//Indicamos cual es nuestra dirección de correo y el nombre que 
	//queremos que vea el usuario que lee nuestro correo
	$mail->From = 'no-responder@xiontecnologias.com';
	
	$mail->FromName = 'Fundacion Sin Obesidad Mexico A.C.';
	
	//Asignamos asunto y cuerpo del mensaje
	//El cuerpo del mensaje lo ponemos en formato html, haciendo 
	//que se vea en negrita
	$mail->Subject = "Activacion de su cuenta";
		
	$mail->Body = "<b>Activacion de usuario para el reto 7SM (7 Semanas en Movimiento)</b>
				   <p>
					  Para activar su cuenta haga clic en el siguiente enlace:
					  <br/>
					  http://www.xiontecnologias.com/sinobesidad/login.php?acticode=".$code."		  
				   </p>
				   <p>
					  <i>Mensaje generado automaticamente del sitio sinobesidadmexico.com.mx</i>
				   </p>";
	
	//Definimos AltBody por si el destinatario del correo no admite 
	//email con formato html
	//$mail->AltBody ='A continuacion el codigo de activacion que debe ingresar en el formulario de registro: '.$code;
	
	//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
	//una cuenta gratuita y voy a usar attachments, por tanto lo pongo a 120  
	$mail->Timeout=10;
					
	//Indicamos cuales son las direcciones de destino del correo y enviamos 
	//los mensajes
	$mail->AddAddress($email);
	
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
	   return false;
	}
	else
	{
	   return true;
	}	
}
?>

<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>7SM - Fundaci&oacute;n Sin Obesidad M&eacute;xico</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

<link href="css/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>

<body>
	
	<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand">
				<i class="icon-user-md"></i>&nbsp;Funcaci&oacute;n Sin Obesidad M&eacute;xico A.C.				
			</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">
					
					<li class="">						
						<a href="login.php" class="" style="font-size:18px">
							Ingresar
						</a>
						
					</li>
					
					<!--<li class="">						
						<a href="index.html" class="">
							<i class="icon-chevron-left"></i>
							Regresar
						</a>
						
					</li>-->
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->



<div class="account-container">
	
	<div class="content clearfix">
		

		
			<h1>Registro</h1>		
			
			<div class="login-fields">
				
				<p>Ingrese sus datos para registrarse</p>
				
				<div class="field">
					<label for="username">Nombre</label>
					<input type="text" id="name" name="name" value="" placeholder="Nombre" class="login username-field" />
				</div> <!-- /field -->
                
                <div class="field">
					<label for="username">Peso</label>
					<input type="text" id="peso" name="peso" value="" placeholder="Peso (Kg)" class="login peso-field" />
				</div> <!-- /field -->
                
                <div class="field">
					<label for="username">Altura</label>
					<input type="text" id="altura" name="altura" value="" placeholder="Altura (cm)" class="login altura-field" />
				</div> <!-- /field -->
                
                <div class="field">
					<label for="username">Username</label>
					<input type="text" id="username" name="username" value="" placeholder="Correo Eletr&oacute;nico" class="login email-field" />
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password1">Password:</label>
					<input type="password" id="password1" name="password1" value="" placeholder="Contrase&ntilde;a" class="login password-field"/>
				</div> <!-- /password -->
                
                <div class="field">
					<label for="password2">Confirmar Password:</label>
					<input type="password" id="password2" name="password2" value="" placeholder="Confirmar Contrase&ntilde;a" class="login password-field"/>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
            <div id="msgbox" style="color:#F00; font-size:1em"></div>
            
			<div class="login-actions">
				
									
				<input type="button" class="button btn btn-success btn-large" value="Registrarse" onClick="Registrarse()" />
				
			</div> <!-- .actions -->
			
			
			

		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->




<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/signin.js"></script>
<script type="text/javascript">
	
	function Registrarse(){
		var name = $("#name").val();
		var peso = $("#peso").val();
		var altura = $("#altura").val();
		var username = $("#username").val();
		var password1 = $("#password1").val();
		var password2 = $("#password2").val();
		
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		if (!regex.test($('#username').val().trim())) {
			$("#msgbox").html("El correo ingresado no es válido");
		}else{		
			if(password1==password2 && password1.length>=4){
				var params = "username="+username+"&password="+password1+"&nombre="+name+"&peso="+peso+"&altura="+altura;
				$.post("ajax/ajaxlogin.php?op=registro", params, function(resp){
					//alert(resp);
					if(resp == 0)
						$("#msgbox").html("El correo ya esta registrado");
					if(resp == 1){
						$("#msgbox").html("Listo!. Se le ha enviado un correo para activar su cuenta, revise en spam o correo no deseado.");
						setTimeout("location.href='index.php'", 7000);
					}
					if(resp == 2)
						$("#msgbox").html("Felicidades usted tiene un peso adecuado, no necesita este reto.");
					if(resp == 3)
						$("#msgbox").html("Ups!. Tenemos inconvenientes con el registro porfavor intente nuevamente");
					if(resp == 4)
						$("#msgbox").html("Lo sentimos este reto es solo para personas con un peso mayor al adecuado.");
				});
			}else{
				if(password1.length < 4){
					$("#msgbox").html("La contraseña debe tener por lo menos 4 caracteres");
				}else{
					$("#msgbox").html("Las contraseñas no coinciden");
				}
			}
		}
	}
	
</script>
</body>

</html>

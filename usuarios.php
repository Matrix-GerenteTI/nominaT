<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sistema para Timbrado de N&oacute;mina</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html"><i class="icon-book"></i>&nbsp;Timbrado de Nomina 1.2 </a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <!--<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-cog"></i> Account <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;">Settings</a></li>
              <li><a href="javascript:;">Help</a></li>
            </ul>
          </li>-->
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                            class="icon-user"></i> <?php echo $_SESSION['username']; ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <!--<li><a href="javascript:;">Profile</a></li>-->
              <li><a href="ajax/control.php?closeSesion=y">Cerrar sesi&oacute;n</a></li>
            </ul>
          </li>
        </ul>
        <!--<form class="navbar-search pull-right">
          <input type="text" class="search-query" placeholder="Search">
        </form>-->
      </div>
      <!--/.nav-collapse --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
   	<?php
   		if($_SESSION['usertype']!='ADMINISTRADOR'){
   	?>
        <li><a href="index.php"><i class="icon-flag"></i><span>Reto 7SM</span> </a> </li>
        <li><a href="suscripcion.php"><i class="icon-money"></i><span>Donaci&oacute;n</span> </a> </li>
	<?php
        }else{
    ?>
		<li><a href="index.php"><i class="icon-ok"></i><span>Timbrar</span> </a> </li>
		<li><a href="empleados.php"><i class="icon-group"></i><span>Empleados</span> </a></li>
    	<li><a href="incidencias.php"><i class="icon-calculator"></i><span>Incidencias</span> </a> </li>
		<li><a href="empresa.php"><i class="icon-building"></i><span>Empresa</span> </a> </li>
        <li><a href="cfdis.php"><i class="icon-list-alt"></i><span>CFDIs</span> </a> </li>
		<li><a href="asistencia.php"><i class="icon-file-clock-o"></i><span>Asistencias</span> </a> </li>
		<li><a href="parametros.php"><i class="icon-fa-cogs"></i><span>Parametros</span> </a> </li>
        <li class="active"><a href="usuarios.php"><i class="icon-user"></i><span>Usuarios</span> </a> </li>
    <?php
		}
	?>
        <!--<li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i><span>Drops</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="icons.html">Icons</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="pricing.html">Pricing Plans</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="error.html">404</a></li>
          </ul>
        </li>-->
      </ul>
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span12">
          <div class="widget widget-nopad">
          	<div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
                            </div>
                      	</td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-header"> <i class="icon-edit"></i>
              <h3> Datos de usuario</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
                <table width="100%">
                    <tr>
                        <td>
                        	Usuario<br/>
                            <input type="text" id="usuario" style="width:92%" />
                       	</td>
                        <td>
                        	Contrase&ntilde;a<br/>
                            <input type="password" style="width:92%" id="password1" />
                       	</td>
                        <td>
                        	Confirma contrase&ntilde;a<br/>
                            <input type="password" style="width:92%" id="password2" onKeyUp="validaPass()" />
                       	</td>
                        <td>
                        	Grupo<br/>
                            <select id="grupo" style="width:92%">
                                <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                            	<option value="USUARIO">USUARIO</option>
                                <option value="DOCTOR">MEDICO/DOCTOR</option>
                            </select>
                       	</td>
                    </tr>
                </table>
              	<table width="100%">
                    <tr>
                        <td>
                        	Nombre personal<br/>
                            <input type="text" id="nombre" style="width:97%" />
                       	</td>
                        <td>
                        	Email<br/>
                            <input type="text" id="email" style="width:97%" />
                       	</td>
                    </tr>
                </table>
                <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th> Usuario </th>
                    <th> Nombre </th>
                    <th> Grupo </th>
                  </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
                </table>
           	</div>
          </div>
        </div>
        <!-- /span6 -->
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main 
<div class="extra">
  <div class="extra-inner">
    <div class="container">
      <div class="row">
                    <div class="span3">
                        <h4>
                            About Free Admin Template</h4>
                        <ul>
                            <li><a href="javascript:;">EGrappler.com</a></li>
                            <li><a href="javascript:;">Web Development Resources</a></li>
                            <li><a href="javascript:;">Responsive HTML5 Portfolio Templates</a></li>
                            <li><a href="javascript:;">Free Resources and Scripts</a></li>
                        </ul>
                    </div>
                    <!-- /span3 
                    <div class="span3">
                        <h4>
                            Support</h4>
                        <ul>
                            <li><a href="javascript:;">Frequently Asked Questions</a></li>
                            <li><a href="javascript:;">Ask a Question</a></li>
                            <li><a href="javascript:;">Video Tutorial</a></li>
                            <li><a href="javascript:;">Feedback</a></li>
                        </ul>
                    </div>
                    <!-- /span3
                    <div class="span3">
                        <h4>
                            Something Legal</h4>
                        <ul>
                            <li><a href="javascript:;">Read License</a></li>
                            <li><a href="javascript:;">Terms of Use</a></li>
                            <li><a href="javascript:;">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <!-- /span3
                    <div class="span3">
                        <h4>
                            Open Source jQuery Plugins</h4>
                        <ul>
                            <li><a href="http://www.egrappler.com">Open Source jQuery Plugins</a></li>
                            <li><a href="http://www.egrappler.com;">HTML5 Responsive Tempaltes</a></li>
                            <li><a href="http://www.egrappler.com;">Free Contact Form Plugin</a></li>
                            <li><a href="http://www.egrappler.com;">Flat UI PSD</a></li>
                        </ul>
                    </div>
                    <!-- /span3
                </div>
      <!-- /row
    </div>
    <!-- /container
  </div>
  <!-- /extra-inner
</div>
<!-- /extra -
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; 2017 Timbrado de N&oacute;mina. <a href="http://www.xiontecnologias.com/">Creado por XION Tecnologias</a></div>
        <!-- /span12 --
      </div>
      <!-- /row --
    </div>
    <!-- /container --
  </div>
  <!-- /footer-inner --
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/excanvas.min.js"></script> 
<script src="js/chart.min.js" type="text/javascript"></script> 
<script src="js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="js/full-calendar/fullcalendar.min.js"></script>
 
<script src="js/base.js"></script> 
<script> 
	  
	$(document).ready(function(e) {
		nuevo();	    
    });
	  
	function lista(){
		$.post("ajax/ajaxusuarios.php?op=lista", "", function(resp){
	  		var row = eval('('+resp+')');
			var echo = "";
			for(i in row){
				echo+= "<tr style='cursor:pointer' onClick='cargar(\""+row[i].username+"\")'>";
				echo+= "	<td>"+row[i].username+"</td><td>"+row[i].nombre+"</td><td>"+row[i].tipo+"</td>";
				echo+= "<tr>";
			}
			$("#tbody").html(echo);
		});
	}
	
	function nuevo(){
		$("#usuario").val('');
		$("#password1").val('');
		$("#password2").val('');
		$("#grupo").val('ADMINISTRADOR');
		$("#nombre").val('');	
		$("#email").val('');
		lista();
	}
	
	function cargar(id){
		$.post("ajax/ajaxusuarios.php?op=cargar", "id="+id, function(resp){
			var row = eval('('+resp+')');
			$("#usuario").val(row.username);
			$("#password1").val(row.password);
			$("#password2").val(row.password);
			$("#grupo").val(row.tipo);
			$("#nombre").val(row.nombre);	
			$("#email").val(row.email);
		});
	}
	
	function guardar(){
		var campos = new Array('usuario_txt','password1_txt','password2_txt','nombre_txt');
		var validacion = validar(campos);
						
		if(validacion == true){
			if(validaPass()){
				var params = "username="+$("#usuario").val();
				params+= "&password="+$("#password1").val();
				params+= "&tipo="+$("#grupo").val();
				params+= "&nombre="+$("#nombre").val();
				params+= "&email="+$("#email").val();
				$.post("ajax/ajaxusuarios.php?op=guardar", params, function(resp){
					if(resp>0){
						alert("El registro se guardo exitosamente!");
						nuevo();
					}else
						alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
				});
			}else
				alert("No coinciden las contraseñas");
		}else{
			if(validaPass())
				alert("Llene los campos requeridos");
			else
				alert("No coinciden las contraseñas");
		}
	}
	
	function eliminar(){
		var username = $("#usuario").val();
		var params = "username="+username;
		$.post("ajax/ajaxusuarios.php?op=eliminar", params, function(resp){
			if(resp<2){
				if(resp==1)
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			}else{
				alert("El usuario se elimino exitosamente!");
				nuevo();
			}
		});	
	}
	
	function validaPass(){
		var pass1 = $("#password1").val();
		var pass2 = $("#password2").val();
		if(pass1!=pass2){
			$("#password2").addClass('bordeRojo');
			return false;
		}else{
			$("#password2").removeClass('bordeRojo');
			return true;
		}
	}
	
	function validar(campos){
		var res = true;
		for(a in campos){
			var arr = campos[a].split("_");
			//alert(arr[1]);
			if(arr[1] == "txt"){
				var campo = $("#"+arr[0]).val();
				if(campo==""){
					 $("#"+arr[0]).addClass('bordeRojo');
					 res = false;
				}else{
					 $("#"+arr[0]).removeClass('bordeRojo');
				}
			}
		}
		return res;
	}

</script><!-- /Calendar -->
</body>
</html>

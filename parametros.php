<?php
	require_once("ajax/control.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sistema de N&oacute;mina</title>
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
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.html"><i class="icon-book"></i>&nbsp;Sistema de Nomina </a>
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
		<li class="active"><a href="parametros.php"><i class="icon-fa-cogs"></i><span>Parametros</span> </a> </li>
        <li><a href="usuarios.php"><i class="icon-user"></i><span>Usuarios</span> </a> </li>
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
            <div class="form-actions" style="margin-top:0px; margin-bottom:0px; padding:5px">
            	<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
						<td width="25%">
                        	Departamento<br/>
                            <select id="pdepartamento" style="width:92%" onChange="comboCatalogo('p','departamento',2,'puesto')">
                            </select>
                       	</td>
						<td width="25%">
                        	Puesto<br/>
                            <select id="ppuesto" style="width:92%" onChange="carga()">
                            </select>
                       	</td>
						<td>
						</td>
                    	<td align="right">
							<table cellpadding="0" cellspacing="0" align="right">
								<tr>
									<td>
										<div class="shortcuts"> 
										   <!-- <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;-->
											<a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
											<!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
											<!--<a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>-->
										</div>
									</td>
								</tr>
							</table>
                      	</td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-header"> <i class="icon-edit"></i>
              <h3> Parametros de Asistencia</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
                <table width="100%">
                    <tr>
						<td width="25%">
                        	Hora de entrada<br/>
                            <input type="text" id="pentrada" style="width:90%" />
                       	</td>
                        <td width="25%">
                        	Hora de salida<br/>
                            <input type="text" id="psalida" style="width:90%" />
                       	</td>
						<td width="25%">
                        	Hora de entrada intermedia<br/>
                            <input type="text" id="pentradai" style="width:90%" />
                       	</td>
						<td width="25%">
                        	Hora de salida intermedia<br/>
                            <input type="text" id="psalidai" style="width:90%" />
                       	</td>
                    </tr>
                </table>
              	<table>
                    <tr>
                        <td>
                        	Minutos de tolerancia<br/>
                            <input type="number" min="0" id="ptolerancia" style="width:87%" />
                       	</td>
						<td>
                        	Retardos para falta<br/>
                            <input type="number" min="0" id="pretardospfalta" style="width:87%" />
                       	</td>
						<td>
                        	Faltas para descuento<br/>
                            <input type="number" min="0" id="pfaltaspdescuento" style="width:87%" />
                       	</td>
						<td>
                        	&nbsp;<br/>
                            <input type="checkbox" id="corrido" value="1" name="corrido"/> Horario Corrido
                       	</td>
                    </tr>
                </table>
            </div>
            <div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                               <!-- <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;-->
                                <!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
                                <!--<a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>-->
                            </div>
                      	</td>
                   	</tr>
              	</table>
            </div>
          </div>
          <!-- /widget -->
        </div>
        <!-- /span6 -->
      </div>
      
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main  -->
<!-- /footer --
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
<script src="js/bootstrap.js"></script> 
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
	var camposTXT = new Array('pentrada_txt',
							   'pentradai',
							   'psalida_txt',
							   'psalidai',
							   'ptolerancia_txt',
							   'pretardospfalta_txt',
							   'pfaltaspdescuento_txt');
	$(document).ready(function(e) {
		comboCatalogo('p','departamento',1,'puesto');	
		
    });
	
	
	
	function carga(opcion){
		var puesto = "";
		if(opcion==0){
			puesto = 0;
		}else{
			puesto = $("#ppuesto").val();
		}
		//alert(puesto);
		$.post("ajax/ajaxparametros.php?op=carga", "puesto="+puesto, function(resp){
			//alert(resp);
			if(resp=='null'){
				$("#pentrada").val("00:00:00");
				$("#pentradai").val("00:00:00");
				$("#psalida").val("00:00:00");
				$("#psalidai").val("00:00:00");
				$("#ptolerancia").val("");
				$("#pretardospfalta").val("");
				$("#pfaltaspdescuento").val("");
				$("#pfaltaspdescuento").val("");
				$("#corrido")[0].checked = false;
			}else{
				var row = eval('('+resp+')');
				$("#pentrada").val(row.entrada);
				$("#pentradai").val(row.entradai);
				$("#psalida").val(row.salida);
				$("#psalidai").val(row.salidai);
				$("#ptolerancia").val(row.tolerancia);
				$("#pretardospfalta").val(row.retardospfalta);
				$("#pfaltaspdescuento").val(row.faltaspdescuento);
				if(row.corrido==1)
					$("#corrido")[0].checked = true;
				else
					$("#corrido")[0].checked = false;
			}
		});
	}
		
	function guardar(){
		var validacion = validar(camposTXT);
						
		if(validacion == true){			
			var params = "entrada=" + $("#pentrada").val();
			params+= "&entradai=" + $("#pentradai").val();
			params+= "&salida=" + $("#psalida").val();
			params+= "&salidai=" + $("#psalidai").val();
			params+= "&tolerancia=" + $("#ptolerancia").val();
			params+= "&retardos=" + $("#pretardospfalta").val();
			params+= "&faltas=" + $("#pfaltaspdescuento").val();
			params+= "&departamento=" + $("#pdepartamento").val();
			params+= "&puesto=" + $("#ppuesto").val();
			params+= "&corrido=" + $('input:checkbox[name=corrido]:checked').val()
			
			$.post("ajax/ajaxparametros.php?op=guardar", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					carga();
				}else
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
			});
		}else{
			alert("Llene los campos requeridos");
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
	
	function comboCatalogoS(prefijo,catalogo,valor){
		var id = "";
		$.post("ajax/ajaxempresa.php?op=comboSelected", "catalogo="+catalogo+"&valor="+valor, function(resp){
			//alert(resp);
			$("#"+prefijo+""+catalogo).html(resp);
		});
	}
	
	function comboCatalogo(prefijo,catalogo,tipo,scatalogo,sscatalogo,ssscatalogo){
		var id = "";
		if(tipo==1){
			$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&tipo="+tipo, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
				
				//Cargamos el subcombo
				if(typeof scatalogo != 'undefined'){
					id = $("#"+prefijo+""+catalogo).val();
					$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&scatalogo="+scatalogo+"&id="+id+"&tipo="+tipo, function(sresp){
						$("#"+prefijo+""+scatalogo).html(sresp);
						carga();
						//Cargamos el subcombo
						if(typeof sscatalogo != 'undefined'){
							id = $("#"+prefijo+""+scatalogo).val();
							$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+scatalogo+"&scatalogo="+sscatalogo+"&id="+id+"&tipo="+tipo, function(ssresp){
								$("#"+prefijo+""+sscatalogo).html(ssresp);
								
								//Cargamos el subcombo
								if(typeof ssscatalogo != 'undefined'){
									id = $("#"+prefijo+""+sscatalogo).val();
									$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+sscatalogo+"&scatalogo="+ssscatalogo+"&id="+id+"&tipo="+tipo, function(sssresp){
										$("#"+prefijo+""+ssscatalogo).html(sssresp);
									});
								}
							});
						}
					});
				}
			});
		}
		if(tipo==2){
			if(typeof scatalogo != 'undefined'){
				id = $("#"+prefijo+""+catalogo).val();
				$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&scatalogo="+scatalogo+"&id="+id+"&tipo="+tipo, function(sresp){
					$("#"+prefijo+""+scatalogo).html(sresp);
					carga();
					//Cargamos el subcombo
					if(typeof sscatalogo != 'undefined'){
						id = $("#"+prefijo+""+scatalogo).val();
						$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+scatalogo+"&scatalogo="+sscatalogo+"&id="+id+"&tipo="+tipo, function(ssresp){
							$("#"+prefijo+""+sscatalogo).html(ssresp);
							carga();
							//Cargamos el subcombo
							if(typeof ssscatalogo != 'undefined'){
								id = $("#"+prefijo+""+sscatalogo).val();
								$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+sscatalogo+"&scatalogo="+ssscatalogo+"&id="+id+"&tipo="+tipo, function(sssresp){
									$("#"+prefijo+""+ssscatalogo).html(sssresp);
								});
							}
						});
					}
				});
			}
		}
		if(tipo==3){
			$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&tipo="+tipo, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
				
				//Cargamos el subcombo
				if(typeof scatalogo != 'undefined'){
					id = $("#"+prefijo+""+catalogo).val();
					$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+catalogo+"&scatalogo="+scatalogo+"&id="+id+"&tipo="+tipo, function(sresp){
						$("#"+prefijo+""+scatalogo).html(sresp);
						
						//Cargamos el subcombo
						if(typeof sscatalogo != 'undefined'){
							id = $("#"+prefijo+""+scatalogo).val();
							$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+scatalogo+"&scatalogo="+sscatalogo+"&id="+id+"&tipo="+tipo, function(ssresp){
								$("#"+prefijo+""+sscatalogo).html(ssresp);
								
								//Cargamos el subcombo
								if(typeof ssscatalogo != 'undefined'){
									id = $("#"+prefijo+""+sscatalogo).val();
									$.post("ajax/ajaxempleado.php?op=comboCatalogo", "catalogo="+sscatalogo+"&scatalogo="+ssscatalogo+"&id="+id+"&tipo="+tipo, function(sssresp){
										$("#"+prefijo+""+ssscatalogo).html(sssresp);
									});
								}
							});
						}
					});
				}
			});
		}
	}
</script>
</body>
</html>

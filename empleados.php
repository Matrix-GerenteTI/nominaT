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
<!-- Modal -->
<div id="busquedaEmpleados" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">B&uacute;squeda de Empleado</h4>
      </div>
      <div class="modal-body">
        
		<table width="100%">
			<tr>
				<td>
					Departamento<br/>
					<select id="busdepartamento" style="width:92%">
					</select>
				</td>
				<td>
					Nombre<br/>
					<input type="text" id="busnombre" style="width:95%" />
				</td>
				<td align="right">
					<div class="shortcuts"> 
						<a href="javascript:buslista();" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label"></span></a>&nbsp;
					</div>
				</td>
			</tr>
		</table>
		<br/>
		<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th> Nombre </th>
				<th> Departamento </th>
			</tr>
		</thead>
		<tbody id="bustbody">
		</tbody>
		</table>
		
		
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>

  </div>
</div>
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
		<li class="active"><a href="empleados.php"><i class="icon-group"></i><span>Empleados</span> </a></li>
    	<li><a href="incidencias.php"><i class="icon-calculator"></i><span>Incidencias</span> </a> </li>
		<li><a href="empresa.php"><i class="icon-building"></i><span>Empresa</span> </a> </li>
        <li><a href="cfdis.php"><i class="icon-list-alt"></i><span>CFDIs</span> </a> </li>
		<li><a href="asistencia.php"><i class="icon-file-clock-o"></i><span>Asistencias</span> </a> </li>
		<li><a href="parametros.php"><i class="icon-fa-cogs"></i><span>Parametros</span> </a> </li>
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
						<td width="75px">
							<div class="shortcuts"> 
								<a href="javascript:buscar();" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;
							</div>
						</td>
                    	<td>
							<table align="right" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<div class="shortcuts"> 
											<a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
											<a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
											<!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
											<a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
										</div>
									</td>
								</tr>
							</table>
                      	</td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-header"> <i class="icon-edit"></i>
              <h3> Datos Personales</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="padding:10px">
				<table width="100%">
                    <tr>
						<td width="15%">
                        	<b>NIP</b><br/>
                            <input type="text" id="pnip" style="width:90%; text-align:center" readonly />
                       	</td>
						<td>
                        	
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
						<td width="15%">
                        	RFC<br/>
                            <input type="text" id="prfc" style="width:90%" />
                       	</td>
                        <td>
                        	Nombre<br/>
                            <input type="text" id="pnombre" style="width:97%" />
                       	</td>
						<td width="20%">
                        	CURP<br/>
                            <input type="text" id="pcurp" style="width:92%" />
                       	</td>
						<td width="20%">
                        	NSS<br/>
                            <input type="text" id="pnss" style="width:92%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Fecha de nacimiento<br/>
                            <input type="text" id="pfecnac" style="width:92%" onChange="calculaTiempo('pfecnac','pedad','edad')" />
                       	</td>
                        <td>
                        	Edad<br/>
                            <input type="text" id="pedad" style="width:92%" readonly />
                       	</td>
                        <td>
                        	Estado civil<br/>
                            <select id="pedocivil" style="width:92%">
                                <option value="SOLTERA(O)">SOLTERA(O)</option>
                            	<option value="CASADA(O)">CASADA(O)</option>
                                <option value="DIVORCIADA(O)">DIVORCIADA(O)</option>
                                <option value="VIUDA(O)">VIUDA(O)</option>
                            </select>
                       	</td>
                        <td>
                        	Sexo<br/>
                            <select id="psexo" style="width:92%">
                                <option value="MUJER">MUJER</option>
                            	<option value="HOMBRE">HOMBRE</option>
                            </select>
                       	</td>
                    </tr>
                </table>
              	<table width="100%">
                    <tr>
                        <td>
                        	Calle<br/>
                            <input type="text" id="pcalle" style="width:95%" />
                       	</td>
						<td width="10%">
                        	Numero Ext.<br/>
                            <input type="text" id="pnumext" style="width:87%" />
                       	</td>
						<td width="10%">
                        	Numero Int.<br/>
                            <input type="text" id="pnumint" style="width:87%" />
                       	</td>
						<td width="30%">
                        	Colonia<br/>
                            <input type="text" id="pcolonia" style="width:95%" />
                       	</td>
						<td width="10%">
                        	C.P.<br/>
                            <input type="text" id="pcp" style="width:85%" />
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
						<td>
                        	Estado<br/>
                            <select id="pestado" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Municipio<br/>
                            <input type="text" id="pmunicipio" style="width:92%" />
                       	</td>
                        <td>
                        	Correo electr&oacute;nico<br/>
                            <input type="text" id="pemail" style="width:92%" />
                       	</td>
                        <td>
                        	Tel&eacute;fono particular<br/>
                            <input type="text" id="ptelefono" style="width:92%" />
                       	</td>
                        <td>
                        	Celular<br/>
                            <input type="text" id="pcelular" style="width:92%" />
                       	</td>
                    </tr>
                </table>     
            </div>
          </div>
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="icon-file"></i>
              <h3> Datos del contrato</h3>
            </div>
            <div class="widget-content" style="padding:10px">
                <table width="100%">
                    <tr>
						<td>
                        	Departamento<br/>
                            <select id="pdepartamento" style="width:92%" onChange="comboCatalogo('p','departamento',2,'puesto')">
                            </select>
                       	</td>
						<td>
                        	Puesto<br/>
                            <select id="ppuesto" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Tipo de contrato<br/>
                            <select id="ptipocontrato" style="width:92%">
                            </select>
                       	</td>
						<td>
                        	Tipo de jornada<br/>
                            <select id="ptipojornada" style="width:92%">
                            </select>
                       	</td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td>
                        	Fecha de Inicio Laboral<br/>
                            <input type="text" id="piniciolaboral" style="width:92%" onChange="calculaTiempo('piniciolaboral','pantiguedad','antiguedadSAT')" />
                       	</td>
                        <td>
                        	Antigüedad<br/>
                            <input type="text" id="pantiguedad" style="width:92%" readonly/>
                       	</td>
						<td>
                        	Sindicalizado<br/>
                            <select id="psindicalizado" style="width:92%">
								<option value='No'>No</option>
								<option value='Sí'>Si</option>
                            </select>
                       	</td>
                        <td>
                        	Tipo de r&eacute;gimen<br/>
                            <select id="ptiporegimen" style="width:92%">
                            </select>
                       	</td>
                        <td>
                        	Riesto del puesto<br/>
                            <select id="priesgopuesto" style="width:92%">
                            </select>
                       	</td>
                    </tr>
                </table>   
                <table width="100%">
                    <tr>
                        <td>
                        	Periodicidad de pago<br/>
                            <select id="pperiodicidadpago" style="width:92%">
                            </select>
                       	</td>
                        <td>
                        	Salario base<br/>
                            <input type="number" id="psalariobase" style="width:92%"  />
                       	</td>
						<td>
                        	Salario diario integrado<br/>
                            <input type="number" id="psalariodiario" style="width:92%"  />
                       	</td>
                        <td>
                        	Banco<br/>
                            <select id="pbanco" style="width:92%">
                            </select>
                       	</td>
                        <td>
                        	Cuenta bancaria<br/>
                            <input type="number" id="pcuentabancaria" style="width:92%"  />
                       	</td>
                    </tr>
                </table>
				<table>
                    <tr>
                        <td>
                        	RFC (Subcontrataci&oacute;n)<br/>
                            <input type="text" id="psubrfc" style="width:92%"  />
                       	</td>
                        <td>
                        	% de tiempo (Subcontrataci&oacute;n)<br/>
                            <input type="number" id="psubporcentaje" style="width:92%"  />
                       	</td>
                        <td>
                        	Fecha de T&eacute;rmino Laboral<br/>
                            <input type="text" id="pterminolaboral" style="width:92%"" />
                       	</td>
                    </tr>
                </table>
            </div>
            
            <div class="form-actions" style="text-align:right; margin-top:0px; margin-bottom:0px; padding:5px">
            	<table align="right" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td align="right">
                            <div class="shortcuts"> 
                                <a href="javascript:nuevo();" class="shortcut"><i class="shortcut-icon icon-file-alt"></i><span class="shortcut-label">Nuevo</span></a>&nbsp;
                                <a href="javascript:guardar();" class="shortcut"><i class="shortcut-icon icon-save"></i><span class="shortcut-label">Guardar</span></a>&nbsp;
                                <!--<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;-->
                                <a href="javascript:eliminar();" class="shortcut"><i class="shortcut-icon icon-remove"></i><span class="shortcut-label">Eliminar</span></a>
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
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main  --
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
	var camposTXT = new Array('pnip',
							   'prfc_txt',
							   'pnombre_txt',
							   'pcurp_txt',
							   'pnss',
							   'pfecnac_txt',
							   'pedad_txt',
							   'pcalle_txt',
							   'pnumext_txt',
							   'pnumint',
							   'pcolonia_txt',
							   'pcp_txt',
							   'pmunicipio_txt',
							   'ptelefono',
							   'pcelular',
							   'pemail',
							   'piniciolaboral_txt',
							   'pterminolaboral',
							   'pantiguedad_txt',
							   'psalariobase_txt',
							   'psalariodiario_txt',
							   'pcuentabancaria',
							   'psubrfc',
							   'psubporcentaje');
							   
	$(document).ready(function(e) {
		$.datepicker.regional['es'] = {
			closeText: 'Cerrar',
			currentText: 'Hoy',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es']);
		$("#pfecnac").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#piniciolaboral").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#pterminolaboral").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
        nuevo();
    });
	
	function $_GET(param){
		url = document.URL;
		url = String(url.match(/\?+.+/));
		url = url.replace("?", "");
		url = url.split("&");
		x = 0;
		while (x < url.length){
			p = url[x].split("=");
			if (p[0] == param){
				return decodeURIComponent(p[1]);
			}
			x++;
		}
	}
	
	function checaGET(){
		var v = $_GET("v");
		if(v=="outview"){
			cargarExpediente();
		}
	}
	
	function formatearFecha(fecha){
		fec = fecha.split("-");
		return fec[2]+"/"+fec[1]+"/"+fec[0];	
	}
	
	function obtenerFolio(){
		$.post("ajax/ajaxempleado.php?op=obtenerFolio", "", function(resp){
			//alert(resp);
			$("#pnip").val(resp);
		});
	}
	
	function calculaTiempo(origen,destino,tipo){
		var fecha = $("#"+origen).val();
		$.post("ajax/ajaxempleado.php?op=calculaTiempo", "fecha="+fecha+"&tipo="+tipo, function(resp){
			$("#"+destino).val(resp);
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
	
	function nuevo(){
		//obtenerFolio();
		reiniciaTXT(camposTXT);
		comboCatalogo('p','estado',1);
		comboCatalogo('p','banco',1);
		comboCatalogo('p','periodicidadpago',1);
		comboCatalogo('p','tiporegimen',1);
		comboCatalogo('p','riesgopuesto',1);
		comboCatalogo('p','tipocontrato',1);
		comboCatalogo('p','tipojornada',1);
		comboCatalogo('p','departamento',1,'puesto');		
		comboCatalogo('bus','departamento',3);
	}
	
	function buscar(){
		$("#busquedaEmpleados").modal('toggle');
		comboCatalogo('bus','departamento',3);
		buslista();
	}
	
	function buslista(){
		var departamento = $("#busdepartamento").val();
		var nombre = $("#busnombre").val();
		$.post("ajax/ajaxempleado.php?op=buslista", "departamento="+departamento+"&nombre="+nombre, function(resp){
			//alert(resp);
	  		var row = eval('('+resp+')');
			var echo = "";
			for(i in row){
				echo+= "<tr style='cursor:pointer' onClick='cargar(\""+row[i].nip+"\")'>";
				echo+= "	<td>"+row[i].departamento+"</td><td>"+row[i].nombre+"</td>";
				echo+= "<tr>";
			}
			$("#bustbody").html(echo);
		});
	}
	
	function cargar(nip){
		reiniciaTXT(camposTXT);
		$("#busquedaEmpleados").modal('toggle');
		$.post("ajax/ajaxempleado.php?op=cargar", "nip="+nip, function(resp){
			//alert(resp);
			var row = eval('('+resp+')');
			//Datos de pempleado
			$("#pnip").val(row.nip);
			$("#prfc").val(row.rfc);
			$("#pnombre").val(row.nombre);
			$("#pcurp").val(row.curp);			
			$("#pnss").val(row.nss);
			$("#pfecnac").val(formatearFecha(row.fechanac));
			calculaTiempo('pfecnac','pedad','edad');
			$("#pedocivil").val(row.edocivil);
			$("#psexo").val(row.sexo);
			$("#pemail").val(row.email);
			$("#ptelefono").val(row.telefono);
			$("#pcelular").val(row.celular);
			//Datos de pdireccion
			$("#pcalle").val(row.calle);
			$("#pnumext").val(row.numext);
			$("#pnumint").val(row.numint);
			$("#pcolonia").val(row.colonia);
			$("#pcp").val(row.cp);
			comboCatalogoS('p','estado',row.estado);
			$("#pmunicipio").val(row.municipio);
			//Datos de pcontrato
			comboCatalogoS('p','departamento',row.iddepartamento);
			comboCatalogoS('p','puesto',row.idpuesto,'departamento',row.iddepartamento);
			comboCatalogoS('p','tipocontrato',row.idtipocontrato);
			comboCatalogoS('p','tipojornada',row.idtipojornada);
			comboCatalogoS('p','tiporegimen',row.idtiporegimen);
			comboCatalogoS('p','riesgopuesto',row.idriesgopuesto);
			comboCatalogoS('p','periodicidadpago',row.idperiodicidadpago);
			comboCatalogoS('p','banco',row.banco);
			$("#piniciolaboral").val(formatearFecha(row.fechainiciolab));
			$("#pterminolaboral").val(formatearFecha(row.fechaterminolab));
			calculaTiempo('piniciolaboral','pantiguedad','antiguedadSAT');
			$("#psalariobase").val(row.salariobase);
			$("#psalariodiario").val(row.salariodiario);
			$("#pcuentabancaria").val(row.cuentabancaria);
			$("#psubrfc").val(row.subrfc);
			$("#psubporcentaje").val(row.subporcentaje);
		});
	}
	
	function eliminar(){
		var folio = $("#pnip").val();
		if(confirm("¿Desea eliminar el empleado No."+folio+"?")){
			var params = "nip="+folio;
			$.post("ajax/ajaxempleado.php?op=eliminar", params, function(resp){
				alert(resp);
				if(resp==1){
					alert("El empleado se desactivo correctamente!");
					nuevo();					
				}
				if(resp==0){
					alert("Ocurrió un error, intente nuevamente. Si el problema persiste contacte a soporte");
				}
			});	
		}
	}
	
	function guardar(){
		
		var validacion = validar(camposTXT);
		
		if(validacion == true){			
			var params = "nip=" + $("#pnip").val();
			//Datos que se van a pempleado
			params+= "&rfc=" + $("#prfc").val();
			params+= "&nombre=" + $("#pnombre").val();			
			params+= "&curp=" + $("#pcurp").val();
			params+= "&nss=" + $("#pnss").val();
			params+= "&fecnac=" + $("#pfecnac").val();
			params+= "&edocivil=" + $("#pedocivil").val();
			params+= "&sexo=" + $("#psexo").val();
			params+= "&telefono=" + $("#ptelefono").val();
			params+= "&celular=" + $("#pcelular").val();
			params+= "&email=" + $("#pemail").val();
			//Datos que se van a pdireccion
			params+= "&calle=" + $("#pcalle").val();
			params+= "&numext=" + $("#pnumext").val();
			params+= "&numint=" + $("#pnumint").val();
			params+= "&colonia=" + $("#pcolonia").val();
			params+= "&cp=" + $("#pcp").val();
			params+= "&estado=" + $("#pestado").val();
			params+= "&municipio=" + $("#pmunicipio").val();
			//Datos que se van a pcontrato
			params+= "&departamento=" + $("#pdepartamento").val();
			params+= "&puesto=" + $("#ppuesto").val();
			params+= "&tipocontrato=" + $("#ptipocontrato").val();
			params+= "&tipojornada=" + $("#ptipojornada").val();
			params+= "&iniciolaboral=" + $("#piniciolaboral").val();
			params+= "&terminolaboral=" + $("#pterminolaboral").val();
			params+= "&sindicalizado=" + $("#psindicalizado").val();
			params+= "&tiporegimen=" + $("#ptiporegimen").val();
			params+= "&riesgopuesto=" + $("#priesgopuesto").val();
			params+= "&periodicidadpago=" + $("#pperiodicidadpago").val();
			params+= "&salariobase=" + $("#psalariobase").val();
			params+= "&salariodiario=" + $("#psalariodiario").val();
			params+= "&banco=" + $("#pbanco").val();
			params+= "&cuentabancaria=" + $("#pcuentabancaria").val();
			params+= "&subrfc=" + $("#psubrfc").val();
			params+= "&subporcentaje=" + $("#psubporcentaje").val();
			
			$.post("ajax/ajaxempleado.php?op=guardar", params, function(resp){
				//alert(resp);
				if(resp>0){
					alert("El registro se guardo exitosamente!");
					//imprimir();
					//cargar(resp);
					//$("#busquedaEmpleados").modal('toggle');
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
	
	function reiniciaTXT(campos){
		var res = true;
		for(a in campos){
			var arr = campos[a].split("_");
			//alert(arr[1]);
			if(arr[1] == "txt"){
				var campo = $("#"+arr[0]).val();
				$("#"+arr[0]).removeClass('bordeRojo');
				$("#"+arr[0]).val('');				
			}else{
				$("#"+arr[0]).val('');
			}
		}
		return res;
	}
	
	function imprimir(){
		var folio = $("#folio").html();
		window.open("impIngreso.php?folio="+folio,"_blank");
		//alert("Ha ocurrido un problema con la configuracion de la impresion");	
	}
	
	function comboCatalogoS(prefijo,catalogo,valor,padre,valorpadre){
		var id = "";
		if(typeof padre != 'undefined'){
			$.post("ajax/ajaxempresa.php?op=comboSelected", "catalogo="+catalogo+"&valor="+valor+"&padre="+padre+"&valorpadre="+valorpadre, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
			});
		}else{
			$.post("ajax/ajaxempresa.php?op=comboSelected", "catalogo="+catalogo+"&valor="+valor, function(resp){
				//alert(resp);
				$("#"+prefijo+""+catalogo).html(resp);
			});
		}
	}
	
	function formatearFecha(fecha){
		fec = fecha.split("-");
		return fec[2]+"/"+fec[1]+"/"+fec[0];	
	}
	
</script>
</body>
</html>

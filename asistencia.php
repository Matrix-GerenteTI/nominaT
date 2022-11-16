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
		<li class="active"><a href="asistencia.php"><i class="icon-file-clock-o"></i><span>Asistencias</span> </a> </li>
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
          <div class="widget">
          	<div class="form-actions" style="margin-top:0px; margin-bottom:0px; padding:5px">
            	<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
						<td>
							Departamento<br/>
							<select id="busdepartamento" style="width:92%">
							</select>
						</td>
						<td>
							Puesto<br/>
							<select id="buspuesto" style="width:92%">
							</select>
						</td>
                    	<td align="right">
							<table cellpadding="0" cellspacing="0" align="right">
								<tr>
									<td>
										<div id="botones" class="shortcuts"> 
											<a href="javascript:lista(0,15);" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;
											<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
											<a href="javascript:descargar();" class="shortcut"><i class="shortcut-icon icon-download"></i><span class="shortcut-label">Exportar</span></a>
										</div>
									</td>
								</tr>
							</table>
                      	</td>
                   	</tr>
              	</table>
				<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td style="padding-left:5px">
                        	Empleado<br/>
							<input type="text" id="busempleado" style="margin-bottom:0px; width:97%" />
                        </td>
                        <td>
                        	<table cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td>
                                        De<br/>
										<input type="text" id="fecIni" style="margin-bottom:0px; width:80%"  /><i class="btn-icon-only icon-calendar" style="font-size:16px"> </i>
                                    </td>
                                    <td>
                                        Al<br/>
										<input type="text" id="fecFin" style="margin-bottom:0px; width:80%" /><i class="btn-icon-only icon-calendar" style="font-size:16px"> </i>
                                    </td>
                                </tr>
                            </table>
                        </td>
                   	</tr>
              	</table>
            </div>
            <div class="widget-content" style="padding:10px" id="tablaExport">
           	  <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                  	<th> Depto. </th>
					<th> Puesto. </th>
                  	<th> Empleado </th>
                    <th> Entrada </th>
                    <th> Salida I </th>
                    <th> Entrada I </th>
					<th> Salida </th>
					<th> Marcado </th>
                  </tr>
                </thead>
                <tbody id="tbody">
                  
                </tbody>
              </table>
			  <div id="paginacion" style="text-align:right"></div>
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
<!-- /main 

<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">	
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
		$("#fecIni").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		$("#fecFin").datepicker({
			changeMonth: true,
            changeYear: true,
			showButtonPanel: true,
			yearRange: '1930:+0'
		});
		
		comboCatalogo('bus','departamento',1,'puesto');
		lista(0,15);
	});
	
	
	function lista(inicio,cantidad){
		var fecIni = $("#fecIni").val();
		var fecFin = $("#fecFin").val();
		var nombre = $("#busempleado").val();
		var depto = $("#busdepartamento").val();		
		var puesto = $("#buspuesto").val();
		var params = "fecIni="+fecIni;
		params+= "&fecFin="+fecFin;
		params+= "&nombre="+nombre;
		params+= "&departamento="+depto;
		params+= "&puesto="+puesto;
		params+= "&inicio="+inicio;
		params+= "&cantidad="+cantidad;
		$.post("ajax/ajaxasistencia.php?op=lista", params, function(resp){
			//alert(resp);
			//$("#paginacion").html(resp);
	  		var row = eval('('+resp+')');
			var echo = "";
			var n=0;
			for(i in row){
				echo+= "<tr>";
				echo+= "	<td>"+row[i].departamento+"</td>";
				echo+= "	<td>"+row[i].puesto+"</td>";
				echo+= "	<td>"+row[i].nombre+"</td>";
				echo+= "	<td>"+row[i].entrada+"</td>";
				echo+= "	<td>"+row[i].salidai+"</td>";
				echo+= "	<td>"+row[i].entradai+"</td>";
				echo+= "	<td>"+row[i].salida+"</td>";
				echo+= "	<td>"+row[i].marcado+"</td>";
				echo+= "<tr>";
				n++;
			}
			for(j=n;j<cantidad;j++){
				echo+= "<tr>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "	<td>&nbsp;</td>";
				echo+= "<tr>";
			}
			$("#tbody").html(echo);
		});
		
		/*$.post("ajax/ajaxasistencia.php?op=listaP", params, function(resp){
			//alert(resp);
			var paginas = Math.ceil(resp/cantidad);
			var echo = '';
			var n2 = 0;
			for(i=1;i<=paginas;i++){
				var inicio = (n2 * cantidad);
				echo+= '<input type="button" value="'+i+'" onclick="lista('+inicio+','+cantidad+')" />';
				n2++;
			}
	  		
			$("#paginacion").html(echo);
		});*/
		
	}
	
	function imprimir(){
		var ficha = document.getElementById('tablaExport');
		var ventimp = window.open(' ', 'popimpr');
		ventimp.document.write( ficha.innerHTML );
		ventimp.document.close();
		ventimp.print( );
		ventimp.close();
	}
	
	function cancelar(){
		if(confirm("Se procederá a cancelar los recibos de nómina seleccionados, ¿Esta seguro de relizar esta acción?")){
			$("input:checkbox:checked").each(function() {
				 var id = $(this).attr('name');
				 var arreglo = new Array();
				 if(id!='chkall'){
					 arreglo = id.split("-");
					 $("#ico"+id).html('<img src="loading.gif" width="17px" heigth="17px" />');
					 $.post("ajax/ajaxtimbrado.php?op=cancelar", "serie="+arreglo[0]+"&folio="+arreglo[1], function(resp){
						//alert(resp);						
						var row = eval('('+resp+')');
						if(row.status==201){
							$("#ico"+id).html("<span class='shortcut-icon icon-ok'></span>");
							$("#divchk"+id).html("");
						}else{
							alert(row.mensaje);
							$("#ico"+id).html("<span class='shortcut-icon icon-exclamation-triangle'></span>");
						}
						
					});
				 }
			});
		}
	}
	
	function descargar(){
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#tablaExport').html()));
		e.preventDefault();
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
						$("#"+prefijo+""+scatalogo).html("<option value=''>TODOS...</option>"+sresp);
						
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
</script>
</body>
</html>

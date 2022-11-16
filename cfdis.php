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
        <li class="active"><a href="cfdis.php"><i class="icon-list-alt"></i><span>CFDIs</span> </a> </li>
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
          <div class="widget">
          	<div class="form-actions" style="margin-top:0px; margin-bottom:0px; padding:5px">
            	<table width="100%" cellpadding="0" cellspacing="0">
                	<tr>
						<td>
							Estado<br/>
							<select id="busstatus" style="width:92%" onchange="botones()">
								<option value="1">VIGENTES</option>
								<option value="99">CANCELADOS</option>
							</select>
						</td>
						<td>
							Departamento<br/>
							<select id="busdepartamento" style="width:92%">
							</select>
						</td>
                    	<td align="right">
							<table cellpadding="0" cellspacing="0" align="right">
								<tr>
									<td>
										<div id="botones" class="shortcuts"> 
											<a href="javascript:lista(0,15);" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;
											<a href="javascript:enviar();" class="shortcut"><i class="shortcut-icon icon-envelope-o"></i><span class="shortcut-label">Enviar</span></a>&nbsp;
											<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;
											<a href="javascript:descargar();" class="shortcut"><i class="shortcut-icon icon-download"></i><span class="shortcut-label">Descargar</span></a>&nbsp;
											<a href="javascript:cancelar();" class="shortcut"><i class="shortcut-icon icon-ban"></i><span class="shortcut-label">Cancelar</span></a>
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
            <div class="widget-content" style="padding:10px">
           	  <table class="table table-striped table-bordered">
                <thead>
                  <tr>
					<th width="10px"><input type="checkbox" id="chkall" name="chkall"></th>
                  	<th> Depto. </th>
                  	<th> Empleado </th>
                    <th> Folio </th>
                    <th> UUID </th>
                    <th> Fecha Pago </th>
                    <th class="td-actions"> </th>
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
<!-- /extra --
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
		$('#chkall').click(function() {
			var c = this.checked;
			$(':checkbox').prop('checked',c);
		});
		comboCatalogo('bus','departamento',3);
		botones();
		lista(0,15);
	});
	
	function botones(){
		var estado = $("#busstatus").val();
		if(estado==1){
			$("#botones").html('<a href="javascript:lista(0,15);" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;<a href="javascript:enviar();" class="shortcut"><i class="shortcut-icon icon-envelope-o"></i><span class="shortcut-label">Enviar</span></a>&nbsp;<a href="javascript:descargar();" class="shortcut"><i class="shortcut-icon icon-download"></i><span class="shortcut-label">Descargar</span></a>&nbsp;<a href="javascript:cancelar();" class="shortcut"><i class="shortcut-icon icon-ban"></i><span class="shortcut-label">Cancelar</span></a>');
		}else{
			$("#botones").html('<a href="javascript:lista(0,15);" class="shortcut"><i class="shortcut-icon icon-search"></i><span class="shortcut-label">Buscar</span></a>&nbsp;<a href="javascript:imprimir();" class="shortcut"><i class="shortcut-icon icon-print"></i><span class="shortcut-label">Imprimir</span></a>&nbsp;<a href="javascript:descargar();" class="shortcut"><i class="shortcut-icon icon-download"></i><span class="shortcut-label">Descargar</span></a>');
		}
	}
	
	function lista(inicio,cantidad){
		var status = $("#busstatus").val();
		var fecIni = $("#fecIni").val();
		var fecFin = $("#fecFin").val();
		var nombre = $("#busempleado").val();
		var depto = $("#busdepartamento").val();
		var params = "fecIni="+fecIni;
		params+= "&fecFin="+fecFin;
		params+= "&nombre="+nombre;
		params+= "&depto="+depto;
		params+= "&inicio="+inicio;
		params+= "&cantidad="+cantidad;
		params+= "&status="+status;
		$.post("ajax/ajaxcfdis.php?op=lista", params, function(resp){
			//alert(resp);
	  		var row = eval('('+resp+')');
			var echo = "";
			var n=0;
			for(i in row){
				echo+= "<tr>";
				echo+= "	<td><div id='divchk"+row[i].serie+"-"+row[i].folio+"'><input type='checkbox' id='chk"+row[i].serie+"-"+row[i].folio+"' name='"+row[i].serie+"-"+row[i].folio+"' /></div></td>";
				echo+= "	<td>"+row[i].departamento+"</td>";
				echo+= "	<td>"+row[i].nombre+"</td>";
				echo+= "	<td>"+row[i].serie+"-"+row[i].folio+"</td>";
				echo+= "	<td>"+row[i].wsuuid+"</td>";
				echo+= "	<td>"+row[i].fechaPago+"</td>";
				echo+= "	<td style='text-align:center'><div id='ico"+row[i].serie+"-"+row[i].folio+"'></div></td>";
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
				echo+= '	<td class="td-actions">&nbsp;</td>';
				echo+= "<tr>";
			}
			$("#tbody").html(echo);
		});
		
		$.post("ajax/ajaxcfdis.php?op=listaP", params, function(resp){
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
		});
		
	}
	
	function imprimir(){
		var ids = '';
		//$("#ico"+id).html('<img src="loading.gif" width="17px" heigth="17px" />');
		$("input:checkbox:checked").each(function() {
			 var id = $(this).attr('name');
			 //var arreglo = new Array();
			 if(id!='chkall'){
				 arreglo = id.split("-");
				 ids+= arreglo[0]+'|'+arreglo[1]+',';
				 //$("#ico"+id).html("<span class='shortcut-icon icon-ok'></span>");
			 }
		});
		if(ids!=''){
			ids = ids.slice(0,-1);
			window.open("ajax/generaPDF.php?ids="+ids+"&registropat=1","_blank");
		}
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
		var ids = '';
		$("input:checkbox:checked").each(function() {
			 var id = $(this).attr('name');
			 var arreglo = new Array();
			 if(id!='chkall'){
				 arreglo = id.split("-");
				 ids+= arreglo[0]+'|'+arreglo[1]+',';
				 //$("#ico"+id).html("<span class='shortcut-icon icon-ok'></span>");
			 }
		});
		if(ids!=''){
			ids = ids.slice(0,-1);
			$.post("ajax/generarZIP2.php", "ids="+ids, function(resp){
				//alert(resp);
				window.open("ajax/descargarZIP2.php?nombre_zip="+resp);
			});
		}
	}
	
	function enviar(){
		var ids = '';
		$("input:checkbox:checked").each(function() {
			 var id = $(this).attr('name');
			 var arreglo = new Array();
			 if(id!='chkall'){
				arreglo = id.split("-");
				ids+= arreglo[0]+'|'+arreglo[1]+',';
				$("#ico"+id).html('<img src="loading.gif" width="17px" heigth="17px" />');
				$.post("ajax/ajaxtimbrado.php?op=enviar",  "serie="+arreglo[0]+"&folio="+arreglo[1], function(resp){
					//alert(resp);
					var row = eval('('+resp+')');
					if(row.status==0){					
						$("#ico"+id).html("<span class='shortcut-icon icon-ok'></span>");
					}else{
						alert(row.mensaje);
						$("#ico"+id).html("<span class='shortcut-icon icon-exclamation-triangle'></span>");
					}
				});
			 }
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
</script>
</body>
</html>

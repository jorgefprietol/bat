
		$(document).ready(function(){
			load(1);
			inicio();
		});

		function load(page){
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_pedido.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}

		function loadpromo(page){
			var q= $("#q").val();
			$("#loaderpromo").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_pedido_promo.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loaderpromo').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_divpromo").html(data).fadeIn('slow');
					$('#loaderpromo').html('');
					
				}
			})
		}

	function agregar(idd)
		{
			alert('paso agregar');
			var precio_ventad=document.getElementById('precio_venta_'+idd).value;
			var cantidadd=document.getElementById('cantidad_'+idd).value;
			//Inicia validacion
			if (isNaN(cantidadd))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+idd).focus();
			return false;
			}
			if (isNaN(precio_ventad))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+idd).focus();
			return false;
			}
			//Fin validacion
			
			$.ajax({
        type: "POST",
        url: "./ajax/agregar_pedido.php",

		error: function( jqXHR, textStatus, errorThrown ) {
			// Otro manejador error
			alert('error function');
		}
        datad: "id="+idd+"&precio_venta="+precio_ventad+"&cantidad="+cantidadd,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datosd){
		$("#resultados").html(datosd);
		}
		.fail( function( jqXHR, textStatus, errorThrown ) {
		// Un callback .fail()
		alert('error callback jfpl');
		});
		}

		function agregarpromo(id)
		{
			alert('paso agregarpromo');
			var precio_venta=document.getElementById('precio_venta_'+id).value;
			var cantidad=document.getElementById('cantidad_'+id).value;
			//Inicia validacion
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
			return false;
			}
			//Fin validacion
			
			$.ajax({
        type: "POST",
        url: "./ajax/agregar_pedido_promo.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
			function eliminar (id)
		{
			
			$.ajax({
        type: "GET",
        url: "./ajax/agregar_pedido.php",
        data: "id="+id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}

	
		function inicio ()
		{
			$.ajax({
        type: "GET",
        url: "./ajax/agregar_pedido.php",
        data: "inicio=1",
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		
		$("#datos_factura").submit(function(){
		  var id_cliente = $("#id_cliente").val();
		  var id_vendedor = $("#id_vendedor").val();
		  var condiciones = $("#condiciones").val();
		  
		  if (id_cliente==""){
			  alert("Debes seleccionar un cliente");
			  $("#nombre_cliente").focus();
			  return false;
		  }
		 VentanaCentrada('./pdf/documentos/pedido_pdf.php?id_cliente='+id_cliente+'&id_vendedor='+id_vendedor+'&condiciones='+condiciones,'Factura','','1024','768','true');
	 	});
		
		$( "#guardar_cliente" ).submit(function( event ) {
		  $('#guardar_datos').attr("disabled", true);
		  
		 var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "ajax/nuevo_cliente.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados_ajax").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#resultados_ajax").html(datos);
					$('#guardar_datos').attr("disabled", false);
					load(1);
				  }
			});
		  event.preventDefault();
		})
		
		$( "#guardar_producto" ).submit(function( event ) {
		  $('#guardar_datos').attr("disabled", true);
		  
		 var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "ajax/nuevo_producto.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados_ajax_productos").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#resultados_ajax_productos").html(datos);
					$('#guardar_datos').attr("disabled", false);
					load(1);
				  }
			});
		  event.preventDefault();
		})

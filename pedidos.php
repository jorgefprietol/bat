<?php
	/*-------------------------
	Autor: Jorge F prieto L
	Web: www.Jorge F prieto L.com
	Mail: info@Jorge F prieto L.com
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	
	$active_facturas="active";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";	
	$title="Pedidos | Distribuidor";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include("head.php");?>

  </head>
  <body>
	<?php
	include("navbar.php");
	?>  
    <div class="container col-2">
		<div class="panel panel-info">
		<div class="panel-heading">

			<h4><i class='glyphicon glyphicon-search'></i> Historico Pedido</h4>
		</div>
			<div class="panel-body">
			<div style="text-align: center;">
			<img src="img/promo.png" class="figure-img img-fluid rounded" style="width: 30%;" alt="...">

			<img src="img/promo2.png" class="figure-img img-fluid rounded" style="width: 30%;" alt="...">

			<img src="img/promo3.png" class="figure-img img-fluid rounded" style="width: 30%;" alt="...">
			</div>
			<br>
				<form class="form-horizontal" role="form" id="datos_cotizacion">
				
						<div class="form-group row">
							<label for="q" class="col-md-2 control-label">Buscar Pedido: </label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="q" placeholder="# de Pedido" onkeyup='load(1);'>
							</div>
							<div class="col-md-4">
								<button type="button" class="btn btn-default"  style="background-color: #f7b917; width: 30%; color:white;" onclick='load(1);'>
									<span class="glyphicon glyphicon-search" ></span> Buscar</button>
								<span id="loader"></span>

							</div>
							
						</div>
			</form>

				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			</div>
		</div>	

	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/pedidos.js"></script>
  </body>
</html>
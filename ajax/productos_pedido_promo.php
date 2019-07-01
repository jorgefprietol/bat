<?php

	/*-------------------------
	Autor: Jorge F prieto L
	Web: bateriasecuador.com
	Mail: info@bateriasecuador.com
	---------------------------*/
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){

		 $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $maxPrecioPromo=$_SESSION['maxPrecioPromo'];
		 $aColumns = array('codigoProducto', 'nombreProducto', 'caja', 'listalinea.codigoLinea', 'codigoListaPrecio','listalinea.nombreLinea');//Columnas de busqueda
		 $sTable = "productos,listalinea";
		 $sWhere = " WHERE listalinea.codigoLinea=productos.codigoLinea and productos.estadoProd=1 and productos.facturar=1 and productos.web=1 and productos.promocion=1 and productos.precioUnitario <= ".$maxPrecioPromo." order by productos.precioUnitario DESC";
		if ( $_GET['q'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		include 'pagination_promo.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/

		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");

		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);
		$reload = './index.php';
		//main query to fetch the data
		$sql="SELECT *, listalinea.nombreLinea as nombreLinea FROM  $sTable $sWhere LIMIT $offset,$per_page";
/* 		print($sql);
		die(); */
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>Linea-333</th>
					<th>Caja</th>
					<th>CódigoLP</th>
					<th>Producto</th>
					<th><span class="pull-right">Cant.</span></th>
					<th><span class="pull-right">Precio</span></th>
					<th class='text-center' style="width: 36px;">Agregar</th>
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
					$id_producto=$row['idProducto'];
					$codigo_linea=$row['nombreLinea'];
					$caja=$row['caja'];
					$codigo_producto=$row['codigoListaPrecio'];
					$nombre_producto=$row['nombreProducto'];
					$precio_venta=$row["precioUnitario"];
					$precio_venta=number_format($precio_venta,2,'.','');
					?>
					<tr>
						<td><?php echo $codigo_linea; ?></td>
						<td><?php echo $caja; ?></td>
						<td><?php echo $codigo_producto; ?></td>
						<td><?php echo $nombre_producto; ?></td>
						<td class='col-xs-1'>
						<div class="pull-right">
						<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto; ?>"  value="1" >
						</div></td>
						<td class='col-xs-2'><div class="pull-right">
						<input type="text" class="form-control" readonly style="text-align:right" id="precio_venta_<?php echo $id_producto; ?>"  value="<?php echo $precio_venta;?>" >
						</div></td>
						<td class='text-center'><a class='btn btn-info'href="#" onclick="agregarpromo('<?php echo $id_producto ?>')"><i class="glyphicon glyphicon-plus"></i></a></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=5><span class="pull-right">
					<?php
					 echo "promo".paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>
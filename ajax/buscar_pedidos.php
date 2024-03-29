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
	if (isset($_GET['id'])){
		$numero_pedido=intval($_GET['id']);
		$del1="delete from pedido where numero_factura='".$numero_pedido."'";
		$del2="delete from detalle_pedido where numero_pedido='".$numero_pedido."'";
		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se puedo eliminar los datos
			</div>
			<?php
			
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		  $sTable = "pedido, clientes";
		 $sWhere = "";
		 $admin='Administrador';
		 if($_SESSION['user_name']==$admin){
			$sWhere.=" WHERE pedido.ruc_cliente=clientes.codigoCliente";
		 }else{
			$sWhere.=" WHERE pedido.ruc_cliente=clientes.codigoCliente and clientes.codigoCliente=".$_SESSION['user_id']."";
		 }
		 
		if ( $_GET['q'] != "" )
		{
		$sWhere.= " and  (clientes.nombreCliente like '%$q%' or pedido.numero_pedido like '%$q%')";
			
		}
		
		$sWhere.=" order by pedido.numero_pedido desc";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './pedidos.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			echo mysqli_error($con);
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="info">
					<th>#</th>
					<th>Distribuidor</th>
					<th>Fecha</th>
					<th>Estado</th>
					<th class='text-right'>Total</th>
					<th class='text-right'>Acciones</th>
					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$id_factura=$row['numero_pedido'];
						$numero_factura=$row['numero_pedido'];
						$fecha=date("d/m/Y", strtotime($row['fecha_pedido']));
						$nombre_cliente=$row['nombreCliente'];
						$telefono_cliente='0983314541';
						$email_cliente=$row['mailCliente'];
						$nombre_vendedor=$row['nombreCliente'];
						$estado_factura=$row['estado_pedido'];
						if ($estado_factura==01){$text_estado="CREADO";$label_class='label-info';}
						elseif ($estado_factura==02){$text_estado="DEVUELTO";$label_class='label-warning';}
						elseif ($estado_factura==03){$text_estado="CANCELADO";$label_class='label-warning';}
						elseif ($estado_factura==04){$text_estado="APROBADO";$label_class='label-success';}
						elseif ($estado_factura==05){$text_estado="PENDIENTE";$label_class='label-warning';}
						elseif ($estado_factura==06){$text_estado="ENVIADO";$label_class='label-info';}
						elseif ($estado_factura==07){$text_estado="ENVIADO S.C.";$label_class='label-success';}
						else{$text_estado="REPROBADO";$label_class='label-warning';}
						$total_venta=$row['subtotal_pedido'];
					?>
					<tr>
					
						<td><?php echo $numero_factura; ?></td>
						<td><?php echo $nombre_cliente; ?></td>
						<td><?php echo $fecha; ?></td>

						<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
						<td class='text-right'><?php echo number_format ($total_venta,2); ?></td>					
					<td class="text-right">
<!-- 						<a href="editar_factura.php?id_factura=<?php //echo $id_factura;?>" class='btn btn-default' title='Editar factura' ><i class="glyphicon glyphicon-edit"></i></a>  
						<a href="#" class='btn btn-default' title='Borrar factura' onclick="eliminar('<?php //echo $numero_factura; ?>')"><i class="glyphicon glyphicon-trash"></i> </a> -->
						<a href="#" class='btn btn-default' title='Descargar factura' onclick="imprimir_factura('<?php echo $id_factura;?>');"><i class="glyphicon glyphicon-download"></i></a>
					</td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>
	<?php
		if (isset($title))
		{
	?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
    <a href="pedidos.php">
      <img width="25%" src="img/logobat.png" alt="Logo">
	  </a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
       </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="<?php echo $active_facturas;?>"><a href="pedidos.php"><i class='glyphicon glyphicon-list-alt'></i> Historico <span class="sr-only">(current)</span></a></li>
        <li class="<?php echo $active_facturas;?>"><a href="nueva_factura.php"><i class='glyphicon glyphicon-plus'></i> Nuevo </a></li>
        <li><a href="#"><?php echo $_SESSION['user_email']; ?> <span class="sr-only"></span></a></li>
		    <li><a href="login.php?logout"><i class='glyphicon glyphicon-off'></i> Salir</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
	<?php
		}
	?>
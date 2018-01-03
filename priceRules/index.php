<?php 
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	
	require("../config/settings.inc.php");
	$msg = $_GET["msg"] ? $_GET["msg"] : "";
	$con = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
	if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); } 

	$product_query = "SELECT ps_product_lang.id_product, ps_product_lang.name, ps_product.reference, ps_product.price AS base_price, ps_specific_price.id_specific_price, ps_specific_price.reduction_type, ps_specific_price.price AS price_reduction, ps_specific_price.reduction AS percentage_reduction, ps_specific_price.from,  ps_specific_price.to FROM ps_product_lang LEFT JOIN ps_product ON ps_product.id_product = ps_product_lang.id_product LEFT JOIN ps_specific_price ON ps_product.id_product = ps_specific_price.id_product WHERE ps_product.active = 1 ORDER BY ps_product.id_product ASC"; 
	$product_results = mysqli_query($con, $product_query);


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MANTENEDOR DE DESCUENTOS</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/css/fileinput.min.css" media="all" rel="stylesheet" crossorigin="anonymous"/>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" media="all" rel="stylesheet" crossorigin="anonymous"/>

    

  </head>
  <body>    	
	<?php include('header.php'); ?>
	<br>
    <div class="container-fluid">
      <h1>Listado de productos activados en el sitio</h1>
      <h3>con informe global de reglas de descuento</h3>
      <hr>
<!--       <div class="row">
  <div class="col-6 col-md-4">.col-6 .col-md-4</div>
  <div class="col-6 col-md-4">.col-6 .col-md-4</div>
  <div class="col-6 col-md-4">.col-6 .col-md-4</div>
</div> -->
      <div class="row">
        <div class="col-12">
        	<table class="table table-striped table-bordered user-select-none" id="rules-table">
					  <thead class="thead-inverse">
					    <tr>
					      <th>ID</th>
					      <th>Producto</th>
					      <th>Código</th>
					      <th>Precio</th>
					      <th>Descuento</th>
					      <th>Total descuento</th>
					      <th>Desde</th>
					      <th>Hasta</th>
					      <th>Acciones</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php while($p = mysqli_fetch_object($product_results)): ?>
						    <tr data-id="<?php echo $p->id_product; ?>">
						      <td scope="row" ><?php echo $p->id_product;?></td>
						      <td><?php echo utf8_encode($p->name); ?></td>
						      <td><?php echo $p->reference; ?></td>
						      <td><?php echo round($p->base_price*1.19, 0); ?></td>
								<?php if($p->reduction_type!=""){?>
									<?php //valido que el descuento esté activo en la fecha de hoy
										$vq = "SELECT * FROM ps_specific_price WHERE id_specific_price = ".$p->id_specific_price/*." AND NOW() BETWEEN from AND to"*/;
										$vr = mysqli_query($con, $vq);
										$c = mysqli_num_rows($vr);
										if( $c == 1){
											$dr = mysqli_fetch_object($vr);
									?>
											<?php if($dr->reduction_type == "percentage") {?> 
						      			<td>
													<button class="btn"><?php echo $dr->reduction*100; ?> <span class="badge badge-secondary">%</span></button>		
												</td>
												<!--Calculo precio descuento %-->		
												<td>
													<?php echo trim(round($p->base_price*1.19, 0)-round(round($p->base_price*1.19, 0) * $dr->reduction)); ?>
												</td>

											<?php }else{ ?> 
												<td>	
													<button class="btn btn-success"><span class="badge badge-secondary">$</span> <?php echo trim(round($dr->reduction, 0)); ?></button>
												</td>
												<!--Calculo precio descuento $-->		
												<td>
													<?php echo trim(round($p->base_price*1.19, 0)-round($dr->reduction, 0)); ?>
												</td>

											<?php } ?>
							    <?php }else{ ?>
							    	<td>
							      	Sin descuentos
							      </td>
							      <td>--</td>
							    <?php } ?>
							  <?php }else{?>
							  		<td>
							      	Sin descuentos
							      </td>
						      	<td>--</td>
						    <?php } ?>
						    		<?php 
						    			$df = strtotime($p->from);
						    			$dt = strtotime($p->to);
						    			$dn = strtotime(date("Y-m-d H:i:s"));
						    			
						    			if(($df < $dn ) && ($dt > $dn)){ /*ACTIVO*/ }else{ /*INACTIVO*/ } 
						    		?>
						    		<td><?php echo $p->from; ?></td>
						    		<td><?php echo $p->to; ?></td>
						    		<td>
						    			<?php if($p->reduction_type!=""){?>
						    			<a href="controllers/clear.php?id_product=<?php echo $p->id_product;?>&id_specific_price=<?php echo $dr->id_specific_price;?>" type="button" class="btn btn-warning btn-sm">
          									<span class="glyphicon glyphicon-trash"></span> Eliminar regla
        								</a>
        								<?php  } ?>
        							</td>
						    </tr>
						<?php endwhile; ?>
					  </tbody>
					</table>
        </div>
      </div>


    </div> <!-- /container -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/fileinput.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/locales/es.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/themes/fa/theme.js" crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
  </body>
</html>
<?php  
	require_once('../config/settings.inc.php');
	error_reporting(E_ALL);

	$con = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
	if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); } 

	$qry = "SELECT * FROM ps_product_lang WHERE id_shop='1' AND id_lang='1'";
	$product_results = mysqli_query($con, $qry);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>INFORMACIÓN DE PRODUCTOS</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/css/fileinput.min.css" media="all" rel="stylesheet" crossorigin="anonymous"/>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" media="all" rel="stylesheet" crossorigin="anonymous"/>
  </head>
  <body>    	
		<?php include('header.php'); ?>
		      <div class="row">
        <div class="col-12">
        	<table class="table table-striped table-bordered user-select-none" id="rules-table">
					  <thead class="thead-inverse">
					    <tr>
					      <th>ID</th>
					      <th>Nombre</th>
					      <th>Descripción</th>
					      <th>Descripción corta</th>
					      <th>Meta título</th>
					      <th>Meta descripción</th>
					      <th>Meta keywords</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php while($p = mysqli_fetch_object($product_results)): ?>
						    <tr>
						      <td><?php echo $p->id_product;?></td>
						      <td><?php echo utf8_encode($p->name); ?></td>
						      <td><?php echo utf8_encode($p->description); ?></td>
						      <td><?php echo utf8_encode($p->description_short); ?></td>
						      <td><?php echo utf8_encode($p->meta_title); ?></td>
						    	<td><?php echo utf8_encode($p->meta_description); ?></td>
						    	<td><?php echo utf8_encode($p->meta_keywords); ?></td>
						    </tr>
							<?php endwhile; ?>
					  </tbody>
					</table>
        </div>
      </div>
	</body>
</html>
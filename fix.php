 <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<?php 
	require_once('config/settings.inc.php');
	error_reporting(E_ALL);

	function splitter($meta) {
    	$a = explode("-",$meta);
    	echo $a[0];
	}

	$mysqli = new mysqli(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
	if($mysqli->connect_errno){
	    printf("Falló la conexión: %s\n", $mysqli->connect_error);
	    exit();
	}

	// AQUÍ SE CONSTRUYE EL ÁRBOL DE DEPENDENCIAS DE CATEGORÍAS BASÁNDOSE EN LA INFORMACIÓN DE LOS META TAGS
	$talla = '35E|36E|37E|38E';
	$q = "SELECT pa.* FROM ps_product_attribute pa INNER JOIN ps_category_product pc ON pa.id_product = pc.id_product WHERE pc.id_category = 13 AND pa.reference REGEXP '35E|36E|37E|38E' ORDER BY reference ASC";
?>

	<?php 
		$res = $mysqli->query($q);
		while($t = mysqli_fetch_object($res)):
	?>
		<?php 
			$actual = $t->reference;
			$talla = substr($actual, -3);

			switch ($talla) {
				case '35E':
						$ia = 2;
						$ria = 22;
						$r = "41E";
					break;
				case '36E':
						$ia = 3;
						$ria = 23;
						$r = "42E";
					break;
				case '37E':
						$ia = 4;
						$ria = 24;
						$r = "43E";
					break;
				case '38E':
						$ia = 5;
						$ria = 25;
						$r = "44E";
					break;
				
				default:
					# code...
					break;
			}

			$fq = "UPDATE ps_product_attribute_combination SET id_attribute='".$ria."' WHERE id_attribute='".$ia."' and`id_product_attribute`='".$t->id_product_attribute."'";

			if($mysqli->query($fq)){
				echo "<p style='color:green;'>Attributo Talla cambiado</p>";
			}else{
				echo "<p style='color:red;'>Hubo error actualizando el attributo talla</p>";
			};

			$fqc = "UPDATE ps_product_attribute SET reference='".substr($actual, 0, -3).$r."' WHERE id_product_attribute='".$t->id_product_attribute."'";

			if($mysqli->query($fqc)){
				echo "<p style='color:blue;'>Reference cambiado</p>";
			}else{
				echo "<p style='color:red;'>Error actualizando el reference</p>";
			};


			//echo "<p>".$fq."</p>";
			//echo "<p>".$fqc."</p>";

		?>

	<?php endwhile; ?>

</table>
</body>
</html>


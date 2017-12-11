<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<?php 
	require_once('../config/settings.inc.php');
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
	
	$q = 'SELECT CONCAT(t1.id_category, "-", n.name) AS lev1, CONCAT(t2.id_category,"-", n1.name) AS lev2, CONCAT(t3.id_category,"-",n2.name) AS lev3, CONCAT(t4.id_category, "-" , n3.name) AS lev4 FROM ps_category AS t1 LEFT JOIN ps_category AS t2 ON t2.id_parent = t1.id_category LEFT JOIN ps_category AS t3 ON t3.id_parent = t2.id_category LEFT JOIN ps_category AS t4 ON t4.id_parent = t3.id_category LEFT JOIN ps_category_lang AS n ON n.id_category = t1.id_category LEFT JOIN ps_category_lang AS n1 ON n1.id_category = t2.id_category LEFT JOIN ps_category_lang AS n2 ON n2.id_category = t3.id_category LEFT JOIN ps_category_lang AS n3 ON n3.id_category = t4.id_category WHERE t1.id_category = 2';
?>

<table>
	<thead>
		<tr>
			<th align="left">nivel 1<span style="padding: 10px; display: inline-block;">></span>nivel 2<span style="padding: 10px; display: inline-block;">></span>nivel 3<span style="padding: 10px; display: inline-block;">></span>nivel 4</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$res = $mysqli->query($q);
			while($t = mysqli_fetch_object($res)):
		?>
		<tr>
			<td><?php echo utf8_encode(strtolower($t->lev1));?><span style="padding: 10px; display: inline-block;">></span><?php echo utf8_encode(strtolower($t->lev2));?><span style="padding: 10px; display: inline-block;">></span><?php echo utf8_encode(strtolower($t->lev3));?><?php if($t->lev4!=""){ ?><span style="padding: 10px; display: inline-block;">></span><?php } ?><?php echo utf8_encode(strtolower($t->lev4));?>
			</td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>
</body>
</html>
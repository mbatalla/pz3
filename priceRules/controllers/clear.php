<?php 
	require_once('../../config/settings.inc.php');
	error_reporting(E_ALL);

	function splitter($meta) {
    	$a = explode("-",$meta);
    	echo $a[0];
	}

	$con = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
	if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); } 


	$idp = $_GET["id_product"] ? $_GET["id_product"] : "";
	$idr = $_GET["id_specific_price"] ? $_GET["id_specific_price"] : "";

	if($idp != ""){
		$qdr = "DELETE FROM ps_specific_price WHERE id_specific_price='".$idr."' and id_product='".$idp."'";
		$qdp = "DELETE FROM ps_specific_price_priority WHERE id_specific_price_priority='".$idr."' and id_product='".$idp."'";

		if(mysqli_query($con, $qdr) && mysqli_query($con, $qdp) ){
			header("Location:../index.php?msg=El%20descuento%20se%20eliminó%20correctamente");
		}else{
			echo "<h1>HUBO UN ERROR ELIMINANDO EL DESCUENTO</h1>";
		}

	}else{
		$qtr = "TRUNCATE ps_specific_price";
		$qtp = "TRUNCATE ps_specific_price_priority";

		if(mysqli_query($con, $qtr) && mysqli_query($con, $qtp) ){
			header("Location:../index.php?msg=Todos%20los%20descuentos%20se%20eliminaron%20correctamente");
		}else{
			echo "<h1>HUBO UN ERROR ELIMINANDO LOS DESCUENTOS</h1>";
		}
	}

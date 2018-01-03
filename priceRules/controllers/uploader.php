<?php 
	require_once('../../config/settings.inc.php');
	error_reporting(E_ALL);

	function splitter($meta) {
    	$a = explode("-",$meta);
    	echo $a[0];
	}

	$con = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
	if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error(); } 


	$upload = $_FILES["product_list"];

	$target_dir = "../uploads/";
	$tmp_name = $upload["tmp_name"];
	$ext = substr($upload["name"], -4);
	if (substr( $ext, 0, 1 ) != ".") {
		$theName = substr($upload["name"], 0, -5);
		$ext = substr($upload["name"], -5);
	}else{
		$theName = substr($upload["name"], 0, -4);
	}

	$name = $theName."_".date("Ymd_H_m_s").$ext;
	if($upload["error"]==0){
		move_uploaded_file($tmp_name, $target_dir."/".$name);
		include('../extensions/PHPExcel/Classes/PHPExcel/IOFactory.php');
		$inputFileName = $target_dir.$name;

		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

/*		echo "<h1>Subida correcta</h1><h2>".$inputFileName."</h2>";
		echo '<hr />';
		echo "<pre>";
		print_r($sheetData);
		echo "</pre>";*/
		$result = array();
		$error = array();
		$idp = 1;
		for ($i=2; $i <= sizeof($sheetData); $i++):
			$vpq = "SELECT id_product FROM ps_product WHERE reference ='".trim(strtoupper($sheetData[$i]['A']))."'";
			$vpr = mysqli_query($con, $vpq);
			$pid = mysqli_fetch_object($vpr);


			$vdq = "SELECT * FROM  ps_specific_price WHERE id_product ='".$pid->id_product."'";
			$vdr = mysqli_query($con, $vdq);
			$vcd = mysqli_num_rows($vdr);
			$dis = mysqli_fetch_object($vdr);
			if($vcd==0){

				if(trim($sheetData[$i]["C"])=="%"){	
					if($pid->id_product!=""){
						$qry = "INSERT INTO ps_specific_price (`id_specific_price`, `id_specific_price_rule`, `id_cart`, `id_product`, `id_shop`, `id_shop_group`, `id_currency`, `id_country`, `id_group`, `id_customer`, `id_product_attribute`, `price`, `from_quantity`, `reduction`, `reduction_tax`, `reduction_type`, `from`, `to`) VALUES ('".$idp."','0', '0', '".$pid->id_product."', '1', '0', '1', '0', '0', '0', '0', '-1.000000', '1', '".number_format(($sheetData[$i]["D"]/100), 6, '.', '')."', '1', 'percentage', '".$sheetData[$i]["E"]."', '".$sheetData[$i]["F"]."')";
						if(mysqli_query($con, $qry)){

						$qry2 = "INSERT INTO ps_specific_price_priority (`id_specific_price_priority`, `id_product`, `priority`) VALUES ('".$idp."','".$pid->id_product."','id_shop;id_currency;id_country;id_group')";
						$qry2e = mysqli_query($con, $qry2);

						$message = "Al artículo ".$sheetData[$i]['A']." se le agregó un descuento de ".$sheetData[$i]["D"]."%, activo desde ".$sheetData[$i]["E"]." hasta ".$sheetData[$i]["F"]." ID_Priority = ".$idp;



						// $qsale = "UPDATE ps_product_shop SET on_sale='1' WHERE id_product='".$pid->id_product."' and`id_shop`='1'";
						// if(mysqli_query($con, $qsale)){
						// 	$message .= " además se marcó el producto como oferta.";
						// }else{
						// 	$message .= " pero hubo un error marcando el producto como oferta.";
						// }
							
						if(array_push($result, $message)){
							$idp++;
						};
					}
					}else{
						$err = "La query no corrió correctamente para el producto ".$sheetData[$i]['A']." - Id: ".$pid->id_product.".";
						array_push($error, $err);
					};
					
				}else if (trim($sheetData[$i]["C"])=="$") {
						if($pid->id_product!=""){
							$qry = "INSERT INTO ps_specific_price (`id_specific_price`, `id_specific_price_rule`, `id_cart`, `id_product`, `id_shop`, `id_shop_group`, `id_currency`, `id_country`, `id_group`, `id_customer`, `id_product_attribute`, `price`, `from_quantity`, `reduction`, `reduction_tax`, `reduction_type`, `from`, `to`) VALUES ('".$idp."','0', '0', '".$pid->id_product."', '0', '0', '0', '0', '0', '0', '0', '-1.000000', '1', '".number_format(($sheetData[$i]["D"]), 6, '.', '')."', '1', 'amount', '".$sheetData[$i]["E"]."', '".$sheetData[$i]["F"]."')";

							if(mysqli_query($con, $qry)){

							$qry2 = "INSERT INTO ps_specific_price_priority (`id_specific_price_priority`, `id_product`, `priority`) VALUES ('".$idp."','".$pid->id_product."','id_shop;id_currency;id_country;id_group')";
							$qry2e = mysqli_query($con, $qry2);

							$message = "Al artículo ".$sheetData[$i]['A']." se le agregó un descuento de $ ".$sheetData[$i]["D"].", activo desde ".$sheetData[$i]["E"]." hasta ".$sheetData[$i]["F"]." ID_Priority = ".$idp;

							// $qsale = "UPDATE ps_product_shop SET on_sale='1' WHERE id_product='".$pid->id_product."' and`id_shop`='1'";
							// if(mysqli_query($con, $qsale)){
							// 	$message .= " además se marcó el producto como oferta.";
							// }else{
							// 	$message .= " pero hubo un error marcando el producto como oferta.";
							// }

							if(array_push($result, $message)){
								$idp++;
							};
						}
					}else{
						$err = "La query no corrió correctamente para el producto ".$sheetData[$i]['A']." - Id: ".$pid->id_product.".";
						array_push($error, $err);
					};				
				}else{
					$err = "EL VALOR PARA ".$sheetData[$i]['A']." - ID: ".$pid->id_product." NO ES NI PESOS NI PORCENTAJE";
					array_push($error, $err);
				}

			}else{
				if($sheetData[$i]["C"]=="%"){	
					if($pid->id_product!=""){
						$qry =  "UPDATE `ps_specific_price` SET `price`='-1.000000', `reduction`='".number_format(($sheetData[$i]["D"]/100), 6, '.', '')."', `from`='".$sheetData[$i]["E"]."', `to`='".$sheetData[$i]["F"]."' WHERE `id_specific_price`=".$dis->id_specific_price;
						if(mysqli_query($con, $qry)){
						$message = "Al artículo ".$sheetData[$i]['A']." se le actualizó un descuento a ".$sheetData[$i]["D"]."%, y se activó desde ".$sheetData[$i]["E"]." hasta ".$sheetData[$i]["F"];

						// $qsale = "UPDATE ps_product_shop SET on_sale='1' WHERE id_product='".$pid->id_product."' and`id_shop`='1'";
						// if(mysqli_query($con, $qsale)){
						// 	$message .= " además se marcó el producto como oferta.";
						// }else{
						// 	$message .= " pero hubo un error marcando el producto como oferta.";
						// }
						if(array_push($result, $message)){
							$idp++;
						};
					}
					}else{
						$err = "La query no corrió correctamente para el producto ".$sheetData[$i]['A']." - Id: ".$pid->id_product.".";
						array_push($error, $err);
					}
				}else if ($sheetData[$i]["C"]=="$") {
					if($pid->id_product!=""){
						$qry =  "UPDATE `ps_specific_price` SET `price`='-1.000000', `reduction`='".number_format(($sheetData[$i]["D"]), 6, '.', '')."', `from`='".$sheetData[$i]["E"]."', `to`='".$sheetData[$i]["F"]."' WHERE `id_specific_price`=".$dis->id_specific_price;
						if(mysqli_query($con, $qry)){
						$message = "Al artículo ".$sheetData[$i]['A']." se le actualizó un descuento a $ ".$sheetData[$i]["D"].", y se activó desde ".$sheetData[$i]["E"]." hasta ".$sheetData[$i]["F"];
						
						// $qsale = "UPDATE ps_product_shop SET on_sale='1' WHERE id_product='".$pid->id_product."' and`id_shop`='1'";
						// if(mysqli_query($con, $qsale)){
						// 	$message .= " además se marcó el producto como oferta.";
						// }else{
						// 	$message .= " pero hubo un error marcando el producto como oferta.";
						// }

						if(array_push($result, $message)){
							$idp++;
						};
					}
					}else{
						$err = "La query no corrió correctamente para el producto ".$sheetData[$i]['A']." - Id: ".$pid->id_product.".";
						array_push($error, $err);
					}
				}else{
					$err = "EL VALOR PARA ".$sheetData[$i]['A']." - ID: ".$pid->id_product." NO ES NI PESOS NI PORCENTAJE";
					array_push($error, $err);
				}
				
			}
		endfor;
?>

<h1>Resultados:</h1>
<pre>
	<?php print_r($result); ?>
</pre>

<h1>Errores:</h1>
<pre>
	<?php print_r($error); ?>
</pre>

<?php

	}else{
		echo "<h1>Error en la subida del archivo de cargas</h1>";
	}

?>
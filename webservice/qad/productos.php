<?php
require_once('nusoap/lib/nusoap.php');

$server = new soap_server();
$server->configureWSDL('updateProductos', 'urn:updateProductos');
$server->register('Producto',
	array(
		'Categoria' => 'xsd:string',
		'CodColor' => 'xsd:string',
		'CodCuero' => 'xsd:string',
		'CodigoCorto' => 'xsd:string',
    	'CodigoQAD' => 'xsd:string',
    	'Descripcion' => 'xsd:string',
		'DscColor' => 'xsd:string',
		'DscCuero' => 'xsd:string',
		'Marca' => 'xsd:string',
   		'Numero' => 'xsd:string'
	),
	array(
		'return' => 'xsd:string'),
		'urn:updateProductos',
		'urn:updateProductos#Producto',
		'rpc',
		'encoded',
		'Actualizacion de productos desde QAD'
	);


function Producto($Categoria,$CodColor,$CodCuero,$CodigoCorto,$CodigoQAD,$Descripcion,$DscColor,$DscCuero,$Marca,$Numero) {

	require_once("WsQAD.php");
	$ws = new WsQAD();
	$product = $ws->find_product(trim($CodigoCorto));
	// If product exists
	if ($product) {

		$return = $ws->update_product(	
												$product,
												$Marca,
												$Categoria,
												$CodigoQAD,
												$CodigoCorto,
												$Descripcion,
												$CodColor,
												$CodCuero,
												$DscColor,
												$DscCuero,
												$Numero
											 );
			
	} else {
		// Create a new product	
		/**
			New version never create products. Just update if exists
		*/
		$return = "success";
		/*$return 		= $ws->create_product(
												$Marca,
												$Categoria,
												$CodigoQAD,
												$CodigoCorto,
												$Descripcion,
												$CodColor,
												$CodCuero,
												$DscColor,
												$DscCuero,
												$Numero
											);*/
		
	}

	return $return;
}

//file_put_contents($file, "\n Param ". file_get_contents('php://input')  ." \n\n" , FILE_APPEND | LOCK_EX);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service(file_get_contents('php://input'));
?>

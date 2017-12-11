<?php
require_once('nusoap/lib/nusoap.php');
require_once("WsQAD.php");

	$server = new soap_server();
	$server->configureWSDL('updatePrecios', 'urn:updateProductos');
	$server->register('Precio',
	array(
		'CodigoCorto' => 'xsd:string',
		'CodigoQAD' => 'xsd:string',
		'PrecioLista' => 'xsd:string',
	),
	array('return' => 'xsd:string'),
		'urn:updatePrecios',
		'urn:updatePrecios#Precio',
		'rpc',
		'encoded',
		'Actualizacion de Precios desde QAD'
	);

function Precio($CodigoCorto, $CodigoQAD, $PrecioLista) {
	
	$ws = new WsQAD();

	$product = $ws->find_product(trim($CodigoCorto));

	//If product exists then we update the price
	if($product)
		{
			$return = $ws->update_price($product, $PrecioLista);
			return $return;
		} 
	else 
		{
			return "error";
		}

}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

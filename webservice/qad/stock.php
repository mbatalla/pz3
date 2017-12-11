<?php

	require_once('nusoap/lib/nusoap.php');
	require_once("WsQAD.php");

	$server = new soap_server();
 
	$server->configureWSDL('updateStock', 'urn:updateStock');
	$server->register('Stock',
		array(
			'CodColor' => 'xsd:string',
			'CodigoCorto' => 'xsd:string',
			'CodigoQAD' => 'xsd:string',
			'Numero' => 'xsd:string',
			'Stock' => 'xsd:string'
		),
		array(
			'return' => 'xsd:string'),
			'urn:updateStock',
			'urn:updateStock#Stock',
			'rpc',
			'encoded',
			'Actualizacion de Stock desde QAD'
	);

	function Stock($CodColor,$CodigoCorto,$CodigoQAD,$Numero,$Stock) 
		{
			
			$ws = new WsQAD();

			$product = $ws->find_product(trim(strtoupper($CodigoCorto)));
			//If product exists then we update the price
			if($product)
				{
					$return = $ws->update_stock($product, $CodColor, $CodigoCorto, $CodigoQAD, $Numero, $Stock);
					return $return;
				} 
			else 
				{
					return "error";
				}

			return $return;
		}

	//$file = 'logfile.log';
	//file_put_contents($file, "\n Param ". file_get_contents('php://input')  ." \n\n" , FILE_APPEND | LOCK_EX);


	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);

<?php
class WebpayTx_compraModuleFrontController extends ModuleFrontController { 

	private	$metodo_cierre						="1"; //1 autorizacion normal, 4 genera extrucutura
	public function __construct()
	{
		$this->auth = true;
		parent::__construct();
		$this->context = Context::getContext();
			
		
		if ($this->metodo_cierre==4){
			$this->instalacion_webpay();
		}
		
		$this->cierreCompra();
	}
	public function postProcess(){	}
	public function initContent() { die();	}
	function instalacion_webpay(){
		$webpay = new webpay();
		
			echo getcwd();
			$sql = " DROP TABLE IF EXISTS  `webpay`;";
				$webpay->ejecuta_query($sql);		
			$sql ="CREATE TABLE IF NOT EXISTS `webpay` (
				  `Tbk_tipo_transaccion` varchar(200) NOT NULL,
				  `Tbk_respuesta` varchar(200) NOT NULL,
				  `Tbk_orden_compra` varchar(200) NOT NULL,
				  `Tbk_id_sesion` varchar(200) NOT NULL,
				  `Tbk_codigo_autorizacion` varchar(200) NOT NULL,
				  `Tbk_monto` varchar(200) NOT NULL,
				  `Tbk_balance` varchar(200) NOT NULL,
				  `Tbk_numero_tarjeta` varchar(200) NOT NULL,
				  `Tbk_numero_final_tarjeta` varchar(200) NOT NULL,
				  `Tbk_fecha_expiracion` date NOT NULL,
				  `Tbk_fecha_contable` date NOT NULL,
				  `Tbk_fecha_transaccion` varchar(200) NOT NULL,
				  `Tbk_hora_transaccion` varchar(200) NOT NULL,
				  `Tbk_id_transaccion` varchar(200) NOT NULL,
				  `Tbk_tipo_pago` varchar(200) NOT NULL,
				  `Tbk_numero_cuotas` varchar(200) NOT NULL,
				  `Tbk_mac` varchar(200) NOT NULL,
				  `Tbk_monto_cuota` varchar(200) NOT NULL,
				  `Tbk_tasa_interes_max` varchar(200) NOT NULL,
				  `Tbk_ip` varchar(200) NOT NULL,
				  UNIQUE KEY `Tbk_tipo_transaccion` (`Tbk_tipo_transaccion`,`Tbk_respuesta`,`Tbk_orden_compra`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
			
				$webpay->ejecuta_query($sql);	
				
			
			$sql = " DROP TABLE IF EXISTS `webpay_orden`;";
				$webpay->ejecuta_query($sql);	
			$sql = "CREATE TABLE IF NOT EXISTS  `webpay_orden` (
						 id MEDIUMINT NOT NULL AUTO_INCREMENT,
						 `monto` int(11),
									`estado` int(11),
						 PRIMARY KEY (id)
						 );";
			
				$webpay->ejecuta_query($sql);	
				
	}
	function cierreCompra(){
	
	?>
	<body style="background:url(https://webpay3g.transbank.cl/webpayserver/imagenes/background.gif) repeat">
	
	<?php
	$webpay = new webpay();	
	$webpay_codigo_comercio=$webpay->webpay_codigo_comercio;
	
	require_once(_PS_ROOT_DIR_.'/modules/webpay/ws/soap-wsse.php');
	require_once(_PS_ROOT_DIR_.'/modules/webpay/ws/soap-validation.php');
	require_once(_PS_ROOT_DIR_.'/modules/webpay/ws/webpaywebservice.php');
	if ($webpay->ambiente==0){
			//certificacion
			define('SERVER_CERT', _PS_ROOT_DIR_.'/modules/webpay/ws/llave/tbk.pem');
		}else{
			//produccion
			define('SERVER_CERT', _PS_ROOT_DIR_.'/modules/webpay/ws/llave/serverTBK.crt');
		}

	$token_ws=$_POST['token_ws'];
	$url_wsdl=$webpay->webpay_wsdl_normal;	
	$commerceCode=$webpay->webpay_codigo_comercio;
	$base =$webpay->https_prefijo.'://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;
	$fracaso	=$base."index.php?fc=module&module=webpay&controller=fracaso";		
	
	try {
		$webpayService = new WebpayService($url_wsdl);
		$getTransactionResult = new getTransactionResult();
		$getTransactionResult->tokenInput = $token_ws;
		$getTransactionResultResponse = $webpayService->getTransactionResult(
		$getTransactionResult);
		$transactionResultOutput = $getTransactionResultResponse->return;
	} catch (SoapFault $fault) {
	?>
		<script type="text/javascript">
		  window.location=<?php echo $fracaso;?>"&TBK_ORDEN_COMPRA="<?php echo $trs_orden_compra;?>
		</script>
		<?php
		exit();
	}	

	$ruta_log =_PS_ROOT_DIR_."/modules/webpay/ws/log/bitacora_".$transactionResultOutput->buyOrder.".txt";
	$log = date("d-m-Y H:i:s")."
		Autorización :" .$transactionResultOutput->buyOrder."\n"; 		
		$log = $log."Request  \n".print_r($getTransactionResult, true);if($fp=fopen($ruta_log ,"a+")){fwrite($fp, $log);fclose($fp);}	
		$log = "Response ".print_r($transactionResultOutput, true); if( $fp = fopen($ruta_log , "a+")){fwrite($fp, $log);fclose($fp);}	
		$xmlResponse = $webpayService->soapClient->__getLastResponse();
		$soapValidation = new SoapValidation($xmlResponse, SERVER_CERT);
		$validationResult = $soapValidation->getValidationResult();
	$log = "Validacion certificado getTransactionResult:".$validationResult."\n"; if( $fp=fopen($ruta_log,"a+")){fwrite($fp,$log);fclose($fp);}			 	if (!$validationResult)	 {
		$redireccionamiento = $fracaso."&TBK_ORDEN_COMPRA=".$trs_orden_compra;
		$estado="Error de certificado";?>
		<form action="<?php echo $redireccionamiento;?>" method="post" name="webpay" id="webpay"  >
			<input type="hidden" name="token_ws" value="<?php echo $token_ws;?>" />
		</form>
		<script>document.getElementById('webpay').submit();</script>
		<?php exit();
		}
		
		$trs_transaccion="Normal";
		$trs_orden_compra=			$transactionResultOutput->buyOrder;
		$trs_id_session=				$transactionResultOutput->buyOrder;
		$trs_cod_autorizacion=	$transactionResultOutput->detailOutput->authorizationCode;
		$trs_monto=							$transactionResultOutput->detailOutput->amount;
		$trs_nro_tarjeta=				"";
		$trs_nro_final_tarjeta=	$transactionResultOutput->cardDetail->cardNumber;
		$trs_fecha_expiracion=	$transactionResultOutput->transactionDate;
		$trs_fecha_contable=		$transactionResultOutput->transactionDate;
		$trs_fecha_transaccion=	$transactionResultOutput->transactionDate;
		$trs_hora_transaccion="";
		$trs_id_transacion		=	$transactionResultOutput->buyOrder;
		$trs_tipo_pago =				$transactionResultOutput->detailOutput->paymentTypeCode;
		
		$estado=$transactionResultOutput->detailOutput->responseCode;
		
		
		$trs_nro_cuotas = $transactionResultOutput->detailOutput->sharesNumber;
		$trs_mac = $token_ws;
		$trs_monto_cuota="";
		$trs_tasa_interes_max="";
		$Tbk_VCI=$transactionResultOutput->VCI;
		
		$redireccionamiento=$transactionResultOutput->urlRedirection;

/****************************************Validaciones de monto y duplicidad**************************************/
		$err='';
		if ($estado=="0") {
				$query = "SELECT count(*) as total FROM `webpay_orden` where id =".$trs_orden_compra." and estado = 1";
				$rows3 = $webpay->query_row($query);
				if ($rows3['total']>0){
						$estado="ORDEN DUPLICADA";$err='1';
				} 
			}
			if ($estado=="0") {
				$query = "SELECT * FROM `webpay_orden` where id =".$trs_orden_compra."";
				$rows3 = $webpay->query_row($query);
				$monto_webpay=round($trs_monto);
				$monto_tienda=round($rows3['monto']);
				$theValue = ($monto_webpay!=$monto_tienda) ? "RECHAZADO" : "ACEPTADO"; 
				if ($theValue=="RECHAZADO") {
					$estado="Montos no Coinciden";$err='1';
				} 
			}
			
			/*$transactionResultOutput->detailOutput->responseCode
			 0 Transacción aprobada.
			-1 Rechazo de transacción.
			-2 Transacción debe reintentarse.
			-3 Error en transacción.
			-4 Rechazo de transacción.
			-5 Rechazo por error de tasa.
			-6 Excede cupo máximo mensual.
			-7 Excede límite diario por transacción.
			-8 Rubro no autorizado.*/
		$log = date("d-m-Y H:i:s")."
		===============================
		Respuesta transaccion:".$transactionResultOutput->detailOutput->responseCode."\n"; 
		if( $fp = fopen($ruta_log , "a+")) {fwrite($fp, $log);fclose($fp);	}	
		if ($err==''){
			//Autorizar el cobro------------------------------------------------*
			$webpayService = new WebpayService($url_wsdl);
			$acknowledgeTransaction = new acknowledgeTransaction();
			$acknowledgeTransaction->tokenInput = $token_ws;
			$acknowledgeTransactionResponse = $webpayService->acknowledgeTransaction(
			$acknowledgeTransaction);
			
			$log= "acknowledgeTransaction:"."\n"; if( $fp = fopen($ruta_log , "a+")) {fwrite($fp, $log);fclose($fp);	}				
			$log= "Request:".print_r($acknowledgeTransaction, true); if( $fp = fopen($ruta_log , "a+")){fwrite($fp, $log);fclose($fp);}	
			$log="Response: acknowledgeTransactionResponse:true\n"; if( $fp= fopen($ruta_log , "a+")) {fwrite($fp, $log);fclose($fp);}	
		
			
			$xmlResponse = $webpayService->soapClient->__getLastResponse();
			$soapValidation = new SoapValidation($xmlResponse, SERVER_CERT);
			$validationResult = $soapValidation->getValidationResult();
			
			$log = date("d-m-Y H:i:s")."Validacion certificado acknowledgeTransaction:" .$validationResult."\n"; 
			if( $fp = fopen($ruta_log , "a+")) {fwrite($fp, $log);fclose($fp);	}				
			$log = "Genera pedido Interno "."\n";if( $fp = fopen($ruta_log , "a+")) {fwrite($fp, $log);fclose($fp);	}	
				//validar el certificado que corresponda a transbank
			if (($validationResult) && ($estado=="0")){
					//creación de pedido presta
					$trs_monto = round($trs_monto);
					$trs_orden_compra = $trs_orden_compra;
					$query_RS_Busca = "UPDATE  `webpay_orden` SET  `estado` =  '1' WHERE  `id` =".$trs_orden_compra.";"; 
					$webpay->ejecuta_query( $query_RS_Busca);
					//crea pedido
					$estado_orden=Configuration::get('PS_OS_PAYMENT');
					$estado_orden=$webpay->estado_procesando; 
					$webpay->validateOrder(intval($trs_orden_compra),$estado_orden,$trs_monto, $webpay->displayName, NULL, array(), null, false, false, NULL);
					$orden_pedido=$webpay->ordenCompra($trs_orden_compra);
					if ($orden_pedido){
							$order = new Order($orden_pedido);
							$history = new OrderHistory();
							$history->id_order = (int)$orden_pedido;
							$history->changeIdOrderState((int)$webpay->estado_aceptado, $history->id_order);
							$history->addWithemail();
							$history->save();
						}
		
						/*********************REGISTRO DE PAGO VIA EMAIL ***********************************************/
						$subject="El Pedido ".$trs_orden_compra." Se a pagado Via Web Pay en Forma Correcta";
						$trs_tipo_pago = $trs_tipo_pago; 
						$trs_nro_cuotas = $trs_nro_cuotas;
						if ($trs_nro_cuotas=='0'){$trs_nro_cuotas='00';}
						$tipo_pago_descripcion="";
						if ($trs_tipo_pago=="VN"){	$tipo_pago_descripcion=" Sin Cuotas";}
						if ($trs_tipo_pago=="VC"){	$tipo_pago_descripcion=" Normales";}
						if ($trs_tipo_pago=="SI"){	$tipo_pago_descripcion=" Sin inter&eacute;s";}
						if ($trs_tipo_pago=="CIC"){	$tipo_pago_descripcion=" Cuotas Comercio";}
						if ($trs_tipo_pago=="VD"){	$tipo_pago_descripcion=" Red Compra";}
					
						$message="El Pedido ".$trs_orden_compra." Se ha pagado Via Web Pay en Forma Correcta <br>
						Cod. Autorización:".$trs_cod_autorizacion."<br>
						Monto:$".$monto_webpay."<br>
						Tipo de pago:".$tipo_pago_descripcion."<br>
						Cuotas:".$trs_nro_cuotas."<br>
						";
						//exit();
					//	$this->miMail ($webpay->mosConfig_mailfrom, $webpay->mosConfig_fromname,$webpay->correo_notificacion,$webpay->nombre_destino, $subject, $message);
					
			} else{
				$redireccionamiento = $fracaso."&TBK_ORDEN_COMPRA=".$trs_orden_compra;
				$estado="Error de certificado";
				}
		}else{
			//por Error montos
			$redireccionamiento = $fracaso."&TBK_ORDEN_COMPRA=".$trs_orden_compra;
		}
	
		// Guardar log de transaccion
		$sql="insert into webpay (Tbk_tipo_transaccion, Tbk_respuesta, Tbk_orden_compra, Tbk_id_sesion, Tbk_codigo_autorizacion, Tbk_monto, Tbk_numero_tarjeta, Tbk_numero_final_tarjeta, Tbk_fecha_expiracion, Tbk_fecha_contable, Tbk_fecha_transaccion, Tbk_hora_transaccion, Tbk_id_transaccion, Tbk_tipo_pago, Tbk_numero_cuotas, Tbk_mac, Tbk_monto_cuota, Tbk_tasa_interes_max,Tbk_ip)
				Values ('".$trs_transaccion."',
				'".$estado."','".$trs_orden_compra."','".$trs_id_session."','".$trs_cod_autorizacion."','".$trs_monto."','".$trs_nro_tarjeta."',
				'".$trs_nro_final_tarjeta."','".$trs_fecha_expiracion."','".$trs_fecha_contable."','".$trs_fecha_transaccion."','".$trs_hora_transaccion."',
				'".$trs_id_transacion."','".$trs_tipo_pago."','".$trs_nro_cuotas."','".$trs_mac  ."','".$trs_monto_cuota."','".$trs_tasa_interes_max."',
				'".$_SERVER['REMOTE_ADDR']."')";
		
		//echo $sql;
		$webpay->ejecuta_query( $sql);
			
	$log = "Envio a URL: ".$redireccionamiento."\nToken:".$token_ws."\n";if($fp=fopen($ruta_log,"a+")){fwrite($fp, $log);fclose($fp);}		
	
	?>
	<form action="<?php echo $redireccionamiento;?>" method="post" name="webpay" id="webpay"  >
		<input type="hidden" name="token_ws" value="<?php echo $token_ws;?>" />
	</form>
	<script>document.getElementById('webpay').submit();</script>
	</body> 
<?php 	
		exit();	
	
	/*function miMail ($mosConfig_mailfrom, $mosConfig_fromname, $email_destino,$nombre_destino, $subject, $message){
		$corre_asunto=$subject;
		$corre_msj=$message;
		$cabeceras  = "MIME-Version: 1.0\r\n";
		$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
		
		$cabeceras .= "To:".$nombre_destino." <".$email_destino.">\r\n";
		$cabeceras .= "From: ".$mosConfig_fromname." <".$mosConfig_mailfrom.">\r\n";
		
		mail($email_destino, $corre_asunto, $corre_msj, $cabeceras);
	}*/
	
	}
	
}



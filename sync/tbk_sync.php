<?php 

	require_once "../config/settings.inc.php";

	$mysqli = new mysqli(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
	if($mysqli->connect_errno){
	    printf("Falló la conexión: %s\n", $mysqli->connect_error);
	    exit();
	}

	$ip = 	getenv('HTTP_CLIENT_IP')?:
			getenv('HTTP_X_FORWARDED_FOR')?:
			getenv('HTTP_X_FORWARDED')?:
			getenv('HTTP_FORWARDED_FOR')?:
			getenv('HTTP_FORWARDED')?:
			getenv('REMOTE_ADDR');

	$url = $_POST["rurl"] ? "https://".substr($_POST["rurl"], 0, -1)."1" : "" ;
	$res = $_POST["respuesta"] ? $_POST["respuesta"] : "" ;
	$oc = $_POST["ocompra"] ? $_POST["ocompra"] : "" ;
	$nt = $_POST["ntarjeta"] ? $_POST["ntarjeta"] : "" ;
	$ca = $_POST["cautorizacion" ] ? $_POST["cautorizacion" ] : "" ;
	$mo = $_POST["monto"] ? $_POST["monto"] : "" ;
	$tp = $_POST["tpago"] ? $_POST["tpago"] : "" ;
	$tc = $_POST["tcuotas"] ? $_POST["tcuotas"] : "" ;
	$nc = $_POST["ncuotas"] ? $_POST["ncuotas"] : "" ;
	$fe = $_POST["fecha"] ? $_POST["fecha"] : "" ;
	$ho = $_POST["hora"] ? $_POST["hora"] : "" ;



	$wrq = "INSERT INTO webpay (Tbk_tipo_transaccion, Tbk_respuesta, Tbk_orden_compra, Tbk_id_sesion, Tbk_codigo_autorizacion, Tbk_monto, Tbk_numero_tarjeta, Tbk_numero_final_tarjeta, Tbk_fecha_expiracion, Tbk_fecha_contable, Tbk_fecha_transaccion, Tbk_hora_transaccion, Tbk_id_transaccion, Tbk_tipo_pago, Tbk_numero_cuotas, Tbk_ip) values ('TR_NORMAL','".$res."','".$oc."','".$oc."','".$ca."','".$mo."', '','".$nt."','".$fe."','".$fe."','".$fe."','".$ho."','','".$tp."','".$nc."', '".$ip."')";
	
	if( $mysqli->query($wrq)){
		header('Location:'.$url);
	}
?>
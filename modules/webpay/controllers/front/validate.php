<?php

require_once _PS_MODULE_DIR_.'webpay/webpay.php';
require_once _PS_MODULE_DIR_.'webpay/libwebpay/webpay-soap.php';


class WebPayValidateModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;

       	parent::initContent();

        if (Context::getContext()->cookie->pago_realizado == "SI") {

            Context::getContext()->cookie->__set('pago_realizado', 'NO');
            $this->processRedirect();
        }else{

            $this->confirmar();
        }
    }


    public function confirmar() {
    	$privatekey = Configuration::get('WEBPAY_SECRETCODE');
    	$comercio = Configuration::get('WEBPAY_STOREID');

    	$errorResponse = array('status' => 'RECHAZADO', 'c' => $comercio);
    	$acceptResponse = array('status' => 'ACEPTADO', 'c' => $comercio);

        Context::getContext()->cookie->__set('pago_realizado', 'SI');



        if (isset($_POST) && sizeof($_POST)==1) {


              $data = $this->validatePayment( $_POST );
            } else {

                Context::getContext()->cookie->__set('WEBPAY_TX_ANULADA', "SI");

                Context::getContext()->cookie->__set('pago_realizado', 'NO');
                $this->processRedirect();
            }
        }

    public function validatePayment($data) {

        if (isset($data["token_ws"])) {
            $token_ws = $data["token_ws"];
        } else {
            $token_ws = 0;
        }

        Context::getContext()->cookie->__set('WEBPAY_TX_ANULADA', "NO");

        $voucher = false;
        $error_transbank = "NO";

        $config = array(
            "MODO"            => Configuration::get('WEBPAY_AMBIENT'),
            "PRIVATE_KEY"     => Configuration::get('WEBPAY_SECRETCODE'),
            "PUBLIC_CERT"     => Configuration::get('WEBPAY_CERTIFICATE'),
            "WEBPAY_CERT"     => Configuration::get('WEBPAY_CERTIFICATETRANSBANK'),
            "CODIGO_COMERCIO" => Configuration::get('WEBPAY_STOREID'),
            "URL_FINAL"       => Context::getContext()->link->getModuleLink('webpay', 'validate', array(), true),
            "URL_RETURN"      => Context::getContext()->link->getModuleLink('webpay', 'validate', array(), true)
        );


        try{
            $webpay = new WebPaySOAP($config);
            $result = $webpay->webpayNormal->getTransactionResult($token_ws);
        }catch(Exception $e){
          try{
              $result = $webpay->webpayNormal->getTransactionResult($token_ws);
          }catch(Exception $e){
              $result["error"] = "Error conectando a Webpay";
              $result["detail"] = $e->getMessage();
              Context::getContext()->cookie->__set('WEBPAY_RESULT_CODE', "500");
              Context::getContext()->cookie->__set('WEBPAY_RESULT_DESC', $e->getMessage());
              Context::getContext()->cookie->__set('WEBPAY_TX_ANULADA', "SI");
              $error_transbank = "SI";

          }

        }

        $order_id = $result->buyOrder;
        if ($order_id && $error_transbank == "NO") {
            $this->processResponse($result, $error_transbank);
        if ( ($result->VCI == "TSY" || $result->VCI == "") && $result->detailOutput->responseCode == "0"){
                $voucher = true;
                WebPaySOAP::redirect($result->urlRedirection, array("token_ws" => $token_ws));

            } else {
                $responseDescription = htmlentities($result->detailOutput->responseDescription);

            }

        }

        if (!$voucher){

            $cart = Context::getContext()->cart;
            $customer = new Customer($cart->id_customer);

            if (!Validate::isLoadedObject($customer))
                Tools::redirect('index.php?controller=order&step=1');

            $currency = Context::getContext()->currency;
            $total = (float)$cart->getOrderTotal(true, Cart::BOTH);

            $this->module->validateOrder((int)$cart->id, Configuration::get('PS_OS_ERROR'), $total, $this->module->displayName, NULL, NULL, (int)$currency->id, false, $customer->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);


            }
    }

    public function processResponse($result, $error_transbank) {

        $paymentTypeCodearray = array(
            "VD" => "Venta Debito",
            "VN" => "Venta Normal",
            "VC" => "Venta en cuotas",
            "SI" => "3 cuotas sin interés",
            "S2" => "2 cuotas sin interés",
            "NC" => "N cuotas sin interés",
        );

        if ($result->detailOutput->responseCode == 0){
            $transactionResponse = "Aceptado";
        } else {
            $transactionResponse = $result->detailOutput->responseDescription; 
            $var = $result->detailOutput->responseCode;

        }

        if($error_transbank == "NO"){

            Context::getContext()->cookie->__set('WEBPAY_RESULT_CODE', $result->detailOutput->responseCode);
            Context::getContext()->cookie->__set('WEBPAY_RESULT_DESC', $transactionResponse);
        }

        $date_tmp = strtotime($result->transactionDate);
        $date_tx_hora = date('H:i:s',$date_tmp);
        $date_tx_fecha = date('d-m-Y',$date_tmp);


        if($result->detailOutput->paymentTypeCode == "SI" || $result->detailOutput->paymentTypeCode == "S2" ||
           $result->detailOutput->paymentTypeCode == "NC" || $result->detailOutput->paymentTypeCode == "VC" )
        {
            $tipo_cuotas = $paymentTypeCodearray[$result->detailOutput->paymentTypeCode];
		}else{
              $tipo_cuotas = "Sin cuotas";
		}


        Context::getContext()->cookie->__set('WEBPAY_TX_ANULADA', "NO");

        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_TXRESPTEXTO', $transactionResponse);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_TOTALPAGO', $result->detailOutput->amount);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_ACCDATE', $result->accountingDate);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_ORDENCOMPRA', $result->buyOrder);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_TXDATE_HORA', $date_tx_hora);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_TXDATE_FECHA', $date_tx_fecha);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_NROTARJETA', $result->cardDetail->cardNumber);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_AUTCODE', $result->detailOutput->authorizationCode);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_TIPOPAGO', $paymentTypeCodearray[$result->detailOutput->paymentTypeCode]);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_TIPOCUOTAS', $tipo_cuotas);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_RESPCODE', $result->detailOutput->responseCode);
        Context::getContext()->cookie->__set('WEBPAY_VOUCHER_NROCUOTAS', $result->detailOutput->sharesNumber);


    }

    private function processRedirect()
    {


        $cart = Context::getContext()->cart;

        if ($cart->id === null) {
            $id_usuario = Context::getContext()->customer->id;
            $sql = "SELECT id_cart FROM ps_cart p WHERE p.id_customer = $id_usuario ORDER BY p.id_cart DESC";
            $id_carro = Db::getInstance()->getValue($sql, $use_cache = true);
            $cart->id = $id_carro;
            $customer = new Customer($cart->id_customer);
            
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key.'&msg=processRedirect');
        }

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
                Tools::redirect('index.php?controller=order&step=1');

        $authorized = false;
        foreach (Module::getPaymentModules() as $module)
                if ($module['name'] == 'webpay')
                {
                        $authorized = true;
                        break;
                }

        if (!$authorized)
                die($this->module->l('This payment method is not available.', 'validation'));

        $customer = new Customer($cart->id_customer);

        if (!Validate::isLoadedObject($customer))
                Tools::redirect('index.php?controller=order&step=1');

        $currency = Context::getContext()->currency;
        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);



        if(Context::getContext()->cookie->WEBPAY_TX_ANULADA == "SI"){
            $this->module->validateOrder((int)$cart->id, Configuration::get('PS_OS_CANCELED'), $total, $this->module->displayName, NULL, NULL, (int)$currency->id, false, $customer->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key.'&msg=WEBPAY_TX_ANULADA');
        }
        if (Context::getContext()->cookie->WEBPAY_RESULT_CODE == 0){

            $this->module->validateOrder((int)$cart->id, Configuration::get('PS_OS_PREPARATION'), $total, $this->module->displayName, NULL, NULL, (int)$currency->id, false, $customer->secure_key);
            //Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
            //Syncronization service
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key.'&sync=0');


        }else{
            $this->module->validateOrder((int)$cart->id, Configuration::get('PS_OS_ERROR'), $total, $this->module->displayName, NULL, NULL, (int)$currency->id, false, $customer->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key.'&msg=PS_OS_ERROR');
        }
    }

}

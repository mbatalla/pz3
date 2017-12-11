<html><head><title>CRUD Tutorial - Retrieve example</title></head><body>
<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
* PrestaShop Webservice Library
* @package PrestaShopWebservice
*/

// Here we define constants /!\ You need to replace this parameters
define('DEBUG', true);											// Debug mode
define('PS_SHOP_PATH', 'http://pz.cl/');							// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'AHSGA5B68DR5VWIYQ5KCMK4ZQAC5EQQB');	// Auth key (Get it in your Back Office)
require_once('PSWebServiceLibrary.php');
require_once('../config/settings.inc.php');
error_reporting(E_ALL);


$con = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);
if (mysqli_connect_errno()){ echo "Failed to connect to MySQL: " . mysqli_connect_error();} 


// Here we make the WebService Call
try{
	$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
	// Here we set the option array for the Webservice : we want customers resources

}catch (PrestaShopWebserviceException $e){
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error<br />'.$e->getMessage();
}

// We set the Title
echo '<h1>Customers ';
if (isset($_GET['id']))
	echo 'Details';
else
	echo 'List';
echo '</h1>';

$opt['resource'] = 'orders';
// We set an id if we want to retrieve infos from a customer
if (isset($_GET['id']))
	$opt['id'] = (int)$_GET['id']; // cast string => int for security measures

// Call
$xml = $webService->get($opt);

// Here we get the elements from children of customer markup which is children of prestashop root markup
$resources = $xml->children()->children();

// We set a link to go back to list if we are in customer's details
if (isset($_GET['id']))
	echo '<a href="?">Return to the list</a>';

// if $resources is set we can lists element in it otherwise do nothing cause there's an error
if (isset($resources)){
	if (!isset($_GET['id'])){
		echo '<table border="5">';
		echo '<tr><th>Id</th><th>More</th></tr>';
		foreach ($resources as $resource)
		{
			// Iterates on the found IDs
			echo '<tr><td>'.$resource->attributes().'</td><td>'.
			'<a href="?id='.$resource->attributes().'">Retrieve</a>'.
			'</td></tr>';
		}
		echo "</table>";
	}else{
		echo "<pre>";
		print_r($resources);
		echo "</pre>";

		if (isset($_GET['id'])){
			$opta['resource'] = 'addresses';
			$opta['id'] = $resources->id_address_delivery;

			$xml2 = $webService->get($opta);

			$address= $xml2->children()->children();

				echo "<pre>";
				print_r($address);
				echo "</pre>";

				$opts['resource'] = 'states';
				$opts['id'] = $address->id_state;

				$xml3 = $webService->get($opts);

				$state= $xml3->children()->children();

				echo "<pre>";
					print_r($state);
				echo "</pre>";

				$body = '<?xml version="1.0" encoding="utf-8"?>
					<soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services" xmlns:qcom="urn:schemas-qad-com:xml-services:common" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
					        <soapenv:Header>
					                <wsa:Action />
					                <wsa:To>urn:services-qad-com:QADERP</wsa:To>
					                <wsa:MessageID>urn:services-qad-com::QADERP</wsa:MessageID>
					                <wsa:ReferenceParameters>
					                        <qcom:suppressResponseDetail>false</qcom:suppressResponseDetail>
					                </wsa:ReferenceParameters>
					                <wsa:ReplyTo>
					                        <wsa:Address>urn:services-qad-com:</wsa:Address>
					                </wsa:ReplyTo>
					        </soapenv:Header>
					        <soapenv:Body>
					                <maintainCustomer>
					                        <qcom:dsSessionContext>
					                                <qcom:ttContext>
					                                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
					                                        <qcom:propertyName>domain</qcom:propertyName>
					                                        <qcom:propertyValue>1000</qcom:propertyValue>
					                                </qcom:ttContext>
					                                <qcom:ttContext>
					                                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
					                                        <qcom:propertyName>version</qcom:propertyName>
					                                        <qcom:propertyValue>eB2_2</qcom:propertyValue>
					                                </qcom:ttContext>
					                                <qcom:ttContext>
					                                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
					                                        <qcom:propertyName>mnemonicsRaw</qcom:propertyName>
					                                        <qcom:propertyValue>false</qcom:propertyValue>
					                                </qcom:ttContext>
					                                <qcom:ttContext>
					                                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
					                                        <qcom:propertyName>scopeTransaction</qcom:propertyName>
					                                        <qcom:propertyValue>true</qcom:propertyValue>
					                                </qcom:ttContext>
					                        </qcom:dsSessionContext>
					                        <dsCustomer>
					                                <customer>
					                                        <cmAddr>'.substr($address->dni,0,8).'</cmAddr>
					                                        <adName>'.$address->firstname.'</adName>
					                                        <adLine1>'.$address->lastname.'</adLine1>
					                                        <adLine2>'.$address->address1.'</adLine2>
					                                        <adLine3>'.$address->address2.'</adLine3>
					                                        <adCity>Web</adCity> 
					                                        <adState>'.$address->city.'</adState>
					                                        <adCtry>CL</adCtry>     
					                                        <adCounty>'.strtoupper($state->name).'</adCounty>
					                                        <adPhone>'.$address->phone_mobile.'</adPhone>        
					                                        <adPhone2></adPhone2>
					                                        <cmShipvia>BLUE EXPRESS</cmShipvia>
					                                        <cmArAcct>104030</cmArAcct>
					                                        <cmType>MINO</cmType>
					                                        <cmCurr>CLP</cmCurr>
					                                        <cmSite>101</cmSite>
					                                        <cmLang>LS</cmLang>
					                                        <cmClass>NORMAL</cmClass>
					                                        <adTaxable>true</adTaxable>
					                                        <adTaxZone>CHILE</adTaxZone>
					                                        <adTaxc>IVA</adTaxc>
					                                        <adTaxIn>FALSE</adTaxIn>
					                                        <adPstId>CL'.substr($address->dni,0,8).'-'.substr($address->dni, -1).'</adPstId>
					                                        <cmCrTerms>CONTADO</cmCrTerms>
					                                </customer>
					                        </dsCustomer>
					                </maintainCustomer>
					        </soapenv:Body>
					</soapenv:Envelope>';


					//echo htmlspecialchars($body);

					$sql = "SELECT * FROM webpay WHERE Tbk_orden_compra = '".$resources->id_cart."'";
					$res = mysqli_query($con, $sql);
        			$webpay = mysqli_fetch_object($res);
        			$tmp_tbk_numero_cuotas = $webpay->Tbk_numero_cuotas;

					$pedido = '<soapenv:Envelope xmlns="urn:schemas-qad-com:xml-services" xmlns:qcom="urn:schemas-qad-com:xml-services:common" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wsa="http://www.w3.org/2005/08/addressing">
                <soapenv:Header>
                <wsa:Action />
                <wsa:To>urn:services-qad-com:QADERP</wsa:To>
                <wsa:MessageID>urn:services-qad-com::QADERP</wsa:MessageID>
                <wsa:ReferenceParameters>
              <qcom:suppressResponseDetail>true</qcom:suppressResponseDetail>
                </wsa:ReferenceParameters>
                <wsa:ReplyTo>
                <wsa:Address>urn:services-qad-com:</wsa:Address>
                </wsa:ReplyTo>
              </soapenv:Header>
              <soapenv:Body>
                <maintainOrdenWeb>
                <qcom:dsSessionContext>
                    <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>domain</qcom:propertyName>
                        <qcom:propertyValue>1000</qcom:propertyValue>
                    </qcom:ttContext>
                    <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>version</qcom:propertyName>
                        <qcom:propertyValue>eB21</qcom:propertyValue>
                    </qcom:ttContext>
                    <qcom:ttContext>
                        <qcom:propertyQualifier>QAD</qcom:propertyQualifier>
                        <qcom:propertyName>scopeTransaction</qcom:propertyName>
                        <qcom:propertyValue>true</qcom:propertyValue>
                    </qcom:ttContext>
                </qcom:dsSessionContext>
                <dsPedido>';

	        if ($tmp_tbk_numero_cuotas == ""){
	            $tmp_tbk_numero_cuotas = 1;
	        }
	    
	        if ($webpay->Tbk_tipo_pago == 'Venta Debito'){ 
	            $tmp_tbk_tipo_pago = 'TD';
	            $xxwebFpago = '116';
	        } else {
	            $tmp_tbk_tipo_pago = 'TC';
	            $xxwebFpago = '113';
	        }

			$pedido .= '<Pedido>
			                <xxwebNbr>'.$resources->id_cart.'</xxwebNbr>
			                <xxwebTrackid>'. $webpay->Tbk_numero_final_tarjeta.'#'.$tmp_tbk_numero_cuotas.'</xxwebTrackid>
			                <xxwebCodAut>'.$webpay->Tbk_codigo_autorizacion.'</xxwebCodAut>
			                <xxwebCust>'.substr($address->dni,0,8).'</xxwebCust>
			                <xxwebFpago>'.$xxwebFpago.'</xxwebFpago>
			                <xxwebSite>101</xxwebSite>
			                <xxwebLprice></xxwebLprice>
			                <xxwebTdoc>35</xxwebTdoc>';
			                echo "<pre>";
			               	print_r($resources->associations->order_rows);
			                echo "</pre>";
			                $i = 1;
			                foreach ($resources->associations->order_rows->order_row as $row => $p) {
			                	$pedido .= '<Detalle>
				                        <xxwebdLn>'.$i.'</xxwebdLn>
				                        <xxwebdPart>'.$p->product_reference.'</xxwebdPart>
				                        <xxwebdQty>'.$p->product_quantity.'</xxwebdQty>
				                        <xxwebdPrList>'.number_format(($p->product_price*1.19),0,'','').'</xxwebdPrList>
				                        <xxwebdPctDisc>0</xxwebdPctDisc>
				                        <xxwebdPrNeto>'.number_format($p->unit_price_tax_incl,0,'','').'</xxwebdPrNeto>
				                        <xxwebdUm>PR</xxwebdUm>
				                    </Detalle>';
// EL DESCUENTO SE MUESTRA CON EL VALOR DEL PORCENTAJE xxwebdPctDisc
/*
				                echo "<pre>";
				               	print_r($p->id);
				                echo "</pre>";*/
			                	$i++;
			                };

			                if(number_format($resources->total_shipping,0,'','')){

			                }

			                $pedido .= '
			                    <Detalle>
			                        <xxwebdLn>'.$i.'</xxwebdLn>
			                        <xxwebdPart>DESCDESPACHO</xxwebdPart>
			                        <xxwebdQty>-1</xxwebdQty>
			                        <xxwebdPrList>'.number_format($resources->total_shipping,0,'','').'</xxwebdPrList>
			                        <xxwebdPctDisc>0</xxwebdPctDisc>
			                        <xxwebdPrNeto>'.number_format($resources->total_shipping,0,'','').'</xxwebdPrNeto>
			                        <xxwebdUm>PR</xxwebdUm>
			                    </Detalle>
			                    <Detalle>
			                        <xxwebdLn>'.($i+1).'</xxwebdLn>
			                        <xxwebdPart>DESPACHO</xxwebdPart>
			                        <xxwebdQty>1</xxwebdQty>
			                        <xxwebdPrList>'.number_format($resources->total_shipping,0,'','').'</xxwebdPrList>
			                        <xxwebdPctDisc>0</xxwebdPctDisc>
			                        <xxwebdPrNeto>'.number_format($resources->total_shipping,0,'','').'</xxwebdPrNeto>
			                        <xxwebdUm>PR</xxwebdUm>
			                    </Detalle>';

							if ( $resources->total_discounts > 0){
								$pedido .='
										<Detalle>
											<xxwebdLn>'. ($i + 2 ) .'</xxwebdLn>
											<xxwebdPart>MONTODCTO</xxwebdPart>
											<xxwebdQty>-1</xxwebdQty>
											<xxwebdPrList>'.number_format($resources->total_discounts, 0, '', '').'</xxwebdPrList>
											<xxwebdPctDisc>0</xxwebdPctDisc>
											<xxwebdPrNeto>'.number_format($resources->total_discounts, 0, '', '').'</xxwebdPrNeto>
											<xxwebdUm>PR</xxwebdUm>
										</Detalle>';
							}

				        $pedido .='
				        </Pedido>
				        </dsPedido>
				        </maintainOrdenWeb>
				        </soapenv:Body>
				        </soapenv:Envelope>';
			/*foreach ($resources as $key => $resource)
			{
				// Iterates on customer's properties
				echo '<tr>';
				echo '<th>'.$key.'</th><td>'.$resource.'</td>';
				echo '</tr>';
			}*/

			echo htmlspecialchars($pedido);

		}
	}
}
?>
</body></html>

{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{*{if ($smarty.get.sync == "0")}<style>* {display: none !important;}</style>{/if}*}
{if ($WEBPAY_TX_ANULADA == "SI")}
    
    <p class="alert alert-danger">La Transaccion fue Anulada por el Cliente.</p>   
          
{else}
{if ($WEBPAY_RESULT_CODE == 0)}
<p class="alert alert-success">{l s='Your order on %s is complete.' sprintf=$shop_name mod='webpay'}</p>
        
<div class="box order-confirmation">
		<h3 class="page-subheading">Detalles del pago :</h3>
<p>  		
		            Respuesta de la Transaccion : {$WEBPAY_RESULT_DESC}
                <br />Tarjeta de credito: **********{$WEBPAY_VOUCHER_NROTARJETA}
                <br />Fecha de Transaccion :  {$WEBPAY_VOUCHER_TXDATE_FECHA}
                <br />Hora de Transaccion :  {$WEBPAY_VOUCHER_TXDATE_HORA}
                <br />Monto Compra :  $ {$WEBPAY_VOUCHER_TOTALPAGO}                
                <br />Orden de Compra :  {$WEBPAY_VOUCHER_ORDENCOMPRA}
                <br />Codigo de Autorizacion :  {$WEBPAY_VOUCHER_AUTCODE}
                <br />Tipo de Pago :  {$WEBPAY_VOUCHER_TIPOPAGO}
                <br />Tipo de Cuotas :  {$WEBPAY_VOUCHER_TIPOCUOTAS}
                <br />Numero de cuotas :  {$WEBPAY_VOUCHER_NROCUOTAS}
                
	</p>
</div>
<div style="display: none">
  {if ($smarty.get.sync == "0")}
    <!-- syncronization service -->
    <form action="https://{$smarty.server.HTTP_HOST}/sync/tbk_sync.php" method="post" id="sync-order">
      <input type="hidden" name="respuesta" id="respuesta" value="{$WEBPAY_RESULT_DESC}">
      <input type="hidden" name="rurl" id="rurl" value="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}">
      <input type="hidden" name="ocompra" id="ocompra" value="{$WEBPAY_VOUCHER_ORDENCOMPRA}">
      <input type="hidden" name="ntarjeta" id="ntarjeta" value="{$WEBPAY_VOUCHER_NROTARJETA}">
      <input type="hidden" name="cautorizacion" id="cautorizacion" value="{$WEBPAY_VOUCHER_AUTCODE}">
      <input type="hidden" name="monto" id="monto" value="{$WEBPAY_VOUCHER_TOTALPAGO}">
      <input type="hidden" name="tpago" id="tpago" value="{$WEBPAY_VOUCHER_TIPOPAGO}">
      <input type="hidden" name="tcuotas" id="tcuotas" value="{$WEBPAY_VOUCHER_TIPOCUOTAS}">
      <input type="hidden" name="ncuotas" id="ncuotas" value="{$WEBPAY_VOUCHER_NROCUOTAS}">
      <input type="hidden" name="fecha" id="fecha" value="{$WEBPAY_VOUCHER_TXDATE_FECHA}">
      <input type="hidden" name="hora" id="hora" value="{$WEBPAY_VOUCHER_TXDATE_HORA}">
      <input type="submit" value="registrar">
    </form>

    {literal}
      <script>
        $("#sync-order").submit();
      </script>
    {/literal}
    <!-- /syncronization service -->
  {/if}
</div>
{else}
    <p class="alert alert-danger">Ha ocurrido un error con su pago. </p>  
   <div class="box order-confirmation">
   		<h3 class="page-subheading">Detalles del pago :</h3>
   		<p>  
                Respuesta de la Transaccion : {$WEBPAY_RESULT_DESC} 
                <br />Orden de Compra :  {$WEBPAY_VOUCHER_ORDENCOMPRA}
                <br />Fecha de Transaccion :  {$WEBPAY_VOUCHER_TXDATE_FECHA}
                <br />Hora de Transaccion :  {$WEBPAY_VOUCHER_TXDATE_HORA}
      </p>
	 </div>
{/if}
{/if}

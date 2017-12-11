{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<table id="addresses-tab" cellspacing="0" cellpadding="0">
	<tr>
		<td style="width: 60%;">
			{if $delivery_address}
				<span class="bold">{l s='Delivery Address' pdf='true'}</span><br/><br/>
				{$delivery_address}
				<br>
				{$customer->dni}
			{/if}
		</td>

		<td style="width: 40%;">
			#OCQAD: {$id_qad}
			<p style="font-weight: bold; font-size: 10pt; color: #9E9F9E">{l s='Transbank.' pdf='true'}</p>							 
			 Tarjeta: {$tarjeta}<br/>
			 Monto pagado: $ {$monto_pagado}<br/>
			 Codigo autorización: {$cod_autorizacion}<br/>
			 Tipo pago: {$tipo_pago}<br/>
			 Cuotas: {$cuotas}<br/>
		</td>
	</tr>
	<tr>
	</tr>
</table>
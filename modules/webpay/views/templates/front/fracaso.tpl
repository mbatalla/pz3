<h1>{l s='Transacción Rechazada' mod='webpay'}</h1>
<br>
<p>{l s='Su transacción número' mod='webpay'}  <b>{$orden_compra}</b> {l s='no ha podido ser procesada' mod='webpay'} </p>

<br><p>{l s='Las posibles causas de este rechazo son:' mod='webpay'}<br />
  {l s='- Error en el ingreso de los datos de su tarjeta de crédito o debito (fecha y/o código de seguridad).' mod='webpay'}<br />
  {l s='- Su tarjeta de crédito o debito no cuenta con el cupo necesario para cancelar la compra.' mod='webpay'}<br />
  {l s='- Tarjeta aún no habilitada en el sistema financiero.' mod='webpay'} <br />
</p>
<br>
<p>{l s='Si desea confirmar su compra porfavor contáctese con ' mod='webpay'}{$email}</p>
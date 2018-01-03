{capture name=path}{l s='Your addresses'}{/capture}
<div class="box">
  <h1 class="page-subheading">{l s='Your addresses'}</h1>
  <p class="info-title">
    {if isset($id_address) && (isset($smarty.post.alias) || isset($address->alias))}
      {l s='Modify address'}
      {if isset($smarty.post.alias)}
        "{$smarty.post.alias}"
      {else}
        {if isset($address->alias)}"{$address->alias|escape:'html':'UTF-8'}"{/if}
      {/if}
    {else}
      {l s='To add a new address, please fill out the form below.'}
    {/if}
  </p>

  {include file="$tpl_dir./errors.tpl"}

  <p class="required">
    <sup>*</sup>
    {l s='Required field'}
  </p>
  <form action="{$link->getPageLink('address', true)|escape:'html':'UTF-8'}" method="post" class="std" id="add_address">
    {assign var="stateExist" value=false}
    {assign var="postCodeExist" value=false}
    {assign var="dniExist" value=false}
    {assign var="homePhoneExist" value=false}
    {assign var="mobilePhoneExist" value=false}
    {assign var="atLeastOneExists" value=false}

    {foreach from=$ordered_adr_fields item=field_name}
      {if $field_name eq 'company'}
        <div class="form-group">
          <label for="company">{l s='Company'}{if isset($required_fields) && in_array($field_name, $required_fields)} <sup>*</sup>{/if}</label>
          <input class="form-control validate" data-validate="{$address_validation.$field_name.validate}" type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{if isset($address->company)}{$address->company|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
      {/if}
      {if $field_name eq 'vat_number'}
        <div id="vat_area">
          <div id="vat_number">
            <div class="form-group">
              <label for="vat-number">{l s='VAT number'}{if isset($required_fields) && in_array($field_name, $required_fields)} <sup>*</sup>{/if}</label>
              <input type="text" class="form-control validate" data-validate="{$address_validation.$field_name.validate}" id="vat-number" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number}{else}{if isset($address->vat_number)}{$address->vat_number|escape:'html':'UTF-8'}{/if}{/if}" />
            </div>
          </div>
        </div>
      {/if}
      {if $field_name eq 'dni'}
      {assign var="dniExist" value=true}
      <div class="required form-group dni">
        <label for="dni">{l s='Identification number'} <sup>*</sup></label>
        <input class="form-control" data-validate="{$address_validation.$field_name.validate}" type="text" name="dni" id="dni" value="{if isset($smarty.post.dni)}{$smarty.post.dni}{else}{if isset($address->dni)}{$address->dni|escape:'html':'UTF-8'}{/if}{/if}" />
        <span class="form_info">{l s='DNI / NIF / NIE'}</span>
      </div>
      {/if}
      {if $field_name eq 'firstname'}
        <div class="required form-group">
          <label for="firstname">{l s='First name'} <sup>*</sup></label>
          <input class="is_required validate form-control" data-validate="{$address_validation.$field_name.validate}" type="text" name="firstname" id="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{else}{if isset($address->firstname)}{$address->firstname|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
      {/if}
      {if $field_name eq 'lastname'}
        <div class="required form-group">
          <label for="lastname">{l s='Last name'} <sup>*</sup></label>
          <input class="is_required validate form-control" data-validate="{$address_validation.$field_name.validate}" type="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{else}{if isset($address->lastname)}{$address->lastname|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
      {/if}
      {if $field_name eq 'address1'}
        <div class="required form-group">
          <label for="address1">{l s='Address'} <sup>*</sup></label>
          <input class="is_required validate form-control" data-validate="{$address_validation.$field_name.validate}" type="text" id="address1" name="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{if isset($address->address1)}{$address->address1|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
      {/if}
      {if $field_name eq 'address2'}
        <div class="required form-group">
          <label for="address2">{l s='Address (Line 2)'}{if isset($required_fields) && in_array($field_name, $required_fields)} <sup>*</sup>{/if}</label>
          <input class="validate form-control" data-validate="{$address_validation.$field_name.validate}" type="text" id="address2" name="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{if isset($address->address2)}{$address->address2|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
      {/if}
      {* {if $field_name eq 'postcode'} *}
        {* {assign var="postCodeExist" value=true} *}
        <!--<div class="required postcode form-group unvisible">
          <label for="postcode">{* {l s='Zip/Postal Code'} *} <sup>*</sup></label>
          <input class="is_required validate form-control" data-validate="{* {$address_validation.$field_name.validate} *}" type="text" id="postcode" name="postcode" value="{* {if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{if isset($address->postcode)}{$address->postcode|escape:'html':'UTF-8'}{/if}{/if} *}" />
        </div>-->
      {* {/if} *}
      {if $field_name eq 'city'}
        <div class="required form-group">
          <label for="city">{l s='Región'} <sup>*</sup></label>
          <select name="city" id="city" class="form-control validate is_required" data-validate="isCityName">
            <option value="">-- Seleccione región --</option>
            <option value="I" {if isset($smarty.post.city)}{if $smarty.post.city == 'I' } selected {/if}{/if}> I Región de Tarapacá</option>
            <option value="II" {if isset($smarty.post.city)}{if $smarty.post.city == 'II' } selected {/if}{/if}> I I Región de Antofagasta</option>
            <option value="III" {if isset($smarty.post.city)}{if $smarty.post.city == 'III' } selected {/if}{/if}> III Región de Atacama</option>
            <option value="IV" {if isset($smarty.post.city)}{if $smarty.post.city == 'IV' } selected {/if}{/if}> IV Región de Coquimbo</option>
            <option value="V" {if isset($smarty.post.city)}{if $smarty.post.city == 'V' } selected {/if}{/if}> V Región de Valparaíso</option>
            <option value="VI" {if isset($smarty.post.city)}{if $smarty.post.city == 'VI' } selected {/if}{/if}> VI Región de O'Higgins</option>
            <option value="VII" {if isset($smarty.post.city)}{if $smarty.post.city == 'VII' } selected {/if}{/if}> VII Región del Maule</option>
            <option value="VIII" {if isset($smarty.post.city)}{if $smarty.post.city == 'VIII' } selected {/if}{/if}> VIII Región del Biobío</option>
            <option value="IX" {if isset($smarty.post.city)}{if $smarty.post.city == 'IX' } selected {/if}{/if}> IX Región de la Araucanía</option>
            <option value="X" {if isset($smarty.post.city)}{if $smarty.post.city == 'X' } selected {/if}{/if}> X Región de los Los Lagos</option>
            <option value="XI" {if isset($smarty.post.city)}{if $smarty.post.city == 'XI' } selected {/if}{/if}> XI Región de Aysén</option>
            <option value="XII" {if isset($smarty.post.city)}{if $smarty.post.city == 'XII' } selected {/if}{/if}> XII Región de Magallanes</option>
            <option value="RM" {if isset($smarty.post.city)}{if $smarty.post.city == 'RM' } selected {/if}{/if}> Región Metropolitana</option>
            <option value="XIV" {if isset($smarty.post.city)}{if $smarty.post.city == 'XIV' } selected {/if}{/if}> XIV Región de Los Ríos</option>
            <option value="XV" {if isset($smarty.post.city)}{if $smarty.post.city == 'XV' } selected {/if}{/if}> XV Región de Arica y Parinacota</option>
          </select>
        </div>
        {* if customer hasn't update his layout address, country has to be verified but it's deprecated *}
      {/if}
      {if $field_name eq 'Country:name' || $field_name eq 'country' || $field_name eq 'Country:iso_code'}
        <div class="required form-group">
          <label for="id_country">{l s='Country'} <sup>*</sup></label>
          <select id="id_country" class="form-control" name="id_country">{$countries_list}</select>
        </div>
      {/if}
      {if $field_name eq 'State:name'}
        {assign var="stateExist" value=true}
        <div class="required id_state form-group">
          <label for="id_state">{l s='Comuna'} <sup>*</sup></label>
          <select name="id_state" id="id_state" class="form-control">
            <option value="">-</option>
          </select>
        </div>
      {/if}
      {* {if $field_name eq 'phone'}
        {assign var="homePhoneExist" value=true}
        <div class="form-group phone-number">
          <label for="phone">{l s='Home phone'}{if isset($one_phone_at_least) && $one_phone_at_least} <sup>**</sup>{/if}</label>
          <input class="{if isset($one_phone_at_least) && $one_phone_at_least}is_required{/if} validate form-control" data-validate="{$address_validation.phone.validate}" type="tel" id="phone" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{if isset($address->phone)}{$address->phone|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
        <div class="clearfix"></div>
      {/if} *}
      {if $field_name eq 'phone_mobile'}
        {assign var="mobilePhoneExist" value=true}
        <div class="{if isset($one_phone_at_least) && $one_phone_at_least}required {/if}form-group">
          <label for="phone_mobile">{l s='Mobile phone'}{if isset($one_phone_at_least) && $one_phone_at_least} <sup>**</sup>{/if}</label>
          <input class="validate form-control" data-validate="{$address_validation.phone_mobile.validate}" type="tel" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{if isset($address->phone_mobile)}{$address->phone_mobile|escape:'html':'UTF-8'}{/if}{/if}" />
        </div>
      {/if}
      {if ($field_name eq 'phone_mobile') || ($field_name eq 'phone_mobile') && !isset($atLeastOneExists) && isset($one_phone_at_least) && $one_phone_at_least}
        {assign var="atLeastOneExists" value=true}
        <p class="inline-infos required">** {l s='You must register at least one phone number.'}</p>
      {/if}
    {/foreach}
    
{*     {if !$postCodeExist}
      <div class="required postcode form-group unvisible">
        <label for="postcode">{l s='Zip/Postal Code'} <sup>*</sup></label>
        <input class="is_required validate form-control" data-validate="{$address_validation.postcode.validate}" type="text" id="postcode" name="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{if isset($address->postcode)}{$address->postcode|escape:'html':'UTF-8'}{/if}{/if}" />
      </div>
    {/if}    
    {if !$stateExist}
      <div class="required id_state form-group unvisible">
        <label for="id_state">{l s='State'} <sup>*</sup></label>
        <select name="id_state" id="id_state" class="form-control">
          <option value="">-</option>
        </select>
      </div>
    {/if} *}
    {if !$dniExist}
      <div class="required dni form-group unvisible">
        <label for="dni">RUT <sup>*</sup></label>
        <input class="is_required form-control" data-validate="{$address_validation.dni.validate}" type="text" name="dni" id="dni" value="{if isset($smarty.post.dni)}{$smarty.post.dni}{else}{if isset($address->dni)}{$address->dni|escape:'html':'UTF-8'}{/if}{/if}" />
        <span class="form_info">RUT / PASAPORTE</span>
      </div>
    {/if}
    <div class="form-group">
      <label for="other">{l s='Additional information'}</label>
      <textarea class="validate form-control" data-validate="{$address_validation.other.validate}" id="other" name="other" cols="26" rows="3" >{if isset($smarty.post.other)}{$smarty.post.other}{else}{if isset($address->other)}{$address->other|escape:'html':'UTF-8'}{/if}{/if}</textarea>
    </div>
    {*{if !$homePhoneExist}
      <div class="form-group phone-number">
        <label for="phone">{l s='Home phone'}</label>
        <input class="{if isset($one_phone_at_least) && $one_phone_at_least}is_required{/if} validate form-control" data-validate="{$address_validation.phone.validate}" type="tel" id="phone" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{if isset($address->phone)}{$address->phone|escape:'html':'UTF-8'}{/if}{/if}" />
      </div>
    {/if}
    <div class="clearfix"></div>
     {if !$mobilePhoneExist}
      <div class="{if isset($one_phone_at_least) && $one_phone_at_least}required {/if}form-group">
        <label for="phone_mobile">{l s='Mobile phone'}{if isset($one_phone_at_least) && $one_phone_at_least} <sup>**</sup>{/if}</label>
        <input class="validate form-control" data-validate="{$address_validation.phone_mobile.validate}" type="tel" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{if isset($address->phone_mobile)}{$address->phone_mobile|escape:'html':'UTF-8'}{/if}{/if}" />
      </div>
    {/if}
    {if isset($one_phone_at_least) && $one_phone_at_least && !$atLeastOneExists}
      <p class="inline-infos required">{l s='You must register at least one phone number.'}</p>
    {/if} *}
    <div class="required form-group" id="adress_alias">
      <label for="alias">{l s='Please assign an address title for future reference.'} <sup>*</sup></label>
      <input type="text" id="alias" class="is_required validate form-control" data-validate="{$address_validation.alias.validate}" name="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{elseif isset($address->alias)}{$address->alias|escape:'html':'UTF-8'}{elseif !$select_address}{l s='My address'}{/if}" />
    </div>
    
    <input type="hidden" name="cityid" value="{$smarty.post.city|intval}"></input>
    <p class="submit2">
      {if isset($id_address)}<input type="hidden" name="id_address" value="{$id_address|intval}" />{/if}
      {if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if}
      {if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
      {if isset($select_address)}<input type="hidden" name="select_address" value="{$select_address|intval}" />{/if}
      <input type="hidden" name="token" value="{$token}" />    
      <button type="submit" name="submitAddress" id="submitAddress" class="btn btn-default btn-md icon-right">
        <span>
          {l s='Save'}
        </span>
      </button>
    </p>
  </form>
</div>

{literal}
<script>
  !function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a){var b,c=navigator.userAgent,d=/iphone/i.test(c),e=/chrome/i.test(c),f=/android/i.test(c);a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},autoclear:!0,dataName:"rawMaskFn",placeholder:"_"},a.fn.extend({caret:function(a,b){var c;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof a?(b="number"==typeof b?b:a,this.each(function(){this.setSelectionRange?this.setSelectionRange(a,b):this.createTextRange&&(c=this.createTextRange(),c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select())})):(this[0].setSelectionRange?(a=this[0].selectionStart,b=this[0].selectionEnd):document.selection&&document.selection.createRange&&(c=document.selection.createRange(),a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length),{begin:a,end:b})},unmask:function(){return this.trigger("unmask")},mask:function(c,g){var h,i,j,k,l,m,n,o;if(!c&&this.length>0){h=a(this[0]);var p=h.data(a.mask.dataName);return p?p():void 0}return g=a.extend({autoclear:a.mask.autoclear,placeholder:a.mask.placeholder,completed:null},g),i=a.mask.definitions,j=[],k=n=c.length,l=null,a.each(c.split(""),function(a,b){"?"==b?(n--,k=a):i[b]?(j.push(new RegExp(i[b])),null===l&&(l=j.length-1),k>a&&(m=j.length-1)):j.push(null)}),this.trigger("unmask").each(function(){function h(){if(g.completed){for(var a=l;m>=a;a++)if(j[a]&&C[a]===p(a))return;g.completed.call(B)}}function p(a){return g.placeholder.charAt(a<g.placeholder.length?a:0)}function q(a){for(;++a<n&&!j[a];);return a}function r(a){for(;--a>=0&&!j[a];);return a}function s(a,b){var c,d;if(!(0>a)){for(c=a,d=q(b);n>c;c++)if(j[c]){if(!(n>d&&j[c].test(C[d])))break;C[c]=C[d],C[d]=p(d),d=q(d)}z(),B.caret(Math.max(l,a))}}function t(a){var b,c,d,e;for(b=a,c=p(a);n>b;b++)if(j[b]){if(d=q(b),e=C[b],C[b]=c,!(n>d&&j[d].test(e)))break;c=e}}function u(){var a=B.val(),b=B.caret();if(o&&o.length&&o.length>a.length){for(A(!0);b.begin>0&&!j[b.begin-1];)b.begin--;if(0===b.begin)for(;b.begin<l&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}else{for(A(!0);b.begin<n&&!j[b.begin];)b.begin++;B.caret(b.begin,b.begin)}h()}function v(){A(),B.val()!=E&&B.change()}function w(a){if(!B.prop("readonly")){var b,c,e,f=a.which||a.keyCode;o=B.val(),8===f||46===f||d&&127===f?(b=B.caret(),c=b.begin,e=b.end,e-c===0&&(c=46!==f?r(c):e=q(c-1),e=46===f?q(e):e),y(c,e),s(c,e-1),a.preventDefault()):13===f?v.call(this,a):27===f&&(B.val(E),B.caret(0,A()),a.preventDefault())}}function x(b){if(!B.prop("readonly")){var c,d,e,g=b.which||b.keyCode,i=B.caret();if(!(b.ctrlKey||b.altKey||b.metaKey||32>g)&&g&&13!==g){if(i.end-i.begin!==0&&(y(i.begin,i.end),s(i.begin,i.end-1)),c=q(i.begin-1),n>c&&(d=String.fromCharCode(g),j[c].test(d))){if(t(c),C[c]=d,z(),e=q(c),f){var k=function(){a.proxy(a.fn.caret,B,e)()};setTimeout(k,0)}else B.caret(e);i.begin<=m&&h()}b.preventDefault()}}}function y(a,b){var c;for(c=a;b>c&&n>c;c++)j[c]&&(C[c]=p(c))}function z(){B.val(C.join(""))}function A(a){var b,c,d,e=B.val(),f=-1;for(b=0,d=0;n>b;b++)if(j[b]){for(C[b]=p(b);d++<e.length;)if(c=e.charAt(d-1),j[b].test(c)){C[b]=c,f=b;break}if(d>e.length){y(b+1,n);break}}else C[b]===e.charAt(d)&&d++,k>b&&(f=b);return a?z():k>f+1?g.autoclear||C.join("")===D?(B.val()&&B.val(""),y(0,n)):z():(z(),B.val(B.val().substring(0,f+1))),k?b:l}var B=a(this),C=a.map(c.split(""),function(a,b){return"?"!=a?i[a]?p(b):a:void 0}),D=C.join(""),E=B.val();B.data(a.mask.dataName,function(){return a.map(C,function(a,b){return j[b]&&a!=p(b)?a:null}).join("")}),B.one("unmask",function(){B.off(".mask").removeData(a.mask.dataName)}).on("focus.mask",function(){if(!B.prop("readonly")){clearTimeout(b);var a;E=B.val(),a=A(),b=setTimeout(function(){B.get(0)===document.activeElement&&(z(),a==c.replace("?","").length?B.caret(0,a):B.caret(a))},10)}}).on("blur.mask",v).on("keydown.mask",w).on("keypress.mask",x).on("input.mask paste.mask",function(){B.prop("readonly")||setTimeout(function(){var a=A(!0);B.caret(a),h()},0)}),e&&f&&B.off("input.mask").on("input.mask",u),A()})}})});

    $('#phone_mobile').mask("+56 9 9999 9999");
</script>
{/literal}
<ul class="footer_links clearfix">
  <li>
    <a class="btn btn-secondary btn-sm icon-left" href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Back to your addresses'}">
      <span>
        {l s='Back to your addresses'}
      </span>
    </a>
  </li>
</ul>

{strip}
  {if isset($smarty.post.id_state) && $smarty.post.id_state}
    {addJsDef idSelectedState=$smarty.post.id_state|intval}
  {elseif isset($address->id_state) && $address->id_state}
    {addJsDef idSelectedState=$address->id_state|intval}
  {else}
    {addJsDef idSelectedState=false}
  {/if}
  {if isset($smarty.post.id_country) && $smarty.post.id_country}
    {addJsDef idSelectedCountry=$smarty.post.id_country|intval}
  {elseif isset($address->id_country) && $address->id_country}
    {addJsDef idSelectedCountry=$address->id_country|intval}
  {else}
    {addJsDef idSelectedCountry=false}
  {/if}
  {if isset($countries)}
    {addJsDef countries=$countries}
  {/if}
  {if isset($vatnumber_ajax_call) && $vatnumber_ajax_call}
    {addJsDef vatnumber_ajax_call=$vatnumber_ajax_call}
  {/if}
{/strip}
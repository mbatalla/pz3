<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<html{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><head>
    <meta charset="utf-8" />
    <title>{$meta_title|escape:'html':'UTF-8'}</title>
  
  
    {if isset($meta_description) && $meta_description}
      <meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
    {/if}
    {if isset($meta_keywords) && $meta_keywords}
      <meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
    {/if}
    <meta name="generator" content="PrestaShop" />
    <meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
    <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.0, initial-scale=1.0" /> 
    <meta name="apple-mobile-web-app-capable" content="yes" /> 
    <link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
    <link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    {if isset($css_files)}
      {foreach from=$css_files key=css_uri item=media}
        {if $css_uri == 'lteIE9'}
          <!--[if lte IE 9]>
          {foreach from=$css_files[$css_uri] key=css_uriie9 item=mediaie9}
          <link rel="stylesheet" href="{$css_uriie9|escape:'html':'UTF-8'}" type="text/css" media="{$mediaie9|escape:'html':'UTF-8'}" />
          {/foreach}
          <![endif]-->
        {else}
          <link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
        {/if}
      {/foreach}
    {/if}
    {if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
      {$js_def}
      {foreach from=$js_files item=js_uri}
        <script src="{$js_uri|escape:'html':'UTF-8'}"></script>
      {/foreach}
    {/if}
    {$HOOK_HEADER}
    {if (($hide_left_column || $hide_right_column) && ($hide_left_column !='true' || $hide_right_column !='true')) && !$content_only}
      {assign var="columns" value="2"}
    {elseif (($hide_left_column && $hide_right_column) && ($hide_left_column =='true' && $hide_right_column =='true')) && !$content_only}
      {assign var="columns" value="1"}
    {elseif $content_only}
      {assign var="columns" value="1"}
    {else}
     {assign var="columns" value="3"}
   {/if}
   {literal}
    <!-- Smartsupp Live Chat script -->
    <script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '1c5687b108bd8d48b03a61877b8bd1077c1c3df9';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='//www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    </script>
   {/literal}
  </head>
  <body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{else} show-left-column{/if}{if $hide_right_column} hide-right-column{else} show-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso} {if !$content_only}{if $columns == 2} two-columns{elseif $columns == 3} three-columns{else} one-column{/if}{/if}">
  {if !isset($content_only) || !$content_only}
  <!--[if IE]>
    <div class="old-ie">
      <a href="http://windows.microsoft.com/en-US/internet-explorer/..">
        <img src="{$img_dir}ie8-panel/warning_bar_0000_us.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
      </a>
    </div>
  <![endif]-->
    {if isset($restricted_country_mode) && $restricted_country_mode}
      <div id="restricted-country">
        <p>{l s='You cannot place a new order from your country.'}{if isset($geolocation_country) && $geolocation_country} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span>{/if}</p>
      </div>
    {/if}
    <div id="page">
      <div class="header-container">
        <header id="header">
          {if $page_name !='order'}
            {capture name='displayBanner'}{hook h='displayBanner'}{/capture}
            {if $smarty.capture.displayBanner}
              <div class="banner">
                <div class="container">
                  <div class="row">
                    {$smarty.capture.displayBanner}
                  </div>
                </div>
              </div>
            {/if}
          {/if}
          {assign var='displayMegaHeader' value={hook h='tmMegaLayoutHeader'}}
          {if isset($HOOK_TOP) || $displayMegaHeader}
            {if $displayMegaHeader}
              {if $page_name !='order'}
                {$displayMegaHeader}
              {else}
                <div class="container" style="padding-top: 25px;">
                  <div class="row">
                    <div id="header_logo">
                      <a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
                        <img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
                      </a>
                    </div>
                    {* {$HOOK_TOP} *}
                  </div>
                </div>
              {/if}
            {else}
              {if $page_name !='order'}
                {capture name='displayNav'}{hook h='displayNav'}{/capture}
                {if $smarty.capture.displayNav}
                  <div class="nav">
                    <div class="container">
                      <div class="row">
                        <nav>{$smarty.capture.displayNav}</nav>
                      </div>
                    </div>
                  </div>
                {/if}
              {/if}
              <div>
                <div class="container">
                  <div class="row">
                    <div id="header_logo">
                      <a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
                        <img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
                      </a>
                    </div>
                    {$HOOK_TOP}
                  </div>
                </div>
              </div>
            {/if}

            {if Module::isEnabled('blockwishlist')}
              <div class="wishlist-link">
                <a href="{$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|escape:'html':'UTF-8'}" title="{l s='My wishlists' mod='blockwishlist'}"><span>{l s='Wishlist'}</span></a>
              </div>
            {/if}
          {/if}
        </header>
      </div>
      <div class="columns-container">
        <div id="columns">
          {if $page_name !='index' && $page_name !='pagenotfound' && $page_name !='order'}
            {include file="$tpl_dir./breadcrumb.tpl"}
          {/if}
          <div id="slider_row">
            <div id="top_column" class="center_column">
              {assign var='displayMegaTopColumn' value={hook h='tmMegaLayoutTopColumn'}}
              {if $displayMegaTopColumn}
                {hook h='tmMegaLayoutTopColumn'}
              {elseif $page_name == 'index'}
                <div class="container">
                  {capture name='displayTopColumn'}{hook h='displayTopColumn'}{/capture}
                  {if $smarty.capture.displayTopColumn}
                    {$smarty.capture.displayTopColumn}
                  {/if}
                </div>
              {/if}
            </div>
            {include file="$tpl_dir./category-description.tpl"}
          </div>
          <div class="container">
            <div class="row">
              <div class="large-left col-sm-{12 - $right_column_size}">
                <div class="row">
                  <div id="center_column" class="center_column col-xs-12 col-sm-{12 - $left_column_size}">
  {/if}
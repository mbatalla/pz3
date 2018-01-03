{*
* 2002-2016 TemplateMonster
*
* TM Collections
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="view_collection">
  {capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}">{l s='My account' mod='tmcollections'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    <a href="{$link->getModuleLink('tmcollections', 'collections', array(), true)|escape:'htmlall':'UTF-8'}">{l s='My collections' mod='tmcollections'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    {$current_collection.name|escape:'htmlall':'UTF-8'}
  {/capture}
  <h1 class="page-heading">
    {$current_collection.name|escape:'htmlall':'UTF-8'}
  </h1>
  {if $products}
      {*define number of products per line in other page for desktop*}
      {if ($hide_left_column || $hide_right_column) && ($hide_left_column !='true' || $hide_right_column !='true')}     {* left or right column *}
        {assign var='nbItemsPerLine' value=3}
        {assign var='nbItemsPerLineTablet' value=2}
        {assign var='nbItemsPerLineMobile' value=2}
      {elseif ($hide_left_column && $hide_right_column) && ($hide_left_column =='true' && $hide_right_column =='true')} {* no columns *}
        {assign var='nbItemsPerLine' value=4}
        {assign var='nbItemsPerLineTablet' value=3}
        {assign var='nbItemsPerLineMobile' value=2}
      {else}																											  {* left and right column *}
        {assign var='nbItemsPerLine' value=2}
        {assign var='nbItemsPerLineTablet' value=1}
        {assign var='nbItemsPerLineMobile' value=2}
      {/if}
      <!-- Products list -->
      <ul class="product_list grid row">
        {foreach from=$products item=product name=products}
          <li class="ajax_block_product col-xs-{12/$nbItemsPerLineMobile} col-sm-{12/$nbItemsPerLineTablet} col-md-{12/$nbItemsPerLine}{if $smarty.foreach.products.iteration%$nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)} last-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModuloMobile)} last-mobile-line{/if}">
            <div class="product-container">
              <div class="left-block">
                <div class="product-image-container">
                  <a href="{$link->getProductlink($product.id_product, $product.link_rewrite)|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
                    <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.cover, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:'html':'UTF-8'}"/>
                  </a>
                </div>
              </div>
              <div class="right-block">
                <h5>
                  <a class="product-name" href="{$link->getProductlink($product.id_product, $product.link_rewrite)|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
                    <span class="quantity-formated"><span class="quantity">{$product.quantity|intval}</span> x </span>{$product.name|truncate:25:'...'|escape:'html':'UTF-8'}
                  </a>
                </h5>
                {if (!$PS_CATALOG_MODE)}
                  <div class="content_price">
                    <span class="price product-price">
                      {convertPrice price=$product.price}
                    </span>
                  </div>
                  {if $product.product_quantity >= 1}
                    <div class="cart-btn">
                      <div class="functional-buttons clearfix">
                        <a class="ajax_add_to_cart_button btn btn-sm btn-primary" title="{l s='Add to cart' mod='tmcollections'}" data-id-product="{$product.id_product|intval}" href="{$link->getPageLink('cart', true, NULL, "")|escape:'html':'UTF-8'}" rel="nofollow">
                          <span>{l s='Add to cart' mod='tmcollections'}</span>
                        </a>
                      </div>
                    </div>
                  {/if}
                {/if}
              </div>
            </div><!-- .product-container> -->
          </li>
        {/foreach}
      </ul>
  {else}
    <p class="alert alert-warning">{l s='No products in this collection.' mod='tmcollections'}</p>
  {/if}
</div>

{**
* 2002-2016 TemplateMonster
*
* TM Look Book
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
<div class="product-container" itemscope itemtype="https://schema.org/Product">
  <div class="left-block">
    <div class="product-image-container">
      <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
        <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.image.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width|escape:'htmlall':'UTF-8'}" height="{$homeSize.height|escape:'htmlall':'UTF-8'}"{/if} itemprop="image" />
      </a>
    </div>
  </div>
  <div class="right-block">
    <div class="top-content">
      <h5 itemprop="name">
        {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
        <a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
          {$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
        </a>
      </h5>
      {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
        <div class="content_price">
          {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
            {hook h="displayProductPriceBlock" product=$product type='before_price'}
            <span class="price product-price">
								{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
							</span>
            {if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
              {hook h="displayProductPriceBlock" product=$product type="old_price"}
              <span class="old-price product-price">
									{displayWtPrice p=$product.price_without_reduction}
								</span>
              {hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
              {if $product.specific_prices.reduction_type == 'percentage'}
                <span class="price-percent-reduction">-{$product.specific_prices.reduction|escape:'htmlall':'UTF-8' * 100}%</span>
              {/if}
            {/if}
            {hook h="displayProductPriceBlock" product=$product type="price"}
            {hook h="displayProductPriceBlock" product=$product type="unit_price"}
            {hook h="displayProductPriceBlock" product=$product type='after_price'}
          {/if}
        </div>
      {/if}
      <a class="product_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
        {l s='Show product' mod='tmlookbook'}
      </a>
    </div>
    <div class="functional-buttons">
      {if isset($quick_view) && $quick_view}
        <div class="qv-wrap"><a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" data-href="{$product.link|escape:'html':'UTF-8'}"></a></div>
      {/if}
      {hook h='displayProductListFunctionalButtons' product=$product}
      {if isset($comparator_max_item) && $comparator_max_item}
        <div class="compare">
          <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to Compare'}"></a>
        </div>
      {/if}
      {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
        {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
          {capture}add=1&amp;id_product={$product.id_product|intval}{if isset($product.id_product_attribute) && $product.id_product_attribute}&amp;ipa={$product.id_product_attribute|intval}{/if}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
          <div>
            <a class="ajax_add_to_cart_button btn btn-sm btn-primary" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
              <span>{l s='Add to cart'}</span>
            </a>
          </div>
        {/if}
      {/if}
    </div>
  </div>
</div>
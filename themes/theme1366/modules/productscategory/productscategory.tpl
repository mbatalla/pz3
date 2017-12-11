{if count($categoryProducts) > 0 && $categoryProducts !== false}
  <section class="page-product-box blockproductscategory">
    <h3 class="productscategory_h3 page-product-heading">
      {if $categoryProducts|@count == 1}
        {l s='%s other product in the same category:' sprintf=[$categoryProducts|@count] mod='productscategory'}
      {else}
        {l s='%s other products in the same category:' sprintf=[$categoryProducts|@count] mod='productscategory'}
      {/if}
    </h3>
    <div id="productscategory_list" class="clearfix">
      <ul id="bxslider1" class="bxslider clearfix">

        <li class="product-box item">
          {foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
            {if ($smarty.foreach.categoryProduct.index is even && $smarty.foreach.categoryProduct.index != 0)}
              </li>
              <li class="product-box item">
            {/if}
            <div class="product">
              <div>
                <div class="left-block">
                  <a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="lnk_img product-image" title="{$categoryProduct.name|htmlspecialchars}">
                    <img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'tm_home_default')|escape:'html':'UTF-8'}" alt="{$categoryProduct.name|htmlspecialchars}" />
                  </a>
                </div>
                <div class="right-block">
                  <h5 itemprop="name" class="product-name">
                    <a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)|escape:'html':'UTF-8'}" title="{$categoryProduct.name|htmlspecialchars}">{$categoryProduct.name|escape:'html':'UTF-8'}</a>
                  </h5>
                  {if $ProdDisplayPrice && $categoryProduct.show_price == 1 && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                    <p class="price_display">
                      {if isset($categoryProduct.specific_prices) && $categoryProduct.specific_prices && ($categoryProduct.displayed_price|number_format:2 !== $categoryProduct.price_without_reduction|number_format:2)}
                        <span class="price special-price">{convertPrice price=$categoryProduct.displayed_price}</span>
                        {if $categoryProduct.specific_prices.reduction && $categoryProduct.specific_prices.reduction_type == 'percentage'}
                          <span class="price-percent-reduction small">-{$categoryProduct.specific_prices.reduction * 100}%</span>
                        {/if}
                        <span class="old-price">{displayWtPrice p=$categoryProduct.price_without_reduction}</span>
                      {else}
                        <span class="price">{convertPrice price=$categoryProduct.displayed_price}</span>
                      {/if}
                    </p>
                  {/if}
                  <div class="block_description">
                    {$categoryProduct.description_short|strip_tags|truncate:90:'...'}
                  </div>
                  <div class="functional-buttons clearfix">
                    {if !$PS_CATALOG_MODE && ($categoryProduct.allow_oosp || $categoryProduct.quantity > 0)}
                      <div class="cart-btn no-print">
                        <a class="ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$categoryProduct.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$categoryProduct.id_product|intval}" title="{l s='Add to cart' mod='productscategory'}">
                          <span>{l s='Add to cart' mod='productscategory'}</span>
                        </a>
                      </div>
                    {/if}
                  </div>
                </div>
              </div>
            </div>
          {/foreach}
         </li>
      </ul>
    </div>
  </section>
{/if}
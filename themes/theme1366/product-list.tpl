{if isset($products) && $products}
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
  {*define numbers of product per line in other page for tablet*}
  
  {assign var='nbLi' value=$products|@count}
  {math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
  {math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
  
  <!-- Products list -->
  <ul{if isset($id) && $id} id="{$id}"{/if} class="product_list grid row{if isset($class) && $class} {$class}{/if}">
    {foreach from=$products item=product name=products}
      {math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
      {math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
      {math equation="(total%perLineM)" total=$smarty.foreach.products.total perLineM=$nbItemsPerLineMobile assign=totModuloMobile}
      {if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
      {if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
      {if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
      <li class="ajax_block_product col-xs-{12/$nbItemsPerLineMobile} col-sm-{12/$nbItemsPerLineTablet} col-md-{12/$nbItemsPerLine}{if $smarty.foreach.products.iteration%$nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)} last-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModuloMobile)} last-mobile-line{/if}">
        <div class="product-container" itemscope itemtype="https://schema.org/Product">
          <div class="left-block">
            <div class="product-image-container">
              {capture name='displayProductListGallery'}{hook h='displayProductListGallery' product=$product}{/capture}
              {if $smarty.capture.displayProductListGallery}
                {hook h='displayProductListGallery' product=$product}
              {else}
                <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
                  <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"{if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
                </a>
              {/if}
              {if isset($product.new) && $product.new == 1}
                <a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
                  <span class="new-label">{l s='New'}</span>
                </a>
              {/if}
              {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                <a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
                  <span class="sale-label">{l s='Sale!'}</span>
                </a>
              {/if}
              <!-- PERCENTAGE REDUCTION -->
              {if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                <div class="tag_percentage">- {(($product.specific_prices.reduction)/($product.price_without_reduction)*100)|round:0} %</div> 
              {/if}
              <!-- /PERCENTAGE REDUCTION -->
              <!-- BADGE LOGO -->
              {assign cat14 [['id_category' => 147]]}
              {if Product::idIsOnCategoryId($product.id_product, $cat14)}
                <span class="badge-logo"></span>
              {/if}

              <!-- /BADGE LOGO -->
            </div>
            {if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
            {hook h="displayProductPriceBlock" product=$product type="weight"}
          </div>
          <div class="right-block">
            <h5 itemprop="name">
              {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
              <a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
                <span class="list-name">{$product.name|truncate:100:'...'|escape:'html':'UTF-8'}</span>
                <span class="grid-name">{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}</span>
              </a>
            </h5>
            {if (!$PS_CATALOG_MODE && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
              <div class="content_price">
                {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                  {hook h="displayProductPriceBlock" product=$product type='before_price'}
                  <span class="price product-price{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0} product-price-new{/if}">
                    {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                  </span>
                  {if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                    {hook h="displayProductPriceBlock" product=$product type="old_price"}
                    <span class="old-price product-price">
                      {displayWtPrice p=$product.price_without_reduction}
                    </span>
                    {hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
                    {if $product.specific_prices.reduction_type == 'percentage'}
                      <span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
                    {/if}
                  {/if}
                  {hook h="displayProductPriceBlock" product=$product type="price"}
                  {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                  {hook h="displayProductPriceBlock" product=$product type='after_price'}
                {/if}
              </div>
            {/if}
            {if (!$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
              {if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
                <span class="availability">
                  {if ($product.allow_oosp || $product.quantity > 0)}
                    <span class="{if $product.quantity <= 0 && isset($product.allow_oosp) && !$product.allow_oosp} label-danger{elseif $product.quantity <= 0} label-warning{else} label-success{/if}">
                      {if $product.quantity <= 0}{if $product.allow_oosp}{if isset($product.available_later) && $product.available_later}{$product.available_later}{else}{l s='In Stock'}{/if}{/if}{else}{if isset($product.available_now) && $product.available_now}{$product.available_now}{else}{l s='In Stock'}{/if}{/if}
                    </span>
                  {elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
                    <span class="label-warning">
                      {l s='Product available with different options'}
                    </span>
                  {else}
                    <span class="label-danger">
                      {l s='Out of stock'}
                    </span>
                  {/if}
                </span>
              {/if}
            {/if}
            <p class="product-desc" itemprop="description">
              <span class="list-desc">{$product.description_short|strip_tags:'UTF-8'|truncate:220:'...'}</span>
            </p>
            {if isset($product.color_list)}
              <div class="color-list-container">{$product.color_list}</div>
            {/if}
            <div class="product-flags">
              {if (!$PS_CATALOG_MODE && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                {if isset($product.online_only) && $product.online_only}
                  <span class="online_only">{l s='Online only'}</span>
                {/if}
              {/if}
              {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
              {elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                <span class="discount">{l s='Reduced price!'}</span>
              {/if}
            </div>
            <div class="functional-buttons clearfix">
              {if isset($quick_view) && $quick_view}
                <div class="qv-wrap"><a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" data-href="{$product.link|escape:'html':'UTF-8'}"></a></div>
              {/if}
              {hook h='displayProductListFunctionalButtons' product=$product}
              {if isset($comparator_max_item) && $comparator_max_item}
                <div class="compare">
                  <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to Compare'}"></a>
                </div>
              {/if}
              {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                {if ($product.allow_oosp || $product.quantity > 0)}
                  <div class="cart-btn">
                    {if (!isset($product.customization_required) || !$product.customization_required)}
                      {capture}add=1&amp;id_product={$product.id_product|intval}{if isset($product.id_product_attribute) && $product.id_product_attribute}&amp;ipa={$product.id_product_attribute|intval}{/if}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
                      <a class="ajax_add_to_cart_button btn btn-sm btn-primary" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
                        <span>{l s='Add to cart'}</span>
                      </a>
                    {else}
                      <a class="customization btn btn-sm btn-primary" href="{$product.link|escape:'html':'UTF-8'}"  title="{l s='Customize'}">
                        <span>{l s='Customize'}</span>
                      </a>
                    {/if}
                  </div>
                {/if}
              {/if}
            </div>
          </div>
        </div><!-- .product-container> -->
      </li>
    {/foreach}
  </ul>
  {addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
  {addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
  {addJsDef comparator_max_item=$comparator_max_item}
  {addJsDef comparedProductsIds=$compared_products}
  {addJsDef nbItemsPerLine=$nbItemsPerLine}
  {addJsDef nbItemsPerLineTablet=$nbItemsPerLineTablet}
  {addJsDef nbItemsPerLineMobile=$nbItemsPerLineMobile}
{/if}
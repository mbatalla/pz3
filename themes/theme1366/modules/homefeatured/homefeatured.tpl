{if isset($products) && $products}
  <h4 class="homefeatured-heading">{l s='Featured products' mod='homefeatured'}</h4>
  {include file="$tpl_dir./product-list.tpl" class='homefeatured tab-pane' id='homefeatured'}
{else}
  <ul id="homefeatured" class="homefeatured tab-pane">
    <li class="alert alert-info">{l s='No featured products at this time.' mod='homefeatured'}</li>
  </ul>
{/if}
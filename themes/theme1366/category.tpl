{include file="$tpl_dir./errors.tpl"}

{if isset($category)}
  {if $category->id && $category->active}
    {if $products}
      <div class="content_sortPagiBar clearfix">
        <div class="sortPagiBar clearfix">
          {include file="./product-sort.tpl"}
          {include file="./product-compare.tpl" paginationId='bottom'}
          {include file="./nbr-product-page.tpl"}
        </div>
      </div>
      {include file="./product-list.tpl" products=$products}
      <div class="content_sortPagiBar">
        <div class="bottom-pagination-content clearfix">
          {include file="./pagination.tpl" paginationId='bottom'}
        </div>
      </div>
    {/if}
  {/if}
{/if}
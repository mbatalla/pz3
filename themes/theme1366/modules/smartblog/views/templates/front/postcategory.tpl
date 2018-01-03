{capture name=path}<span class="navigation-page">{if $title_category != ''}<a href="{smartblog::GetSmartBlogLink('smartblog')}">{/if}{l s='All Blog News' mod='smartblog'}{if $title_category != ''}</a>{/if}
  {if $title_category != ''}<span class="navigation-pipe">{$navigationPipe}</span>{$title_category}{/if}</span>{/capture}
  {if $title_category != ''}
    <h1 class="page-heading">{$title_category}</h1>
  {elseif $page_name == 'module-smartblog-category'}
    <h1 class="page-heading">{l s='Blog' mod='smartblog'}</h1>
  {/if}
  {if $postcategory == ''}
    {if $title_category != ''}
      <p class="alert alert-warning">{l s='No Post in Category' mod='smartblog'}</p>
    {else}
      <p class="alert alert-warning">{l s='No Post in Blog' mod='smartblog'}</p>
    {/if}
  {else}
    {if $smartdisablecatimg == '1'}
      {assign var="activeimgincat" value='0'}
      {$activeimgincat = $smartshownoimg}
      {if $title_category != ''}
        {foreach from=$categoryinfo item=category}
          <div id="sdsblogCategory" class="clearfix">
            {if ($cat_image != "no" && $activeimgincat == 0) || $activeimgincat == 1}
              <img alt="{$category.meta_title}" src="{$modules_dir}/smartblog/images/category/{$cat_image}-home-default.jpg" class="imageFeatured">
            {/if}
            {$category.description}
          </div>
        {/foreach}
      {/if}
    {/if}
    <div id="smartblogcat" class="block row">
      {foreach from=$postcategory item=post}
        {include file="./category_loop.tpl" postcategory=$postcategory}
      {/foreach}
    </div>
    {if !empty($pagenums)}
      <div class="bottom-pagination-content clearfix">
        <ul class="pagination">
          {for $k=0 to $pagenums}
            {if $title_category != ''}
              {assign var="options" value=null}
              {$options.page = $k+1}
              {$options.id_category = $id_category}
              {$options.slug = $cat_link_rewrite}
            {else}
              {assign var="options" value=null}
              {$options.page = $k+1}
            {/if}
            {if ($k+1) == $c}
              <li class="active"><span class="page-active btn btn-sm btn-secondary-2"><span>{$k+1}</span></span></li>
            {else}
              {if $title_category != ''}
                <li><a class="page-link btn btn-sm btn-secondary-2" href="{smartblog::GetSmartBlogLink('smartblog_category_pagination',$options)}"><span>{$k+1}</span></a></li>
              {else}
                <li><a class="page-link btn btn-sm btn-secondary-2" href="{smartblog::GetSmartBlogLink('smartblog_list_pagination',$options)}"><span>{$k+1}</span></a></li>
              {/if}
            {/if}
          {/for}
        </ul>
      </div>
    {/if}
  {/if}

{if isset($smartcustomcss)}
  <style>
    {$smartcustomcss}
  </style>
{/if}
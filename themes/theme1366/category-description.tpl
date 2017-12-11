{if $page_name =='category' && isset($category)}
  {if $category->id && $category->active}
    {if $scenes || $category->description || $category->id_image}
      <div class="content_scene_cat">
        {if $scenes}
          <div class="content_scene">
            <!-- Scenes -->
            {include file="$tpl_dir./scenes.tpl" scenes=$scenes}
            {if $category->description}
              <div class="cat_desc">
                <div>{$category->description}</div>
              </div>
            {/if}
          </div>
        {else}
          <!-- Category image -->
          <div class="content_scene_cat_bg" {if $category->id_image} style="background-image: url('{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'tm_category_default')|escape:'html':'UTF-8'}')" {/if}>
              <div class="container">
                <div class="cat_desc">
                  <span class="category-name">
                    {strip}
                      {$category->name|escape:'html':'UTF-8'}
                      {if $category->description}
                        {if isset($categoryNameComplement)}
                          {* {$categoryNameComplement|escape:'html':'UTF-8'} *}
                        {/if}
                      {/if}
                    {/strip}
                  </span>
                  <!--<div class="category-description">{* {$category->description} *}</div>-->
                </div>
              </div>
          </div>
        {/if}
      </div>
    {/if}
  {elseif $category->id}
    <p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
  {/if}
{/if}
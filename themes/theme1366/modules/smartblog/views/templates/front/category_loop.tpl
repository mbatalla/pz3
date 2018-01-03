<div itemtype="#" itemscope="" class="sdsarticleCat col-xs-12 col-sm-6 col-lg-4">
  <div id="smartblogpost-{$post.id_post}">
    {assign var="options" value=null}
    {$options.id_post = $post.id_post}
    {$options.slug = $post.link_rewrite}
    {assign var="options" value=null}
    {$options.id_post = $post.id_post}
    {$options.slug = $post.link_rewrite}
    {assign var="catlink" value=null}
    {$catlink.id_category = $post.id_category}
    {$catlink.slug = $post.cat_link_rewrite}
    <div class="articleContent">
      <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" itemprop="url" title="{$post.meta_title}" class="imageFeaturedLink post-image">
        {assign var="activeimgincat" value='0'}
        {$activeimgincat = $smartshownoimg}
        {if ($post.post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
          <img itemprop="image" alt="{$post.meta_title}" src="{$modules_dir}/smartblog/images/{$post.post_img}-single-default.jpg" class="imageFeatured img-responsive">
        {/if}
      </a>
      <h2 class='title_block_exclusive'><a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>{$post.meta_title}</a></h2>
      <div class="sdsarticle-des" itemprop="description">
        {assign var="options" value=null}
        {$options.id_post = $post.id_post}
        {$options.slug = $post.link_rewrite}
        {$post.short_description|truncate:145:'...'}
      </div>
      <a class="read-more link" title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{l s='Read more...' mod='smartblog'}</a>
    </div>
  </div>
</div>
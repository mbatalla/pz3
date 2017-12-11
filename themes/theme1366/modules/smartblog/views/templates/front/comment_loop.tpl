{if $comment.id_smart_blog_comment != ''}
  <ul class="commentList">
    <li id="comment-{$comment.id_smart_blog_comment}" class="even">
      <div class="well">
        <div class="comment-content clearfix">
          <img class="avatar" alt="Avatar" src="{$modules_dir}/smartblog/images/avatar/avatar-author-default.jpg">
          <div>
            <p><span class="name">{$childcommnets.name}</span> <span class="created" itemprop="commentTime">{$childcommnets.created|date_format}</span></p>
            <p>{$childcommnets.content}</p>
            {if Configuration::get('smartenablecomment') == 1}
              {if $comment_status == 1}
                <div class="reply">
                  <a onclick="return addComment.moveForm('comment-{$comment.id_smart_blog_comment}', '{$comment.id_smart_blog_comment}', 'respond', '{$smarty.get.id_post}')"  class="comment-reply-link link"><span>{l s='Reply' mod='smartblog'}</span></a>
                </div>
              {/if}
            {/if}
          </div>
        </div>
      </div>
      {if isset($childcommnets.child_comments)}
        {foreach from=$childcommnets.child_comments item=comment}
          {if isset($childcommnets.child_comments)}
            {include file="./comment_loop.tpl" childcommnets=$comment}
            {$i=$i+1}
          {/if}
        {/foreach}
      {/if}
    </li>
  </ul>
{/if} 
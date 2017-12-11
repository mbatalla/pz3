{*
* 2002-2016 TemplateMonster
*
* TM Social Feeds
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if $hook_content}
  <div class="socialfeedblock block {$hook_name|escape:'htmlall':'UTF-8'}">
    <div class="row hook_{$hook_name|escape:'htmlall':'UTF-8'}{if $hook_name == 'left_column' || $hook_name == 'right_column'} block_content{/if}">
      {foreach from=$hook_content item=content name=myLoop}
        <div class="item_{$smarty.foreach.myLoop.iteration|escape:'htmlall':'UTF-8'} col-xs-12">
          {if $content.item_type == 'twitter' && $global_twitter}
            <div class="twitter-socialfeed">
              <script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/widget.js"></script>
              <a class="twitter-timeline"
                href="https://twitter.com/twitterapi"
                data-widget-id="{$global_twitter_id|escape:'html':'UTF-8'}"
                {if isset($content.item_theme) && $content.item_theme}
                  data-theme="{$content.item_theme|escape:'html':'UTF-8'}"
                {/if}
                {if isset($content.item_width) && $content.item_width}
                  width="{$content.item_width|escape:'html':'UTF-8'}"
                {/if}
                {if isset($content.item_height) && $content.item_height}
                  height="{$content.item_height|escape:'html':'UTF-8'}"
                {/if}
                data-chrome="{if !$content.item_header}noheader{/if} {if !$content.item_footer}nofooter{/if} {if !$content.item_border}noborder{/if} {if !$content.item_scroll}noscrollbar{/if} {if !$content.item_background}transparent{/if}"
                data-show-replies="{if $content.item_replies == 1}true{else}false{/if}"
                {if isset($content.item_limit) && $content.item_limit}
                  data-tweet-limit="{$content.item_limit|escape:'html':'UTF-8'}"
                {/if}></a>
            </div>
          {elseif $content.item_type == 'facebook' && $global_facebook}
            <div class="facebook-socialfeed">
              <script type="text/javascript" src="{$module_dir|escape:'html':'UTF-8'}views/js/facebook.js"></script>
              <div class="fb-page"
              	data-href="{$global_facebook_id|escape:'html':'UTF-8'}"
                data-tabs="{if $content.item_scroll}timeline{else}messages{/if}"
                data-width="{$content.item_width|escape:'html':'UTF-8'}"
                data-height="{$content.item_height|escape:'html':'UTF-8'}"
                data-small-header="{if $content.item_header}true{else}false{/if}"
                data-adapt-container-width="true"
                data-show-facepile="{if $content.item_replies == 1}true{else}false{/if}"
                data-small-header="{if $content.item_header}true{else}false{/if}"
                data-hide-cover="{if $content.item_border}true{else}false{/if}">
                <div class="fb-xfbml-parse-ignore"></div>
              </div>
            </div>
          {elseif $content.item_type == 'pinterest' && $global_pinterest}
            <div class="pinterest-socialfeed">
              <script type="text/javascript" src="{$module_dir|escape:'html':'UTF-8'}views/js/pinterest.js"></script>
              <a
                data-pin-do="embedUser"
                href="{$global_pinterest_id|escape:'html':'UTF-8'}"
                {if isset($content.item_col_width) && $content.item_col_width}
                  data-pin-scale-width="{$content.item_col_width|escape:'html':'UTF-8'}"
                {/if}
                {if isset($content.item_height) && $content.item_height}
                  data-pin-scale-height="{$content.item_height|escape:'html':'UTF-8'}"
                {/if}
                {if isset($content.item_width) && $content.item_width}
                  data-pin-board-width ="{$content.item_width|escape:'html':'UTF-8'}"
                {/if}
              ></a>
            </div>
          {elseif $content.item_type == 'instagram' && $global_instagram}
            {if $content.item_limit && $content.item_limit !=''}{assign var="limit" value=$content.item_limit}{else}{assign var="limit" value=20}{/if}
            <div class="instagram-widget">
              <h4>{$meta_title|escape:'html':'UTF-8'} {l s='on Instagram' mod='tmsocialfeeds'}</h4>
              <p>{l s='Instagram description' mod='tmsocialfeeds'}</p>
              <div id="instafeed_{$content.hook|escape:'html':'UTF-8'}" class="data">
                <ul class="instagram_items row">
                {foreach from=$instagram_param.media.nodes item=media name=media}
                  {if $smarty.foreach.media.iteration <= $content.item_limit}
                    <li class="col-xs-4 col-md-2">
                      <a class="instagram_link" href="https://www.instagram.com/p/{$media.code}/" target="_blank" rel="nofollow" style="background: url('{$media.thumbnail_src}') no-repeat center"></a>
                    </li>
                  {/if}
                {/foreach}
                </ul>
              </div>
            </div>
          {/if}
        </div>
      {/foreach}
    </div>
  </div>
{/if}
{addJsDef l_code = $l_code}
{if isset($htmlitems) && $htmlitems}
  {assign var='hookName' value={$hook|escape:'htmlall':'UTF-8'}}
  <div id="htmlcontent_{$hookName}">
    <ul class="htmlcontent-home {if $hookName =='footer'}clearfix row{/if}">
      {foreach name=items from=$htmlitems item=hItem}
        <li class="htmlcontent-item-{$smarty.foreach.items.iteration|escape:'htmlall':'UTF-8'}{if $hookName =='footer'} col-xs-4{/if} {if $hookName =='home'} wow {if $smarty.foreach.items.iteration % 2} fadeInLeft {else} fadeInRight {/if} {/if}">
          {if $hookName =='top'}
            {if $hItem.title && $hItem.title_use == 1}
              <h3 class="item-title">{$hItem.title|escape:'htmlall':'UTF-8'}</h3>
            {/if}
            {if $hItem.image}
              <img src="{$link->getMediaLink("`$module_dir`img/`$hItem.image`")}" class="item-img" title="{$hItem.title|escape:'htmlall':'UTF-8'}" alt="{$hItem.title|escape:'htmlall':'UTF-8'}" width="{if $hItem.image_w}{$hItem.image_w|intval}{else}100%{/if}" height="{if $hItem.image_h}{$hItem.image_h|intval}{else}100%{/if}"/>
            {/if}
            {if $hItem.html || $hItem.url}
              <div class="item-html">
                {if $hItem.html}
                  {$hItem.html}
                {/if}
                {if $hItem.url}
                  <a href="{$hItem.url|escape:'htmlall':'UTF-8'}" class="item-link"{if $hItem.target == 1} onclick="return !window.open(this.href);"{/if} title="{$hItem.title|escape:'htmlall':'UTF-8'}"></a>
                {/if}
              </div>
            {/if}
          {elseif $hookName =='home'}
            {if $hItem.title && $hItem.title_use == 1}
              <h3 class="item-title">{$hItem.title|escape:'htmlall':'UTF-8'}</h3>
            {/if}
            {if $hItem.image}
              {if $hItem.url}
                <a href="{$hItem.url|escape:'htmlall':'UTF-8'}" class="item-link"{if $hItem.target == 1} onclick="return !window.open(this.href);"{/if} title="{$hItem.title|escape:'htmlall':'UTF-8'}">
              {/if}
                  <div class="img-wrapper" style="width: {($hItem.image_w|intval + 40) / 177 * 10}%"><img src="{$link->getMediaLink("`$module_dir`img/`$hItem.image`")}" class="item-img" title="{$hItem.title|escape:'htmlall':'UTF-8'}" alt="{$hItem.title|escape:'htmlall':'UTF-8'}" width="{if $hItem.image_w}{$hItem.image_w|intval}{else}100%{/if}" height="{if $hItem.image_h}{$hItem.image_h|intval}{else}100%{/if}"/></div>
              {if $hItem.url}
                </a>
              {/if}
            {/if}
            {if $hItem.html || $hItem.url}
              <div class="item-html">
                {if $hItem.html}
                  {$hItem.html}
                {/if}
              </div>
            {/if}
          {else}
            {if $hItem.url}
              <a href="{$hItem.url|escape:'htmlall':'UTF-8'}" class="item-link"{if $hItem.target == 1} onclick="return !window.open(this.href);"{/if} title="{$hItem.title|escape:'htmlall':'UTF-8'}">
            {/if}
            {if $hItem.image}
              <img src="{$link->getMediaLink("`$module_dir`img/`$hItem.image`")}" class="item-img" title="{$hItem.title|escape:'htmlall':'UTF-8'}" alt="{$hItem.title|escape:'htmlall':'UTF-8'}" width="{if $hItem.image_w}{$hItem.image_w|intval}{else}100%{/if}" height="{if $hItem.image_h}{$hItem.image_h|intval}{else}100%{/if}"/>
            {/if}
            {if $hItem.title && $hItem.title_use == 1}
              <h3 class="item-title">{$hItem.title|escape:'htmlall':'UTF-8'}</h3>
            {/if}
            {if $hItem.html}
              <div class="item-html">
                {$hItem.html}
              </div>
            {/if}
            {if $hItem.url}
              </a>
            {/if}
          {/if}
        </li>
      {/foreach}
    </ul>
  </div>
{/if}
{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
  {if isset($params.type) && $params.type == 'image'}
    <div style="width: 100px; height: 100px; overflow: hidden; padding: 5px;">
      <img src="{$tr.$key|escape:'htmlall':'UTF-8'}" style="width: 100%"/>
    </div>
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
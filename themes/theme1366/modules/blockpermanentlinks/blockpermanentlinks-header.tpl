<!-- Block permanent links module HEADER -->
<ul id="header_links">
  <li id="header_link_home">
    <a {if $page_name =='index'}class="active"{/if} href="{$link->getPageLink('index', true)|escape:'html'}" title="{l s='home' mod='blockpermanentlinks'}">{l s='home' mod='blockpermanentlinks'}</a>
  </li>
  <li id="header_link_contact">
    <a {if $page_name =='contact'}class="active"{/if} href="{$link->getPageLink('contact', true)|escape:'html'}" title="{l s='contact' mod='blockpermanentlinks'}">{l s='contact' mod='blockpermanentlinks'}</a>
  </li>
  <li id="header_link_sitemap">
    <a {if $page_name =='sitemap'}class="active"{/if} href="{$link->getPageLink('sitemap')|escape:'html'}" title="{l s='sitemap' mod='blockpermanentlinks'}">{l s='sitemap' mod='blockpermanentlinks'}</a>
  </li>
  {if class_exists(smartblog)}
    <li id="header_link_blog">
      <a {if $page_name =='module-smartblog-category'}class="active"{/if} href="{$link->getPageLink('smartblog')|escape:'html'}" title="{l s='blog' mod='blockpermanentlinks'}">{l s='blog' mod='blockpermanentlinks'}</a>
    </li>
  {/if}
</ul>
<!-- /Block permanent links module HEADER -->
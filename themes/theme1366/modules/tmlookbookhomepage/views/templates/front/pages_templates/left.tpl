{**
* 2002-2016 TemplateMonster
*
* TM Header Account Block
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
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{capture name=path}
  <a href="{Tmlookbook::getTMLookbookLink()|escape:'html':'UTF-8'}">
    {l s='All LookBooks' mod='tmlookbook'}
  </a>
  <span class="navigation-pipe">
			{$navigationPipe|escape:'htmlall':'UTF-8'}
		</span>
  <span class="navigation_page">
    {$tm_page_name|escape:'htmlall':'UTF-8'}
  </span>
{/capture}

{if $tabs && count($tabs) > 0}
<div class="lookbook-default tm-lookbook-block">
  <ul class="tm-lookbook-slider">
    {foreach from=$tabs item=tab name=tab}
      <li class="row">
        <div class="col-lg-8">
          <!--<div class="caption">
            <h3>{* {$tab.name|escape:'html':'UTF-8'} *}</h3>
            <p>{* {$tab.description} *}</p>
          </div>-->
          <div class="hotSpotWrap hotSpotWrap_{$tab.id_tab|escape:'htmlall':'UTF-8'}_{$smarty.foreach.tab.iteration|escape:'htmlall':'UTF-8'}">
            <img src="{$tab.image|escape:'htmlall':'UTF-8'}" alt="">
            {if isset($tab.hotspots)}
              <script>
                {literal}
                $(document).ready(function(){
                  var items = [
                    {/literal}
                    {foreach from=$tab.hotspots item=hotspot}
                    {assign var=name value=$hotspot.name}
                    {assign var=description value=$hotspot.description}
                    {assign var=type value=$hotspot.type}
                    {if $type == 1}
                    {assign var=products value=$hotspot.product}
                    {/if}
                    {assign var=content value={include '../tooltip.tpl'}}
                    {literal}
                    {
                      content: '{/literal}{$content|escape:'javascript':'UTF-8'}{literal}',
                      coordinates: {/literal}{$hotspot.coordinates|escape:'quotes':'UTF-8'}{literal}
                    },
                    {/literal}
                    {/foreach}
                    {literal}
                  ];
                  $('.hotSpotWrap_{/literal}{$tab.id_tab|escape:'htmlall':'UTF-8'}_{$smarty.foreach.tab.iteration|escape:'htmlall':'UTF-8'}{literal}').hotSpot({
                    items: items
                  });
                });
                {/literal}
              </script>
            {/if}
            <div class="slide-controls">
              <span class="prev"></span>
              <span class="next"></span>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="row">
            {if isset($tab.products) && $tab.products}
              {assign var=products value=$tab.products}
              <ul class="product_list">
                {foreach from=$products item=product name=products}
                  <li class="col-xs-6 col-sm-3 col-lg-6">
                    {include './product.tpl'}
                  </li>
                {/foreach}
              </ul>
            {/if}
          </div>
        </div>
      </li>
    {/foreach}
  </ul>
</div>
<script>
  $(document).ready(function(){
    var lookbook_slider = $('.tm-lookbook-slider');
    lookbook_slider.bxSlider({
      responsive:true,
      useCSS: false,
      minSlides: 1,
      maxSlides: 1,
      slideWidth: 2560,
      slideMargin: 0,
      moveSlides: 1,
      mode: 'fade',
      pager: false,
      autoHover: false,
      speed: 500,
      pause: 3000,
      controls: true,
      autoControls: true,
      startText:'',
      stopText:'',
      prevText:'',
      nextText:'',
      nextSelector: '.slide-controls .next',
      prevSelector: '.slide-controls .prev'
    });

    $('.point[data-toggle=popover]').on('shown.bs.popover', function () {
      var $t = $(this);
      $t.addClass('active');
      var top = ($t.height()/2) + $t[0].offsetTop;
      $('+.popover', $t).css('top',top + 'px');
    });

    $('.point[data-toggle=popover]').on('hide.bs.popover', function() {
      $(this).removeClass('active');
    });
  });
</script>
{else}
  <div class="alert alert-warning" role="alert">
    {l s='No one lookbook added'}
  </div>
{/if}
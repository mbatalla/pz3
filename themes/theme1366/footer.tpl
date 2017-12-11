{if !isset($content_only) || !$content_only}
              </div><!-- #center_column -->
              {if isset($left_column_size) && !empty($left_column_size)}
                <div id="left_column" class="column col-xs-12 col-sm-{$left_column_size|intval}">{$HOOK_LEFT_COLUMN}</div>
              {/if}
              </div><!--.row-->
            </div><!--.large-left-->
            {if isset($right_column_size) && !empty($right_column_size)}
              <div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
            {/if}
            </div><!-- .row -->
          </div><!-- .container -->
        </div><!-- #columns -->
        {assign var='displayMegaHome' value={hook h='tmMegaLayoutHome'}}
        {if isset($HOOK_HOME) && $HOOK_HOME|trim}
          {if $displayMegaHome}
            {hook h='tmMegaLayoutHome'}
          {else}
            <div class="container">
              {$HOOK_HOME}
            </div>
          {/if}
        {/if}
      </div><!-- .columns-container -->
      {assign var='displayMegaFooter' value={hook h='tmMegaLayoutFooter'}}
      {if isset($HOOK_FOOTER) || $displayMegaFooter}
        <!-- Footer -->
        <div class="footer-container">
          <footer id="footer">
            {if $displayMegaFooter}
              {$displayMegaFooter}
            {else}
              <div class="container">
                {$HOOK_FOOTER}
              </div>
            {/if}
          </footer>
        </div><!-- #footer -->
      {/if}
    </div><!-- #page -->
  {/if}

  {include file="$tpl_dir./global.tpl"}
  {literal}
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-32000308-6"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-32000308-6');

  /* <![CDATA[ */
    var google_conversion_id = 837584716;
    var google_conversion_label = "FydDCKKa3ncQzI6yjwM";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
  /* ]]> */
  </script>
  <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
  <noscript>
    <div style="display:inline;">
      <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/837584716/?label=FydDCKKa3ncQzI6yjwM&amp;guid=ON&amp;script=0"/>
      <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/837584716/?guid=ON&amp;script=0"/>
    </div>
  </noscript>
  {/literal}
  </body>
</html>
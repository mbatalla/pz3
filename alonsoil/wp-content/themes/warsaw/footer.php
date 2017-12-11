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
</body>
</html>
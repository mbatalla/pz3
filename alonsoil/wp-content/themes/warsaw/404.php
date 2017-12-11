<?php
$options = _WSH()->option();
    get_header(); 
?>

<!--Page Title-->
<section class="page-title">
    <div class="auto-container">
        <!--Bread Crumb-->
        <div class="bread-crumb">
            <div class="links-outer">
            	<?php echo balanceTags(warsaw_get_the_breadcrumb()); ?>
            </div>
        </div>
    </div>
</section>

<!--  Your page Content End Here -->
<div class="error_page container">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 shop_aside pull-right">  <!-- /.shop aside use for styling input search box -->
            <img src="<?php echo esc_url(get_template_directory_uri());?>/images/404/2.png" alt="<?php esc_html_e('images', 'warsaw');?>" class="img-responsive">
            <p><?php esc_html_e('The page you are looking for no longer exists. Perhaps you can return back to the sites homepage and see if you can find what you are looking for. Or, you can try finding it with the information below.', 'warsaw');?></p>
            <div class="input-group"> <!--input-group -->
                <?php get_template_part('searchform2')?>
            </div><!-- /input-group -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="transition-ease"><i class="fa fa-angle-double-left"></i><?php esc_html_e('Back to Home', 'warsaw')?> </a>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
            <img src="<?php echo esc_url(get_template_directory_uri());?>/images/404/1.png" alt="<?php esc_html_e('image', 'warsaw');?>" class="img-responsive">
        </div>
        
    </div> <!-- /row -->
</div> <!-- /error_page -->
<!--  Your Blog Content End Here -->  		
<?php get_footer(); ?>
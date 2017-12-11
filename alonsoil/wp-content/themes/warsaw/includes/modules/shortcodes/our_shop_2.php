<?php  
   $count = 1;
   $query_args = array('post_type' => 'product' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
   
   if( $cat ) $query_args['product_cat'] = $cat;
   $query = new WP_Query($query_args) ; 
   
   ob_start() ;?>
   
<?php if($query->have_posts()): ?>
<!--Products Section Two-->
<section class="products-section-two">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title); ?></h2>
        </div>
        
        <div class="row clearfix">
        	<?php while($query->have_posts()): $query->the_post();
				global $post;
			?>
            <!--Product Tyle TWo-->
            <div class="product-style-two col-md-6 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <div class="clearfix">
                        <!--Image Column-->
                        <div class="image-column col-lg-5 col-md-12 col-sm-12 col-xs-12">
                        <figure class="image"><a href="<?php esc_url(get_permalink(get_the_id())); ?>"><?php the_post_thumbnail('warsaw_266x280', array('class' => 'img-responsive'));?></a></figure>
                        </div>
                        <!--Content Column-->
                        <div class="content-column col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <div class="inner">
                                <h3><?php the_title();?></h3>
                                <div class="price"><?php woocommerce_template_loop_price(); ?></div>
                                <div class="text"><?php echo wp_kses_post(warsaw_trim(get_the_content(), 8));?></div>
                                <div class="link-box"><a href="<?php esc_url(get_permalink(get_the_id())); ?>" class="theme-btn btn-style-four"><?php esc_html_e('Shop Now', 'warsaw'); ?></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <!--Button Outer-->
        <div class="more-btn-outer text-center"><a href="#" class="theme-btn btn-style-four"><?php esc_html_e('View More', 'warsaw'); ?></a></div>
            
    </div>
</section>

<?php endif; ?>
<?php 
	wp_reset_postdata();
   $output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
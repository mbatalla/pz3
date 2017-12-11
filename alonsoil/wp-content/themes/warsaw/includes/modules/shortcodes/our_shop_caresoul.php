<?php  
   $count = 1;
   $query_args = array('post_type' => 'product' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
   
   if( $cat ) $query_args['product_cat'] = $cat;
   $query = new WP_Query($query_args) ; 
   
   ob_start() ;?>

<?php if($query->have_posts()):  ?>

<!--Deal of the Day-->
<section class="deal-of-day">
    <div class="auto-container">
        
        <!--Title Box-->
        <div class="title-box">
            <h3><?php echo balanceTags($sub_title); ?></h3>
            <h2><?php echo balanceTags($title); ?></h2>
        </div>
        
        <!--Carousel Outer-->
        <div class="carousel-outer">
            <div class="single-item-carousel">
            	<?php while($query->have_posts()): $query->the_post();
					global $post;
					$post_thumbnail_id = get_post_thumbnail_id($post->ID);
					$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
				?>
                <!--Slide Item-->
                <div class="slide-item">
                    <div class="row clearfix">
                        <!--Image Column-->
                        <div class="image-column col-md-8 col-sm-7 col-xs-12">
                            <figure class="image-box"><a href="<?php echo esc_url($post_thumbnail_url);?>" class="lightbox-image"><?php the_post_thumbnail('warsaw_600x450', array('class' => 'img-responsive'));?></a></figure>
                        </div>
                        <!--Content Column-->
                        <div class="content-column col-md-4 col-sm-5 col-xs-12">
                            <div class="inner">
                                <div class="prod-title"><?php the_title();?></div>
                                <div class="price"><?php esc_html_e('Price:', 'warsaw'); ?> <?php woocommerce_template_loop_price(); ?></div>
                                <div class="text"><?php echo wp_kses_post(warsaw_trim(get_the_content(), 8));?></div>
                                <div class="options clearfix">
                                    <a href="#" class="theme-btn normal-btn"><span class="flaticon-shopping-bag"></span></a>
                                    <a href="<?php esc_url(get_permalink(get_the_id())); ?>" class="theme-btn shop-btn"><?php esc_html_e('Shop Now', 'warsaw'); ?></a>
                                    <a href="#" class="theme-btn normal-btn"><span class="flaticon-connection"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile;?>
            </div>
        </div>
        
    </div>
</section>

<?php endif; ?>
<?php 
	wp_reset_postdata();
   $output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
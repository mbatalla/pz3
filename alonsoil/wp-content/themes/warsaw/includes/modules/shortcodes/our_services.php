<?php  
   $count = 1;
   $query_args = array('post_type' => 'bunch_services' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
   if( $cat ) $query_args['services_category'] = $cat;
   $query = new WP_Query($query_args) ; 
   ob_start() ;?> 
<?php if($query->have_posts()):  ?>

<!--Welcome Section-->
<section class="welcome-section">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        
        <div class="row clearfix">
            <?php while($query->have_posts()): $query->the_post();
				global $post ; 
				$services_meta = _WSH()->get_meta();
			?>
            <!--Featured Block One-->
            <div class="featured-block-one col-md-4 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <figure class="image"><?php the_post_thumbnail('warsaw_150x150');?></figure>
                    <div class="content">
                        <h3><?php the_title();?></h3>
                        <div class="text"><?php echo wp_kses_post(warsaw_trim(get_the_content(), $text_limit));?></div>
                        <a href="<?php echo esc_url(warsaw_set($services_meta, 'ext_url'));?>" class="read-more"><?php esc_html_e('Read More', 'warsaw');?></a>
                    </div>
                </div>
            </div>
            <?php endwhile;?>
        </div>
        
    </div>
</section>

<?php endif; ?>
<?php 
	wp_reset_postdata();
   $output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
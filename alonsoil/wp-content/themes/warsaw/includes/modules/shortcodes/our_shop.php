<?php ob_start() ;?>

<!--Gallery Section-->
<section class="gallery-section">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title); ?></h2>
        </div>
        
        <div class="mixitup-gallery">
            <!--Filter-->
            <div class="filters text-center">
                <ul class="filter-tabs filter-btns clearfix">
                	<?php $count =0; $product_tabs = (array)json_decode(urldecode($prdct_tabs));
					if( $product_tabs && is_array($product_tabs) ):
					 
					foreach( (array)$product_tabs as $key => $value ):
					?>
					<li data-filter=".fruits<?php echo esc_attr($key);?>" class="filter <?php if($count == 0) echo ' active ';?>" data-role="button"><?php echo balanceTags(warsaw_set($value, 'tab_title'));?></li>
					<?php $count++; endforeach; endif;?>
                </ul>
            </div>
            <!--Filter List-->
            
            <div class="filter-list row clearfix">
				<?php $product_tabs = (array)json_decode(urldecode($prdct_tabs));
					if( $product_tabs && is_array($product_tabs) ):
					 
					foreach( (array)$product_tabs as $key => $value ):
					$num = warsaw_set($value, 'num');
					$cat = warsaw_set($value, 'cat');
					$sort = warsaw_set($value, 'sort');
					$order = warsaw_set($value, 'order');
				?>
            	<?php  
				   $count = 1;
				   $query_args = array('post_type' => 'product' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
				   
				   if( $cat ) $query_args['product_cat'] = $cat;
				   $query = new WP_Query($query_args); 
					
					$modal_content = array();
					
					if($query->have_posts()):
					 
					while($query->have_posts()): $query->the_post();
					global $post;
					global $product; 
					$product_meta = _WSH()->get_meta();
					
					$post_thumbnail_id = get_post_thumbnail_id($post->ID);
					$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
				?>
                <!--Default Food Item-->
                <div class="col-md-4 col-sm-6 col-xs-12 default-food-item mix mix_all fruits<?php echo esc_attr($key);?>">
                    <div class="inner-box">
                        <div class="image-box">
                            <figure class="image"><a class="lightbox-image option-btn 4" data-fancybox-group="example-gallery" href="<?php echo esc_url($post_thumbnail_url);?>" title=""><?php the_post_thumbnail('warsaw_370x310', array('class' => 'img-responsive'));?></a></figure>
                            <div class="lower-content">
                                <h3><a href="<?php esc_url(get_permalink(get_the_id())); ?>"><?php the_title();?></a></h3>
                                <div class="price"><?php woocommerce_template_loop_price(); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; endif; wp_reset_postdata();?>
                <?php endforeach; endif;?>
			</div>
            
            <!--Button Outer-->
            <div class="more-btn-outer text-center"><a href="#" class="theme-btn btn-style-four"><?php esc_html_e('View More', 'warsaw'); ?></a></div>

        </div>
        
    </div>
</section>

<?php 
	wp_reset_postdata();
   $output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
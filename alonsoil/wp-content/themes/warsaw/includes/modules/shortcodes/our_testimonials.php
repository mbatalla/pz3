<?php  
   $count = 1;
   $query_args = array('post_type' => 'bunch_testimonials' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
   if( $cat ) $query_args['testimonials_category'] = $cat;
   $query = new WP_Query($query_args) ; 
   ob_start() ;?>
<?php if($query->have_posts()):  ?>

<!--Testimonials Section-->
<section class="testimonial-section" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img));?>')">
    
    <div class="oval-cut"></div>
    
    <div class="auto-container">
        <div class="carousel-outer">
            <div class="icon-box"><span class="flaticon-blocks-with-angled-cuts"></span></div>
            
            <div class="single-item-carousel">
                <?php while($query->have_posts()): $query->the_post();
					global $post ; 
					$testimonial_meta = _WSH()->get_meta();
				?>
                <!--Slide Item-->
                <div class="slide-item">
                    <div class="text-content"><?php echo wp_kses_post(warsaw_trim(get_the_content(), $text_limit));?></div>
                    <div class="quote-info">
                        <figure class="author-thumb img-circle"><?php the_post_thumbnail('warsaw_110x110', array('class' => 'img-circle'));?></figure>
                        <h4><?php the_title();?></h4>
                        <div class="designation"><?php echo wp_kses_post(warsaw_set($testimonial_meta, 'designation'));?></div>
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
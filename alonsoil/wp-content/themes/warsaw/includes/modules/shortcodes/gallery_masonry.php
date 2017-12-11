<?php  
   $count = 1;
   $query_args = array('post_type' => 'bunch_gallery' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
   if( $cat ) $query_args['gallery_category'] = $cat;
   $query = new WP_Query($query_args) ; 
   ob_start() ;?>   
<?php if($query->have_posts()):  ?>   

<!--Gallery Section Three-->
<section class="gallery-section-three">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>    
    
        <div class="row clearfix">
            
            <div class="col-md-6 col-sm-12 col-xs-12">
                <!--Default Portfolio Item -->
                <div class="default-portfolio-item">
                    <div class="inner-box">
                        <figure class="image-box"><img src="<?php echo esc_url(wp_get_attachment_url($image));?>" alt=""></figure>
                        <div class="overlay-box">
                            <div class="overlay-inner">
                                <div class="overlay-content">
                                    <a href="<?php echo esc_url(wp_get_attachment_url($image));?>" class="lightbox-image option-btn theme-btn" data-fancybox-group="fancybox"><span class="fa fa-search"></span></a>
                                    <a href="<?php echo esc_url($btn_link);?>" class="option-btn theme-btn"><span class="fa fa-link"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="row clearfix">
                    <?php while($query->have_posts()): $query->the_post();
						global $post ; 
						$gallery_meta = _WSH()->get_meta();
					?>
					<?php 
						$post_thumbnail_id = get_post_thumbnail_id($post->ID);
						$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
					?>
                    <!--Default Portfolio Item -->
                    <div class="default-portfolio-item col-md-6 col-sm-6 col-xs-12">
                        <div class="inner-box">
                            <figure class="image-box"><?php the_post_thumbnail('270x250');?></figure>
                            <div class="overlay-box">
                                <div class="overlay-inner">
                                    <div class="overlay-content">
                                        <a href="<?php echo esc_url($post_thumbnail_url);?>" class="lightbox-image option-btn theme-btn" data-fancybox-group="fancybox"><span class="fa fa-search"></span></a>
                                        <a href="<?php echo esc_url(warsaw_set($gallery_meta, 'ext_url'));?>" class="option-btn theme-btn"><span class="fa fa-link"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile;?>
                </div>
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
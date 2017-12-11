<?php
ob_start() ;?>

<!--sponsors style one-->
<section class="sponsors-style-one alternate">
	<!--Floted Image Left-->
    <figure class="floated-image-left wow slideInLeft" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url((wp_get_attachment_url($image)));?>" alt=""></figure>
    
    <div class="auto-container">
        
        <div class="sponsors-outer">
            <!--Sponsors Carousel-->
            <ul class="sponsors-carousel">
                <?php $skills_array = (array)json_decode(urldecode($partners));
					if( $skills_array && is_array($skills_array) ): 
					foreach( (array)$skills_array as $value ):
				?>
                <li class="slide-item"><figure class="image-box"><a href="<?php echo esc_url(warsaw_set( $value, 'ext_url' )); ?>"><img src="<?php echo esc_url(wp_get_attachment_url(warsaw_set( $value, 'image' ))); ?>" alt=""></a></figure></li>
                <?php endforeach; endif;?>
            </ul>
        </div>
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
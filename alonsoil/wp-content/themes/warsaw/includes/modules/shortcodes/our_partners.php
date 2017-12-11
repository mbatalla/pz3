<?php
ob_start() ;?>

<!--sponsors style one-->
<section class="sponsors-style-one <?php if($style_two == true) echo 'alternate';?>">
	
    <div class="auto-container">
        <!--Section Title-->
        <?php if(!($style_two == true)):?>
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        <?php endif;?>
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
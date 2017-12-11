<?php
ob_start() ;?>

<!--Fluid Section One-->
<section class="fluid-section-one" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img));?>')">
    <div class="oval-cut"></div>
    
    <!--FLoated Image Left-->
    <figure class="floated-image-left wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image1));?>" alt=""></figure>
    <!--FLoated Image Right-->
    <figure class="floated-image-right wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image2));?>" alt=""></figure>
    
    <div class="outer-box clearfix">
        <!--Left Column-->
        <div class="left-column">
            <div class="clearfix">
                <div class="inner-box">
                    <h3><?php echo balanceTags($title1);?></h3>
                    <h5><?php echo balanceTags($sub_title1);?></h5>
                    <a href="<?php echo esc_url($btn_link1);?>" class="theme-btn btn-style-three"><?php esc_html_e('Shop Now', 'warsaw');?></a>
                </div>
            </div>
        </div>	
        
        <!--Right Column-->
        <div class="right-column">
            <div class="clearfix">
                <div class="inner-box">
                    <h3><?php echo balanceTags($title2);?></h3>
                    <h5><?php echo balanceTags($sub_title2);?></h5>
                    <a href="<?php echo esc_url($btn_link2);?>" class="theme-btn btn-style-three"><?php esc_html_e('Track Now', 'warsaw');?></a>
                </div>
            </div>
        </div>	
        
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
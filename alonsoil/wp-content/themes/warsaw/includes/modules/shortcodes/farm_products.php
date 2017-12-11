<?php
ob_start() ;?>

<!--Products Section One-->
<section class="products-section-one">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        
        <div class="row clearfix">
            
            <!--Product Style One-->
            <div class="product-style-one orange-theme col-md-4 col-sm-6 col-xs-12">
                <div class="inner-box" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img1));?>')">
                    <div class="content">
                        <h3><?php echo balanceTags($title1);?></h3>
                        <div class="text"><?php echo balanceTags($price1);?></div>
                        <a href="<?php echo esc_url($btn_link1);?>" class="default-link"><?php esc_html_e('Shop Now', 'warsaw');?></a>
                    </div>
                    <figure class="food-image wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image1));?>" alt=""></figure>
                </div>
            </div>
            
             <!--Product Style One-->
            <div class="product-style-one light-theme col-md-4 col-sm-6 col-xs-12">
                <div class="inner-box" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img2));?>')">
                    <div class="content">
                        <h3><?php echo balanceTags($title2);?></h3>
                        <div class="text"><?php echo balanceTags($price2);?></div>
                        <a href="<?php echo esc_url($btn_link2);?>" class="default-link"><?php esc_html_e('Shop Now', 'warsaw');?></a>
                    </div>
                    <figure class="food-image wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image2));?>" alt=""></figure>
                </div>
            </div>
            
             <!--Product Style One-->
            <div class="product-style-one green-theme col-md-4 col-sm-6 col-xs-12">
                <div class="inner-box" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img3));?>')">
                    <div class="content">
                        <h3><?php echo balanceTags($title3);?></h3>
                        <div class="text"><?php echo balanceTags($price3);?></div>
                        <a href="<?php echo esc_url($btn_link3);?>" class="default-link"><?php esc_html_e('Shop Now', 'warsaw');?></a>
                    </div>
                    <figure class="food-image wow fadeInUp" data-wow-delay="600ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image3));?>" alt=""></figure>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
   
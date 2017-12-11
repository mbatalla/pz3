<?php
ob_start() ;?>

<!--Featured Food Items-->
<section class="featured-food-items">
    <div class="auto-container">
        
        <!--Featured Items Row-->
        <div class="featured-item-row">
            <div class="row clearfix">
                <!--Featured Column-->
                <div class="featured-column green-theme left-aligned col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-box" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img1));?>')">
                        <div class="content-box">
                            <div class="upper-title"><?php echo balanceTags($upper_title1);?></div>
                            <div class="text-content">
                                <div class="sub-title"><?php echo balanceTags($sub_title1);?></div>
                                <h3><?php echo balanceTags($title1);?></h3>
                                <a href="<?php echo esc_url($btn_link1);?>" class="theme-btn btn-style-five"><?php esc_html_e('Shop Now', 'warsaw');?></a>
                            </div>
                        </div>
                        <div class="bottom-image wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($fruit_img));?>" alt=""></div>
                    </div>
                </div>
                
                <!--Products-->
                <div class="products col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                    
                        <!--Default Food Item-->
                        <div class="col-md-6 col-sm-6 col-xs-12 default-food-item">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image"><a class="lightbox-image option-btn" data-fancybox-group="example-gallery" href="<?php echo esc_url(wp_get_attachment_url($image1));?>" title="<?php esc_html_e('Image Title Here', 'warsaw');?>"><img src="<?php echo esc_url(wp_get_attachment_url($image1));?>" alt=""></a></figure>
                                    <div class="lower-content">
                                        <h3><a href="<?php echo esc_url($ext_url1);?>"><?php echo balanceTags($fruit_title1);?></a></h3>
                                        <div class="price"><?php echo balanceTags($price1);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Default Food Item-->
                        <div class="col-md-6 col-sm-6 col-xs-12 default-food-item">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image"><a class="lightbox-image option-btn" data-fancybox-group="example-gallery" href="<?php echo esc_url(wp_get_attachment_url($image2));?>" title="<?php esc_html_e('Image Title Here', 'warsaw');?>"><img src="<?php echo esc_url(wp_get_attachment_url($image2));?>" alt=""></a></figure>
                                    <div class="lower-content">
                                        <h3><a href="<?php echo esc_url($ext_url2);?>"><?php echo balanceTags($fruit_title2);?></a></h3>
                                        <div class="price"><?php echo balanceTags($price2);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>    
                </div>
                
            </div>
        </div>
        
        <!--Featured Items Row-->
        <div class="featured-item-row">
            <div class="row clearfix">
                <!--Featured Column-->
                <div class="featured-column orange-theme right-aligned col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-box" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img2));?>">
                        <div class="content-box">
                            <div class="upper-title"><?php echo balanceTags($upper_title2);?></div>
                            <div class="text-content">
                                <div class="sub-title"><?php echo balanceTags($sub_title2);?></div>
                                <h3><?php echo balanceTags($title2);?></h3>
                                <a href="<?php echo esc_url($btn_link2);?>" class="theme-btn btn-style-five"><?php esc_html_e('Shop Now', 'warsaw');?></a>
                            </div>
                        </div>
                        <div class="bottom-image wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($vegi_img));?>" alt=""></div>
                    </div>
                </div>
                
                <!--Products-->
                <div class="products col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                    
                        <!--Default Food Item-->
                        <div class="col-md-6 col-sm-6 col-xs-12 default-food-item">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image"><a class="lightbox-image option-btn" data-fancybox-group="example-gallery" href="<?php echo esc_url(wp_get_attachment_url($image3));?>" title="<?php esc_html_e('Image Title Here', 'warsaw');?>"><img src="<?php echo esc_url(wp_get_attachment_url($image3));?>" alt=""></a></figure>
                                    <div class="lower-content">
                                        <h3><a href="<?php echo esc_url($ext_url3);?>"><?php echo balanceTags($vegi_title1);?></a></h3>
                                        <div class="price"><?php echo balanceTags($price3);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Default Food Item-->
                        <div class="col-md-6 col-sm-6 col-xs-12 default-food-item">
                            <div class="inner-box">
                                <div class="image-box">
                                    <figure class="image"><a class="lightbox-image option-btn" data-fancybox-group="example-gallery" href="<?php echo esc_url(wp_get_attachment_url($image4));?>" title="<?php esc_html_e('Image Title Here', 'warsaw');?>"><img src="<?php echo esc_url(wp_get_attachment_url($image4));?>" alt=""></a></figure>
                                    <div class="lower-content">
                                        <h3><a href="<?php echo esc_url($ext_url4);?>"><?php echo balanceTags($vegi_title2);?></a></h3>
                                        <div class="price"><?php echo balanceTags($price4);?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>    
                </div>
                
            </div>
        </div>
        
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
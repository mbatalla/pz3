<?php
ob_start() ;?>

<!--Featured Package Section-->
<section class="featured-package-section">
    <div class="auto-container">
        <div class="package-box">
            <div class="inner">
                <div class="row clearfix">
                    <!--Image Column-->
                    <div class="image-column col-md-6 col-sm-6 col-xs-12">
                        <figure class="image-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image));?>" alt=""></figure>
                    </div>
                    <!--Content Column-->
                    <div class="content-column col-md-6 col-sm-12 col-xs-12">
                        <div class="content-box">
                            <div class="info">
                                <div class="sub-title"><?php echo balanceTags($sub_title);?></div>
                                <div class="title"><?php echo balanceTags($title);?></div>
                                <div class="pricing"><?php echo balanceTags($contents);?></div>
                            </div>
                            <div class="text"><?php echo balanceTags($text);?></div>
                            
                            <!--Countdown Timer-->
                            <div class="time-counter"><div class="time-countdown clearfix" data-countdown="<?php echo balanceTags($date);?>"></div></div>
                            
                            <div class="link-box"><a href="<?php echo esc_url($btn_link);?>" class="theme-btn btn-style-four"><?php esc_html_e('Shop Now', 'warsaw');?></a></div>
                                
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
   
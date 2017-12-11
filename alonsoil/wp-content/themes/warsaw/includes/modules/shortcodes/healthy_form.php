<?php
ob_start() ;?>

<!--Intro Section-->
<section class="intro-section">
    <div class="inner-part">
        <div class="auto-container">
            <div class="content-box">
                <div class="inner-box">
                    <!--Section Title-->
                    <div class="sec-title-one">
                        <h2><?php echo balanceTags($title);?></h2>
                    </div>
                    
                    <div class="row clearfix">
                        <!--Content Column-->
                        <div class="content-column col-md-12 col-sm-12 col-xs-12">
                            <div class="inner text-left wow fadeInLeft text-justify" data-wow-delay="0ms" data-wow-duration="1500ms">
                                <h3><?php echo balanceTags($title1);?></h3>
                                <div class="text"><?php echo balanceTags($text1);?></div>
                                <a href="<?php echo esc_url($btn_link1);?>" class="theme-btn btn-style-two"><?php esc_html_e('Leer más', 'warsaw');?></a>
                            </div>
                        </div>
                        <!--Content Column-->
                        <!--<div class="content-column col-md-6 col-sm-6 col-xs-12">
                            <div class="inner text-right wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
                                <h3><?php echo balanceTags($title2);?></h3>
                                <div class="text"><?php echo balanceTags($text2);?></div>
                                <a href="<?php echo esc_url($btn_link2);?>" class="theme-btn btn-style-two"><?php esc_html_e('Leer más', 'warsaw');?></a>
                            </div>
                        </div>-->
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
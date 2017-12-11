<?php
ob_start() ;?>

<!--Our Team-->
<section class="team-section <?php if( $style_two == true ) echo 'no-padding-top';?>">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12 col-xs-12">
                <!--Default Team Member-->
                <div class="default-team-member">
                    <div class="inner-box clearfix">
                        <!--Image Column-->
                        <div class="image-column"><figure class="image"><a href="<?php echo esc_url($btn_link1);?>"><img src="<?php echo esc_url(wp_get_attachment_url($image1));?>" alt=""></a></figure></div>
                        <!--Content Column-->
                        <div class="content-column">
                            <div class="inner">
                                <h3><?php echo balanceTags($title1);?></h3>
                                <div class="text"><?php echo balanceTags($text1);?></div>
                                <div class="social-links">
                                    <a href="<?php echo esc_url($facebook);?>"><span class="fa fa-facebook-official"></span></a>
                                    <a href="<?php echo esc_url($twitter);?>"><span class="fa fa-twitter"></span></a>
                                    <a href="<?php echo esc_url($instagram);?>"><span class="fa fa-instagram"></span></a>
                                    <a href="<?php echo esc_url($skype);?>"><span class="fa fa-skype"></span></a>
                                    <a href="<?php echo esc_url($vimeo);?>"><span class="fa fa-vimeo-square"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Default Team Member-->
                <div class="default-team-member alternate">
                    <div class="inner-box clearfix">
                        <!--Image Column-->
                        <div class="image-column"><figure class="image"><a href="<?php echo esc_url($btn_link2);?>"><img src="<?php echo esc_url(wp_get_attachment_url($image2));?>" alt=""></a></figure></div>
                        <!--Content Column-->
                        <div class="content-column">
                            <div class="inner">
                                <h3><?php echo balanceTags($title2);?></h3>
                                <div class="text"><?php echo balanceTags($text2);?></div>
                                <div class="social-links">
                                    <a href="<?php echo esc_url($facebook1);?>"><span class="fa fa-facebook-official"></span></a>
                                    <a href="<?php echo esc_url($twitter1);?>"><span class="fa fa-twitter"></span></a>
                                    <a href="<?php echo esc_url($instagram1);?>"><span class="fa fa-instagram"></span></a>
                                    <a href="<?php echo esc_url($skype1);?>"><span class="fa fa-skype"></span></a>
                                    <a href="<?php echo esc_url($vimeo1);?>"><span class="fa fa-vimeo-square"></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="col-md-4 col-sm-6 col-xs-12">
                <!--Default Team Member / Vertical-->
                <div class="default-team-member vertical">
                    <div class="inner-box clearfix">
                        <!--Image Column-->
                        <div class="image-column"><figure class="image"><a href="<?php echo esc_url($btn_link3);?>"><img src="<?php echo esc_url(wp_get_attachment_url($image3));?>" alt=""></a></figure></div>
                        <!--Content Column-->
                        <div class="content-column">
                            <div class="inner">
                                <h3><?php echo balanceTags($title3);?></h3>
                                <div class="text"><?php echo balanceTags($text3);?></div>
                                <div class="social-links">
                                    <a href="<?php echo esc_url($facebook2);?>"><span class="fa fa-facebook-official"></span></a>
                                    <a href="<?php echo esc_url($twitter2);?>"><span class="fa fa-twitter"></span></a>
                                    <a href="<?php echo esc_url($instagram2);?>"><span class="fa fa-instagram"></span></a>
                                    <a href="<?php echo esc_url($skype2);?>"><span class="fa fa-skype"></span></a>
                                    <a href="<?php echo esc_url($vimeo2);?>"><span class="fa fa-vimeo-square"></span></a>
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
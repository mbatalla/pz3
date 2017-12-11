<?php
ob_start() ;?>

<!--About Farm Section-->
<section class="about-farm style-two">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
                    
        <div class="row clearfix">
            <!--Column-->
            <div class="column col-lg-5 col-md-4 col-sm-12 col-xs-12">
                <figure class="image-box wow slideInLeft" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image));?>" alt=""></figure>
            </div>
            <!--Column-->
            <div class="column col-lg-7 col-md-8 col-sm-12 col-xs-12">
                <div class="tabs-box tabs-style-one">
                    <div class="row clearfix">
                        
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <!--Tab Buttons-->
                            <ul class="tab-buttons clearfix">
                                <?php $skills_array = (array)json_decode(urldecode($about_form));
									if( $skills_array && is_array($skills_array) ): 
									foreach( (array)$skills_array as $key => $value ):
								?>
                                <li class="tab-btn <?php if($key == 1) echo 'active-btn';?>" data-tab="#tab-one<?php echo esc_attr($key);?>"><span class="txt"><?php echo balanceTags(warsaw_set( $value, 'year' )); ?></span></li>
                                <?php endforeach; endif;?>
                            </ul>
                        </div>
                        
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            <!--Tabs Content-->
                            <div class="tabs-content">
                                <!--Tab-->
                                <?php $skills_array = (array)json_decode(urldecode($about_form));
									if( $skills_array && is_array($skills_array) ): 
									foreach( (array)$skills_array as $key => $value ):
								?>
                                <div class="tab <?php if($key == 1) echo 'active-tab';?>" id="tab-one<?php echo esc_attr($key);?>">
                                    <div class="subtitle"><?php echo balanceTags(warsaw_set( $value, 'sub_title' )); ?></div>
                                    <h3><?php echo balanceTags(warsaw_set( $value, 'title1' )); ?></h3>
                                    <div class="content">
                                        <?php echo balanceTags(warsaw_set( $value, 'text' )); ?>
                                    </div>
                                    <h5 class="author-name"><?php echo balanceTags(warsaw_set( $value, 'author_name' )); ?></h5>
                                </div>
                            	<?php endforeach; endif;?>
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
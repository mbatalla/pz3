<?php $options = _WSH()->option();
	warsaw_bunch_global_variable(); ?>

<!-- Main Header-->
<header class="main-header">
    
    <!-- Main Box -->
    <div class="main-box">
        <div class="auto-container">
            <div class="outer-container clearfix">
                <!--Logo Box-->
                <div class="logo-box">
                    <div class="logo">
                        <?php if(warsaw_set($options, 'logo_image')):?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url(warsaw_set($options, 'logo_image'));?>" alt="" title="<?php esc_html_e('Warsaw', 'warsaw');?>"></a>
                        <?php else:?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url(get_template_directory_uri().'/images/logo.png');?>" alt="<?php esc_html_e('Warsaw', 'warsaw');?>"></a>
                        <?php endif;?>
                    </div>
                </div>
                
                <!--Nav Outer-->
                <div class="nav-outer clearfix">
                    <!-- Main Menu -->
                    <nav class="main-menu">
                        <div class="navbar-header">
                            <!-- Toggle Button -->    	
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            </button>
                        </div>
                        
                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">
                                <?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'container_id' => 'navbar-collapse-1',
                                    'container_class'=>'navbar-collapse collapse navbar-right',
                                    'menu_class'=>'nav navbar-nav',
                                    'fallback_cb'=>false, 
                                    'items_wrap' => '%3$s', 
                                    'container'=>false,
                                    'walker'=> new Bunch_Bootstrap_walker()  
                                ) ); ?>
                             </ul>
                        </div>
                    </nav><!-- Main Menu End-->
                    
                </div><!--Nav Outer End-->
                
                <!-- Hidden Nav Toggler -->
                <div class="nav-toggler">
                <button class="hidden-bar-opener"><span class="icon fa fa-bars"></span></button>
                </div><!-- / Hidden Nav Toggler -->
                
            </div>    
        </div>
    </div>

</header>
<!--End Main Header -->

<!-- Hidden Navigation Bar -->
<section class="hidden-bar right-align">
    
    <div class="hidden-bar-closer">
        <button class="btn"><i class="fa fa-close"></i></button>
    </div>
    
    <!-- Hidden Bar Wrapper -->
    <div class="hidden-bar-wrapper">
    
        <!-- .logo -->
        <div class="logo text-center">
            <?php if(warsaw_set($options, 'small_logo_image')):?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url(warsaw_set($options, 'small_logo_image'));?>" alt="" title="<?php esc_html_e('Warsaw', 'warsaw');?>"></a>
            <?php else:?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url(get_template_directory_uri().'/images/logo-4.png');?>" alt="<?php esc_html_e('Warsaw', 'warsaw');?>"></a>
            <?php endif;?>
        </div><!-- /.logo -->
        
        <!-- .Side-menu -->
        <div class="side-menu">
        <!-- .navigation -->
            <ul class="navigation">
                <?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'container_id' => 'navbar-collapse-1',
                    'container_class'=>'navbar-collapse collapse navbar-right',
                    'menu_class'=>'nav navbar-nav',
                    'fallback_cb'=>false, 
                    'items_wrap' => '%3$s', 
                    'container'=>false,
                    'walker'=> new Bunch_Bootstrap_walker()  
                ) ); ?>
            </ul>
        </div><!-- /.Side-menu -->
        
        <?php if(warsaw_set($options, 'show_social_icons')):?>
        <?php if($socials = warsaw_set(warsaw_set($options, 'social_media'), 'social_media')): //warsaw_set_printr($socials);?>
        <div class="social-icons">
            <ul>
                <?php foreach($socials as $key => $value):
                    if(warsaw_set($value, 'tocopy')) continue;
                ?>
                <li><a href="<?php echo esc_url(warsaw_set($value, 'social_link'));?>"><i class="fa <?php echo esc_attr(warsaw_set($value, 'social_icon'));?>"></i></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <?php endif;?>
        <?php endif;?>
    </div><!-- / Hidden Bar Wrapper -->
</section>
<!-- / Hidden Bar -->
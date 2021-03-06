<?php warsaw_bunch_global_variable(); 
	$options = _WSH()->option();
	get_header(); 
	if( $wp_query->is_posts_page ) {
		$meta = _WSH()->get_meta('_bunch_layout_settings', get_queried_object()->ID);
		$meta1 = _WSH()->get_meta('_bunch_header_settings', get_queried_object()->ID);
		if(warsaw_set($_GET, 'layout_style')) $layout = warsaw_set($_GET, 'layout_style'); else
		$layout = warsaw_set( $meta, 'layout', 'right' );
		$sidebar = warsaw_set( $meta, 'sidebar', 'default-sidebar' );
		$bg = warsaw_set($meta1, 'header_img');
		$title = warsaw_set($meta1, 'header_title');
	} else {
		$settings  = _WSH()->option(); 
		if(warsaw_set($_GET, 'layout_style')) $layout = warsaw_set($_GET, 'layout_style'); else
		$layout = warsaw_set( $settings, 'archive_page_layout', 'right' );
		$sidebar = warsaw_set( $settings, 'archive_page_sidebar', 'default-sidebar' );
		$bg = warsaw_set($settings, 'archive_page_header_img');
		$title = warsaw_set($settings, 'archive_page_header_title');
	}
	$layout = warsaw_set( $_GET, 'layout' ) ? warsaw_set( $_GET, 'layout' ) : $layout;
	$sidebar = ( $sidebar ) ? $sidebar : 'default-sidebar';
	_WSH()->page_settings = array('layout'=>'right', 'sidebar'=>$sidebar);
	$classes = ( !$layout || $layout == 'full' || warsaw_set($_GET, 'layout_style')=='full' ) ? ' col-lg-12 col-md-12 col-sm-12 col-xs-12 ' : ' col-lg-9 col-md-8 col-sm-12 col-xs-12 ' ;
	?>
	
    <!--Page Title-->
    <section class="page-title" <?php if($bg):?>style="background-image:url('<?php echo esc_url($bg)?>');"<?php endif;?>>
        <div class="auto-container">
            <h1><?php if($title) echo wp_kses_post($title); else esc_html_e('warsaw', 'warsaw');?></h1>
        </div>
    </section>
    
    <!--Sidebar Page-->
    <div class="sidebar-page-container">
        <div class="auto-container">
            <div class="row clearfix">
                
                <!-- sidebar area -->
                <?php if( $layout == 'left' ): ?>
                <?php if ( is_active_sidebar( $sidebar ) ) { ?>
                <div class="sidebar-side col-lg-3 col-md-4 col-sm-6 col-xs-12">        
                    <aside class="sidebar">
                        <?php dynamic_sidebar( $sidebar ); ?>
                    </aside>
                </div>
                <?php } ?>
                <?php endif; ?>
                
                <!--Content Side-->	
                <div class="content-side <?php echo esc_attr($classes);?>">
                    
                    <!--Default Section-->
                    <section class="blog-classic-view">
                        <!--Blog Post-->
                        <?php while( have_posts() ): the_post();?>
                            <!-- blog post item -->
                            <!-- Post -->
                            <div id="post-<?php the_ID(); ?>" <?php post_class();?>>
                                <?php get_template_part( 'blog' ); ?>
                            <!-- blog post item -->
                            </div><!-- End Post -->
                        <?php endwhile;?>
                        
                        <!--Pagination-->
                        <div class="styled-pagination text-left">
                            <?php warsaw_the_pagination(); ?>
                        </div>
                    </section>
                    
                </div>
                <!--Content Side-->
                
                <!--Sidebar-->	
                <!-- sidebar area -->
                <?php if( $layout == 'right' ): ?>
                <?php if ( is_active_sidebar( $sidebar ) ) { ?>
                <div class="sidebar-side col-lg-3 col-md-4 col-sm-6 col-xs-12">        
                    <aside class="sidebar">
                        <?php dynamic_sidebar( $sidebar ); ?>
                    </aside>
                </div>
                <?php } ?>
                <?php endif; ?>
                <!--Sidebar-->
            </div>
        </div>
    </div>
<?php get_footer(); ?>
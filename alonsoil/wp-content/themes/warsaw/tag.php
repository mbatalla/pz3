<?php warsaw_bunch_global_variable();
	$options = _WSH()->option();
	get_header(); 
	$meta = _WSH()->get_term_meta( '_bunch_category_settings' );
	if(warsaw_set($_GET, 'layout_style')) $layout = warsaw_set($_GET, 'layout_style'); else
	$layout = warsaw_set( $meta, 'layout', 'right' );
	$sidebar = warsaw_set( $meta, 'sidebar', 'blog-sidebar' );
	$view = warsaw_set( $meta, 'view', 'list' ) ? warsaw_set( $meta, 'view', 'list' ) : 'list';
	_WSH()->page_settings = array('layout'=>$layout, 'sidebar'=>$sidebar);
	$classes = ( !$layout || $layout == 'full' ) ? ' col-lg-12 col-md-12 col-sm-12 col-xs-12' : ' col-lg-9 col-md-8 col-sm-12 col-xs-12 ';
	$bg = warsaw_set($meta, 'header_img');
	$title = warsaw_set($meta, 'header_title');
?>

<!--Page Title-->
<section class="page-title" <?php if($bg):?>style="background-image:url('<?php echo esc_url($bg)?>');"<?php endif;?>>
    <div class="auto-container">
        <h1><?php if($title) echo wp_kses_post($title); else wp_title('');?></h1>
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
			<!-- sidebar area -->
			
			<!-- Left Content -->
			<div class="content-side <?php echo esc_attr($classes);?>">
				
                <section class="blog-classic-view">
                
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
			<!-- sidebar area -->
			
			<!-- sidebar area -->
			<?php if( $layout == 'right' ): ?>
				<?php if ( is_active_sidebar( $sidebar ) ) { ?>
					<div class="sidebar-side col-lg-3 col-md-4 col-sm-6 col-xs-12">       
						<aside class="sidebar">
							<?php dynamic_sidebar( $sidebar ); ?>
						</aside>
					</div>
				<?php }?>
			<?php endif; ?>
			<!-- sidebar area -->
		</div>
	</div>
</div>
<?php get_footer(); ?>
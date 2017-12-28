<?php $options = _WSH()->option();
	get_header(); 
	$settings  = warsaw_set(warsaw_set(get_post_meta(get_the_ID(), 'bunch_page_meta', true) , 'bunch_page_options') , 0);
	$meta = _WSH()->get_meta('_bunch_layout_settings');
	$meta1 = _WSH()->get_meta('_bunch_header_settings');
	$meta2 = _WSH()->get_meta();
	_WSH()->page_settings = $meta;
	if(warsaw_set($_GET, 'layout_style')) $layout = warsaw_set($_GET, 'layout_style'); else
	$layout = warsaw_set( $meta, 'layout', 'full' );
	if( !$layout || $layout == 'full' || warsaw_set($_GET, 'layout_style')=='full' ) $sidebar = ''; else
	$sidebar = warsaw_set( $meta, 'sidebar', 'blog-sidebar' );
	$classes = ( !$layout || $layout == 'full' || warsaw_set($_GET, 'layout_style')=='full' ) ? ' col-lg-9 col-md-8 col-sm-12 col-xs-12 ' : ' col-lg-9 col-md-8 col-sm-6 col-xs-12 ' ;
	/** Update the post views counter */
	_WSH()->post_views( true );
	$bg = warsaw_set($meta1, 'header_img');
	$title = warsaw_set($meta1, 'header_title');
?>
<?php if(!is_product()): ?>
<!--Page Title-->
<section class="page-title" <?php if($bg):?>style="background-image:url('<?php echo esc_url($bg)?>');"<?php endif;?>>
    <div class="auto-container">
        <h1><?php if($title) echo wp_kses_post($title); else wp_title('');?></h1>
    </div>
</section>
<?php endif; ?>

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
                <section class="blog-classic-view post-details">
                    <?php while( have_posts() ): the_post(); 
						$post_meta = _WSH()->get_meta();
					?>
                    <!--Blog Post-->
                    <div class="news-style-one">
                        <div class="inner-box">
                        	<figure class="image-box"><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php the_post_thumbnail('warsaw_1200x313', array('class' => 'img-responsive'));?></a></figure>
                            <div class="lower-content">
                                <div class="post-content">
                                    <?php the_content();?>
                                </div>
                            </div>
                            <div class="post-info clearfix">
                                <div class="post-tags"><?php the_tags('<span class="fa fa-tag"></span> &ensp; ', ',');?></div>
                            </div>
                        </div>  
                    </div>
                        
                        <!--Comments Area-->
		            	<?php wp_link_pages(array('before'=>'<div class="paginate-links">'.esc_html__('Pages: ', 'warsaw'), 'after' => '</div>', 'link_before'=>'<span>', 'link_after'=>'</span>')); ?>
						<?php comments_template(); ?><!-- end comments -->  
                    <?php endwhile;?>
                </section>
            </div>
            <!--Content Side-->
            
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
        </div>
    </div>
</div>

<?php get_footer(); ?>
<?php /* Template Name: VC Page */
	get_header() ;
	$meta = _WSH()->get_meta('_bunch_header_settings');
	$bg = warsaw_set($meta, 'header_img');
	$title = warsaw_set($meta, 'header_title');
?>
<?php if(warsaw_set($meta, 'breadcrumb')):?>
	
    <!--Page Title-->
    <section class="page-title" <?php if($bg):?>style="background-image:url('<?php echo esc_url($bg)?>');"<?php endif;?>>
        <div class="auto-container">
            <h1><?php if($title) echo wp_kses_post($title); else wp_title('');?></h1>
        </div>
    </section>
    
<?php endif;?>
<?php while( have_posts() ): the_post(); ?>
    <?php the_content(); ?>
<?php endwhile;  ?>
<?php get_footer() ; ?>
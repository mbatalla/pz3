<?php 
warsaw_bunch_global_variable();
$paged = get_query_var('paged');
$args = array('post_type' => 'bunch_gallery', 'showposts'=>$num, 'orderby'=>$sort, 'order'=>$order, 'paged'=>$paged);
$terms_array = explode(",",$exclude_cats);
if($exclude_cats) $args['tax_query'] = array(array('taxonomy' => 'gallery_category','field' => 'id','terms' => $terms_array,'operator' => 'NOT IN',));
$query = new WP_Query($args);

$t = $GLOBALS['_bunch_base'];

$data_filtration = '';
$data_posts = '';
?>

<?php if( $query->have_posts() ):
	
ob_start();?>

	<?php $count = 0; 
	$fliteration = array();?>
	<?php while( $query->have_posts() ): $query->the_post();
		global  $post;
		$meta = get_post_meta( get_the_id(), '_bunch_portfolio_meta', true );//printr($meta);
		$meta1 = _WSH()->get_meta();
		$post_terms = get_the_terms( get_the_id(), 'gallery_category');// printr($post_terms); exit();
		foreach( (array)$post_terms as $pos_term ) $fliteration[$pos_term->term_id] = $pos_term;
		$temp_category = get_the_term_list(get_the_id(), 'gallery_category', '', ', ');
	?>
		<?php $post_terms = wp_get_post_terms( get_the_id(), 'gallery_category'); 
		$term_slug = '';
		if( $post_terms ) foreach( $post_terms as $p_term ) $term_slug .= $p_term->slug.' ';?>		
           
		   <?php 
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
			$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
		   ?>     
		   
           <div class="default-portfolio-item mix mix_all <?php echo esc_attr($term_slug); ?>">
                <div class="inner-box">
                    <figure class="image-box"><?php the_post_thumbnail('warsaw_376x270');?></figure>
                    <div class="overlay-box">
                        <div class="overlay-inner">
                            <div class="overlay-content">
                                <a href="<?php echo esc_url($post_thumbnail_url);?>" class="lightbox-image option-btn theme-btn" title="<?php esc_html_e('Image Caption Here', 'warsaw');?>" data-fancybox-group="fancybox"><span class="fa fa-search"></span></a>
                                <a href="<?php echo esc_url(warsaw_set($meta1, 'ext_url'));?>" class="option-btn theme-btn"><span class="fa fa-link"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
<?php endwhile;?>
  
<?php wp_reset_postdata();
$data_posts = ob_get_contents();
ob_end_clean();

endif; 

ob_start();?>	 

<?php $terms = get_terms(array('gallery_category')); ?>

<!--Gallery Section-->
<section class="gallery-section fullwidth-gallery-two gallery-page">
    
    <div class="mixitup-gallery">
        <div class="auto-container">
            <!--Filter-->
            <div class="filters text-center">
                <ul class="filter-tabs filter-btns clearfix">
                    <li class="active filter" data-role="button" data-filter="all"><?php esc_attr_e('All', 'warsaw');?></li>
                    <?php foreach( $fliteration as $t ): ?>
                    <li class="filter" data-role="button" data-filter=".<?php echo esc_attr(warsaw_set( $t, 'slug' )); ?>"><?php echo balanceTags(warsaw_set( $t, 'name')); ?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>

        <!--Filter List-->
        <div class="filter-list images-outer clearfix">
        	<?php echo balanceTags($data_posts); ?>
        </div>

    </div>
        
</section>

<?php $output = ob_get_contents();
ob_end_clean(); 
return $output;?>
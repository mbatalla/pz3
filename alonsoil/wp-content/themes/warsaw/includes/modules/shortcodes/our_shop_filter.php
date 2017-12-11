<?php 
warsaw_bunch_global_variable();
$args = array('post_type' => 'product', 'showposts'=>$num, 'orderby'=>$sort, 'order'=>$order);
$terms_array = explode(",",$exclude_cats);
if($exclude_cats) $args['tax_query'] = array(array('taxonomy' => 'product_cat','field' => 'id','terms' => $terms_array,'operator' => 'NOT IN',));
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
		$meta1 = _WSH()->get_meta();
		$post_terms = get_the_terms( get_the_id(), 'product_cat');
		foreach( (array)$post_terms as $pos_term ) $fliteration[$pos_term->term_id] = $pos_term;
		$temp_category = get_the_term_list(get_the_id(), 'product_cat', '', ', ');
	?>
		<?php $post_terms = wp_get_post_terms( get_the_id(), 'product_cat'); 
		$term_slug = '';
		if( $post_terms ) foreach( $post_terms as $p_term ) $term_slug .= $p_term->slug.' ';?>		
           
		   <?php 
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
			$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
		   ?>     
		   
           <div class="col-md-4 col-sm-6 col-xs-12 default-food-item mix mix_all <?php echo esc_attr($term_slug); ?>">
                <div class="inner-box">
                    <div class="image-box">
                        <figure class="image"><a class="lightbox-image option-btn 3" data-fancybox-group="example-gallery" href="<?php the_permalink(); ?>" title=""><?php the_post_thumbnail('warsaw_370x310_custom');?></a></figure>
                        <div class="lower-content">
                            <h3><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php the_title();?></a></h3>
                            <div class="price"><?php woocommerce_template_loop_price(); ?></div>
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
<?php $terms = get_terms(array('product_cat')); ?>

<!--Gallery Section-->
<section class="gallery-section">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one prod">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        
        <div class="mixitup-gallery">
            <!--Filter-->
            <div class="filters text-center">
                <ul class="filter-tabs filter-btns clearfix">
                    <li class="active filter" data-role="button" data-filter="all"><?php esc_attr_e('All', 'warsaw');?></li>
                    <?php foreach( $fliteration as $t ): ?>
                    <li class="filter" data-role="button" data-filter=".<?php echo esc_attr(warsaw_set( $t, 'slug' )); ?>"><?php echo balanceTags(warsaw_set( $t, 'name')); ?></li>
                    <?php endforeach;?>
                </ul>
            </div>

            <!--Filter List-->
            <div class="filter-list row clearfix">
            	<?php echo balanceTags($data_posts); ?>    
            </div>

            <!--Button Outer-->
            <div class="more-btn-outer text-center"><a href="<?php echo esc_url(get_permalink(get_the_id()));?>" class="theme-btn btn-style-four"><?php esc_html_e('Ver más', 'warsaw');?></a></div>

        </div>
        
    </div>
</section>

<?php $output = ob_get_contents();
ob_end_clean(); 
return $output;?>
<?php $options = _WSH()->option();

//printr($options);

global $post, $product, $post_type;

$post_id = get_the_id();

 

//$header_style = bunch_set( $options, 'header_style' );

//$header_style = bunch_set( $_GET, 'header_style' ) ? 'side' : 'normal';



/** Set the default values */

$bg = bunch_set( $options, 'grid_page_header_img' );

$title = bunch_set( $options, 'grid_page_title' ) ;

$text = bunch_set( $options, 'grid_page_header_text' );?>



<?php if( is_category() || is_tag() || is_tax( 'product_cat' ) ) {

	$meta = _WSH()->get_term_meta( '_bunch_category_settings' );

	$object = get_queried_object();

	$bg = bunch_set( $meta, 'header_img' );

	$title = ( bunch_set( $meta, 'header_title' ) ) ? bunch_set( $meta, 'header_title' ) : $object->name ;

	$text = bunch_set( $meta, 'header_text' );

} else if ( is_search() ) {



	$bg = bunch_set( $options, 'search_page_header_img' );

	$title = ( bunch_set( $options, 'search_page_title' ) ) ? bunch_set( $options, 'search_page_title' ) : get_search_query() ;

	$text = bunch_set( $options, 'search_page_header_text' );



} else if( is_author() ) {

	$bg = bunch_set( $options, 'author_page_header_img' );

	$title = ( bunch_set( $options, 'author_page_title' ) ) ? bunch_set( $options, 'author_page_title' ) : get_queried_object()->data->dispaly_name ;

	$text = bunch_set( $options, 'author_page_header_text' );

} else if( is_archive('product') ) { 



	if( class_exists('woocommerce') && is_shop() ) $post_id = get_option( 'woocommerce_shop_page_id' );

	$meta = _WSH()->get_meta('_bunch_header_settings', $post_id ); 

	$bg = bunch_set( $meta, 'bg_image' );

	$title = ( bunch_set( $meta, 'header_title' ) ) ? bunch_set( $meta, 'header_title' ) : get_the_title();

	$text = ( bunch_set( $meta, 'header_text' ) ) ? bunch_set( $meta, 'header_text' ) : 'A minimal make-up theme';



}

else if( is_archive() ) {

	$bg = bunch_set( $options, 'archive_page_header_img' );

	$title = ( bunch_set( $options, 'archive_page_title' ) ) ? bunch_set( $options, 'archive_page_title' ) : get_queried_object()->data->dispaly_name ;

	$text = bunch_set( $options, 'archive_page_header_text' );

} else if( is_page() ) {

	if( class_exists('woocommerce') && is_shop() ) $post_id = get_option( 'woocommerce_shop_page_id' );

	$meta = _WSH()->get_meta('_bunch_header_settings', $post_id ); 

	$bg = bunch_set( $meta, 'bg_image' );

	$title = ( bunch_set( $meta, 'header_title' ) ) ? bunch_set( $meta, 'header_title' ) : get_the_title();

	$text = ( bunch_set( $meta, 'header_text' ) ) ? bunch_set( $meta, 'header_text' ) : 'A minimal make-up theme';

}else if( is_singular() ){

	$meta = _WSH()->get_meta('_bunch_header_settings', $post_id ); 

	$bg = bunch_set( $meta, 'bg_image' );

	$title = ( bunch_set( $meta, 'header_title' ) ) ? bunch_set( $meta, 'header_title' ) : get_the_title();

	$text = bunch_set( $meta, 'header_text' );

}

?>



<div id="container" class="container intro-effect-grid clearfix">

    <header class="header-wrapper">

        <ul class="grid">

        <?php 

			$products_num = bunch_set($options, 'products_num');

			$sort = bunch_set($options, 'sort');

			$order = bunch_set($options, 'order');

			$product_category = bunch_set($options, 'product_category');

			$query_args = array('post_type' => 'product' , 'showposts' => $products_num ,'orderby' => $order , 'order' => $sort );

			if( $product_category ) $query_args['tax_query'] = array(array('taxonomy' => 'product_cat','field' => 'id','terms' => $product_category));

			$query = new WP_Query($query_args) ; ?>

			<?php while( $query->have_posts() ): $query->the_post(); 

				$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 720,405 ), false, '' );

				$product = new WC_Product( get_the_ID() );

				$price = $product->price;

			?>

            	<li style="background-image: url(<?php echo $src[0];?>)"><h2><a href="<?php the_permalink();?>"><?php the_title();?> <small><?php echo $product->get_price_html()?></small></a></h2></li>

        <?php endwhile;?>

        <?php wp_reset_query();?>

        </ul>

        <div class="bg-img">

        	<?php if($bg):?>

            	<img src="<?php echo $bg;?>" class="img-responsive" alt="">

            <?php else:?>

            	<img src="<?php echo get_template_directory_uri();?>/images/shop_bg.jpg" class="img-responsive" alt="">

            <?php endif;?>    

        </div>

            <div class="title">

            	

				<?php wp_nav_menu( array( 

									'container' => false,

									'fallback_cb' => false, 

									'theme_location' => 'main_menu',

									'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',

									'walker' => new Bunch_Bootstrap_walker()

									) );

									?>

                

            <h1><a href="<?php esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php if($title){echo $title;}else{wp_title('');} ?></a></h1>

            <p class="subline"><?php if($text){echo $text;}else{bloginfo('description');} ?></p>

        </div>

    </header>

    <button class="trigger" data-info="<?php _e('Click to read', BUNCH_NAME);?>"><span><?php _e('Trigger', BUNCH_NAME);?></span></button>

</div>

<hr>


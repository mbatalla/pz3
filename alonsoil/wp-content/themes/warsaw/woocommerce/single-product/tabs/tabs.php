<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">
    	<div class="buttons-column col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <ul class="tabs wc-tabs">
                <?php foreach ( $tabs as $key => $tab ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="content-column col-lg-9 col-md-8 col-sm-8 col-xs-12">
			<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ); ?>
			</div>
			<?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
	</div>

<?php endif; ?>

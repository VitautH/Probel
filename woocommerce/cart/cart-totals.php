<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">
<div class="content_cart_totals">
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>


		<span class="total">Итого:</span>
	<br>
			<span data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></span>







		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>



		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>




		<a href="<?php echo esc_url( wc_get_checkout_url() ) ;?>" class="checkout-button button alt wc-forward">
	<span>Оформить заказ </span>
		</a>



	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
	<input type="submit" class="button" name="update_cart" value="Обновить корзину" />

	<?php do_action( 'woocommerce_cart_actions' ); ?>

	<?php wp_nonce_field( 'woocommerce-cart' ); ?>
</div>
</div>

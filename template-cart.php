<?
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Cart
 *
 * @package storefront
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
function oncore_style_catalog() {
    wp_enqueue_style('oncore_style', get_stylesheet_directory_uri() . '/css/cart.css' );
}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
get_header( 'shop' );
global $post, $product;

?>

<? //wc_print_notices();
if (WC()->cart->get_cart_contents_count() !== 0 ) {
    do_action('woocommerce_before_cart'); ?>
    <aside>
        <div class="container"><div class="row"><div class="span12"> <span class="count_cart_items"> <?php echo 'В вашей корзине '.WC()->cart->get_cart_contents_count().' товар(ов)'; ?></span></div></div></div>

    </aside>
    <div class="container">
        <div class="row" style="margin-bottom: 45px;">

            <form action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <div class="span3 total" style="float: right; margin-right: 28px;">
                    <div class="cart-collaterals">
                        <?php do_action('woocommerce_cart_collaterals'); ?>

                    </div>
                </div>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>


                        <div
                            class="span9 <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">


                            <div class="product-thumbnail span3">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                if (!$product_permalink) {
                                    echo $thumbnail;
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                }
                                ?>
                            </div>
                            <div class="span6">
                                <div class="row">
                                    <div class="product-name span6" data-title="<?php _e('Product', 'woocommerce'); ?>">
                                        <?php
                                        if (!$product_permalink) {
                                            echo apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key) . '&nbsp;';
                                        } else {
                                            echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_title()), $cart_item, $cart_item_key);
                                        }

                                        // Meta data
                                        echo WC()->cart->get_item_data($cart_item);

                                        // Backorder notification
                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                            echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="sku span6">
                                        код товара <?php echo $_product->get_sku(); ?>
                                    </div>
                                </div>
                                <div style="margin-top: 30px;" class="row">
                                    <div class="product-quantity span2"
                                         data-title="<?php _e('Quantity', 'woocommerce'); ?>">
                                        <span>Количество</span>
                                        <br>
                                        <?php
                                        if ($_product->is_sold_individually()) {
                                            $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                        } else {
                                            $product_quantity = woocommerce_quantity_input(array(
                                                'input_name' => "cart[{$cart_item_key}][qty]",
                                                'input_value' => $cart_item['quantity'],
                                                'max_value' => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                                'min_value' => '0'
                                            ), $_product, false);
                                        }

                                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                        ?>
                                    </div>
                                    <div class="product-price span2" data-title="<?php _e('Price', 'woocommerce'); ?>">
                                        <span>Цена</span>
                                        <br>
                                        <?php
                                        echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                        ?>
                                    </div>


                                    <?php
                                    echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                        '<a href="%s" class="remove span1 offset1" title="%s" data-product_id="%s" data-product_sku="%s"> <div class="product-remove"></div></a>',
                                        esc_url(WC()->cart->get_remove_url($cart_item_key)),
                                        __('Remove this item', 'woocommerce'),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku())
                                    ), $cart_item_key);
                                    ?>
                                </div>
                            </div>
                        </div>


                        <?php
                    }
                }

                do_action('woocommerce_cart_contents');
                ?>


            </form>
        </div>
    </div>
    <?php
}
else {
    ?>
    <aside>
        <div class="container"><div class="row"><div class="span12"> <span class="count_cart_empty"> Ваша карзина пустая</span></div></div></div>

    </aside>
<div class="container">
    <div class="row">
    <div class="span12 cart_empty">
         <div class="row"> <div class="span5 offset3 cart_empty_content"><div class="ico_cart_empty"></div> <span class="title">В Вашей карзине нет товаров</span><br>
                 <span class="tooltip">Перейдите в каталог для выбора</span>

           </div>
             <div class="row">
                 <div class="span5 offset3">
                     <a class="offset1 button wc-backward" href="<?php echo get_site_url(); ?>">
                        <div class="return-to-shop"><?php _e( 'Return To Shop', 'woocommerce' ) ?></div>

                     </a>

                     </div>
             </div>
        </div>
        </div>
    </div>
    </div>
        <?php
}
?>
<?php get_footer( 'shop' ); ?>

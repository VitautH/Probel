<?

$id_order = wc_get_order_id_by_order_key($_GET['key']);
$order = new WC_Order($id_order);
$payment_gateways = WC()->payment_gateways->payment_gateways();
$payment_method = $order->payment_method;
 $title_payment_method = 	$payment_gateways[ $payment_method ]->method_title;

 if ( $order->has_status( 'failed' ) ) : ?>

    <p class="woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

    <p class="woocommerce-thankyou-order-failed-actions">
        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
        <?php if ( is_user_logged_in() ) : ?>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
        <?php endif; ?>
    </p>

<?php else : ?>
    <aside>
        <div class="container">
            <div class="row">
                <div class="span12"><span>Информация о заказе</span></div>
            </div>
        </div>
    </aside>
    <div class="container main_block">
    <div class="row">
        <div class="span12 order_detalis_block">
            <div class="order_inner_block">
    <p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

    <ul class="woocommerce-thankyou-order-details order_details">
        <li class="order">
            <?php _e( 'Order Number:', 'woocommerce' ); ?>
            <strong><?php echo $order->get_order_number(); ?></strong>
        </li>
        <li class="date">
            <?php _e( 'Date:', 'woocommerce' ); ?>
            <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
        </li>
        <li class="total">
            <?php _e( 'Total:', 'woocommerce' ); ?>
            <strong><?php echo $order->get_formatted_order_total(); ?></strong>
        </li>
        <?php if ( $order->payment_method ) : ?>
            <li class="method">
                <?php _e( 'Payment Method:', 'woocommerce' ); ?>
                <strong><?php echo $title_payment_method; ?></strong>
            </li>
        <?php endif; ?>
    </ul>
    <div class="clear"></div>

<?php endif; ?>

            <?
            $order = wc_get_order( $id_order );

            $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
            $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
            ?>
            <h2><?php _e( 'Order Details', 'woocommerce' ); ?></h2>
            <table class="shop_table order_details">
                <thead>
                <tr>
                    <th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
                    <th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach( $order->get_items() as $item_id => $item ) {
                    $product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );

                    wc_get_template( 'order/order-details-item.php', array(
                        'order'			     => $order,
                        'item_id'		     => $item_id,
                        'item'			     => $item,
                        'show_purchase_note' => $show_purchase_note,
                        'purchase_note'	     => $product ? get_post_meta( $product->id, '_purchase_note', true ) : '',
                        'product'	         => $product,
                    ) );
                }
                ?>
                <?php do_action( 'woocommerce_order_items_table', $order ); ?>
                </tbody>
                <tfoot>
                <?php
                foreach ( $order->get_order_item_totals() as $key => $total ) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $total['label']; ?></th>
                        <td><?php echo $total['value']; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tfoot>
            </table>

            <?php //do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</div>
    </div>
        </div>
    </div>

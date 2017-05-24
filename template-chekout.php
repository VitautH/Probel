<?
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Checkout
 *
 * @package storefront
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
function oncore_style_catalog() {
    wp_enqueue_style('oncore_style_chekout', get_stylesheet_directory_uri() . '/css/chekout.css' );

}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
if (isset ($_GET['key']) ) {
    function oncore_style_order_detalis()
    {

        wp_enqueue_style('oncore_style_order_detalis', get_stylesheet_directory_uri() . '/css/order_detalis.css');
    }

    add_action('wp_enqueue_scripts', 'oncore_style_order_detalis');

}

get_header( 'shop' );
global $post, $product,$woocommerce, $checkout;
if (isset ($_GET['key']) ) {

 get_template_part( 'template-order_detalis' );
}
else {
    ?>

    <aside>
        <div class="container">
            <div class="row">
                <div class="span12"><span>Оформление заказа</span></div>
            </div>
        </div>
    </aside>

    <?php


    if (WC()->cart->get_cart_contents_count() != 0 || is_wc_endpoint_url('order-received')) {
        ?>
        <?
        $checkout = WC()->checkout();


        ?>
        <form name="checkout" method="post" class="checkout woocommerce-checkout"
              action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
            <?php do_action('woocommerce_checkout_before_customer_details'); ?>
            <div class="container main_block">
                <div class="row">
                    <div class="span6 contact_block">
                        <span class="title">1. Контактная информация</span>


                        <?php do_action('woocommerce_before_checkout_billing_form'); ?>
                        <div class="contact_form">

                            <?php


                            foreach ($checkout->checkout_fields['billing'] as $key => $field) : ?>
                                <?php if ($key == 'billing_first_name') {
                                    ?>
                                    <span>Представьтесь пожалуйста <span class="required">*</span></span>
                                    <br>
                                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                <? }
                                ?>
                                <?php if ($key == 'billing_sur_name') {
                                    ?>

                                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                <? }
                                ?>
                                <?php if ($key == 'billing_last_name') {
                                    ?>

                                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                <? }
                                ?>


                                <?php if ($key == 'billing_email') {
                                    ?>

                                    <span>Электронная почта <span class="required">*</span></span>
                                    <br>
                                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                <? }
                                ?>
                                <?php if ($key == 'billing_phone') {
                                    ?>
                                    <span>Телефон <span class="required">*</span></span>
                                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                <? }
                                ?>


                            <?php endforeach; ?>
                            <span>Дополнительная информация</span>
                            <br>
                            <?php foreach ($checkout->checkout_fields['order'] as $key => $field) : ?>

                                <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>

                            <?php endforeach; ?>

                        </div>
                    </div>


                    <div class="span6 method_block"><span class="title">2. Способ получения</span>
                        <?php
                        $get_shipping_method = new WC_Shipping();

                        $local_self_export = (array)$get_shipping_method->get_shipping_methods()['local_self_export'];
                        $mail = (array)$get_shipping_method->get_shipping_methods()['mail'];

                        $couier = (array)$get_shipping_method->get_shipping_methods()['couier'];
                        ?>
                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                            <ul id="shipping_method">
                                <?php if ($couier ['settings']['enabled'] == 'yes') {
                                    ?>
                                    <li>
                                        <input type="radio" name="shipping_method[0]" data-index="0"
                                               id="shipping_method_0_couier"
                                               value="couier" class="shipping_method" checked="checked">
                                        <label
                                            for="shipping_method_0_couier"></label>
                                        <span class="title_method_pay"><?php echo $couier ['settings']['title']; ?> </span>


                                    </li>

                                    <?
                                }
                                ?>
                                <?php if ($local_self_export ['settings']['enabled'] == 'yes') {
                                    ?>

                                    <li>
                                        <input type="radio" name="shipping_method[0]" data-index="0"
                                               id="shipping_method_0_local_self_export" value="local_self_export"
                                               class="shipping_method">
                                        <label
                                            for="shipping_method_0_local_self_export"></label>

                                        <span class="title_method_pay"><?php echo $local_self_export ['settings']['title']; ?> </span>

                                    </li>
                                    <?php
                                }
                                ?>
                                <?php if ($mail ['settings']['enabled'] == 'yes') {
                                    ?>
                                    <li>
                                        <input type="radio" name="shipping_method[0]" data-index="0"
                                               id="shipping_method_0_mail" value="mail" class="shipping_method">
                                        <label
                                            for="shipping_method_0_mail"></label>

                                        <span class="title_method_pay"><?php echo $mail ['settings']['title']; ?> </span>


                                    </li>
                                    <ul class="shipping_box shipping_method_0_couier span6" style="display: block;">
                                          <span class="remark"> <?php echo $couier ['settings']['remark']; ?>
</span>
                                        <br>
                                        <div class="contact_form">
                                            <span>Область <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-first   address-field"
                                               id="shipping_state_field_couier">
                                                <select name="shipping_state_couier" id="shipping_state_couier"
                                                        class="state_select " placeholder="">
                                                    <option value="MNS">Минская область</option>
                                                    <option value="MGS">Могилёвская облатсь</option>
                                                    <option value="GMS">Гомельская область</option>
                                                    <option value="BRS">Брестская область</option>
                                                    <option value="VTS">Витебская область</option>
                                                    <option value="GRS">Гродненская область</option>
                                                </select>
                                            </p>
                                            <div class="clear"></div>
                                            <span>Город <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-wide  " id="shipping_city_field_couier"><input
                                                    type="text" class="input-text " name="shipping_city_couier"
                                                    id="shipping_city_couier" placeholder="" value="">
                                            </p>
                                            <div class="clear"></div>
                                            <span>Адрес доставки <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-wide  " id="shipping_address_2_field_couier">
                                                <input type="text" class="input-text " name="shipping_address_2_couier"
                                                       id="shipping_address_2_couier"
                                                       placeholder="Улица, дом, корпус, этаж" value="">
                                            </p>
                                            <div class="clear"></div>
                                        </div>

                                    </ul>
                                    <ul class="shipping_box shipping_method_0_local_self_export span6"
                                        style="display: none;">
<span class="free_title">Бесплатно</span>

                                        <span class="address_title">
                                         Забрать можно по адресу
                                             </span>
                                            <br>
                                        <span class="address">
                                            <?php echo $local_self_export ['settings']['address']; ?>
                                            </span>
                                        <br>
                                        <span class="maps">
                                            <?php echo $local_self_export ['settings']['maps']; ?>
                                                </span>

                                        <span class="remark"><?php echo $local_self_export ['settings']['remark']; ?></span>
                                    </ul>
                                    <ul class="shipping_box shipping_method_0_mail span6 " style="display: none;">
                                          <span class="remark">
<?php echo $mail ['settings']['remark']; ?>
</span>
                                        <div class="contact_form">
                                            <br>
                                            <span>Область <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-first   address-field"
                                               id="shipping_state_field_mail">
                                                <select name="shipping_state_mail" id="shipping_state_mail"
                                                        class="state_select " placeholder="">

                                                    <option value="MNS">Минская область</option>
                                                    <option value="MGS">Могилёвская облатсь</option>
                                                    <option value="GMS">Гомельская область</option>
                                                    <option value="BRS">Брестская область</option>
                                                    <option value="VTS">Витебская область</option>
                                                    <option value="GRS">Гродненская область</option>
                                                </select>
                                            </p>
                                            <div class="clear"></div>
                                            <span>Город <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-wide  " id="shipping_city_field_mail"><input
                                                    type="text" class="input-text " name="shipping_city_mail"
                                                    id="shipping_city_mail" placeholder="" value="">
                                            </p>
                                            <div class="clear"></div>
                                            <span>Адрес доставки <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-wide  " id="shipping_address_2_field_mail">
                                                <input type="text" class="input-text " name="shipping_address_2_mail"
                                                       id="shipping_address_2_mail"
                                                       placeholder="Улица, дом, корпус, этаж" value="">
                                            </p>
                                            <div class="clear"></div>
                                            <span>Индекс <span class="required">*</span></span>
                                            <br>
                                            <p class="form-row form-row-wide  " id="shipping_postcode_field_mail"><input
                                                    type="text" class="input-text " name="shipping_postcode_mail"
                                                    id="shipping_postcode_mail" placeholder="222201" value="">
                                            </p>
                                            <div class="clear"></div>
                                        </div>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </ul>

                        <?php endif; ?>


                    </div>
                </div>
                <div class="row">
                    <div class="span6 payment_block">
                        <span class="title"> 3. Способ оплаты </span>


                        <ul class="wc_payment_methods payment_methods methods">
                            <li class="wc_payment_method payment_method_cash_oncore">
                                <input id="payment_method_cash_oncore" type="radio" class="input-radio"
                                       name="payment_method" value="cash_oncore" checked="checked"
                                       data-order_button_text="">

                                <label for="payment_method_cash_oncore">

                                </label>
                                <span class="title_method_pay"> Оплатить при получении</span>
                            </li>
                            <li class="wc_payment_method payment_method_business_oncore">
                                <input id="payment_method_business_oncore" type="radio" class="input-radio"
                                       name="payment_method" value="business_oncore" data-order_button_text="">

                                <label for="payment_method_business_oncore">

                                </label>
                                <span class="title_method_pay">   Банковский перевод (для юр. лиц)</span>
                                <div class="payment_box payment_method_business_oncore" style="display: block;">

                                    <div class="contact_form">

                                        <span>Название компании <span class="required">*</span></span>
                                        <br>
                                        <p class="form-row form-row   validate-required woocommerce-validated"
                                           id="company_name_field">
                                            <input type="text" class="input-text " name="company_name" id="company_name"
                                                   placeholder="Название компании">
                                        </p>
                                        <span> Юридический адрес <span class="required">*</span></span>
                                        <br>
                                        <p class="form-row form-row  validate-required woocommerce-validated"
                                           id="company_address_field">
                                            <input type="text" class="input-text " name="company_address"
                                                   id="company_address" placeholder="Юридический адресс">
                                        </p>
                                        <span>  Ваш р/с <span class="required">*</span></span>
                                        <br>
                                        <p class="form-row form-row   validate-required woocommerce-validated"
                                           id="account_number_field">
                                            <input type="text" class="input-text " name="account_number"
                                                   id="account_number" placeholder="000000000000000000000">
                                        </p>
                                        <span> Наименование банка <span class="required">*</span></span>
                                        <br>
                                        <p class="form-row form-row validate-required woocommerce-validated"
                                           id="bank_name_field">
                                            <input type="text" class="input-text " name="bank_name" id="bank_name"
                                                   placeholder="Наименование банка">
                                        </p>
                                        <span> Код банка <span class="required">*</span></span>
                                        <br>
                                        <p class="form-row form-row   validate-required woocommerce-validated"
                                           id="bank_code_field">
                                            <input type="text" class="input-text " style="width: 110px!important;"
                                                   name="bank_code" id="bank_code" placeholder="153001755">
                                        </p>
                                        <span> УНП <span class="required">*</span></span>
                                        <br>
                                        <p class="form-row form-row   validate-required woocommerce-validated"
                                           id="unp_field">
                                            <input type="text" class="input-text " style="width: 50px;" name="unp"
                                                   id="unp" placeholder="69174807">
                                        </p>
                                    </div>


                            </li>
                        </ul>
                        <p class="form-row terms wc-terms-and-conditions">
                            <input type="checkbox" class="input-checkbox" name="terms" id="terms">
                            <label for="terms" class="checkbox"></label>

                            <span class="terms_title">    Я принимаю <a
                                    href="http://modx.adventuretime.by/%d0%b4%d0%be%d0%b3%d0%be%d0%b2%d0%be%d1%80-%d0%be%d1%84%d0%b5%d1%80%d1%82%d1%8b/"
                                    target="_blank">условия</a> магазина <span class="required">*</span></span>

                            <input type="hidden" name="terms-field" value="1">
                        </p>


                        <?php
                        wp_nonce_field('woocommerce-process_checkout', '_wpnonce', false, true);
                        ?>
                        <noscript>
                            <?php _e('Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'); ?>
                            <br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals"
                                        value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"/>
                        </noscript>


                    </div>
                    <div class="span3 pay_button_block">

                        <span>К оплате: <? wc_cart_totals_subtotal_html(); ?></span>
                        <button type="submit" class="button alt place-order-button"
                                name="woocommerce_checkout_place_order" id="place_order">ОПЛАТИТЬ
                        </button>
                    </div>
                </div>


            </div>
        </form>

        <script>
            $("input.shipping_method").on("click", function () {
                $(".shipping_box").hide();

                var shipping_method_active = $("input:checked").attr("id");
                $("." + shipping_method_active).show();
            });
        </script>
        <?php
    } else {
        ?>
        <div class="container main_block">
            <div class="row">
                <span>Ощибка! Ваша корзина пуста! Оплата не возможна! </span>
            </div>
        </div>
        <?
    }

}
    ?>

<?php get_footer( 'shop' ); ?>
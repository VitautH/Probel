<?
add_theme_support( 'woocommerce' );

if ( ! function_exists( 'oncore_theme_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function oncore_theme_setup() {
        add_theme_support( 'post-thumbnails' );
        /*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
        ) );
        /*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
        add_theme_support( 'post-formats', array(
            'aside', 'image', 'video', 'quote', 'link',
        ) );
    }


endif;
add_action('pre_get_posts','shop_filter_cat');

function shop_filter_cat($query)
{

    if ($_GET['filters'] or $_POST['filters']) {
        if ($_POST['filters']) {

            $_GET['filters'] = substr($_POST['filters'], 9);
        }
        br_aapf_args_converter();
        $args = br_aapf_args_parser();

        if (@ $_POST['price']) {

            $min_price = $_POST['price'] [0];
            $max_price = $_POST['price'] [1];
            $query->set('meta_query', array(

                array(
                    'key' => '_regular_price', // name of custom field
                    'value' => array($min_price, $max_price), // matches exactly "red"
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                )));
        }

        if (@ $_POST['limits']) {

          //  $args_fields_new = array(array('taxonomy' => 'pa_moshhnost-vt', 'field' => 'slug', 'compare' => 'BETWEEN', 'type' => 'NUMERIC', 'terms' => array(400, 2000)));


            foreach ($_POST['limits'] as $v) {
             $args_fields_new = array(array('taxonomy' => $v[0], 'field' => 'slug', 'compare' => 'BETWEEN', 'type' => 'NUMERIC', 'terms' => array($v[1], $v[2])));

           $query->set('tax_query',$args_fields_new);


            }

            }

    $args_fields = array('meta_key', 'tax_query', 'fields', 'where', 'join', 'meta_query');
    foreach ($args_fields as $args_field) {
        if (@ $args[$args_field]) {

           print_r($query->set($args_field, $args[$args_field]));
        }
    }
}
}
function woo_archive_custom_cart_button_text($add_cart_url) {
    global $woocommerce;
    global $product;



    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
        $_product = $values['data'];

        if (get_the_ID() == $_product->id) {


            return  '<a href="'.$woocommerce->cart->get_cart_url().'" class="product_in_cart">В
                                    КОРЗИНЕ</a>';

        }


    }
    if (!$product->is_in_stock()) {
       return '<button type="submit" class="not_buy">КУПИТЬ</button>';

    }
    else {
        return '<button type="submit" ' . $add_cart_url . ' class="buy">КУПИТЬ</button>';
    }

}
/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function oncore_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'oncore' ),
        'id'            => 'sidebar-1',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>'
    ) );
    register_sidebar(array(
        'name' => __( 'Search', 'oncore' ),
        'id' => 'oncore-search',
        'description' => __( 'Место для поиска.', 'oncore' )
    ));

    register_sidebar(array(
        'name' => __( 'Oncore Footer', 'oncore' ),
        'id' => 'oncore-site-footer',
        'description' => __( 'The footer will divide into however many widgets are put here.', 'oncore' )
    ));
    register_sidebar(array(
        'name' => __( 'Oncore Bottom', 'oncore' ),
        'id' => 'oncore-site-bottom',
        'description' => __( 'The footer will divide into however many widgets are put here.', 'oncore' )
    ));
}


// Create function to check if WooCommerce exists.
if ( ! function_exists( 'oncore_is_woocommerce_activated' ) ) :

    function oncore_is_woocommerce_activated() {
        if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
    }

endif; // topshop_is_woocommerce_activated

if ( oncore_is_woocommerce_activated() ) {
    require get_template_directory() . '/includes/inc/woocommerce-inc.php';
}

add_action( 'widgets_init', 'oncore_widgets_init' );
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}
register_nav_menus(
    array(
        'head_menu' => 'Шапка сайта',
        'footer_menu_catalog' => 'Подвал сайта (Каталог)',
        'footer_menu_company' => 'Подвал сайта (Компания)',
    )
);



class True_Walker_Nav_Menu extends Walker_Nav_Menu {
    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output
     * @param object $item Объект элемента меню, подробнее ниже.
     * @param int $depth Уровень вложенности элемента меню.
     * @param object $args Параметры функции wp_nav_menu
     */
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        /*
         * Некоторые из параметров объекта $item
         * ID - ID самого элемента меню, а не объекта на который он ссылается
         * menu_item_parent - ID родительского элемента меню
         * classes - массив классов элемента меню
         * post_date - дата добавления
         * post_modified - дата последнего изменения
         * post_author - ID пользователя, добавившего этот элемент меню
         * title - заголовок элемента меню
         * url - ссылка
         * attr_title - HTML-атрибут title ссылки
         * xfn - атрибут rel
         * target - атрибут target
         * current - равен 1, если является текущим элементов
         * current_item_ancestor - равен 1, если текущим является вложенный элемент
         * current_item_parent - равен 1, если текущим является вложенный элемент
         * menu_order - порядок в меню
         * object_id - ID объекта меню
         * type - тип объекта меню (таксономия, пост, произвольно)
         * object - какая это таксономия / какой тип поста (page /category / post_tag и т д)
         * type_label - название данного типа с локализацией (Рубрика, Страница)
         * post_parent - ID родительского поста / категории
         * post_title - заголовок, который был у поста, когда он был добавлен в меню
         * post_name - ярлык, который был у поста при его добавлении в меню
         */
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        /*
         * Генерируем строку с CSS-классами элемента меню
         */
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // функция join превращает массив в строку
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        /*
         * Генерируем ID элемента
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

        /*
         * Генерируем элемент меню
         */
        $output .= $indent . '<li' . $id . $value . $class_names .'>';

        // атрибуты элемента, title="", rel="", target="" и href=""
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        // ссылка и околоссылочный текст
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    $fields['shipping']['shipping_phone'] = array(
        'label'     => __('Phone', 'woocommerce'),
        'placeholder'   => _x('Phone', 'placeholder', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
    );

    return $fields;
}
///
function true_load_posts(){

    $next_page= $_POST['page'] + 1; // следующая страница
    $term_id= $_POST['term_id'];
    global $wp_query;
    $items = get_field('items_product_category', 'option');

    if (isset($_POST['order_by'])) {
        $order_by = $_POST['order_by'];

        switch ($order_by) {
            case 'popular':
                $args = array(
                    'post_type'		=> 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                        )
                    ),
                    'posts_per_page' => $items,
                    'paged' => $next_page,
                    'order'=>'DESC',
                    'meta_key'=>'views',
                    'orderby'=>'meta_value_num',

                    'meta_query' => array(
                        array(
                            'key' => '_regular_price', // name of custom field
                            'value' => '"0"', // matches exactly "red"
                            'compare' => '>'
                        )
                    )
                );

                break;
            case 'cheap':
                $args = array(
                    'post_type'		=> 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                        )
                    ),
                    'paged' => $next_page,
                    'order'=>'ASC',
                    'meta_key'=>'_regular_price',
                    'orderby' => 'meta_value_num',
                    'posts_per_page' => $items,

                    'meta_query' => array(
                        array(
                            'key' => '_regular_price', // name of custom field
                            'value' => '"0"', // matches exactly "red"
                            'compare' => '>'
                        )
                    )
                );

                break;
            case 'expensive':
                $args = array(
                    'post_type'		=> 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                        )
                    ),
                    'paged' => $next_page,
                    'order'=>'DESC',
                    'meta_key'=>'_regular_price',
                    'orderby' => 'meta_value_num',
                    'posts_per_page' => $items
                );

                break;
            case 'default':
                $args = array(
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                            'operator'  => 'IN'
                        )
                    ),
                    'posts_per_page' => $items,
                    'paged' => $next_page
                );

                break;
            case 'new':
                $args = array(
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                        )
                    ),
                    'posts_per_page' => $items,
                    'paged' => $next_page,
                    'meta_query' => array(
                        array(
                            'key' => 'status_product', // name of custom field
                            'value' => '"new"', // matches exactly "red"
                            'compare' => 'LIKE'
                        )
                    )
                );

                break;
            case 'actions':
                $args = array(
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                        )
                    ),
                    'posts_per_page' => $items,
                    'paged' => $next_page,
                    'meta_query' => array(
                        array(
                            'key' => '_sale_price', // name of custom field
                            'value' => '"0"', // matches exactly "red"
                            'compare' => '>'
                        )
                    )
                );

                break;
            default:
                $args = array(
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'id',
                            'terms' => $term_id,
                            'operator'  => 'IN'
                        )
                    ),
                    'posts_per_page' => $items,
                    'paged' => $next_page
                );

        }

    }
    else {

        $args = array(
            'post_type' => 'product',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $term_id,
                    'operator'  => 'IN'
                )
            ),
            'posts_per_page' => $items,
            'paged' => $next_page
        );
    }

global $wp;
    global $woocommerce;
    global $post;
    global $product;
    $featured_query = new WP_Query( $args );
    $total_page =  $featured_query->max_num_pages;
    $currenturl = add_query_arg($wp->query_string, '', home_url($wp->request));



    if ($featured_query->have_posts()) :
        while ($featured_query->have_posts()) :

            $featured_query->the_post();

            $product = get_product( $featured_query->post->ID );  ?>





<?
if ($_POST['view_catalog']== 'table') { ?>


    <div class="span9 catalog-content">
        <?
 $add_cart_url = apply_filters('woocommerce_loop_add_to_cart_link',
            sprintf('data-add_cart="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s"',
                esc_url($product->add_to_cart_url()),
                esc_attr(isset($quantity) ? $quantity : 1),
                esc_attr($product->id),
                esc_attr($product->get_sku()),
                esc_html($product->add_to_cart_text())
            ),
            $product);

        ?>
        <script>

            $(document).ready(function(){

                $('button.buy').click(function(event) {

                    var  url_add_cart = $(this).data("add_cart");
                    $( "<a href='<?php echo $woocommerce->cart->get_cart_url(); ?>' class='product_in_cart'>В КОРЗИНЕ</a>" ).insertAfter( this );

                    $(this).remove();
                    $.ajax({
                        url: url_add_cart,
                        success: function(){
                            $('#cart_count_product').removeClass ('hide');
                            var result =  $('.counts_mini_cart span').html();
                            result++;
                            $('.counts_mini_cart span').html(result);


                        }
                    });
                });
            });
        </script>
        <div class="span2">
            <?

            if (get_field('status_product') != '') {

                if (in_array('new', get_field('status_product')) or 'new' == get_field('status_product')) {
                    ?>

                    <div class="new">НОВИНКА</div>

                    <?php
                }

                ?>
                <?

                if (in_array('hits', get_field('status_product')) or 'hits' == get_field('status_product')) {
                    ?>

                    <div class="hits">ХИТЫ ПРОДАЖ</div>

                    <?php
                }
            } else {
                ?>
                <div class="default"></div>
                <?
            }
            ?>


            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(120, 130)); ?></a>
            <div class="line"></div>
        </div>
        <div class="span3 description_block">
            <a href="<?php the_permalink(); ?>"> <span class="title"><?php the_title(); ?> </span> </a>
            <? the_excerpt() ?>
        </div>
        <div class="span2 offset1 sale_block">
            <div class="title">
                <?php echo $product->get_price_html(); ?>
            </div>
            <div class="clearfix"></div>

            <div class="reviews"> 10 Отзывов</div>
            <div class="in_stock">
                <?

                if (!$product->is_in_stock()) {
                    ?>
                    <div class="in_stock">
                        <div class="ico_not_stock"></div>
                        <p>Нет в наличии</p></div>


                    <?
                    echo woo_archive_custom_cart_button_text($add_cart_url);
                    wc_get_template( 'compare.php', $args = $product->id  );
                    ?>
                    <?
                }
                else {
                ?>
                <div class="ico_in_stock"></div>
                <p>В наличии</p></div>

            <?
            echo woo_archive_custom_cart_button_text($add_cart_url);
            wc_get_template( 'compare.php', $args = $product->id  );
            }
            ?>



        </div>
    </div>

    <?php
}
    ?>

        <?php    if ($_POST['view_catalog']== 'grid') { ?>
            <div class="span3 item">
                <?
                $add_cart_url =  apply_filters( 'woocommerce_loop_add_to_cart_link',
                    sprintf( 'data-add_cart="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s"',
                        esc_url( $product->add_to_cart_url() ),
                        esc_attr( isset( $quantity ) ? $quantity : 1 ),
                        esc_attr( $product->id ),
                        esc_attr( $product->get_sku() ),
                        esc_html( $product->add_to_cart_text() )
                    ),
                    $product );

                ?>
                <?

                if ( get_field('status_product') != '') {

                    if (in_array('new', get_field('status_product')) or 'new' == get_field('status_product')) {
                        ?>

                        <div class="new">НОВИНКА</div>

                        <?php
                    }

                    ?>
                    <?

                    if (in_array('hits', get_field('status_product')) or 'hits' == get_field('status_product')) {
                        ?>

                        <div class="hits">ХИТЫ ПРОДАЖ</div>

                        <?php
                    }
                }
                else {
                    ?>
                    <div class="default"></div>
                    <?
                }
                ?>
                <a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
                    <?php echo $product->get_image(); ?>

                </a>
                <div class="line"></div>
                <span class="title_item"><?php echo $product->get_title(); ?></span>

                <span class="price"><?php echo $product->get_price_html(); ?></span>
                <div class="clearfix"></div>

                <?
                echo woo_archive_custom_cart_button_text($add_cart_url);

                wc_get_template( 'compare.php', $args = $product->id  );
                ?>

            </div>
            <?php
        }
            ?>

        <?php endwhile; ?>


        <?
    endif;
    ?>
    <?
    if ($next_page == $total_page) {
        echo "<script type='text/javascript'>$(document.body).ready(function () {  $('#true_loadmore').hide(); });</script>";
        wp_reset_postdata();
        die();
    }
    die();
}


add_action('wp_ajax_loadmore', 'true_load_posts');
add_action('wp_ajax_nopriv_loadmore', 'true_load_posts');
add_action('wp_ajax_clearallcompareajax', 'clear_all_compare_callback');
add_action('wp_ajax_nopriv_clearallcompareajax', 'clear_all_compare_callback');
function clear_all_compare_callback(){
    WC_Compare_Functions::clear_compare_list();
    echo json_encode('1');
    die();
}

add_action('wp_ajax_removecompareajax', 'remove_product_compare_callback');
add_action('wp_ajax_nopriv_removecompareajax', 'remove_product_compare_callback');
function remove_product_compare_callback(){
    $product_id  = $_POST['product_id'];
    WC_Compare_Functions::delete_product_on_compare_list($product_id);


    echo json_encode('1');
    die();
}


apply_filters('woocommerce_products_compare_max_products', 3 );
/////// Платежи наличными курьеру /////


class WC_Gateway_Cash_Oncore extends WC_Payment_Gateway {

    public function WC_Gateway_Cash_Oncore () {
        $this->id = 'cash_oncore';
        $this->has_fields = false; // false
        $this->method_title = 'Оплата при получении';
        $this->method_description = 'Оплата при получении';
        $this->init_form_fields();
        $this->init_settings();

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }
    public function payment_fields() {


    }

    public function init_form_fields()  {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __( 'Включить/Выключить', 'woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Оплата при получении', 'woocommerce' ),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __( 'Оплата при получении', 'woocommerce' ),
                'type' => 'text',
                'description' => __( 'Это Заголовок который видит пользователь пользователь.', 'woocommerce' ),
                'default' => __( 'Оплата при получении', 'woocommerce' ),
                'desc_tip'      => true,
            ),
            'description' => array(
                'title' => __( 'Сообщение', 'woocommerce' ),
                'type' => 'textarea',
                'default' => ''
            )
        );
        add_option( 'myhack_extraction_length', '255', '', 'yes' );
    }



    public function process_payment( $order_id ) {
        global $woocommerce;
        $order = new WC_Order( $order_id );
        // Доставка//
        update_post_meta( $order_id, '_shipping_first_name', wc_clean( $_POST[ 'billing_first_name' ] ));
        update_post_meta( $order_id, '_shipping_last_name', wc_clean( $_POST[ 'billing_last_name' ] ));
        update_post_meta( $order_id, '_shipping_sur_name', wc_clean( $_POST[ 'billing_sur_name' ] ));
        update_post_meta( $order_id, '_shipping_country', 'BY');

        foreach ($_POST["shipping_method"] as $method_shipping) {


        }

        switch ($method_shipping) {
            case 'couier':

               update_post_meta( $order_id, '_shipping_state', wc_clean($_POST['shipping_state_couier']));
               update_post_meta( $order_id, '_shipping_city', wc_clean($_POST['shipping_city_couier']));
               update_post_meta( $order_id, '_shipping_address_1', wc_clean($_POST['shipping_address_2_couier']));
               update_post_meta( $order_id, '_shipping_postcode', 'N/A');




                break;
            case 'mail':
                update_post_meta( $order_id, '_shipping_state', wc_clean($_POST['shipping_state_mail']));
                update_post_meta( $order_id, '_shipping_city', wc_clean($_POST['shipping_city_mail']));
                update_post_meta( $order_id, '_shipping_address_1', wc_clean($_POST['shipping_address_2_mail']));
                update_post_meta( $order_id, '_shipping_postcode', wc_clean($_POST['shipping_postcode_mail']));
                break;
            case 'local_self_export':
                update_post_meta( $order_id, '_shipping_state','N/A');
                update_post_meta( $order_id, '_shipping_city', 'N/A');
                update_post_meta( $order_id, '_shipping_address_1', 'Самовывоз');
                update_post_meta( $order_id, '_shipping_postcode', 'N/A');
                break;
        }
        // Отметка (мы ожидаем чек)
        $order->update_status('on-hold', __( 'В ожидании оплаты курьеру', 'woocommerce' ));

        // Уменьшение уровня запасов
        $order->reduce_order_stock();

        // Очистка корзины
        $woocommerce->cart->empty_cart();

        // Редирект на страницу благодарности(успешной орплаты)
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url( $order )
        );



    }
    public function validate_fields() {

        foreach ($_POST["shipping_method"] as $method_shipping) {


        }

        switch ($method_shipping) {
            case 'couier':
                if( empty ($_POST['shipping_state_couier']) || empty ($_POST['shipping_city_couier']  ) || empty ($_POST['shipping_address_2_couier']) ){
                    $error_message = 'Заполните все реквизиты для доставки';
                    wc_add_notice( __('Ошибка!', 'woothemes') . $error_message, 'error' );
                    return false;
                }
                break;
            case 'mail':
                if( empty ($_POST['shipping_state_mail']) || empty ($_POST['shipping_city_mail']  ) || empty ($_POST['shipping_address_2_mail']) || empty ($_POST['shipping_postcode_mail']) ){
                    $error_message = 'Заполните все реквизиты для доставки';
                    wc_add_notice( __('Ошибка оплаты:', 'woothemes') . $error_message, 'error' );
                    return false;
                }
                break;

        }


    }
}


add_action('plugins_loaded', 'cash_gateway_class');
function cash_gateway_class()
{
    class WC_Gateway_Cash_Oncore extends WC_Payment_Gateway
    {
    }
}

function add_cash_gateway_class($methods)
{
    $methods[] = 'WC_Gateway_Cash_Oncore';
    return $methods;
}


add_filter('woocommerce_payment_gateways', 'add_cash_gateway_class');

//// Платежи для юр.лиц ////
class WC_Gateway_Business_Oncore extends WC_Payment_Gateway {

    public function WC_Gateway_Business_Oncore () {
        $this->id = 'business_oncore';
        $this->has_fields = true; // false
        $this->method_title = 'Банковский перевод (для юр. лиц)';
        $this->method_description = 'Банковский перевод (для юр. лиц)';
        $this->init_form_fields();
        $this->init_settings();

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }
    public function payment_fields() {


    }

    public function init_form_fields()  {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __( 'Включить/Выключить', 'woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Включить банковский перевод (для юр. лиц)', 'woocommerce' ),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __( 'Банковский перевод (для юр. лиц)', 'woocommerce' ),
                'type' => 'text',
                'description' => __( 'Это Заголовок который видит пользователь пользователь.', 'woocommerce' ),
                'default' => __( 'Банковский перевод (для юр. лиц)', 'woocommerce' ),
                'desc_tip'      => true,
            ),
            'description' => array(
                'title' => __( 'Сообщение', 'woocommerce' ),
                'type' => 'textarea',
                'default' => ''
            )
        );
        add_option( 'myhack_extraction_length', '255', '', 'yes' );
    }



    public function process_payment( $order_id ) {
        global $woocommerce;
        $order = new WC_Order( $order_id );
        $name_company = wc_clean($_POST['company_name']);
        $company_address = wc_clean($_POST['company_address']);
        $account_number = wc_clean($_POST['account_number']);
        $bank_name = wc_clean($_POST['bank_name']);
        $bank_code= wc_clean($_POST['bank_code']);
        $unp = wc_clean($_POST['unp']);
        add_post_meta($order_id, 'buisness_company_name', $name_company);
        add_post_meta($order_id, 'buisness_company_address', $company_address);
        add_post_meta($order_id, 'buisness_account_number', $account_number);
        add_post_meta($order_id, 'buisness_bank_name', $bank_name);
        add_post_meta($order_id, 'buisness_bank_code', $bank_code);
        add_post_meta($order_id, 'buisness_unp', $unp);
        // Доставка//
       update_post_meta( $order_id, '_shipping_first_name', wc_clean( $_POST[ 'billing_first_name' ] ));
        update_post_meta( $order_id, '_shipping_last_name', wc_clean( $_POST[ 'billing_last_name' ] ));
        update_post_meta( $order_id, '_shipping_sur_name', wc_clean( $_POST[ 'billing_sur_name' ] ));
        update_post_meta( $order_id, '_shipping_country', 'BY');
        foreach ($_POST["shipping_method"] as $method_shipping) {


        }
        switch ($method_shipping) {
            case 'couier':

                update_post_meta( $order_id, '_shipping_state', wc_clean($_POST['shipping_state_couier']));
                update_post_meta( $order_id, '_shipping_city', wc_clean($_POST['shipping_city_couier']));
                update_post_meta( $order_id, '_shipping_address_1', wc_clean($_POST['shipping_address_2_couier']));
                update_post_meta( $order_id, '_shipping_postcode', 'Индекс: N/A');




                break;
            case 'mail':
                update_post_meta( $order_id, '_shipping_state', wc_clean($_POST['shipping_state_mail']));
                update_post_meta( $order_id, '_shipping_city', wc_clean($_POST['shipping_city_mail']));
                update_post_meta( $order_id, '_shipping_address_1', wc_clean($_POST['shipping_address_2_mail']));
                update_post_meta( $order_id, '_shipping_postcode', wc_clean($_POST['shipping_postcode_mail']));
                break;
            case 'local_self_export':
                update_post_meta( $order_id, '_shipping_state','N/A');
                update_post_meta( $order_id, '_shipping_city', 'N/A');
                update_post_meta( $order_id, '_shipping_address_1', 'Самовывоз');
                update_post_meta( $order_id, '_shipping_postcode', 'N/A');
                break;
        }

        // Отметка (мы ожидаем чек)
        $order->update_status('on-hold', __( 'В ожидании оплаты по безналу (юр. лица)', 'woocommerce' ));

        // Уменьшение уровня запасов
        $order->reduce_order_stock();

        // Очистка корзины
        $woocommerce->cart->empty_cart();

        // Редирект на страницу благодарности(успешной орплаты)
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url( $order )
        );



    }
    public function validate_fields() {

        foreach ($_POST["shipping_method"] as $method_shipping) {


        }

        if (  empty ($_POST['company_name']) || empty ($_POST['company_address']  ) || empty ($_POST['account_number']  ) || empty ($_POST['bank_name']  ) || empty ($_POST['bank_code']  )|| empty ($_POST['unp']  )){
            $error_message = 'Заполните все реквизиты Вашей компании';

                wc_add_notice(__('Ошибка оплаты:', 'woothemes') . $error_message, 'error');


            return false;
        }

        switch ($method_shipping) {
            case 'couier':
                if( empty ($_POST['shipping_state_couier']) || empty ($_POST['shipping_city_couier']  ) || empty ($_POST['shipping_address_2_couier']) ){
                    $error_message = 'Заполните все реквизиты для доставки';
                    wc_add_notice( __('Ошибка!', 'woothemes') . $error_message, 'error' );
                    return false;
                }
                break;
            case 'mail':
                if( empty ($_POST['shipping_state_mail']) || empty ($_POST['shipping_city_mail']  ) || empty ($_POST['shipping_address_2_mail']) || empty ($_POST['shipping_postcode_mail']) ){
                    $error_message = 'Заполните все реквизиты для доставки';
                    wc_add_notice( __('Ошибка оплаты:', 'woothemes') . $error_message, 'error' );
                    return false;
                }
                break;

        }


    }
}


add_action('plugins_loaded', 'business_gateway_class');
function business_gateway_class()
{
    class WC_Gateway_Business_Oncore extends WC_Payment_Gateway
    {
    }
}

function add_business_gateway_class($methods)
{
    $methods[] = 'WC_Gateway_Business_Oncore';
    return $methods;
}


add_filter('woocommerce_payment_gateways', 'add_business_gateway_class');
// Добавьте следующий код в файл functions.php темы для того, чтобы добавить способ оплаты во ВСЕ письма
add_action( 'woocommerce_email_after_order_table', 'wc_add_payment_type_to_emails', 15, 2 );
function wc_add_payment_type_to_emails( $order, $is_admin_email ) {
    global $woocommerce;
    $payment_gateways = WC()->payment_gateways->payment_gateways();
    $payment_method = $order->payment_method;


    echo '<p><strong>Способ оплаты:</strong> ' .  $payment_gateways[ $payment_method ]->method_title . '</p>';
}





?>
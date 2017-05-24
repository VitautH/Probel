<?
global $product_compare_id;
global $woocommerce;
global $post;

global $woo_compare_grid_view_settings, $woo_compare_grid_view_button_style;
global $woo_compare_comparison_page_global_settings;
global $woo_compare_gridview_view_compare_style;
 $product_id = $args;
?>
<?
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$term_id = get_term_by( 'name', $term->name, 'product_cat');
$id_term= $term_id->term_id;
$result = WC_Compare_Functions::get_total_compare_list();


if (WC_Compare_Functions::check_product_activate_compare($product_id) && WC_Compare_Functions::check_product_have_cat($product_id)) {

    $widget_compare_popup_view_button = '';
    if ($woo_compare_comparison_page_global_settings['open_compare_type'] != 'new_page') $widget_compare_popup_view_button = 'woo_bt_view_compare_popup';

    $compare_grid_view_custom_class = '';
    $compare_grid_view_text = $woo_compare_grid_view_button_style['button_text'];
    $compare_grid_view_class = 'woo_bt_compare_this_button';
    if ($woo_compare_grid_view_button_style['grid_view_button_type'] == 'link') {
        $compare_grid_view_custom_class = '';
        $compare_grid_view_text = $woo_compare_grid_view_button_style['link_text'];
        $compare_grid_view_class = 'woo_bt_compare_this_link';
    }

    $view_compare_html = '';
    if ($woo_compare_gridview_view_compare_style['disable_gridview_view_compare'] == 0) {
        $gridview_view_compare_custom_class = '';
        $gridview_view_compare_text = $woo_compare_gridview_view_compare_style['gridview_view_compare_link_text'];
        $gridview_view_compare_class = 'woo_bt_view_compare_link';

        $product_compare_page = get_permalink($product_compare_id);
        if ($woo_compare_comparison_page_global_settings['open_compare_type'] != 'new_page') {
            $product_compare_page = '#';
        }

    }

    $compare_html = '<a data-category_compare="' . $id_term . '" class="woo_bt_compare_this ' . $compare_grid_view_class . ' ' . $compare_grid_view_custom_class . '" id="woo_bt_compare_this_' . $product_id . '"><div class="button_compare"></div></a>' . $view_compare_html . '<input type="hidden" id="input_woo_bt_compare_this_' . $product_id . '" name="product_compare_' . $product_id . '" value="' . $product_id . '" />';
    $compare_active_current = new WC_Compare_Functions();
    ?>
    <?php if ($compare_active_current->check_products_compare($product_id) == 1) {
        ?>
        <a href="<? echo $product_compare_page; ?>">
            <div class="button_compare_active"></div>
        </a>
        <?php

    } else {
        if (!isset($_COOKIE['compare_category']) || $_COOKIE['compare_category'] == $id_term || $_COOKIE['compare_category'] == 'null' && json_encode( $result )<=3 ) {
            echo $compare_html;
        }
        else {

            ?>
            <a href="#">
                <div class="button_compare_none"></div>
            </a>
            <?


        }
    }

}




?>

<?php

/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Compare
 *
 * @package storefront
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function oncore_style_catalog() {
	wp_enqueue_style('oncore_style', get_stylesheet_directory_uri() . '/css/compare.css' );
}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
global $woocommerce;
global $post, $product;
global $woo_compare_grid_view_settings, $woo_compare_grid_view_button_style;
global $woo_compare_comparison_page_global_settings;
global $woo_compare_gridview_view_compare_style;
global $product_compare_id;
$product_cats = array();
$products_fields = array();
// Variables for JS scripts
$woocommerce_params = array(
	'ajax_url'                         => WC()->ajax_url(),
	'ajax_loader_url'                  => apply_filters( 'woocommerce_ajax_loader_url', str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/images/ajax-loader@2x.gif' ),
	'i18n_view_cart'                   => $woo_compare_viewcart_style['viewcart_text'],
	'cart_url'                         => get_permalink( wc_get_page_id( 'cart' ) ),
	'is_cart'						   => false,
	'cart_redirect_after_add'          => get_option( 'woocommerce_cart_redirect_after_add' )
);

$wc_frontend_scripts_class = new WC_Frontend_Scripts();
$wc_frontend_scripts_class->load_scripts();
$close_custom_class = '';
$close_button_text = $woo_compare_close_window_button_style['close_link_text'];
$close_button_class = 'compare_close_link_type';
if ($woo_compare_close_window_button_style['close_button_type'] == 'button') {
	$close_button_class = 'compare_close_button_type';
	$close_custom_class = '';
	$close_button_text = $woo_compare_close_window_button_style['button_text'];
}
$result = WC_Compare_Functions::get_total_compare_list();
get_header( 'shop' );


?>
<script>
	jQuery(function ($) {
		var ajaxurl = '<?php echo admin_url("admin-ajax.php", null); ?>';
		var data = {
			action: 'clearallcompareajax'


		};
		$( ".clear_compare" ).click(function() {

			jQuery.ajax({
				url: ajaxurl,
				data: data,
				dataType: 'json',
				type: 'post',
				success: function(response) {
					if (response == '1'){

						location.reload();
					}
				}
			});
		});

	});

    jQuery(function ($) {

        $( ".clear_compare_product" ).click(function() {
            var ajaxurl = '<?php echo admin_url("admin-ajax.php", null); ?>';
            var product_id = $(this).data( "product_id" );
            var data = {
                action: 'removecompareajax',
                product_id: product_id


            };
            jQuery.ajax({
                url: ajaxurl,
                data: data,
                dataType: 'json',
                type: 'post',
                success: function(response) {
                    if (response == '1'){

                        location.reload();
                    }
                }
            });
        });

    });
</script>
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
	<aside>
		<div class="container"><div class="row"><div class="span12">
					<div class="breadcrumb span3">
						<?php
						$args = array(
							'delimiter' => ' > '
						);
						?>
						<?php woocommerce_breadcrumb( $args ); ?>

					</div>
					<?php if (json_encode($result) != 0) {
					?>


					<div class="span3 offset5"><a href="#" class="clear_compare">

							<div class="product-remove"></div>
						</a>
						<span class="title_compare">В СРАВНЕНИИ <?php echo json_encode($result); ?> ТОВАРА</span></div>
				</div>
			</div>
		</div>

	</aside>

	<div class="container">
		<div class="row">
			<div class="span12 compare_content">
				<?php
				echo WC_Compare_Functions::get_compare_list_html_popup();
				?>
</div>
</div>
</div>

	<?
}
else {
	?>
	<div class="span3 offset5"> <span class="title_compare">НЕТ ТОВАРОВ ДЛЯ СРАВНЕНИЯ</span></div>
	</div></div></div>

	</aside>
	<div class="container">
		<div class="row">
			<div class="span12 compare_empty">
				<div class="row">
					<div class="span5 offset4 compare_empty_content">
						<div class="ico_compare_empty"></div>
						<span class="title">Товары для сравнения не выбраны</span><br>
						<span class="tooltip">Перейдите в каталог для выбора</span>

					</div>
					<div class="row">
						<div class="span5 offset4">
							<a class="offset1 button wc-backward" href="<? echo get_site_url(); ?>">
								<div class="return-to-shop">Вернуться в магазин</div>

							</a>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?
}
	?>
<?php get_footer( 'shop' ); ?>
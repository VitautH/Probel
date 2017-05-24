<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function oncore_style_catalog() {
    wp_enqueue_style('oncore_style', get_stylesheet_directory_uri() . '/css/catalog_horiz.css' );
    wp_enqueue_script( 'script', get_template_directory_uri() . '/js/dropdown.js', true);
}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
?>
<?
get_header( 'shop' );
global $wp;

$currenturl = add_query_arg($wp->query_string, '', home_url($wp->request));
global $woocommerce;
global $post;
global $woo_compare_grid_view_settings, $woo_compare_grid_view_button_style;
global $woo_compare_comparison_page_global_settings;
global $woo_compare_gridview_view_compare_style;
global $product_compare_id;

$items = $woocommerce->cart->get_cart();

$cart_product = reset($items);

?>
<script>
    $( document ).ready(function() {
        if ($.cookie("catalog_view_type") != null){
            var catalog_view_type = $.cookie("catalog_view_type");

            $('.'+catalog_view_type+'_ico').addClass('active');

        }

        $('.grid_ico').click(function () {
            $.removeCookie("catalog_view_type");
            $.cookie("catalog_view_type", 'grid', {
                expires : 1000,           //expires in 10 days

                path    : '/' });
            $('.grid_ico').addClass('active');
            $('.table_ico').removeClass('active');
        });
        $('.table_ico').click(function () {
            $.removeCookie("catalog_view_type");
            $.cookie("catalog_view_type", 'table', {
                expires : 1000,           //expires in 10 days

                    path    : '/' });
            $('.table_ico').addClass('active');
            $('.grid_ico').removeClass('active');
        });
    });
</script>

<aside>
    <div class="container">
        <div class="row" style="margin-top: 31px;">
            <div class="span12">
                <div class="breadcrumb span4">
                    <?php
                    $args = array(
                        'delimiter' => ' > '
                    );
                    ?>
                    <?php woocommerce_breadcrumb( $args ); ?>

                </div>

                <?php
                $items = get_field('items_product_category', 'option');
                $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                $term_id = get_term_by( 'name', $term->name, 'product_cat');
                $id= $term_id->term_id;

                if (isset($_GET['order_by'])) {
                    $order_by = $_GET['order_by'];
                    switch ($order_by) {
                        case 'popular':
                            $args = array(
                                'post_type'		=> 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),
                                'posts_per_page' => $items,

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
                            $current_order_by = 'По популярности';
                            break;
                        case 'cheap':
                            $args = array(
                                'post_type'		=> 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),
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
                            $current_order_by = 'От дешёвых';
                            break;
                        case 'expensive':
                            $args = array(
                                'post_type'		=> 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),
                                'order'=>'DESC',
                                'meta_key'=>'_regular_price',
                                'orderby' => 'meta_value_num',
                                'posts_per_page' => $items
                            );
                            $current_order_by = 'От дорогих';
                            break;
                        case 'default':
                            $args = array(
                                'post_type' => 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),

                                'posts_per_page' => $items
                            );
                            $current_order_by = 'По умолчанию';
                            break;
                        case 'new':
                            $args = array(
                                'post_type' => 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),
                                'posts_per_page' => $items,
                                'meta_query' => array(
                                    array(
                                        'key' => 'status_product', // name of custom field
                                        'value' => '"new"', // matches exactly "red"
                                        'compare' => 'LIKE'
                                    )
                                )
                            );
                            $current_order_by = 'Новинки';
                            break;
                        case 'actions':
                            $args = array(
                                'post_type' => 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),
                                'posts_per_page' => $items,

                                'meta_query' => array(
                                    array(
                                        'key' => '_sale_price', // name of custom field
                                        'value' => '"0"', // matches exactly "red"
                                        'compare' => '>'
                                    )
                                )
                            );
                            $current_order_by = 'Акционные';
                            break;
                        default:
                            $args = array(
                                'post_type' => 'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'id',
                                        'terms' => $id,
                                    )
                                ),
                                'posts_per_page' => $items
                            );
                            $current_order_by = 'По умолчанию';
                    }

                }
                else {
                    $current_order_by = 'По умолчанию';
                    $args = array(
                        'post_type' => 'product',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'id',
                                'terms' => $id,
                            )
                        ),
                        'posts_per_page' => $items
                    );
                }
                ?>
                <script>
                    $( document ).ready(function() {
                        if ($.cookie("order_by") != null){
                            $('.order').removeClass('current');
                            var order_by = $.cookie("order_by");

                            $('#'+order_by).addClass('current');

                        }
                        else {
                            var order_by = 'default';
                            $('#default').addClass('current');
                        }

                        $('#menu3').children('li').click(function(){
                            var current_id = $(this).children('a').attr('id');

                            $.removeCookie("order_by");
                            $.cookie("order_by", current_id, {
                                expires : 1000,           //expires in 10 days

                                path    : '/' });

                        });

                    })
                </script>

                <div class="span2 offset4 sort_name">

                    <ul class="nav nav-pills">

                        <li class="dropdown">
                            <a class="dropdown-toggle sorting" id="drop5" role="button" data-toggle="dropdown" href="#"><? echo $current_order_by; ?><b class="caret"></b></a>
                            <ul id="menu3" class="dropdown-menu" role="menu" aria-labelledby="drop5">
                                <li role="presentation"><a class="order" id="default" role="menuitem" tabindex="-1" href="?order_by=default">По умолчанию</a></li>
                                <li role="presentation"><a class="order" id="popular" role="menuitem" tabindex="-1" href="?order_by=popular">Популярные</a></li>
                                <li role="presentation"><a class="order" id="cheap" role="menuitem" tabindex="-1" href="?order_by=cheap">От дешёвых</a></li>
                                <li role="presentation"><a class="order" id="expensive" role="menuitem" tabindex="-1" href="?order_by=expensive">От дорогих</a></li>
                                <li role="presentation"><a class="order" id="new" role="menuitem" tabindex="-1" href="?order_by=new">Новинки</a></li>
                                <li role="presentation"><a class="order" id="actions" role="menuitem" tabindex="-1" href="?order_by=actions">Акционные</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="span1 toogle_view">
                   <a href="<? echo $currenturl ?>"><div class="grid_ico"></div></a>
                    <a href="<? echo $currenturl ?>"> <div class="table_ico"></div></a>
                </div>
            </div>

        </div>
    </div>
</aside>

<div class="container">
    <div class="row"> <aside class="filter">
            <div class="span3 filter-block">

<form>
                <?php if (!dynamic_sidebar("Sidebar") ) : ?>


                <?php endif; ?>
</form>
            </div>
        </aside>


        <article>

          <?php


            $loop = new WP_Query( $args );
          $total_page =  $loop->max_num_pages;
            if ( $loop->have_posts() ) {
            while ($loop->have_posts()) : $loop->the_post();



                global $product;
                $product_id = $product->id;
                global $product_id;

                ?>

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
                       <? $review_count = $product->get_review_count();?>
                        <div class="reviews"> <? echo $review_count; ?> Отзывов</div>
                        <div class="in_stock">
                            <?

                            if (!$product->is_in_stock()) {
                                ?>
                                <div class="in_stock">
                                    <div class="ico_not_stock"></div>
                                    <p>Нет в наличии</p></div>

                                <?php

                                echo woo_archive_custom_cart_button_text($add_cart_url);








                                wc_get_template( 'compare.php', $args = $product->id  );

                            }

                            else {
                            ?>
                            <div class="ico_in_stock"></div>
                            <p>В наличии</p></div>
                        <?php


                        echo woo_archive_custom_cart_button_text($add_cart_url);

                        wc_get_template('compare.php', $args = $product->id);

                        }
                        ?>




                    </div>
                </div>
            <?

            endwhile;
            if ($total_page > 1)  { ?>
                <div id="true_loadmore" class="span4"><span>Загрузить ещё</span></div> <? } ?>

            <?php if ($total_page > 1) : ?>
                <script>

                    jQuery(function ($) {
                        var ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
                        var true_posts = '<?php echo serialize($loop->query_vars); ?>';
                        var current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
                        var max_pages = '<?php echo $total_page; ?>';

                        $('#true_loadmore').click(function () {
                            var get = location.search;

                            if ($.cookie("order_by") != null) {

                                var order_by = $.cookie("order_by");


                            }
                            else {
                                var order_by = 'default';

                            }
                            $(this).empty();
                            $(this).append( "<span>Загружаю</span>" ); // ???????? ????? ??????, ?? ????? ?????? ???????? ?????????
                            var data = {
                                'action': 'loadmore',
                                'query': true_posts,
                                'order_by': order_by,
                                'view_catalog': 'table',
                                'page': current_page,
                                'filters': get,
                                'term_id': '<?php echo $id; ?>'
                            };
                            $.ajax({
                                url: ajaxurl, // ??????????
                                data: data, // ??????
                                type: 'POST', // ??? ???????
                                success: function (data) {
                                    if (data) {
                                        $('#true_loadmore').empty();
                                        $('#true_loadmore').append( "<span>Загрузить ещё</span>" ).before(data); // ????????? ????? ?????
                                        current_page++; // ??????????? ????? ???????? ?? ???????
                                        if (current_page == max_pages) $("#true_loadmore").hide();
                                        // ???? ????????? ????????, ??????? ??????

                                    } else {
                                        $('#true_loadmore').hide(); // ???? ?? ????? ?? ????????? ???????? ??????, ?????? ??????

                                    }
                                }
                            });
                        });
                    });
                </script>
            <?
            endif;
            ?>
            <?
            }
            else {
                ?>
            <div class="span9 catalog-empty">
                <div class="span4 offset2 catalog_empty_content "><div class="ico_catalog_empty"></div> <span class="title">Товары не найдены</span><br>
                    <span class="tooltip">Измените критерии поиска</span>
                </div>
                </div>
            <?

            }
            wp_reset_postdata();
            ?>













        </article>

    </div>


    </aside>
</div>




                <?php if (!dynamic_sidebar("Oncore Bottom") ) : ?>



                <?php endif; ?>
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


<?php get_footer( 'shop' ); ?>

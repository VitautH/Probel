<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


global $wp, $woocommerce;
if ($_GET['filters']) {
    $catalog_view_type = $_COOKIE['catalog_view_type'];
    wc_get_template('archive-product-' . $catalog_view_type . '.php');
}
else {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

    $term_id = get_term_by('name', $term->name, 'product_cat');

    $id = $term_id->term_id;

    $args = array(

        'orderby' => 'ID',

        'order' => 'ASC',
        'hide_empty' => false,

        'exclude' => array(),

        'exclude_tree' => array(),

        'include' => array(),

        'number' => '',

        'fields' => 'all',

        'slug' => '',

        'parent' => $id,

        'hierarchical' => true,

        'child_of' => 0,

        'get' => '', // СЃС‚Р°РІРёРј all С‡С‚РѕР±С‹ РїРѕР»СѓС‡РёС‚СЊ РІСЃРµ С‚РµСЂРјРёРЅС‹

        'name__like' => '',

        'pad_counts' => false,

        'offset' => '',

        'search' => '',

        'cache_domain' => 'core',

        'name' => '', // str/arr РїРѕР»Рµ name РґР»СЏ РїРѕР»СѓС‡РµРЅРёСЏ С‚РµСЂРјРёРЅР° РїРѕ РЅРµРјСѓ. C 4.2.

        'childless' => false, // true РЅРµ РїРѕР»СѓС‡РёС‚ (РїСЂРѕРїСѓСЃС‚РёС‚) С‚РµСЂРјРёРЅС‹ Сѓ РєРѕС‚РѕСЂС‹С… РµСЃС‚СЊ РґРѕС‡РµСЂРЅРёРµ С‚РµСЂРјРёРЅС‹. C 4.2.

        'update_term_meta_cache' => true, // РїРѕРґРіСЂСѓР¶Р°С‚СЊ РјРµС‚Р°РґР°РЅРЅС‹Рµ РІ РєСЌС€

        'meta_query' => ''

    );


    $myterms = get_terms(array('product_cat'), $args);


    $size = count($myterms);
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $term_id = get_term_by('name', $term->name, 'product_cat');
    $id = $term_id->term_id;
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $id,
            )
        ),
        'posts_per_page' => 1
    );
    $loop = new WP_Query($args);

    if ($size > 0 or !$loop->have_posts()) {
        wc_get_template('archive-parents.php');
    } else {
if ($_COOKIE['catalog_view_type']) {
        $catalog_view_type = $_COOKIE['catalog_view_type'];
        wc_get_template('archive-product-'.$catalog_view_type.'.php');
}
else {
  wc_get_template('archive-product-table.php');
}
    }
}
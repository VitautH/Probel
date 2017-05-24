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

    wp_enqueue_style('oncore_style', get_stylesheet_directory_uri() . '/css/archive_parents.css' );


}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
?>
<?
get_header( 'shop' );
global $wp;
$currenturl = add_query_arg($wp->query_string, '', home_url($wp->request));
global $woocommerce;
$items = $woocommerce->cart->get_cart();


$cart_product = reset($items);

?>
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
            </div>

        </div>
    </div>
</aside>

<?







$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

$term_id = get_term_by( 'name', $term->name, 'product_cat');

$id= $term_id->term_id;

$args = array(

    'orderby'       => 'ID',

    'order'         => 'ASC',
    'hide_empty'    => true,

    'exclude'       => array(),

    'exclude_tree'  => array(),

    'include'       => array(),

    'number'        => '',

    'fields'        => 'all',

    'slug'          => '',

    'parent'         => $id,

    'hierarchical'  => true,

    'child_of'      => 0,

    'get'           => '', // СЃС‚Р°РІРёРј all С‡С‚РѕР±С‹ РїРѕР»СѓС‡РёС‚СЊ РІСЃРµ С‚РµСЂРјРёРЅС‹

    'name__like'    => '',

    'pad_counts'    => false,

    'offset'        => '',

    'search'        => '',

    'cache_domain'  => 'core',

    'name'          => '', // str/arr РїРѕР»Рµ name РґР»СЏ РїРѕР»СѓС‡РµРЅРёСЏ С‚РµСЂРјРёРЅР° РїРѕ РЅРµРјСѓ. C 4.2.

    'childless'     => false, // true РЅРµ РїРѕР»СѓС‡РёС‚ (РїСЂРѕРїСѓСЃС‚РёС‚) С‚РµСЂРјРёРЅС‹ Сѓ РєРѕС‚РѕСЂС‹С… РµСЃС‚СЊ РґРѕС‡РµСЂРЅРёРµ С‚РµСЂРјРёРЅС‹. C 4.2.

    'update_term_meta_cache' => true, // РїРѕРґРіСЂСѓР¶Р°С‚СЊ РјРµС‚Р°РґР°РЅРЅС‹Рµ РІ РєСЌС€

    'meta_query'    => ''

);



$myterms = get_terms( array( 'product_cat' ), $args );

//print_r($myterms);

$size = count($myterms);







?>
<div class="container">
    <div class="row">
        <article>
            <h1>Категория <?php echo $term->name; ?></h1>

<?



if ($size != 0) {
    ?>
            <div class="content">
                <?
    for ($i = 0; $i < $size; $i++) {


        ?>

        <div class="item">

            <a href="<? echo $myterms[$i]->slug ?>"> <? echo $myterms[$i]->name; ?></a>

        </div>


        <?


    };
    ?>
                </div>

            <?
}
else {
?>
      <div class="row">
          <div class="contetnt_none span6 offset4"><p> Извините! В данный момент нет товаров в данной категории.</p>
</div>
          </div>
          <div class="row">
                <div class="span4 offset5">
                    <a class="offset1 button wc-backward" href="<?php echo get_site_url(); ?>">
                        <div class="return-to-shop"><?php _e( 'Return To Shop', 'woocommerce' ) ?></div>

                    </a>


            </div>
</div>

                <?php }
                ?>

            </article>
        </div>
    </div>





<?php if (!dynamic_sidebar("Oncore Bottom") ) : ?>



<?php endif; ?>



<?php get_footer( 'shop' ); ?>

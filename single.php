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

    wp_enqueue_style('oncore_style', get_stylesheet_directory_uri() . '/css/news.css' );


}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
?>
<?
get_header( 'shop' );
global $wp;


?>
<aside>
    <div class="container">
        <div class="row" style="margin-top: 31px;">
            <div class="span12">
                <div class="breadcrumb span6">
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
<div class="container">
    <div class="row">
        <div class="span12">


<?php

while( have_posts() ) : the_post();
    ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $has_img . ' blog-post-side-layout' ); ?>>


    <div class="img" style="background-image:url('<?php
    the_post_thumbnail_url( array(1140, 340) );   ?>');">
    <div class="blackout"> <h1>  <?php the_title();
        ?>
          </h1>
        </div>
        </div>

    

    

    	<div class="content">
    		<?php

                the_content();
    		?>


    	</div><!-- .entry-content -->


    

    
    <div class="clearboth"></div>
</article><!-- #post-## -->
    <?
    endwhile;
    ?>
        </div>
    </div>
</div>
<?php get_footer( 'shop' ); ?>
<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
?>

<div class="images">
    <div class="zoom-left">
        <div style="height:274px;width:411px;" class="zoomWrapper"><img style="border: 1px solid rgb(232, 232, 230); position: absolute; width: 411px; height: 274px;" id="zoom_03" src="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image4.png" data-zoom-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/large/image3.jpg"><div style="height: 274px; width: 411px; z-index: 2000; position: absolute; display: none; background: url(&quot;http://www.elevateweb.co.uk/spinner.gif&quot;) center center no-repeat;"></div></div>

        <div id="gallery_01" style="width=" 500pxfloat:left;="" "="">

        <a href="#" class="elevatezoom-gallery" data-update="" data-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image1.png" data-zoom-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/large/image1.jpg">
            <img src="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image1.png" width="100"></a>

        <a href="#" class="elevatezoom-gallery" data-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image2.png" data-zoom-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/large/image2.jpg"><img src="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image2.png" width="100"></a>

        <a href="tester" class="elevatezoom-gallery" data-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image3.png" data-zoom-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/large/image3.jpg">
            <img src="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image3.png" width="100">
        </a>

        <a href="tester" class="elevatezoom-gallery active" data-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image4.png" data-zoom-image="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/large/image4.jpg"><img src="http://www.elevateweb.co.uk/wp-content/themes/radial/zoom/images/small/image4.png" width="100"></a>

    </div>
</div>
<style>
    /*set a border on the images to prevent shifting*/
    #gallery_01 img{border:2px solid white;}

    /*Change the colour*/
    .active img{border:2px solid #333 !important;}
</style>

<script>

    $(document).ready(function () {
        $("#zoom_03").elevateZoom({gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: "active", imageCrossfade: true, loadingIcon: "http://www.elevateweb.co.uk/spinner.gif"});

        $("#zoom_03").bind("click", function(e) {
            var ez =   $('#zoom_03').data('elevateZoom');
            ez.closeAll(); //NEW: This function force hides the lens, tint and window
            $.fancybox(ez.getGalleryList());
            return false;
        });

    });


 
</script>
	<?php
		if ( has_post_thumbnail() ) {
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );
			echo apply_filters(
				'woocommerce_single_product_image_html',
				sprintf(
					'<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto%s">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $props['caption'] ),
					$gallery,
					$image
				),
				$post->ID
			);
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
		}

		do_action( 'woocommerce_product_thumbnails' );
	?>
</div>

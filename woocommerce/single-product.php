<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function oncore_style_catalog() {
    wp_enqueue_style('oncore_style', get_stylesheet_directory_uri() . '/css/single-product.css' );
}

add_action( 'wp_enqueue_scripts', 'oncore_style_catalog' );
get_header( 'shop' );
global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
?>


<aside>
<div class="container">
    <div class="row">
        <div class="span12">
            <div class="breadcrumb">
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
$post_id = get_the_ID();
$args = array(
'post_type' => 'product',
'p' => $post_id
);
$loop = new WP_Query( $args );
if ( $loop->have_posts() ) {
while ( $loop->have_posts() ) : $loop->the_post();



global $product, $post;
?>
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
    <article>
        <div class="container main_cart_product">
            <div class="row">
                <div class="span12 title_product">

                    <?php the_title(); ?>

                </div>

                <div class="span12 sku">
          <span>код товара <?php echo $product->get_sku(); ?>
          </span>

                </div>
                </div>
              <div class="row">
                  <div class="span12">
                      <?php
                     // do_action( 'woocommerce_before_single_product_summary', 'wooswipe_woocommerce_show_product_thumbnails', 20 );
                  //    do_action( 'woocommerce_before_single_product_summary', 20 );

                 //     do_action( 'woocommerce_before_single_product_summary', 20 );
                      ?>
                      <div class="images span5">

                          <div class="img-block">



                          <ul class="span1 img-mini-block">

                              <?


                                  global $product;
                                  $attachment_ids = $product->get_gallery_attachment_ids();

                              if (!empty($attachment_ids)) {

                                  ?>

                                  <?
                                  $i=0;
                                  foreach( $attachment_ids as $attachment_id ) {

                                      $i++;

                                      ?>



                                      <li class="img-mini <? if ($i==1){ echo 'active';}?>" id="img-mini<? echo $i; ?>">

                                              <img src="<? echo $image_link = wp_get_attachment_url( $attachment_id, 'thumbnail' );?>" width="124" height="58" class="image<?echo $i;?>">

                                      </li>

                                  <?
                                      if ($i == get_field( 'max_count_images', 'option' )) {
                                      break;
                                      }

                                  }
                                  ?>
                                      </ul>

                              <div class="img-important span3">
                               <img src="<? echo $image_link = wp_get_attachment_url( $attachment_ids [0], 'large' );?>"/>
                              </div>

</div>

                              <? }
                              else {
                                  ?>



                                  </div>

                                  <div class="img-important span3">
                                      <img src="<?php bloginfo('template_directory');?>/img/no.png"  class="image1">                                  </div>
                              <?

                              }
                              ?>

</div>

                      <div class="span3 info-block">
                          <div class="shipping">
                              Доставка: <?
                              echo get_field( 'cost_shipping_minsk', 'option' );
                              ?>
                              <br>
                              за пределами Минска: <?
                              echo get_field( 'cost_shipping_Belarus', 'option' );
                              ?> руб.
                          </div>
                          <div class="warranty">
                              Гарантия: <?
                           echo get_field('warranty');
                              ?>
                          </div>
                          <?
                          $rating_count = $product->get_rating_count();

                          $review_count = $product->get_review_count();
                          $average      = $product->get_average_rating();

                     ?>

                              <div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                  <div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php printf( __( 'out of %s5%s', 'woocommerce' ), '<span itemprop="bestRating">', '</span>' ); ?>
                <?php printf( _n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, 'woocommerce' ), '<span itemprop="ratingCount" class="rating">' . $rating_count . '</span>' ); ?>
			</span>
                                  </div>
                                 <?php if ( comments_open() ) : ?><a href="#reviews" style="  font-family: OpenSans;
  font-size: 13px;
  font-weight: normal;
  font-style: normal;
  font-stretch: normal;
  letter-spacing: 0.2px;
  color: #000000; margin-left:10px;" class="woocommerce-review-link" data-toggle="tab" href="#menu2"><span itemprop="reviewCount" class="count">  <? echo $review_count; ?> отзывов</span></a><?php endif ?>
<span class="write_review"><a href="#review_form" class="open_modal">Написать озыв</a></span>
                              </div>



                      </div>

                      <div class="span3 sale-block">
<div class="price"> <?php echo $product->get_price_html(); ?></div>




                          <div class="in_stock">
                              <?

                              if ( !$product->is_in_stock() ) {
                                  ?>
                                  <div class="in_stock"><div class="ico_not_stock"></div> <p>Нет в наличии</p></div>
                                  <div class="clearfix"></div>
                              <div class="button_product">

                              <?

                              echo woo_archive_custom_cart_button_text($add_cart_url);








                              wc_get_template( 'compare.php', $args = $product->id  );
                              ?>
                                  </div>
                              <?
                              }
                              else {
                              ?>
                              <div class="ico_in_stock"></div> <p>В наличии</p></div>
                          <div class="clearfix"></div>
<div class="button_product">
    <?php

    echo woo_archive_custom_cart_button_text($add_cart_url);








    wc_get_template( 'compare.php', $args = $product->id  );
    ?>
    </div>
                          <?
                          }
                          ?>



</div>
            </div>
             </div>
            </div>



                      <script>

                          $(document).ready(function () {
                              $('.img-mini').click(function () {
                                  $('.img-mini').removeClass('active');
                                  var tmp = $(this).find("img").attr('src');
                                  $(this).addClass('active');
                                  $(".img-important > img").attr('src', tmp);
                              });


                          });



                      </script>


                  </div>
              </div>

    </article>
    <div class="container">
        <div class="row">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">ХАРАКТЕРИСТИКИ</a></li>
        <li><a data-toggle="tab" href="#attributes">СВОЙСТВА</a></li>
        <li><a data-toggle="tab" href="#menu1">ОБЗОР И ВИДЕО</a></li>
        <li id="review_block_tab"><a data-toggle="tab" href="#menu2">ОТЗЫВЫ</a></li>
      <? if ( get_field('download_instruction') != '')  { ?> <li><a  target="_blank" href="<? echo get_field('download_instruction');?>">СКАЧАТЬ ИНСТРУКЦИЮ</a></li> <? } ?>
    </ul>
</div>
        </div>
    <div class="tab-content container">

        <div id="home" class="tab-pane fade in active ">

           <div class="block"><h3>Основные характеристики</h3>
            <p><?
               echo get_field('main_parametrs');
                ?></p>
               </div>
            <div class="block"><h3>Дополнительно</h3>
                <p><?
                    echo get_field('extra_parametrs');
                    ?></p>
            </div>
            <div class="block"><h3>Габариты</h3>
                <p><?
                    echo get_field('dimensions');
                    ?></p>
            </div>
        </div>

        <div id="attributes" class="tab-pane fade  ">

            <div class="block"><h3>Свойства товара</h3>
                <?
                $attributes = $product->get_attributes();

                ?>
            </div>
        </div>
        <div id="menu1" class="tab-pane fade">
            <div class="block2">
                <? if ( get_field('review_video') != '') {
                    echo get_field('review_video');
                }
                else {
                    ?>
                    <span>Извините, данные не доступны!</span>
                <?
                }
                    ?>
            </div>
        </div>
        <div id="menu2" class="tab-pane fade ">
<div class="block2">
  <?
  global $product;

    if ( ! comments_open() ) {
    return;
    }

    $contribution_types = wc_product_reviews_pro()->get_enabled_contribution_types();
    $ratings            = array( 5, 4, 3, 2, 1 );
    $total_rating_count = $product->get_rating_count();

    ?>
    <div id="reviews">



        <?php
        /**
         * Fires before contribution list and title
         *
         * @since 1.0.1
         */
        do_action( 'wc_product_reviews_pro_before_contributions' ); ?>

        <h3 class="contributions-form-title"><?php esc_html_e( 'Let us know what you think...', 'woocommerce-product-reviews-pro' ); ?></h3>

        <div class="contribution-type-selector">
            <?php $key = 0; ?>
            <?php foreach ( $contribution_types as $type ) : ?>

                <?php if ( 'contribution_comment' !== $type ) : $key++; ?>

                    <?php $contribution_type = wc_product_reviews_pro_get_contribution_type( $type ); ?>
                    <a href="#share-<?php echo esc_attr( $type ); ?>" class="js-switch-contribution-type <?php if ( $key === 1 ) : ?>active<?php endif; ?>"><?php echo $contribution_type->get_call_to_action(); ?></a>

                <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <?php $key = 0; ?>
        <?php foreach ( $contribution_types as $type ) : ?>

            <?php if ( 'contribution_comment' !== $type ) : $key++; ?>

                <div id="<?php echo esc_attr( $type ); ?>_form_wrapper" class="contribution-form-wrapper <?php if ( $key === 1 ) : ?>active<?php endif; ?>">
                    <?php //wc_get_template( 'single-product/form-contribution.php', array( 'type' => $type ) ); ?>
                </div>

            <?php endif; ?>

        <?php endforeach; ?>

        <?php if ( ! is_user_logged_in() && get_option('comment_registration') ) : ?>

            <noscript>
                <style type="text/css">#reviews .contribution-form-wrapper { display: none; }</style>
                <p class="must-log-in"><?php printf( __( 'You must be <a href="%s">logged in</a> to join the discussion.', 'woocommerce-product-reviews-pro' ), esc_url( add_query_arg( 'redirect_to', urlencode( get_permalink( get_the_ID() ) ), wc_get_page_permalink( 'myaccount' ) . '#tab-reviews' ) ) ); ?></p>
            </noscript>

        <?php endif; ?>

        <?php // Comments list ?>
        <div id="comments">

            <form method="get" action="#comments" class="contributions-filter">
                <?php

                // Filter options
                $options = array(
                    '' => __( 'Show everything', 'woocommerce-product-reviews-pro' ),
                );

                // Add option for each contribution type
                foreach ( $contribution_types as $type ) {

                    if ( 'contribution_comment' === $type ) {
                        continue;
                    }

                    $contribution_type = wc_product_reviews_pro_get_contribution_type( $type );
                    $options[ 'comment_type=' . $type ] = $contribution_type->get_filter_title();
                }

                // Review qualifier options
                $review_qualifiers = wp_get_post_terms( $product->id, 'product_review_qualifier' );

                foreach ( $review_qualifiers as $review_qualifier ) {

                    $qualifier_options = array_filter( explode( "\n", get_woocommerce_term_meta( $review_qualifier->term_id, 'options' ) ) );

                    foreach ( $qualifier_options as $option ) {
                        $options[ 'comment_type=review&review_qualifier=' . $review_qualifier->term_id . ':' . $option ] = sprintf( __( 'Show all reviews that said %s is "%s"', 'woocommerce-product-reviews-pro' ), $review_qualifier->name, $option );
                    }

                }

                // Special options
                $options[ 'comment_type=review&classification=positive&helpful=1' ] = __( 'Show helpful positive reviews', 'woocommerce-product-reviews-pro' );
                $options[ 'comment_type=review&classification=negative&helpful=1' ] = __( 'Show helpful negative reviews', 'woocommerce-product-reviews-pro' );
                $options[ 'comment_type=question&unanswered=1' ] = __( 'Show unanswered questions', 'woocommerce-product-reviews-pro' );

                /**
                 * Filter the filter options.
                 *
                 * @since 1.0.0
                 * @param array $options The filter options.
                 */
                $options = apply_filters( 'wc_product_reviews_pro_contribution_filter_options', $options );

                // Other field args
                $args = array(
                    'type'    => 'select',
                    'options' => $options,
                );

                $comments_filter = isset( $_REQUEST['comments_filter'] ) ? $_REQUEST['comments_filter'] : null;

                ?>

                <a href="<?php the_permalink(); ?>" class="js-clear-filters" style="display:none;" title="<?php _e( 'Click to clear filters', 'woocommerce-product-reviews-pro' ); ?>"><?php _e( '(clear)', 'woocommerce-product-reviews-pro' ); ?></a>

                <?php woocommerce_form_field( 'comments_filter', $args, $comments_filter ); ?>

                <noscript><button type="submit" class="button"><?php _e( 'Go', 'woocommerce-product-reviews-pro' ); ?></button></noscript>
            </form>

            <div id="contributions-list">
                <?php wc_get_template( 'single-product/contributions-list.php', array( 'comments' => $comments ) ); ?>
            </div>
        </div>

        <div class="clear"></div>

        <?php if ( ! is_user_logged_in() ) : ?>

            <div id="wc-product-reviews-pro-modal">

                <a href="#" class="close">&times;</a>

                <?php wc_get_template( 'myaccount/form-login.php' ); ?>

                <div class="switcher">
                    <p class="login"><?php printf( /* translators: Placeholders: %1$s - opening <a> link tag, %2$s - closing </a> link tag */
                            __( 'Already have an account? %1$sLog In%2$s', 'woocommerce-product-reviews-pro' ), '<a href="#">', '</a>' ); ?></p>
                    <p class="register"><?php printf( /* translators: Placeholders: %1$s - opening <a> link tag, %2$s - closing </a> link tag */
                            __( 'Don\'t have an account? %1$sSign Up%2$s', 'woocommerce-product-reviews-pro' ), '<a href="#">', '</a>' ); ?></p>
                </div>

            </div>
            <div id="wc-product-reviews-pro-modal-overlay"></div>

        <?php endif; ?>

        <?php /* display all forms when no JS */ ?>
        <noscript>
            <style type="text/css">
                .contribution-form-wrapper { display: block; }
            </style>
        </noscript>

    </div>

</div>

        </div>

    </div>



    <script>
        $(function () {
            $('#myTab a:last').tab('show');
        })
    </script>
<?
endwhile;
} else {
    echo __( 'No products found' );
}
wp_reset_postdata();
?>




            <?php if (!dynamic_sidebar("Oncore Bottom") ) : ?>



            <?php endif; ?>

<div id="review_form" class="modal_div"> <!-- скрытый див с уникaльным id = modal1 -->
    <div class="row"> <div class="span4"> <h3>ОСТАВИТЬ ОТЗЫВ </h3> </div> <div class="span1"><span class="modal_close"></span> </div></div>

    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" enctype="multipart/form-data" novalidate class="form-contribution form-<?php echo esc_attr( $type ); ?>">

        <div class="row">
      <div class="span4"> <label for="review_title" class="title">Заголовок отзыва</label>
            <input type="text" class="input-text " name="review_title" id="review_title" placeholder="Заголовок отзыва" value="">
</div>
        </div>
        <div class="row">
            <div class="span4">
        <label for="review_comment" class="title">Отзыв <span class="required" title="обязательно">*</span></label>
            <textarea name="review_comment" class="input-text " id="review_comment" placeholder="Отзыв о товаре..." rows="2" cols="5" data-min-word-count="" data-max-word-count=""></textarea>
            </div>
        </div>
        <?php //foreach ( $contribution_type->get_fields() as $key => $field ) : ?>

            <?php //woocommerce_form_field( $key, $field, wc_product_reviews_pro_get_form_field_value( $key ) ); ?>

        <?php //endforeach; ?>

        <?php if ( ! is_user_logged_in() && get_option( 'require_name_email' ) && ! get_option( 'comment_registration' ) ) : ?>
        <div class="row" style="margin-top: 20px;">
            <div class="span4" >

                <label for="author" class="title">Имя <span class="required" title="обязательно">*</span></label><input type="text" class="input-text " name="author" id="author" placeholder="" value="">
            </div>
        </div>
                <div class="row">
                    <div class="span4">
        <label for="email" class="title">E-mail <span class="required" title="обязательно">*</span></label><input type="text" class="input-text " name="email" id="email" placeholder="" value="">
                    </div>
                </div>
        <?php endif; ?>

        <?php if ( 'review' === $type ) : ?>
            <?php //wc_product_reviews_pro_review_qualifiers_form_controls(); ?>
        <?php endif; ?>

        <input type="hidden" name="comment" value="<?php echo wp_create_nonce( 'contribution-content-input' ); ?>">
        <input type="hidden" name="comment_type" value="<?php echo esc_attr( $type ); ?>" />
        <input type="hidden" name="comment_post_ID" value="<?php the_ID(); ?>">

        <?php if ( 'contribution_comment' === $type ) : ?>
            <input type="hidden" name="comment_parent" value="<?php echo esc_attr( $comment->comment_ID ); ?>">
        <?php endif; ?>

        <?php if ( is_user_logged_in() && wc_product_reviews_pro_comment_notification_enabled() ) : ?>
            <input type="hidden" name="comment_author_ID" value="<?php echo esc_attr( get_current_user_id() ); ?>">
            <?php //woocommerce_form_field( 'subscribe_to_replies', array( 'type' => 'checkbox', 'label' => __( 'Notify me of replies', 'woocommerce-product-reviews-pro' ) ) ); ?>
        <div class="row" style="margin-top: 20px;">
            <div class="span4">
                <input type="checkbox" class="input-checkbox " name="subscribe_to_replies" id="subscribe_to_replies" value="1">
                <label  class="title" for="subscribe_to_replies">
                    Подписаться
                </label>
                </div>
            </div>
        <?php endif; ?>

        <?php wp_comment_form_unfiltered_html_nonce(); ?>
        <div class="row" style="margin-top: 20px;">
            <div class="span5">
        <div class=" star-rating-selector validate-required validate-required" id="review_rating_field"><label for="review_rating_5" class="title" style="float:left;">Ваша оценка <span class="required" title="required">*</span></label><input type="radio" class="input-checkbox" value="5" name="review_rating" id="review_rating_5"><label for="review_rating_5" class="checkbox "></label> <input type="radio" class="input-checkbox" value="4" name="review_rating" id="review_rating_4"><label for="review_rating_4" class="checkbox "></label> <input type="radio" class="input-checkbox" value="3" name="review_rating" id="review_rating_3"><label for="review_rating_3" class="checkbox "></label> <input type="radio" class="input-checkbox" value="2" name="review_rating" id="review_rating_2"><label for="review_rating_2" class="checkbox "></label> <input type="radio" class="input-checkbox" value="1" name="review_rating" id="review_rating_1"><label for="review_rating_1" class="checkbox "></label> <span class="star-label" data-selected-text=""></span></div>
</div>
            </div>
        <div class="row" style="margin-top: 20px;">
            <div class="span3">
            <button type="submit" class="button">ОТПРАВИТЬ</button>
            </div>
            </div>
    </form>

</div>



<?php get_footer( 'shop' ); ?>

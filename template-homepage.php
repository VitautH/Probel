<?
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package storefront
 */
get_header();
if( have_rows('slider') ): ?>

    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <div class="container">
    <div class="row">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">

<?php

 $i=0;

while( have_rows('slider') ): the_row(); ?>

            <div class="item  <?php if ($i == '0'){echo 'active';};?> span12">
              <a href="<?php the_sub_field('link'); ?>"> <div class="span6 content">
                    <h1><?php the_sub_field('title'); ?></h1>

                    <p> <?php the_sub_field('description'); ?></p>
                    </div>
                  </a>
                <div class="span5">
                <div class="fill" style="background-image:url('<?php the_sub_field('images'); ?>');"></div>
             </div>
            </div>

<?php
$i++;
endwhile; ?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="icon-next"></span>
        </a>
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?
            unset($i);
            $i=0;?>
            <?php while( have_rows('slider') ): the_row(); ?>

                <li data-target="#carousel-example-generic" data-slide-to="<? echo $i;?>" class="<? if ($i == '0'){echo "active";}?>"></li>
                <? $i++;?>

            <?php endwhile; ?>
        </ol>

    </div>
    </div>
    </div>
<?php endif; ?>


<?php
$args=array(
    'orderby' => 'name',
    'taxonomy'     => 'product_cat',
    'hide_empty'   => 0,
    'parent'       => 0,
    'order' => 'ASC'
);
$categories=get_categories($args);

global $wp_query;

?>
    <div class="grid">
<?
$i=0;
foreach($categories as $category) {
    $i++;
    $cat = $wp_query->get_queried_object();
    $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
    $image = wp_get_attachment_url( $thumbnail_id );
   ?>
    <div class="mosaic"  style='background-image: url("<?php echo $image; ?>");'>
        <div class="content">
            <?php
            $term_id = $category->term_id;
            $taxonomy_name = 'product_cat';
            $termchildren = get_term_children( $term_id, $taxonomy_name );

            echo '<ul> <span>'.$category->name.'</span>';
            foreach ( $termchildren as $child ) {
                $term = get_term_by( 'id', $child, $taxonomy_name );
                echo '<li><a href="' . get_term_link( $child, $taxonomy_name ) . '">' . $term->name . '</a></li>';
            }
            echo '</ul>';
            ?>

        </div>
        <span class="title"><?php echo $category->name; ?></span>
    </div>

    <?
}
?>
</div>


<?php if (!dynamic_sidebar("Oncore Bottom") ) : ?>
<?php endif; ?>

    <section class="news">
        <div class="container">
            <div class="row">
                <div class="span3 title">НОВОСТИ</div>
            </div>
            <?php
            global $post;
            $items = get_field('items_product_category', 'option');
            $args = array(
                'post_type'		=> 'post',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'news'
                    )
                ),
                'posts_per_page' => 3
            );

               $loop = new WP_Query( $args );



            ?>
            <div class="row">
                <?php    if ( $loop->have_posts() ) {
                    $i=0;
                    while ($loop->have_posts()) : $loop->the_post();
                        $i++;
                        ?>
                        <a href="<?the_permalink();?>" >

                            <div class="span4" style="margin-left:0; margin-right: 15px;">

                                <div class="img" style="background-image:url('<?php
                                the_post_thumbnail_url( array(200, 100) );   ?>');">
                            <span
                                class="block"><span class="title_news"><? the_title();?></span>

                                <span
                                    class="data"><?php  the_date( 'Y-m-d' ); ?></span> </span>
                                </div>
                                </div>

                        </a>
                        <?php
                    endwhile;
                }
                wp_reset_postdata();
                ?>
            </div>

        </div>
    </section>
    <section class="additional">
        <div class="container">
            <div class="row">   <span class="span12 title-additional">Возможно вы искали что-то из этих категорий товаров</span>
            </div>
            <div class="row">
                <div class="span12 block" style="margin-top: 20px; margin-bottom: 75px;">
                    <div class="span3 offset3"><a href="#" class="button"><span>МАГАЗИН БАССЕЙНОВ</span></a> </div>
                    <div class="span3"><a href="#" class="button"><span>МАГАЗИН АВТОЭЛЕКТРОНИКИ</span></a> </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $('.carousel').carousel({
            interval: 5000 //changes the speed
        })
    </script>
<?
get_footer();
?>
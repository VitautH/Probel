<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <?php wp_head(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script   src="https://code.jquery.com/jquery-2.2.3.js"   integrity="sha256-laXWtGydpwqJ8JA+X9x2miwmaiKhn8tVmOVEigRNtP4="   crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/menu.js"></script>
    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/popup.js"></script>
    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery.elevatezoom.js"></script>
    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap-collapse.js"></script>
    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap-transition.js"></script>
    <link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/style.css" rel="stylesheet" media="screen">

    <link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/reset.css" rel="stylesheet">


    <script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery.cookie.js"></script>
<script>
    $.ajaxSetup({cache: false});
</script>
</head>

<body <?php body_class();?>>
<div class="navbar-fixed-top navbar">
<header>
    <div class="container">
        <div class="row">
            <div class="span2">
                <a href="<?php echo get_site_url(); ?>"><img class="logo" src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.png"/> </a>
            </div>
            <div class="span5">
                <span class="title">Поможем подобрать подарок на 23 февраля!</span>
                <?php if (!dynamic_sidebar("oncore-search") ) : ?>


              
                <?php endif; ?>
            </div>
            <div class="span2 phone-number">

                <span class="tel-code">+375(44)</span>
                <span class="tel"> 763-03-51</span>
                <span class="call">Заказать звонок</span>
            </div>

            <div class="span2 compare">
                <?php
                global $woo_compare_grid_view_settings, $woo_compare_grid_view_button_style;
                global $woo_compare_comparison_page_global_settings;
                global $woo_compare_gridview_view_compare_style;
                global $product_compare_id;

                $product_compare_page = get_permalink($product_compare_id);

                if ($woo_compare_comparison_page_global_settings['open_compare_type'] != 'new_page') {
                    $product_compare_page = '#';
                }
                $result = WC_Compare_Functions::get_total_compare_list();

                ?>
                <?php if (json_encode( $result ) != 0) { ?>
                    <div class="count-compare">
                        <span>  <?php echo json_encode($result); ?></span>
                    </div>
                    <?php
                }
else {
    ?>
                    <div class="count-compare hide">
                        <span>  </span>
            </div>

           <?php
}
                ?>
               <a href="<?php echo $product_compare_page; ?>">

                   <div class="three-bars-graph"></div>
               <span class="title">СРАВНЕНИЕ</span>
                   </a>


            </div>
            <div class="span1 shopping-cart">
                <?
                $cart_url = wc_get_cart_url();
                ?>


                    <?php include('woocommerce/cart/mini-cart.php'); ?>
                <a href="<? echo $cart_url; ?>">
                    <div class="ico_shopping-cart"></div>
                        <span class="title">КОРЗИНА</span> </a>

            </div>
        </div>
    </div>
</header>
<nav>
    <div class="container">
        <div class="row">
            <div class="span12">
                <?

                $args = array(
                    'menu' => 'Header_menu',
                    'theme_location' => 'head_menu',
                    'walker'=> new True_Walker_Nav_Menu()
                );
                wp_nav_menu( $args );


                ?>


            </div>

        </div>
    </div>
</nav>
</div>
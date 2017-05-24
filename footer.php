
<footer>
    <div class="container">
        <div class="row">
            <div class="span3 block">
                <span class="title">КОМПАНИЯ ПРОБЕЛ</span>

                <?

                $args = array(
                    'menu' => 'Footer_Company',
                    'theme_location' => 'footer_menu_company',
                    'walker'=> new True_Walker_Nav_Menu()
                );
                wp_nav_menu( $args );


                ?>
            </div>
            <div class="span3 block">
                <span class="title">КАТАЛОГ</span>
                <?

                $args = array(
                    'menu' => 'Footer_catalog',
                    'theme_location' => 'footer_menu_catalog',
                    'walker'=> new True_Walker_Nav_Menu()
                );
                wp_nav_menu( $args );


                ?>
            </div>
            <div class="span3 contact_block">
                <span class="phone">+375(44) 763-03-51</span>
                <span class="call">Заказать звонок</span>
                <span class="info">Время работы:
8:00 - 22:00 без выходных</span>
                <span class="maps">Мы на карте</span>
            </div>
            <div class="span3"></div>

        </div>
        <hr>
        <div class="row">
            <div class="span2"><span class="copy"> © PROBEL 2016</span></div>

            <div class="span3 offset3"><span class="info"> В торговом реестре с 12.01.2009</span></div>

            <div class="developt"><span>Разработка —</span> <a href="http://oncore.by"> ONCORE</a></div>
        </div>
    </div>
  </footer>
<div id="overlay" style="display: none;">
    <div id="blanket"></div>
    <div id="form_popup">
        <div class="form_inner">
        <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
        <span class="title">Задайте нам вопрос</span>
        <form class="inner">
            <span>Представьтесь пожалуйста</span>
            <input type="text" id="name" name="name" placeholder="Имя" required="">
            <span>Ваша почта</span>
            <input type="email" id="email" name="email" placeholder="E-mail" required="">
            <span>Суть вашего вопроса</span>
            <textarea></textarea>
            <div class="recall"><span>Отправить</span> </div>
        </form>
        </div>
    </div></div>
</body>
<?php wp_footer(); ?>
</html>
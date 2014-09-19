<?php # DEFAULT SUB-MENU VIEW                      ?>
<?php /** @var  Nayjest\Menu\MenuSet $menu */  ?>
<li class="dropdown">
    <a
        class="dropdown-toggle"
        data-toggle="dropdown"
        href="#"
        ><?= $menu->getOption('title') ?>&nbsp;<span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <?= View::make('menu::default', compact('menu'))?>
    </ul>
</li>
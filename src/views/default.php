<?php # DEFAULT MENU VIEW
/** @var  Nayjest\Menu\MenuSet $menu */
echo "\n";
foreach($menu->getItems() as $item):
    echo $menu->renderItem($item), "\n";
endforeach;
echo "\n";


<?php

namespace App\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu
            ->addChild('new')
            ->setLabel('sylius.admin.custom_doc')
        ;

        $newSubmenu
            ->addChild('new-subitem',[
                "route" => "app_admin_legal_index"
            ])
            ->setLabel('sylius.admin.legal_doc')
            ->setLabelAttribute('icon','balance scale')
        ;
    }
}
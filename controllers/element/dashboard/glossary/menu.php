<?php

namespace Concrete\Package\MdContentTranslator\Controller\Element\Dashboard\Glossary;

use Concrete\Core\Controller\ElementController;
use Macareux\ContentTranslator\Glossary\MenuManager;

class Menu extends ElementController
{
    public function getElement()
    {
        return 'dashboard/glossary/menu';
    }

    public function view()
    {
        /** @var MenuManager $menuItemManager */
        $menuItemManager = $this->app->make(MenuManager::class);
        $this->set('menuItems', $menuItemManager->getMenuItems());
    }
}

<?php

namespace Macareux\ContentTranslator\Glossary;

use HtmlObject\Link;

class MenuManager
{
    /**
     * @var array
     */
    protected $menuItems = [];

    /**
     * @param Link $link
     *
     * @return void
     */
    public function addMenuItem(Link $link)
    {
        array_unshift($this->menuItems, $link);
    }

    /**
     * @return array
     */
    public function getMenuItems(): array
    {
        return $this->menuItems;
    }
}

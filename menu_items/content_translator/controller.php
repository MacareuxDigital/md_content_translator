<?php

namespace Concrete\Package\MdContentTranslator\MenuItem\ContentTranslator;

use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;

class Controller extends \Concrete\Core\Application\UserInterface\Menu\Item\Controller
{
    public function displayItem()
    {
        $page = Page::getCurrentPage();
        if (is_object($page) && !$page->isError() && !$page->isAdminArea() && !$page->isAliasPageOrExternalLink() && !$page->isSystemPage()) {
            $checker = new Checker($page);
            if ($checker->canTranslatePageContents()) {
                /** @var ResolverManagerInterface $resolver */
                $resolver = $this->app->make(ResolverManagerInterface::class);
                $this->menuItem->setLink($resolver->resolve(['/ccm/md_content_translator/dialog', 'page/content_translator'])->setQuery(['cID' => $page->getCollectionID()]));

                return true;
            }
        }

        return false;
    }
}

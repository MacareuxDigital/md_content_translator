<?php

namespace Concrete\Package\MdContentTranslator\Controller\SinglePage\Dashboard\ContentTranslator;

use Concrete\Core\Http\Request;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Search\Pagination\PaginationFactory;
use Macareux\ContentTranslator\Search\RequestList;

class Search extends DashboardPageController
{
    public function view()
    {
        /** @var RequestList $list */
        $list = $this->app->make(RequestList::class);
        $list->sortByDateDescending();
        $factory = new PaginationFactory(Request::getInstance());
        $pagination = $factory->createPaginationObject($list, PaginationFactory::PERMISSIONED_PAGINATION_STYLE_PAGER);
        $this->set('list', $list);
        $this->set('pagination', $pagination);
        $this->set('pageTitle', t('Translate Requests'));
        $this->setThemeViewTemplate('full.php');
    }
}

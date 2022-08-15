<?php

namespace Concrete\Package\MdContentTranslator\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;

class ContentTranslator extends DashboardPageController
{
    public function view()
    {
        /** @var ResolverManagerInterface $resolver */
        $resolver = $this->app->make(ResolverManagerInterface::class);

        return $this->buildRedirect($resolver->resolve(['/dashboard/content_translator/search']));
    }
}

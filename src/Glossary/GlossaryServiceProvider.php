<?php

namespace Macareux\ContentTranslator\Glossary;

use Concrete\Core\Foundation\Service\Provider as ServiceProvider;
use HtmlObject\Link;
use Concrete\Core\Support\Facade\Url as UrlFacade;

class GlossaryServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton(MenuManager::class, function ($app) {
            $manager = new MenuManager();
            $addTermMenuItem = new Link(
                (string) UrlFacade::to('/dashboard/content_translator/glossary/form'),
                t('Add Term'),
                ['class' => 'btn btn-primary']
            );
            $manager->addMenuItem($addTermMenuItem);

            return $manager;
        });
    }

}
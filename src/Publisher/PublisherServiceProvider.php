<?php

namespace Macareux\ContentTranslator\Publisher;

use Concrete\Core\Foundation\Service\Provider as ServiceProvider;
use Macareux\ContentTranslator\Publisher\Routine\Manager;
use Macareux\ContentTranslator\Publisher\Routine\PublishCollectionDescriptionPagePropertyRoutine;
use Macareux\ContentTranslator\Publisher\Routine\PublishCollectionNamePagePropertyRoutine;
use Macareux\ContentTranslator\Publisher\Routine\PublishContentBlockRoutine;
use Macareux\ContentTranslator\Publisher\Routine\PublishImageBlockRoutine;
use Macareux\ContentTranslator\Publisher\Routine\PublishTextareaAttributeRoutine;
use Macareux\ContentTranslator\Publisher\Routine\PublishTextAttributeRoutine;

class PublisherServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(Manager::class, function ($app) {
            $manager = new Manager();
            $manager->registerRoutine(new PublishCollectionNamePagePropertyRoutine());
            $manager->registerRoutine(new PublishCollectionDescriptionPagePropertyRoutine());
            $manager->registerRoutine(new PublishTextAttributeRoutine());
            $manager->registerRoutine(new PublishTextareaAttributeRoutine());
            $manager->registerRoutine(new PublishContentBlockRoutine());
            $manager->registerRoutine(new PublishImageBlockRoutine());

            return $manager;
        });
    }
}

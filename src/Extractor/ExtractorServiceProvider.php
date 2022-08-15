<?php

namespace Macareux\ContentTranslator\Extractor;

use Concrete\Core\Foundation\Service\Provider as ServiceProvider;
use Macareux\ContentTranslator\Extractor\Routine\ExtractCollectionDescriptionPagePropertyRoutine;
use Macareux\ContentTranslator\Extractor\Routine\ExtractCollectionNamePagePropertyRoutine;
use Macareux\ContentTranslator\Extractor\Routine\ExtractContentBlockRoutine;
use Macareux\ContentTranslator\Extractor\Routine\ExtractImageBlockRoutine;
use Macareux\ContentTranslator\Extractor\Routine\ExtractTextareaAttributeRoutine;
use Macareux\ContentTranslator\Extractor\Routine\ExtractTextAttributeRoutine;
use Macareux\ContentTranslator\Extractor\Routine\Manager;

class ExtractorServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(Manager::class, function ($app) {
            $manager = new Manager();
            $manager->registerRoutine(new ExtractCollectionNamePagePropertyRoutine());
            $manager->registerRoutine(new ExtractCollectionDescriptionPagePropertyRoutine());
            $manager->registerRoutine(new ExtractTextAttributeRoutine());
            $manager->registerRoutine(new ExtractTextareaAttributeRoutine());
            $manager->registerRoutine(new ExtractContentBlockRoutine());
            $manager->registerRoutine(new ExtractImageBlockRoutine());

            return $manager;
        });
    }
}

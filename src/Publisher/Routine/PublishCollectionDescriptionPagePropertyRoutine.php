<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

use Concrete\Core\Cache\Level\RequestCache;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Macareux\ContentTranslator\Entity\TranslateContent;

class PublishCollectionDescriptionPagePropertyRoutine implements PublishRoutineInterface
{
    public function publish(Page $page, TranslateContent $content): bool
    {
        if ($content->getSourceType() === 'collection_description') {
            $translated = $content->getTranslated();
            // Retrieve Page object to prevent cache issue
            $app = Application::getFacadeApplication();
            $app->make(RequestCache::class)->flush();
            $cx = Page::getByID($content->getSourceIdentifier());
            $cx->update(['cDescription' => $translated]);

            return true;
        }

        return false;
    }
}

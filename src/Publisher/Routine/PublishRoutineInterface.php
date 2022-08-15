<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;

interface PublishRoutineInterface
{
    /**
     * @param Page $page
     * @param TranslateContent $content
     *
     * @return bool True for successfully updated, false for failed or skipped
     */
    public function publish(Page $page, TranslateContent $content): bool;
}

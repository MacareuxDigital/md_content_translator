<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateRequest;

interface ExtractPagePropertyRoutineInterface
{
    public function extractContent(TranslateRequest $request, Page $page);
}

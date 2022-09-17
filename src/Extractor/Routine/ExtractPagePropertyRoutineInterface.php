<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;

interface ExtractPagePropertyRoutineInterface
{
    public function getContent(TranslateRequest $request, Page $page): ?TranslateContent;
}

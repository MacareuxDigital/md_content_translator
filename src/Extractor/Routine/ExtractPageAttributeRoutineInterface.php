<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Entity\Attribute\Value\PageValue;
use Macareux\ContentTranslator\Entity\TranslateRequest;

interface ExtractPageAttributeRoutineInterface
{
    public function extractContent(TranslateRequest $request, PageValue $value);
}

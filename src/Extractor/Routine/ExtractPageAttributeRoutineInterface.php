<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Entity\Attribute\Value\PageValue;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;

interface ExtractPageAttributeRoutineInterface
{
    public function getContent(TranslateRequest $request, PageValue $value): ?TranslateContent;
}

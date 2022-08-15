<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Block\Block;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;

interface ExtractBlockRoutineInterface
{
    public function getContent(TranslateRequest $request, Block $block): ?TranslateContent;
}

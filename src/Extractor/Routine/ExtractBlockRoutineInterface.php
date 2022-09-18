<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Block\Block;
use Macareux\ContentTranslator\Entity\TranslateRequest;

interface ExtractBlockRoutineInterface
{
    public function extractContent(TranslateRequest $request, Block $block);
}

<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

abstract class AbstractExtractPageAttributeRoutine implements ExtractRoutineInterface
{
    public function getCategoryHandle(): string
    {
        return 'page_attribute';
    }
}

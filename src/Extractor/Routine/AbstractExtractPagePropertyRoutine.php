<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

abstract class AbstractExtractPagePropertyRoutine implements ExtractRoutineInterface
{
    public function getCategoryHandle(): string
    {
        return 'page_property';
    }
}

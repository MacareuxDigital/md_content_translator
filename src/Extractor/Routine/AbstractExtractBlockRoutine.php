<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

abstract class AbstractExtractBlockRoutine implements ExtractRoutineInterface
{
    public function getCategoryHandle(): string
    {
        return 'block';
    }
}

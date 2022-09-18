<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;

class ExtractCollectionNamePagePropertyRoutine extends AbstractExtractPagePropertyRoutine implements ExtractPagePropertyRoutineInterface
{
    public function extractContent(TranslateRequest $request, Page $page)
    {
        $text = $page->getCollectionName();
        if ($text) {
            $content = new TranslateContent();
            $content->setRequest($request);
            $content->setStatus(TranslateContent::STATUS_DRAFT);
            $content->setContent($text);
            $content->setSourceIdentifier((string) $page->getCollectionID());
            $content->setSourceType('collection_name');
            $content->setLabel(t('Page Name'));
            $content->setType(TranslateContent::TYPE_STRING);
            $request->getContents()->add($content);
        }
    }
}

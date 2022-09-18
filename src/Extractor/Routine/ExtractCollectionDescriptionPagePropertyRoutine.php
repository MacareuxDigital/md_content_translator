<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;

class ExtractCollectionDescriptionPagePropertyRoutine extends AbstractExtractPagePropertyRoutine implements ExtractPagePropertyRoutineInterface
{
    public function extractContent(TranslateRequest $request, Page $page)
    {
        $text = $page->getCollectionDescription();
        if ($text) {
            $content = new TranslateContent();
            $content->setRequest($request);
            $content->setStatus(TranslateContent::STATUS_DRAFT);
            $content->setContent($text);
            $content->setSourceIdentifier((string) $page->getCollectionID());
            $content->setSourceType('collection_description');
            $content->setLabel(t('Page Description'));
            $content->setType(TranslateContent::TYPE_TEXT);
            $request->getContents()->add($content);
        }
    }
}

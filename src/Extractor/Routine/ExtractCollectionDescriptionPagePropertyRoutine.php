<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;

class ExtractCollectionDescriptionPagePropertyRoutine extends AbstractExtractPagePropertyRoutine implements ExtractPagePropertyRoutineInterface
{
    public function getContent(TranslateRequest $request, Page $page): TranslateContent
    {
        $content = new TranslateContent();
        $content->setRequest($request);
        $content->setStatus(TranslateContent::STATUS_DRAFT);
        $content->setContent($page->getCollectionDescription());
        $content->setSourceIdentifier((string) $page->getCollectionID());
        $content->setSourceType('collection_description');
        $content->setLabel(t('Page Description'));
        $content->setType(TranslateContent::TYPE_TEXT);
        $request->getContents()->add($content);

        return $content;
    }
}

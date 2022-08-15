<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Block\Content\Controller;
use Concrete\Core\Block\Block;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Traits\BlockTrait;

class ExtractContentBlockRoutine extends AbstractExtractBlockRoutine implements ExtractBlockRoutineInterface
{
    use BlockTrait;

    public function getContent(TranslateRequest $request, Block $block): ?TranslateContent
    {
        $content = null;

        if ($block->getBlockTypeHandle() === 'content') {
            /** @var Controller $controller */
            $controller = $block->getController();
            $extracted = $controller->getSearchableContent();
            if ($extracted) {
                $content = new TranslateContent();
                $content->setRequest($request);
                $content->setStatus(TranslateContent::STATUS_DRAFT);
                $content->setContent($extracted);
                $content->setSourceIdentifier($this->getBlockIdentifier($block));
                $content->setSourceType('block_content');
                $content->setLabel($block->getBlockName() ?: $controller->getBlockTypeName());
                $content->setType(TranslateContent::TYPE_HTML);
                $request->getContents()->add($content);
            }
        }

        return $content;
    }
}

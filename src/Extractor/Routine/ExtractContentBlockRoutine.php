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

    public function extractContent(TranslateRequest $request, Block $block)
    {
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
                $content->setLabel($this->getLabel($block, $controller));
                $content->setType(TranslateContent::TYPE_HTML);
                $request->getContents()->add($content);
            }
        }
    }
}

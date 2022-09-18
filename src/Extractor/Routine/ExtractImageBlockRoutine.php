<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Block\Image\Controller;
use Concrete\Core\Block\Block;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Traits\BlockTrait;

class ExtractImageBlockRoutine extends AbstractExtractBlockRoutine implements ExtractBlockRoutineInterface
{
    use BlockTrait;

    public function extractContent(TranslateRequest $request, Block $block)
    {
        if ($block->getBlockTypeHandle() === 'image') {
            /** @var Controller $controller */
            $controller = $block->getController();

            $altText = $controller->getAltText();
            if ($altText) {
                $content = new TranslateContent();
                $content->setRequest($request);
                $content->setStatus(TranslateContent::STATUS_DRAFT);
                $content->setContent($altText);
                $content->setSourceIdentifier($this->getBlockIdentifier($block));
                $content->setSourceSubfield('altText');
                $content->setSourceType('block_image');
                $content->setLabel($this->getLabel($block, $controller, t('Alt Text')));
                $content->setType(TranslateContent::TYPE_TEXT);
                $request->getContents()->add($content);
            }

            $title = $controller->getTitle();
            if ($title) {
                $content = new TranslateContent();
                $content->setRequest($request);
                $content->setStatus(TranslateContent::STATUS_DRAFT);
                $content->setContent($title);
                $content->setSourceIdentifier($this->getBlockIdentifier($block));
                $content->setSourceSubfield('title');
                $content->setSourceType('block_image');
                $content->setLabel($this->getLabel($block, $controller, t('Title')));
                $content->setType(TranslateContent::TYPE_TEXT);
                $request->getContents()->add($content);
            }
        }
    }
}

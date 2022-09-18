<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Traits\BlockTrait;

class PublishImageBlockRoutine implements PublishRoutineInterface
{
    use BlockTrait;

    public function publish(Page $page, TranslateContent $content): bool
    {
        if ($content->getSourceType() === 'block_image') {
            $identifier = $content->getSourceIdentifier();
            $block = $this->getBlockToEdit($identifier, $page);
            if ($block) {
                $data = $this->getOriginalBlockRecord($block);
                foreach ($this->getTranslationsForSameBlock($content) as $relatedContent) {
                    $data[$relatedContent->getSourceSubfield()] = $relatedContent->getTranslated();
                }
                $block->update($data);

                return true;
            }
        }

        return false;
    }
}

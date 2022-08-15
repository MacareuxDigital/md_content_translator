<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Traits\BlockTrait;

class PublishContentBlockRoutine implements PublishRoutineInterface
{
    use BlockTrait;

    public function publish(Page $page, TranslateContent $content): bool
    {
        if ($content->getSourceType() === 'block_content') {
            $block = $this->getBlockToEdit($content->getSourceIdentifier(), $page);
            if ($block) {
                $block->update(['content' => $content->getTranslated()]);

                return true;
            }
        }

        return false;
    }
}

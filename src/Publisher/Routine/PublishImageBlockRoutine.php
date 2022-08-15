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
            $block = $this->getBlockToEdit($content->getSourceIdentifier(), $page);
            if ($block) {
                $translated = explode(PHP_EOL, $content->getTranslated());
                $data = [
                    'altText' => $translated[0],
                ];
                if (isset($translated[1])) {
                    $data['title'] = $translated[1];
                }
                $block->update($data);

                return true;
            }
        }

        return false;
    }
}

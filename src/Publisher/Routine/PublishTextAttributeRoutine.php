<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

use Concrete\Attribute\Text\Controller;
use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Traits\AttributeValueTrait;

class PublishTextAttributeRoutine implements PublishRoutineInterface
{
    use AttributeValueTrait;

    public function publish(Page $page, TranslateContent $content): bool
    {
        if ($content->getSourceType() === 'attribute_text') {
            $value = $this->getPageValueToEdit($content->getSourceIdentifier(), $page);
            if ($value) {
                /** @var Controller $controller */
                $controller = $value->getController();
                $newValue = $controller->createAttributeValue($content->getTranslated());
                $page->setAttribute($value->getAttributeKey(), $newValue);

                return true;
            }
        }

        return false;
    }
}

<?php

namespace Macareux\ContentTranslator\Publisher\Routine;

use Concrete\Attribute\Textarea\Controller;
use Concrete\Core\Page\Page;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Traits\AttributeValueTrait;

class PublishTextareaAttributeRoutine implements PublishRoutineInterface
{
    use AttributeValueTrait;

    public function publish(Page $page, TranslateContent $content): bool
    {
        if ($content->getSourceType() === 'attribute_textarea') {
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

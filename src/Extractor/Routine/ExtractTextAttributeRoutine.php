<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Entity\Attribute\Value\PageValue;
use Concrete\Core\Entity\Attribute\Value\Value\TextValue;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Traits\AttributeValueTrait;

class ExtractTextAttributeRoutine extends AbstractExtractPageAttributeRoutine implements ExtractPageAttributeRoutineInterface
{
    use AttributeValueTrait;

    public function getContent(TranslateRequest $request, PageValue $value): ?TranslateContent
    {
        $content = null;

        $typeHandle = $value->getAttributeTypeObject()->getAttributeTypeHandle();
        if ($typeHandle === 'text') {
            /** @var TextValue $typeValue */
            $typeValue = $value->getValueObject();
            if ($typeValue) {
                $content = new TranslateContent();
                $content->setRequest($request);
                $content->setStatus(TranslateContent::STATUS_DRAFT);
                $content->setContent((string) $typeValue->getValue());
                $content->setSourceIdentifier($this->getPageValueIdentifier($value));
                $content->setSourceType('attribute_text');
                $content->setLabel($value->getAttributeKey()->getAttributeKeyDisplayName());
                $content->setType(TranslateContent::TYPE_STRING);
                $request->getContents()->add($content);
            }
        }

        return $content;
    }
}

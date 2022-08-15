<?php

namespace Macareux\ContentTranslator\Extractor\Routine;

use Concrete\Core\Entity\Attribute\Key\Settings\TextareaSettings;
use Concrete\Core\Entity\Attribute\Value\PageValue;
use Concrete\Core\Entity\Attribute\Value\Value\TextValue;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Traits\AttributeValueTrait;

class ExtractTextareaAttributeRoutine extends AbstractExtractPageAttributeRoutine implements ExtractPageAttributeRoutineInterface
{
    use AttributeValueTrait;

    public function getContent(TranslateRequest $request, PageValue $value): ?TranslateContent
    {
        $content = null;

        $typeHandle = $value->getAttributeTypeObject()->getAttributeTypeHandle();
        if ($typeHandle === 'textarea') {
            $key = $value->getAttributeKey();
            if ($key->getAttributeKeyHandle() !== 'header_extra_content') {
                /** @var TextareaSettings $settings */
                $settings = $key->getAttributeKeySettings();
                $contentType = $settings->getMode() === 'rich_text' ? TranslateContent::TYPE_HTML : TranslateContent::TYPE_TEXT;

                /** @var TextValue $typeValue */
                $typeValue = $value->getValueObject();
                if ($typeValue) {
                    $content = new TranslateContent();
                    $content->setRequest($request);
                    $content->setStatus(TranslateContent::STATUS_DRAFT);
                    $content->setContent((string) $typeValue->getValue());
                    $content->setSourceIdentifier($this->getPageValueIdentifier($value));
                    $content->setSourceType('attribute_textarea');
                    $content->setLabel($key->getAttributeKeyDisplayName());
                    $content->setType($contentType);
                    $request->getContents()->add($content);
                }
            }
        }

        return $content;
    }
}

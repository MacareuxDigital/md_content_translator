<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Error\ErrorList\ErrorList;
use Macareux\ContentTranslator\Entity\TranslateRequest;

class CopyTranslator extends AbstractTranslator implements TranslatorInterface
{
    public function translate(TranslateRequest $request): void
    {
        foreach ($request->getContents() as $content) {
            if (empty($content->getTranslated())) {
                $this->setTranslatedContent($content->getContent(), $content, false);
            }
        }
    }

    public function finishTranslate(TranslateRequest $request): ErrorList
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $this->errorList;
    }
}

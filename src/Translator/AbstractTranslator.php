<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Entity\Translator;

abstract class AbstractTranslator
{
    /** @var EntityManagerInterface */
    protected $entityManager;
    /** @var ErrorList */
    protected $errorList;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ErrorList $errorList
     */
    public function __construct(EntityManagerInterface $entityManager, ErrorList $errorList)
    {
        $this->entityManager = $entityManager;
        $this->errorList = $errorList;
    }

    public function setupTranslate(TranslateRequest $request): ErrorList
    {
        // Please override if your translator requires validating translate request.
        return new ErrorList();
    }

    public function finishTranslate(TranslateRequest $request): ErrorList
    {
        $request->setStatus(TranslateRequest::STATUS_PROGRESS);
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return $this->errorList;
    }

    protected function setTranslatedContent(string $translated, TranslateContent $content, bool $updateStatus = true)
    {
        $content->setTranslated($translated);
        if ($updateStatus) {
            $content->setStatus(TranslateContent::STATUS_TRANSLATED);
        }

        $this->entityManager->persist($content);
    }

    public function validateConfigurationRequest(Request $request, ErrorList $errorList): void
    {
        // Please override if your translator requires validating configuration request.
    }

    public function updateConfiguration(Request $request, Translator $translator): void
    {
        // Please override if your translator requires to save configuration.
    }

    public function loadConfiguration(string $configuration): void
    {
        // Please override if your translator requires to setup with configuration.
    }
}

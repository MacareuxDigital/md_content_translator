<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request as HttpRequest;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Entity\Translator;

interface TranslatorInterface
{
    /**
     * Setup Translate service.
     *
     * @return ErrorList return ErrorList object if translator has some issue
     */
    public function setupTranslate(TranslateRequest $request): ErrorList;

    /**
     * Translate contents.
     *
     * @param TranslateRequest $request
     *
     * @return void
     */
    public function translate(TranslateRequest $request): void;

    /**
     * Return error if something failed while translate process.
     *
     * @param TranslateRequest $request
     *
     * @return ErrorList
     */
    public function finishTranslate(TranslateRequest $request): ErrorList;

    /**
     * Validate user input from configuration screen. Add error if input is invalid.
     *
     * @param HttpRequest $request
     * @param ErrorList $errorList
     *
     * @return void
     */
    public function validateConfigurationRequest(HttpRequest $request, ErrorList $errorList): void;

    /**
     * Update configuration with user input.
     *
     * @param HttpRequest $request
     * @param Translator $translator
     *
     * @return void
     */
    public function updateConfiguration(HttpRequest $request, Translator $translator): void;

    /**
     * Load configuration from database.
     *
     * @param string $configuration
     *
     * @return void
     */
    public function loadConfiguration(string $configuration): void;
}

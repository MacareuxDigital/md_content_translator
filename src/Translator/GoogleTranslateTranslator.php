<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request;
use Google\ApiCore\ApiException;
use Google\Cloud\Translate\V3\TranslateTextGlossaryConfig;
use Google\Cloud\Translate\V3\TranslationServiceClient;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Entity\Translator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GoogleTranslateTranslator extends AbstractTranslator implements TranslatorInterface
{
    protected $configuration;

    public function setupTranslate(TranslateRequest $request): ErrorList
    {
        return parent::setupTranslate($request); // @todo: check available language
    }

    public function translate(TranslateRequest $request): void
    {
        $htmlStrings = [];
        $htmlContents = [];
        $textStrings = [];
        $textContents = [];
        foreach ($request->getContents() as $content) {
            if ($content->getStatus() !== TranslateContent::STATUS_TRANSLATED) {
                if ($content->getType() === TranslateContent::TYPE_HTML) {
                    $htmlStrings[] = $content->getContent();
                    $htmlContents[] = $content;
                } else {
                    $textStrings[] = $content->getContent();
                    $textContents[] = $content;
                }
            }
        }

        $translationOptions = [
            'sourceLanguageCode' => $request->getSourceLanguage(),
        ];
        $googleTranslationServiceClient = new TranslationServiceClient(['credentials' => $this->configuration['credentials']]);
        $formattedParent = TranslationServiceClient::locationName(
            $this->configuration['credentials']['project_id'],
            'us-central1'
        );
        if (isset($this->configuration['glossaryId'])) {
            $glossaryPath = TranslationServiceClient::glossaryName(
                $this->configuration['credentials']['project_id'],
                'us-central1',
                $this->configuration['glossaryId']
            );
            $glossaryConfig = new TranslateTextGlossaryConfig();
            $glossaryConfig->setGlossary($glossaryPath);
            $translationOptions['glossaryConfig'] = $glossaryConfig;
        }

        try {
            if ($htmlStrings) {
                $translationOptions['mineType'] = 'text/html';
                $response = $googleTranslationServiceClient->translateText(
                    $htmlStrings,
                    $request->getTargetLanguage(),
                    $formattedParent,
                    $translationOptions
                );
                if (isset($glossaryConfig)) {
                    foreach ($response->getGlossaryTranslations() as $index => $translation) {
                        $this->setTranslatedContent($translation->getTranslatedText(), $htmlContents[$index]);
                    }
                } else {
                    foreach ($response->getTranslations() as $index => $translation) {
                        $this->setTranslatedContent($translation->getTranslatedText(), $htmlContents[$index]);
                    }
                }
            }
            if ($textStrings) {
                $translationOptions['mineType'] = 'text/plain';
                $response = $googleTranslationServiceClient->translateText(
                    $textStrings,
                    $request->getTargetLanguage(),
                    $formattedParent,
                    $translationOptions
                );
                if (isset($glossaryConfig)) {
                    foreach ($response->getGlossaryTranslations() as $index => $translation) {
                        $this->setTranslatedContent($translation->getTranslatedText(), $textContents[$index]);
                    }
                } else {
                    foreach ($response->getTranslations() as $index => $translation) {
                        $this->setTranslatedContent($translation->getTranslatedText(), $textContents[$index]);
                    }
                }
            }
        } catch (ApiException $apiException) {
            $this->errorList->add($apiException->getBasicMessage());
        } finally {
            $googleTranslationServiceClient->close();
        }
    }

    public function validateConfigurationRequest(Request $request, ErrorList $errorList): void
    {
        require_once DIR_PACKAGES . '/md_content_translator/vendor/autoload.php';

        $uploaded = (bool) $request->request->get('uploaded');
        $file = $request->files->get('config_json');
        if ($file instanceof UploadedFile) {
            $config = json_decode($file->getContent(), true);
            $googleTranslationServiceClient = new TranslationServiceClient(['credentials' => $config]);
            $formattedParent = TranslationServiceClient::locationName($config['project_id'], 'global');

            try {
                $googleTranslationServiceClient->translateText(['Hello, Workd!'], 'fr', $formattedParent);
            } catch (ApiException $exception) {
                $errorList->add($exception->getBasicMessage());
            }
        } elseif (!$uploaded) {
            $errorList->add(t('Please upload config.json file.'));
        }
    }

    public function updateConfiguration(Request $request, Translator $translator): void
    {
        $config = $this->configuration ?: [];

        $file = $request->files->get('config_json');
        if ($file instanceof UploadedFile) {
            $config['credentials'] = json_decode($file->getContent(), true);
        }

        $bucket = $request->request->get('glossaryId');
        if ($bucket) {
            $config['glossaryId'] = $bucket;
        }

        if ($config) {
            $translator->setConfiguration(json_encode($config));
            $this->entityManager->persist($translator);
            $this->entityManager->flush();
        }
    }

    public function loadConfiguration(string $configuration): void
    {
        $this->configuration = json_decode($configuration, true);
    }
}

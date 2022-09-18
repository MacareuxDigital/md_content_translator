<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Translate\V2\TranslateClient;
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
        $option = [
            'source' => $request->getSourceLanguage(),
            'target' => $request->getTargetLanguage(),
            'format' => 'html',
        ];

        try {
            $client = new TranslateClient(['keyFile' => $this->configuration]);
            if ($htmlStrings) {
                foreach ($client->translateBatch($htmlStrings, $option) as $index => $result) {
                    $this->setTranslatedContent($result['text'], $htmlContents[$index]);
                }
            }
            if ($textStrings) {
                foreach ($client->translateBatch($textStrings, $option) as $index => $result) {
                    $this->setTranslatedContent($result['text'], $textContents[$index]);
                }
            }
        } catch (ServiceException $exception) {
            $error = json_decode($exception->getMessage());
            $this->errorList->add($error->error->message);
        }
    }

    public function validateConfigurationRequest(Request $request, ErrorList $errorList): void
    {
        require_once DIR_PACKAGES . '/md_content_translator/vendor/autoload.php';

        $uploaded = (bool) $request->request->get('uploaded');
        $file = $request->files->get('config_json');
        if ($file instanceof UploadedFile) {
            $config = json_decode($file->getContent(), true);
            try {
                new TranslateClient(['keyFile' => $config]);
            } catch (\Exception $exception) {
                $errorList->add($exception->getMessage());
            }
        } elseif (!$uploaded) {
            $errorList->add(t('Please upload config.json file.'));
        }
    }

    public function updateConfiguration(Request $request, Translator $translator): void
    {
        $file = $request->files->get('config_json');
        if ($file instanceof UploadedFile) {
            $translator->setConfiguration($file->getContent());
            $this->entityManager->persist($translator);
            $this->entityManager->flush();
        }
    }

    public function loadConfiguration(string $configuration): void
    {
        $this->configuration = json_decode($configuration, true);
    }
}

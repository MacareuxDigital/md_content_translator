<?php

namespace Macareux\ContentTranslator\Translator;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request;
use DeepL\DeepLException;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Entity\Translator;

class DeeplTranslator extends AbstractTranslator implements TranslatorInterface
{
    protected $configuration;

    public function setupTranslate(TranslateRequest $request): ErrorList
    {
        return parent::setupTranslate($request); // @todo: check available language
    }

    public function translate(TranslateRequest $request): void
    {
        $texts = [];
        $contents = [];
        foreach ($request->getContents() as $content) {
            if ($content->getStatus() !== TranslateContent::STATUS_TRANSLATED) {
                $texts[] = $content->getContent();
                $contents[] = $content;
            }
        }
        $options = [
            'preserve_formatting' => true,
            'tag_handling' => 'html',
        ];
        try {
            $deeplTranslator = new \DeepL\Translator($this->configuration['auth_key']);
            $translations = $deeplTranslator->translateText($texts, $request->getSourceLanguage(), $request->getTargetLanguage(), $options);
            foreach ($translations as $index => $translation) {
                $this->setTranslatedContent($translation->text, $contents[$index]);
            }
        } catch (DeepLException $exception) {
            $this->errorList->add($exception->getMessage());
        }
    }

    public function validateConfigurationRequest(Request $request, ErrorList $errorList): void
    {
        require_once DIR_PACKAGES . '/md_content_translator/vendor/autoload.php';

        $auth_key = $request->request->get('auth_key');
        if ($auth_key) {
            try {
                $deeplTranslator = new \DeepL\Translator($auth_key);
                $deeplTranslator->getUsage();
            } catch (DeepLException $exception) {
                $errorList->add($exception->getMessage());
            }
        } else {
            $errorList->add(t('Please provide an authentication key.'));
        }
    }

    public function updateConfiguration(Request $request, Translator $translator): void
    {
        $auth_key = $request->request->get('auth_key');
        if ($auth_key) {
            $config = [
                'auth_key' => $auth_key,
            ];
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

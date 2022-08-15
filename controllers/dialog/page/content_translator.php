<?php

namespace Concrete\Package\MdContentTranslator\Controller\Dialog\Page;

use Concrete\Controller\Backend\UserInterface\Page;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Localization\Service\LanguageList;
use Concrete\Core\Multilingual\Page\Section\Section;
use Concrete\Core\Multilingual\Service\Detector;
use Concrete\Core\Page\EditResponse;
use Doctrine\ORM\EntityManagerInterface;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Entity\TranslateRequestRepository;
use Macareux\ContentTranslator\Extractor\Extractor;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContentTranslator extends Page
{
    protected $helpers = ['form'];

    protected $viewPath = '/dialogs/page/content_translator';

    public function view()
    {
        /** @var EntityManagerInterface $em */
        $em = $this->app->make(EntityManagerInterface::class);
        /** @var TranslateRequestRepository $repository */
        $repository = $em->getRepository(TranslateRequest::class);
        $this->set('draft', $repository->findDraftByCollection($this->page));
        $this->set('progress', $repository->findProgressByCollection($this->page));
        $this->set('published', $repository->findPublishedByCollection($this->page));

        $sourceLang = null;
        $targetLang = null;

        /** @var Detector $detector */
        $detector = $this->app->make('multilingual/detector');
        if ($detector->isEnabled()) {
            /** @var Section $section */
            $section = Section::getBySectionOfSite($this->page);
            $targetLang = $section->getLanguage();
            if (!$section->isDefaultMultilingualSection()) {
                $sourceLang = Section::getDefaultSection()->getLanguage();
            }
        }

        $this->set('defaultSourceLang', $sourceLang);
        $this->set('defaultTargetLang', $targetLang);

        /** @var LanguageList $languages */
        $languages = $this->app->make('localization/languages');
        $this->set('languages', ['' => t('** Select Language')] + $languages->getLanguageList());
    }

    public function submit()
    {
        if ($this->validateAction()) {
            $sourceLang = (string) $this->post('sourceLang');
            $targetLang = (string) $this->post('targetLang');

            /** @var ErrorList $error */
            $error = $this->app->make(ErrorList::class);
            if (!$sourceLang || !$targetLang) {
                $error->add(t('Please select language.'));
            }

            $pr = new EditResponse();
            $pr->setPage($this->page);

            if ($error->has()) {
                $pr->setError($error);
                $pr->setMessage((string) $error);
            } else {
                /** @var Extractor $extractor */
                $extractor = $this->app->make(Extractor::class, [
                    'page' => $this->page,
                    'sourceLang' => $sourceLang,
                    'targetLang' => $targetLang,
                ]);
                $extractor->extract();
                $pr->setMessage(t('Translate request successfully submitted.'));
            }

            return new JsonResponse($pr->getJSONObject());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function canAccess()
    {
        return $this->permissions->canTranslatePageContents();
    }
}

<?php

namespace Concrete\Package\MdContentTranslator\Controller\SinglePage\Dashboard\ContentTranslator;

use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\Filesystem\ElementManager;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Controller\DashboardSitePageController;
use Concrete\Core\Search\Pagination\PaginationFactory;
use Macareux\ContentTranslator\Entity\GlossaryTerm;
use Macareux\ContentTranslator\Entity\GlossaryTranslation;
use Macareux\ContentTranslator\Search\GlossaryTermList;

class Glossary extends DashboardSitePageController
{
    public function view()
    {
        $defaultLanguage = $this->getDefaultLanguage();
        $languages = $this->getLanguages($defaultLanguage);
        if (count($languages) > 1) {
            $this->set('languages', $languages);

            /** @var GlossaryTermList $list */
            $list = $this->app->make(GlossaryTermList::class);
            $list->sortByTerm($defaultLanguage);
            $factory = new PaginationFactory(Request::getInstance());
            $pagination = $factory->createPaginationObject($list, PaginationFactory::PERMISSIONED_PAGINATION_STYLE_PAGER);
            $this->set('list', $list);
            $this->set('pagination', $pagination);
            $this->set('headerMenu', $this->app->make(ElementManager::class)->get('dashboard/glossary/menu', 'md_content_translator'));
            $this->setThemeViewTemplate('full.php');
        }
    }

    public function form(int $termID = null)
    {
        $defaultLanguage = $this->getDefaultLanguage();
        $this->set('languages', $this->getLanguages($defaultLanguage));
        $this->render('/dashboard/content_translator/glossary/form', 'md_content_translator');

        $term = null;
        if ($termID) {
            $repository = $this->getEntityManager()->getRepository(GlossaryTerm::class);
            $term = $repository->find($termID);
        }

        if ($term) {
            $this->set('term', $term);
            $this->set('description', $term->getDescription());
            $this->set('pageTitle', t('Edit Glossary Term'));
        } else {
            $this->set('pageTitle', t('Add Glossary Term'));
        }

        $this->set('headerMenu', $this->app->make(ElementManager::class)->get('dashboard/glossary/delete', ['termID' => $termID], 'md_content_translator'));
    }

    public function delete()
    {
        if (!$this->token->validate('delete_glossary_term')) {
            $this->error->add($this->token->getErrorMessage());
            $this->flash('error', $this->token->getErrorMessage());
        }

        $termID = $this->post('termID');
        /** @var GlossaryTerm $term */
        $term = $this->getEntityManager()->getRepository(GlossaryTerm::class)->find($termID);
        if (!$term) {
            $this->error->add(t('Invalid Term.'));
            $this->flash('error', t('Invalid Term.'));
        }

        if (!$this->error->has()) {
            $this->getEntityManager()->remove($term);
            $this->getEntityManager()->flush();
            $this->flash('success', t('Successfully deleted.'));
        }

        return $this->buildRedirect($this->action('view'));
    }

    public function submit()
    {
        if (!$this->token->validate('content_translation_glossary')) {
            $this->error->add($this->token->getErrorMessage());
        }

        if (!$this->error->has()) {
            $termID = $this->post('termID');
            /** @var GlossaryTerm $term */
            $term = $this->getEntityManager()->getRepository(GlossaryTerm::class)->find($termID);
            if ($term) {
                foreach ($this->getLanguages($this->getDefaultLanguage()) as $language) {
                    $translation = $term->getTranslationByLanguage($language);
                    if (!$translation) {
                        $translation = new GlossaryTranslation();
                    }
                    $translation->setLanguage($language);
                    $content = $this->post($language);
                    $translation->setContent($content);
                    $translation->setTerm($term);
                    $term->getTranslations()->add($translation);
                }
            } else {
                $term = new GlossaryTerm();
                foreach ($this->getLanguages($this->getDefaultLanguage()) as $language) {
                    $translation = new GlossaryTranslation();
                    $translation->setLanguage($language);
                    $content = $this->post($language);
                    $translation->setContent($content);
                    $translation->setTerm($term);
                    $term->getTranslations()->add($translation);
                }
            }

            $description = $this->post('description');
            if ($description) {
                $term->setDescription($description);
            }

            $this->getEntityManager()->persist($term);
            $this->getEntityManager()->flush();

            /**
             * @todo: Clear expensive cache
             * @see \Macareux\ContentTranslator\Glossary\GlossaryService::getTermsFromContent
             */
            $cache = $this->app->make(ExpensiveCache::class);

            $this->flash('success', t('Successfully saved.'));

            return $this->buildRedirect($this->action('view'));
        }
    }

    protected function getDefaultLanguage(): string
    {
        $siteConfig = $this->getSite()->getConfigRepository();
        $defaultSourceLanguage = 'en';
        $defaultSourceLocale = $siteConfig->get('multilingual.default_source_locale');
        if ($defaultSourceLocale) {
            if (strpos($defaultSourceLocale, '_') === false) {
                $defaultSourceLanguage = $defaultSourceLocale;
            } else {
                list($defaultSourceLanguage, $defaultSourceCountry) = explode('_', $defaultSourceLocale);
            }
        }

        return $defaultSourceLanguage;
    }

    protected function getLanguages(string $defaultLanguage): array
    {
        $languages = [$defaultLanguage];
        foreach ($this->getSite()->getLocales() as $locale) {
            if (!in_array($locale->getLanguage(), $languages)) {
                $languages[] = $locale->getLanguage();
            }
        }

        return $languages;
    }
}

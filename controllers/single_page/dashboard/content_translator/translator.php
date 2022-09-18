<?php

namespace Concrete\Package\MdContentTranslator\Controller\SinglePage\Dashboard\ContentTranslator;

use Concrete\Core\Page\Controller\DashboardPageController;
use Macareux\ContentTranslator\Translator\Manager;

class Translator extends DashboardPageController
{
    public function view()
    {
        /** @var Manager $manager */
        $manager = $this->app->make(Manager::class);
        $this->set('translators', $manager->getInstalledTranslators());
    }

    public function config($id)
    {
        /** @var Manager $manager */
        $manager = $this->app->make(Manager::class);
        $translator = $manager->getTranslatorByID($id);
        if ($translator) {
            $this->set('translator', $translator);
            $this->set('pageTitle', t('Edit %s Translator', $translator->getName()));
            $this->render('/dashboard/content_translator/translator/config', 'md_content_translator');
        } else {
            $this->view();
        }
    }

    public function save($id)
    {
        /** @var Manager $manager */
        $manager = $this->app->make(Manager::class);
        $translator = $manager->getTranslatorByID($id);
        if ($translator) {
            if (!$this->token->validate('content_translator_config')) {
                $this->error->add($this->token->getErrorMessage());
            }

            $service = $manager->getTranslatorService($translator);
            $service->validateConfigurationRequest($this->request, $this->error);

            if (!$this->error->has()) {
                $service->updateConfiguration($this->request, $translator);
                $active = $this->request->request->get('active');
                $translator->setActive((bool) $active);
                $this->entityManager->persist($translator);
                $this->entityManager->flush();
                $this->flash('success', t('Successfully updated.'));

                return $this->buildRedirect($this->action('view'));
            }
                $this->flash('error', $this->error->toText());
                $this->set('translator', $translator);
                $this->set('pageTitle', t('Edit %s Translator', $translator->getName()));
                $this->render('/dashboard/content_translator/translator/config', 'md_content_translator');
        } else {
            return $this->buildRedirect($this->action('view'));
        }
    }
}

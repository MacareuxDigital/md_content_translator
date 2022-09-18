<?php

namespace Concrete\Package\MdContentTranslator\Controller\SinglePage\Dashboard\ContentTranslator;

use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Glossary\GlossaryService;
use Macareux\ContentTranslator\Publisher\Publisher;
use Macareux\ContentTranslator\Translator\Manager;

class Detail extends DashboardPageController
{
    public function view($id = null)
    {
        if ($id) {
            $request = $this->getEntity((int) $id);
            $this->set('request', $request);
            $sourcePage = Page::getByID($request->getCID());
            $permission = new Checker($sourcePage);
            $canPublish = $permission->canApprovePageVersions() && $permission->canEditPageContents();
            $this->set('canPublish', $canPublish);
            $this->set('editor', $this->app->make('editor'));
            /** @var Manager $manager */
            $manager = $this->app->make(Manager::class);
            $translators = ['' => t('** Choose Translator')];
            foreach ($manager->getAvailableTranslators() as $translator) {
                $translators[$translator->getHandle()] = $translator->getName();
            }
            $this->set('translators', $translators);
            $this->set('service', $this->app->make(GlossaryService::class));

            $this->requireAsset('content_translator');
        }
    }

    public function submit($id)
    {
        $request = $this->getEntity($id);
        if ($request) {
            if (!$this->token->validate('content_translator')) {
                $this->error->add($this->token->getErrorMessage());
            }

            if (!$this->error->has()) {
                foreach ($request->getContents() as $content) {
                    $translated = $this->request->request->get('translate_' . $content->getId());
                    if ($translated) {
                        if ($content->getType() === TranslateContent::TYPE_HTML) {
                            $translated = LinkAbstractor::translateTo($translated);
                        }
                        if ($translated !== $content->getContent()) {
                            $content->setTranslated($translated);
                            $content->setStatus(TranslateContent::STATUS_TRANSLATED);
                            $this->entityManager->persist($content);
                        }
                    } else {
                        $content->clearTranslated();
                        $content->setStatus(TranslateContent::STATUS_DRAFT);
                        $this->entityManager->persist($content);
                    }
                }
                $request->setStatus(TranslateRequest::STATUS_PROGRESS);
                $this->entityManager->persist($request);
                $this->entityManager->flush();
                $this->flash('success', t('Successfully saved.'));
            } else {
                $this->flash('error', $this->error->toText());
            }
        }

        return $this->buildRedirect($this->action('view', $id));
    }

    public function translate($id)
    {
        $request = $this->getEntity($id);
        if ($request) {
            if (!$this->token->validate('content_translator_translate')) {
                $this->error->add($this->token->getErrorMessage());
            }

            $translatorHandle = $this->request->request->get('translator');
            /** @var Manager $manager */
            $manager = $this->app->make(Manager::class);
            $translatorEntity = $manager->getTranslatorByHandle($translatorHandle);
            if ($translatorEntity) {
                $translator = $manager->getTranslatorService($translatorEntity);
                if ($translator) {
                    $setupError = $translator->setupTranslate($request);
                    if ($setupError->has()) {
                        $this->error->addError($setupError);
                    }
                } else {
                    $this->error->add(t('Translator class is not found.'));
                }
            } else {
                $this->error->add(t('Invalid Translator.'));
            }

            if (!$this->error->has() && isset($translator)) {
                $translator->translate($request);
                $resultError = $translator->finishTranslate($request);
                if ($resultError->has()) {
                    $this->flash('error', $resultError->toText());
                } else {
                    $this->flash('success', t('Successfully translated with %s', h($translatorEntity->getName())));
                }
            } else {
                $this->flash('error', $this->error->toText());
            }
        }

        return $this->buildRedirect($this->action('view', $id));
    }

    public function cancel($id)
    {
        $request = $this->getEntity($id);
        if ($request) {
            if (!$this->token->validate('content_translator_cancel')) {
                $this->error->add($this->token->getErrorMessage());
            }

            if (!$this->error->has()) {
                $request->setStatus(TranslateRequest::STATUS_CANCELED);
                $this->entityManager->persist($request);
                $this->entityManager->flush();
                $this->flash('success', t('Discarded.'));
            } else {
                $this->flash('error', $this->error->toText());
            }
        }

        return $this->buildRedirect($this->app->make(ResolverManagerInterface::class)->resolve(['/dashboard/content_translator/search']));
    }

    public function publish($id)
    {
        $request = $this->getEntity($id);
        if ($request) {
            if (!$this->token->validate('content_translator_publish')) {
                $this->error->add($this->token->getErrorMessage());
            }

            $page = Page::getByID($request->getCID());
            $permission = new Checker($page);
            if (!$permission->canApprovePageVersions()) {
                $this->error->add(t('You do not have access to approve page.'));
            }
            if (!$permission->canEditPageContents()) {
                $this->error->add(t('You do not have access to edit page.'));
            }

            if (!$this->error->has()) {
                /** @var Publisher $publisher */
                $publisher = $this->app->make(Publisher::class, ['request' => $request]);
                $publisher->publish();
                $resolver = $this->app->make(ResolverManagerInterface::class);

                return $this->buildRedirect($resolver->resolve([$page]));
            }
            $this->flash('error', $this->error->toText());
        }

        return $this->buildRedirect($this->action('view', $id));
    }

    protected function getEntity(int $id): ?TranslateRequest
    {
        $repository = $this->entityManager->getRepository(TranslateRequest::class);

        return $repository->find($id);
    }
}

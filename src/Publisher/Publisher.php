<?php

namespace Macareux\ContentTranslator\Publisher;

use Concrete\Core\Cache\Level\RequestCache;
use Concrete\Core\Page\Page;
use Doctrine\ORM\EntityManager;
use Macareux\ContentTranslator\Entity\TranslateContent;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Publisher\Routine\Manager;

class Publisher
{
    /**
     * @var TranslateRequest
     */
    protected $request;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param TranslateRequest $request
     * @param Manager $manager
     * @param EntityManager $entityManager
     */
    public function __construct(TranslateRequest $request, Manager $manager, EntityManager $entityManager)
    {
        $this->request = $request;
        $this->manager = $manager;
        $this->entityManager = $entityManager;
    }

    public function publish()
    {
        $updated = false;
        $c = Page::getByID($this->request->getCID());
        if ($c && !$c->isError()) {
            $c = $c->getVersionToModify();
            foreach ($this->request->getContents() as $content) {
                if ($content->getStatus() === TranslateContent::STATUS_TRANSLATED) {
                    foreach ($this->manager->getRoutines() as $routine) {
                        if ($routine->publish($c, $content)) {
                            $updated = true;
                        }
                    }
                }
            }
        }

        if ($updated) {
            $nv = $c->getVersionObject();
            $nv->setComment(t('Translate applied.'));
            $nv->approve();
            $this->request->setStatus(TranslateRequest::STATUS_PUBLISHED);
            $this->entityManager->persist($this->request);
            $this->entityManager->flush();
        }
    }
}

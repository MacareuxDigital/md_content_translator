<?php

namespace Macareux\ContentTranslator\Extractor;

use Concrete\Core\Page\Page;
use Doctrine\ORM\EntityManager;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Macareux\ContentTranslator\Extractor\Routine\ExtractBlockRoutineInterface;
use Macareux\ContentTranslator\Extractor\Routine\ExtractPageAttributeRoutineInterface;
use Macareux\ContentTranslator\Extractor\Routine\ExtractPagePropertyRoutineInterface;
use Macareux\ContentTranslator\Extractor\Routine\Manager;

class Extractor
{
    /**
     * @var Page
     */
    protected $page;

    /**
     * @var string
     */
    protected $sourceLang;

    /**
     * @var string
     */
    protected $targetLang;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param Page $page
     * @param EntityManager $entityManager
     */
    public function __construct(Page $page, string $sourceLang, string $targetLang, Manager $manager, EntityManager $entityManager)
    {
        $this->page = $page;
        $this->sourceLang = $sourceLang;
        $this->targetLang = $targetLang;
        $this->manager = $manager;
        $this->entityManager = $entityManager;
    }

    public function extract()
    {
        $request = new TranslateRequest();
        $request->setCID($this->page->getCollectionID());
        $request->setSourceLanguage($this->sourceLang);
        $request->setTargetLanguage($this->targetLang);
        $request->setStatus(TranslateRequest::STATUS_DRAFT);
        $request->setTitle($this->page->getCollectionName());

        /** @var ExtractPagePropertyRoutineInterface $routine */
        foreach ($this->manager->getRoutinesByCategory('page_property') as $routine) {
            $content = $routine->getContent($request, $this->page);
            if ($content) {
                $request->getContents()->add($content);
                $this->entityManager->persist($content);
            }
        }

        foreach ($this->page->getSetCollectionAttributes() as $attribute) {
            /** @var ExtractPageAttributeRoutineInterface $routine */
            foreach ($this->manager->getRoutinesByCategory('page_attribute') as $routine) {
                $content = $routine->getContent($request, $this->page->getAttributeValueObject($attribute));
                if ($content) {
                    $request->getContents()->add($content);
                    $this->entityManager->persist($content);
                }
            }
        }

        foreach ($this->page->getBlocks() as $block) {
            if ($block->isAliasOfMasterCollection() === false) {
                /** @var ExtractBlockRoutineInterface $routine */
                foreach ($this->manager->getRoutinesByCategory('block') as $routine) {
                    $content = $routine->getContent($request, $block);
                    if ($content) {
                        $request->getContents()->add($content);
                        $this->entityManager->persist($content);
                    }
                }
            }
        }

        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }
}

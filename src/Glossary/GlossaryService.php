<?php

namespace Macareux\ContentTranslator\Glossary;

use Concrete\Core\Cache\Level\ExpensiveCache;
use Doctrine\ORM\EntityManagerInterface;
use Macareux\ContentTranslator\Entity\GlossaryTranslation;
use Macareux\ContentTranslator\Entity\GlossaryTranslationRepository;
use Macareux\ContentTranslator\Entity\TranslateRequest;

class GlossaryService
{
    /**
     * @var GlossaryTranslationRepository
     */
    protected $repository;

    /**
     * @var ExpensiveCache
     */
    protected $cache;

    public function __construct(EntityManagerInterface $entityManager, ExpensiveCache $cache)
    {
        $this->repository = $entityManager->getRepository(GlossaryTranslation::class);
        $this->cache = $cache;
    }

    public function getTermsFromContent(string $content, TranslateRequest $request): array
    {
        // @todo: Check expensive cache by language, store $terms to expensive cache
        $allTerms = $this->repository->findByLanguage($request->getSourceLanguage());
        $terms = [];
        /** @var GlossaryTranslation $translation */
        foreach ($allTerms as $translation) {
            $source = $translation->getContent();
            $translated = $translation->getTerm()->getTranslationByLanguage($request->getTargetLanguage());
            if ($source && $translated) {
                $terms[$source] = $translated->getContent();
            }
        }

        foreach ($terms as $source => $translated) {
            if (strpos(strtolower($content), strtolower($source)) === false) {
                unset($terms[$source]);
            }
        }

        return $terms;
    }
}

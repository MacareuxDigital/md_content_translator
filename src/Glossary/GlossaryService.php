<?php

namespace Macareux\ContentTranslator\Glossary;

use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\Database\Connection\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Macareux\ContentTranslator\Entity\GlossaryTranslation;
use Macareux\ContentTranslator\Entity\GlossaryTranslationRepository;
use Macareux\ContentTranslator\Entity\TranslateRequest;

class GlossaryService
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var GlossaryTranslationRepository
     */
    protected $repository;

    /**
     * @var ExpensiveCache
     */
    protected $cache;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager, ExpensiveCache $cache)
    {
        $this->connection = $connection;
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

    /**
     * Get all language codes in the glossary.
     *
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAvailableLanguages(): array
    {
        $languages = [];
        $qb = $this->connection->createQueryBuilder();
        $results= $qb->select('language')
            ->from('MdGlossaryTranslations')
            ->groupBy('language')
            ->execute()
            ->fetchAllAssociative();
        foreach ($results as $result) {
            $languages[] = $result['language'];
        }

        return $languages;
    }
}

<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\ORM\EntityRepository;

class GlossaryTranslationRepository extends EntityRepository
{
    public function findByLanguage(string $language): array
    {
        return $this->findBy(['language' => $language]);
    }
}

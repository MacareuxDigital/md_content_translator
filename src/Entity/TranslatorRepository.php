<?php

namespace Macareux\ContentTranslator\Entity;

use Doctrine\ORM\EntityRepository;

class TranslatorRepository extends EntityRepository
{
    public function findOneByHandle(string $handle): ?Translator
    {
        return $this->findOneBy([
            'handle' => $handle,
        ]);
    }
}

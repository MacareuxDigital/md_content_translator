<?php

namespace Macareux\ContentTranslator\Entity;

use Concrete\Core\Entity\Express\EntityRepository;

class TranslatorRepository extends EntityRepository
{
    public function findOneByHandle(string $handle): ?Translator
    {
        return $this->findOneBy([
            'handle' => $handle
        ]);
    }
}

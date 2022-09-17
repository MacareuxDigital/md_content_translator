<?php

namespace Macareux\ContentTranslator\Entity;

use Concrete\Core\Page\Collection\Collection;
use Doctrine\ORM\EntityRepository;

class TranslateRequestRepository extends EntityRepository
{
    public function findDraftByCollection(Collection $collection)
    {
        return $this->findOneBy([
            'status' => TranslateRequest::STATUS_DRAFT,
            'cID' => $collection->getCollectionID(),
        ]);
    }

    public function findProgressByCollection(Collection $collection)
    {
        return $this->findOneBy([
            'status' => TranslateRequest::STATUS_PROGRESS,
            'cID' => $collection->getCollectionID(),
        ]);
    }

    public function findCanceledByCollection(Collection $collection)
    {
        return $this->findOneBy([
            'status' => TranslateRequest::STATUS_CANCELED,
            'cID' => $collection->getCollectionID(),
        ]);
    }

    public function findPublishedByCollection(Collection $collection)
    {
        return $this->findOneBy([
            'status' => TranslateRequest::STATUS_PUBLISHED,
            'cID' => $collection->getCollectionID(),
        ]);
    }
}

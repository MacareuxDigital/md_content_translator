<?php

namespace Macareux\ContentTranslator\Search;

use Concrete\Core\Page\Page;
use Concrete\Core\Search\ItemList\EntityItemList;
use Concrete\Core\Search\Pagination\PaginationProviderInterface;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Macareux\ContentTranslator\Entity\TranslateRequest;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class RequestList extends EntityItemList implements PaginationProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        $app = Application::getFacadeApplication();

        return $app->make(EntityManagerInterface::class);
    }

    public function filterBySourceLanguage(string $lang)
    {
        $this->query
            ->andWhere($this->query->expr()->eq('source_language', ':source_language'))
            ->setParameter('source_language', $lang)
        ;
    }

    public function filterByTargetLanguage(string $lang)
    {
        $this->query
            ->andWhere($this->query->expr()->eq('target_language', ':target_language'))
            ->setParameter('target_language', $lang)
        ;
    }

    public function filterByStatus(string $status)
    {
        $this->query
            ->andWhere($this->query->expr()->eq('status', ':status'))
            ->setParameter('status', $status)
        ;
    }

    public function filterByPage(Page $page)
    {
        $this->query
            ->andWhere($this->query->expr()->eq('cID', ':cID'))
            ->setParameter('cID', $page->getCollectionID())
        ;
    }

    public function sortByDate()
    {
        $this->sortBy('r.created_at');
    }

    public function sortByDateDescending()
    {
        $this->sortBy('r.created_at', 'desc');
    }

    public function createQuery()
    {
        $this->query->select('r')->from(TranslateRequest::class, 'r');
    }

    public function getResult($result)
    {
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalResults()
    {
        $count = 0;
        $query = $this->query->select('count(distinct r.id)')
            ->setMaxResults(1)->resetDQLParts(['groupBy', 'orderBy']);

        try {
            $count = $query->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginationAdapter()
    {
        return new QueryAdapter($this->deliverQueryObject());
    }
}

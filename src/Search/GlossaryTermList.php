<?php

namespace Macareux\ContentTranslator\Search;

use Concrete\Core\Search\ItemList\EntityItemList;
use Concrete\Core\Search\Pagination\PaginationProviderInterface;
use Concrete\Core\Support\Facade\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Macareux\ContentTranslator\Entity\GlossaryTerm;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class GlossaryTermList extends EntityItemList implements PaginationProviderInterface
{
    /**
     * @var string
     */
    protected $joinLanguage;

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        $app = Application::getFacadeApplication();

        return $app->make(EntityManagerInterface::class);
    }

    public function createQuery()
    {
        $this->query->select('g')->from(GlossaryTerm::class, 'g');
    }

    public function finalizeQuery(\Doctrine\ORM\QueryBuilder $query)
    {
        if ($this->joinLanguage) {
            $query
                ->leftJoin('g.translations', 't')
                ->where($this->query->expr()->eq('t.language', ':language'))
                ->setParameter('language', $this->joinLanguage)
            ;
        }

        return $query;
    }

    public function sortByTerm(string $lang)
    {
        $this->joinLanguage = $lang;
        $this->sortBy('t.content');
    }

    public function sortByTermDescending(string $lang)
    {
        $this->sortBy('t.content', 'DESC');
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
        $query = $this->query->select('count(distinct g.id)')
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

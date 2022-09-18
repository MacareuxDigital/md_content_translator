<?php

namespace Macareux\ContentTranslator\Utility;

use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Checker;

class UtilityService
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function canAccessToTranslateInterface(): bool
    {
        $translatePage = Page::getByPath('/dashboard/content_translator/detail');
        $checker = new Checker($translatePage);

        return (bool) $checker->canViewPage();
    }

    public function isThirdPartyTranslatorsEnabled(): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $r = $qb->select('id')
            ->from('MdTranslators')
            ->where(
                $qb->expr()->or(
                    $qb->expr()->eq('handle', ':google_translate'),
                    $qb->expr()->eq('handle', ':deepl'),
                )
            )
            ->andWhere($qb->expr()->eq('active', ':active'))
            ->setParameter('google_translate', 'google_translate')
            ->setParameter('deepl', 'deepl')
            ->setParameter('active', true)
            ->execute()->fetchOne();

        return $r !== false;
    }
}

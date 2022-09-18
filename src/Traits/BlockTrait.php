<?php

namespace Macareux\ContentTranslator\Traits;

use Concrete\Core\Area\Area;
use Concrete\Core\Block\Block;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Legacy\BlockRecord;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Doctrine\Common\Collections\Criteria;
use Macareux\ContentTranslator\Entity\TranslateContent;

trait BlockTrait
{
    /**
     * Generate an identifier for the given block instance.
     *
     * @param Block $block
     *
     * @return string
     */
    protected function getBlockIdentifier(Block $block): string
    {
        return $block->getAreaHandle() . '|' . $block->getBlockID();
    }

    /**
     * Generate a label for the given block instance.
     *
     * @param Block $block
     * @param BlockController $controller
     * @param string $property
     *
     * @return string
     */
    protected function getLabel(Block $block, BlockController $controller, string $property = ''): string
    {
        $label = $block->getBlockName() ?: $controller->getBlockTypeName();
        if ($property) {
            $label .= ' ' . $property;
        }

        return $label;
    }

    /**
     * Get the block instance to edit.
     *
     * @param string $identifier
     * @param Page $page
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return Block|null
     */
    protected function getBlockToEdit(string $identifier, Page $page): ?Block
    {
        $chunks = explode('|', $identifier);
        $arHandle = $chunks[0];
        $bID = $chunks[1];
        $b = null;

        $ax = Area::get($page, $arHandle);
        if ($ax) {
            $nvc = $page->getVersionToModify();
            $bx = Block::getByID($bID, $nvc, $ax);

            if (is_object($bx)) {
                if ($bx->isAlias()) {
                    $nb = $bx->duplicate($nvc);
                    $bx->deleteBlock();
                    $b = $nb;
                } else {
                    $b = $bx;
                }
            }
        }

        return $b;
    }

    /**
     * Get an array of record from original block instance.
     *
     * @param Block $block
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @return array
     */
    protected function getOriginalBlockRecord(Block $block): array
    {
        $data = [];
        $controller = $block->getController();
        /** @var BlockRecord $blockRecord */
        $blockRecord = $controller->getBlockControllerData();
        $app = Application::getFacadeApplication();
        /** @var Connection $connection */
        $connection = $app->make(Connection::class);
        $sm = $connection->getSchemaManager();
        $columns = $sm->listTableColumns($controller->getBlockTypeDatabaseTable());
        foreach ($columns as $column) {
            $key = $column->getName();
            $data[$key] = $blockRecord->{$key};
        }

        return $data;
    }

    /**
     * Get all translations that has same identifier in the same translate request.
     *
     * @param TranslateContent $translateContent
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection|TranslateContent[]
     */
    protected function getTranslationsForSameBlock(TranslateContent $translateContent)
    {
        $criteria = Criteria::create();

        return $translateContent->getRequest()->getContents()->matching(
            $criteria->where(Criteria::expr()->eq('source_identifier', $translateContent->getSourceIdentifier()))
                ->andWhere(Criteria::expr()->eq('status', TranslateContent::STATUS_TRANSLATED))
        );
    }
}

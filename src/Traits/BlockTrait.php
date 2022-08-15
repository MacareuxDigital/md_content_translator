<?php

namespace Macareux\ContentTranslator\Traits;

use Concrete\Core\Area\Area;
use Concrete\Core\Block\Block;
use Concrete\Core\Page\Page;

trait BlockTrait
{
    protected function getBlockIdentifier(Block $block): string
    {
        return $block->getAreaHandle() . '|' . $block->getBlockID();
    }

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
}

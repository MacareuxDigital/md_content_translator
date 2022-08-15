<?php

use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Macareux\ContentTranslator\Entity\Translator[] $translators */
?>
<table class="table table-striped">
    <tbody>
    <?php foreach ($translators as $translator) { ?>
        <tr>
            <th>
                <a href="<?= UrlFacade::to('/dashboard/content_translator/translator/config', $translator->getId()) ?>">
                    <i class="fas fa-robot"></i> <?= h($translator->getName()) ?>
                </a>
            </th>
            <th><?= $translator->isActive() ? t('Active') : t('Not available') ?></th>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php

use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die('Access Denied.');

$languages = $languages ?? [];

/** @var \Concrete\Core\Search\Pagination\Pagination $pagination */
if (isset($pagination)) {
    ?>
    <div id="ccm-search-results-table">
        <table class="ccm-search-results-table">
            <thead>
            <tr>
                <?php
                foreach ($languages as $language) {
                    $languageName = \Punic\Language::getName($language);
                    ?>
                    <th><?= h($languageName) ?></th>
                    <?php
                }
                ?>
                <th><?= t('Description') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var \Macareux\ContentTranslator\Entity\GlossaryTerm $term */
            foreach ($pagination->getCurrentPageResults() as $term) {
                ?>
                <tr data-details-url="<?= UrlFacade::to('/dashboard/content_translator/glossary/form', $term->getId()) ?>">
                    <?php
                    foreach ($languages as $language) {
                        $content = '';
                        $translation = $term->getTranslationByLanguage($language);
                        if ($translation) {
                            $content = $translation->getContent();
                        }
                        ?>
                        <td class="ccm-search-results-name"><?= h($content) ?></td>
                        <?php
                    }
                    ?>
                    <td><?= h($term->getDescription()) ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    echo $pagination->renderView('dashboard');
} else {
    ?>
    <p><?= t('You must enable multilingual sections to use glossary.') ?></p>
    <?php
}

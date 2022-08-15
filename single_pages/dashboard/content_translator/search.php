<?php

use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url as UrlFacade;
use Macareux\ContentTranslator\Entity\TranslateRequest;

defined('C5_EXECUTE') or die('Access Denied.');

$app = Application::getFacadeApplication();
/** @var Date $date */
$date = $app->make(Date::class);

/** @var \Concrete\Core\Search\Pagination\Pagination $pagination */
if ($pagination) {
    ?>
    <div id="ccm-search-results-table">
        <table class="ccm-search-results-table">
            <thead>
            <tr>
                <th><?= t('Title') ?></th>
                <th><?= t('Source') ?></th>
                <th><?= t('Target') ?></th>
                <th><?= t('Status') ?></th>
                <th><?= t('Created At') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var TranslateRequest $request */
            foreach ($pagination->getCurrentPageResults() as $request) {
                ?>
                <tr>
                    <td>
                        <?php
                        $page = \Concrete\Core\Page\Page::getByID($request->getCID());
                        if (!$page || $page->isError() || $page->isInTrash()) {
                            echo h($request->getTitle()) . ' ' . t('(Deleted Page)');
                        } elseif ($request->getStatus() === TranslateRequest::STATUS_PUBLISHED) {
                            ?>
                            <a href="<?= UrlFacade::to($page) ?>">
                                <?= h($request->getTitle()) ?>
                            </a>
                            <?php
                        } elseif ($request->getStatus() === TranslateRequest::STATUS_CANCELED) {
                            echo h($request->getTitle());
                        } else { ?>
                            <a href="<?= UrlFacade::to('/dashboard/content_translator/detail', $request->getId()) ?>">
                                <?= h($request->getTitle()) ?>
                            </a>
                        <?php } ?>
                    </td>
                    <td><?= h(Punic\Language::getName($request->getSourceLanguage())) ?></td>
                    <td><?= h(Punic\Language::getName($request->getTargetLanguage())) ?></td>
                    <td><?= $request->getDisplayStatus() ?></td>
                    <td><?= $date->formatPrettyDateTime($request->getCreatedAt()) ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    echo $pagination->renderView('dashboard');
}

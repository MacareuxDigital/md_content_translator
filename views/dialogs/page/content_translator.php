<?php

use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die('Access Denied.');

$defaultSourceLang = $defaultSourceLang ?? '';
$defaultTargetLang = $defaultTargetLang ?? '';
$sourceLang = $sourceLang ?? $defaultSourceLang;
$targetLang = $targetLang ?? $defaultTargetLang;
$languages = $languages ?? [];
$draft = $draft ?? null;
$progress = $progress ?? $draft;

/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Concrete\Package\MdContentTranslator\Controller\Dialog\Page\ContentTranslator $controller */

if ($progress) {
    ?>
    <div class="alert alert-primary" role="alert">
        <?= t('Translate Request of this page is in progress.') ?>
    </div>
    <div class="dialog-buttons">
        <button type="button" class="btn btn-secondary" data-dialog-action="cancel"><?= t('Cancel') ?></button>
        <a href="<?= UrlFacade::to('/dashboard/content_translator/detail', $progress->getId()) ?>"
           class="btn btn-primary ms-auto"><?= t('Continue') ?></a>
    </div>
    <?php
} else {
    ?>
    <div class="ccm-ui">
        <form method="post" action="<?php echo $controller->action('submit') ?>" data-dialog-form="content_translator">
            <?php
            if (isset($published)) {
                ?>
                <div class="alert alert-warning" role="alert">
                    <?= t('This page is already translated. Are you sure you want to translate again?') ?>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <?= $form->label('sourceLang', t('Source Language')) ?>
                <?= $form->select('sourceLang', $languages, $sourceLang) ?>
            </div>
            <div class="form-group">
                <?= $form->label('targetLang', t('Target Language')) ?>
                <?= $form->select('targetLang', $languages, $targetLang) ?>
            </div>
            <div class="dialog-buttons">
                <button type="button" class="btn btn-secondary" data-dialog-action="cancel"><?= t('Cancel') ?></button>
                <button type="submit" class="btn btn-primary ms-auto"
                        data-dialog-action="submit"><?= t('Submit') ?></button>
            </div>
        </form>
    </div>
    <script>
        $(function () {
            $('[data-dialog-form=content_translator]').ajaxForm({
                beforeSubmit: function () {
                    jQuery.fn.dialog.showLoader();
                },
                success: function (r) {
                    jQuery.fn.dialog.hideLoader();
                    jQuery.fn.dialog.closeTop();
                    if (r.error) {
                        ConcreteAlert.error({
                            title: <?php echo json_encode(t('Content Translate')); ?>,
                            message: r.message
                        });
                    } else {
                        ConcreteAlert.notify({
                            title: <?php echo json_encode(t('Content Translate')); ?>,
                            message: r.message
                        });
                    }
                }
            });
        });
    </script>
    <?php
}

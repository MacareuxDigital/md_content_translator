<?php

use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\Support\Facade\Url as UrlFacade;
use Macareux\ContentTranslator\Entity\TranslateContent;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Page\View\PageView $view */
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var \Concrete\Core\Editor\EditorInterface $editor */
/** @var \Macareux\ContentTranslator\Entity\TranslateRequest $request */
$canPublish = $canPublish ?? false;
$translators = $translators ?? [];
if (isset($request)) {
    if (count($translators) > 1) {
        ?>
        <div class="ccm-dashboard-header-buttons">
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#choose-translator"><i
                        class="fas fa-angle-double-right"></i> <?= t('Machine Translators') ?></button>
        </div>
    <?php } ?>
    <form method="post" action="<?= $view->action('submit', $request->getId()) ?>" id="translator">
        <?php $token->output('content_translator') ?>
        <table class="table">
            <thead>
            <tr>
                <th class="col-1" scope="col"></th>
                <th class="col-5" scope="col"><?= \Punic\Language::getName($request->getSourceLanguage()) ?></th>
                <th class="col-5" scope="col"><?= \Punic\Language::getName($request->getTargetLanguage()) ?></th>
                <th class="col-1" scope="col"><?= t('Action') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($request->getContents() as $content) {
                if ($content->getType() === TranslateContent::TYPE_HTML) {
                    ?>
                    <tr>
                        <th class="col-1" scope="row"><?= $content->getLabel() ?></th>
                        <td class="col-5"><?= htmLawed($content->getContent()) ?></td>
                        <td class="col-5">
                            <div class="readonly-editor-content"
                                 data-edit-translate-placeholder="translate_<?= $content->getId() ?>">
                                <?= $content->getTranslated() ?>
                            </div>
                            <div class="editor-content d-none"
                                 data-edit-translate-target="translate_<?= $content->getId() ?>">
                                <?= $editor->outputStandardEditor('translate_' . $content->getId(), LinkAbstractor::translateFromEditMode($content->getTranslated())) ?>
                            </div>
                        </td>
                        <td class="col-1">
                            <button type="button" class="btn btn-primary"
                                    data-edit-translate-editor="translate_<?= $content->getId() ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                } elseif ($content->getType() === TranslateContent::TYPE_TEXT) {
                    ?>
                    <tr>
                        <th class="col-1" scope="row"><?= $content->getLabel() ?></th>
                        <td class="col-5"><?= nl2br($content->getContent()) ?></td>
                        <td class="col-5">
                            <?= $form->textarea('translate_' . $content->getId(), $content->getTranslated(), ['readonly' => true]) ?>
                        </td>
                        <td class="col-1">
                            <button type="button" class="btn btn-primary"
                                    data-edit-translate="translate_<?= $content->getId() ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <th class="col-1" scope="row"><?= $content->getLabel() ?></th>
                        <td class="col-5"><?= $content->getContent() ?></td>
                        <td class="col-5">
                            <?= $form->text('translate_' . $content->getId(), $content->getTranslated(), ['readonly' => true]) ?>
                        </td>
                        <td class="col-1">
                            <button type="button" class="btn btn-primary"
                                    data-edit-translate="translate_<?= $content->getId() ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <a href="<?= UrlFacade::to('/dashboard/content_translator/search'); ?>"
                   class="btn btn-secondary float-start"><?= t('Cancel'); ?></a>
                <?php
                echo $form->button('cancel', t('Discard'), [
                    'class' => 'btn btn-danger float-start ms-2',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#discard-translate',
                ]);
                if ($canPublish) {
                    echo $form->button('publish', t('Publish'), [
                        'class' => 'btn btn-success float-end',
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#publish-translate',
                    ]);
                }
                echo $form->submit('save', t('Save'), [
                    'class' => 'btn btn-primary float-end me-2',
                    'disabled' => 'disabled'
                ]);
                ?>
            </div>
        </div>
    </form>

    <script>
        $('#translator [data-edit-translate]').on('click', function () {
            let target = $(this).data('edit-translate');
            $('#' + target).prop('readonly', false);
            $(this).prop('disabled', true);
            $('#publish').prop('disabled', true);
            $('#save').prop('disabled', false);
        });
        $('#translator [data-edit-translate-editor]').on('click', function () {
            let target = $(this).data('edit-translate-editor');
            $('[data-edit-translate-placeholder=' + target + ']').addClass('d-none');
            $('[data-edit-translate-target=' + target + ']').removeClass('d-none');
            $(this).prop('disabled', true);
            $('#publish').prop('disabled', true);
            $('#save').prop('disabled', false);
        });
    </script>

    <div class="modal fade" id="choose-translator" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="<?= $view->action('translate', $request->getId()) ?>">
                <?= $token->output('content_translator_translate') ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= t('Choose Translator') ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="<?= t('Close') ?>"></button>
                    </div>
                    <div class="modal-body">
                        <?= $form->label('translator', t('Translators')) ?>
                        <?= $form->select('translator', $translators) ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal"><?= t('Close') ?></button>
                        <button type="submit" class="btn btn-primary"><?= t('Translate') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="discard-translate" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" action="<?= $view->action('cancel', $request->getId()) ?>">
                <?= $token->output('content_translator_cancel') ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= t('Discard Request') ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="<?= t('Close') ?>"></button>
                    </div>
                    <div class="modal-body">
                        <?= t('Are you sure you want to discard this request? You can not undo.') ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal"><?= t('Close') ?></button>
                        <button type="submit" class="btn btn-danger"><?= t('Discard') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($canPublish) { ?>
        <div class="modal fade" id="publish-translate" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="post" action="<?= $view->action('publish', $request->getId()) ?>">
                    <?= $token->output('content_translator_publish') ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= t('Publish Translate') ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="<?= t('Close') ?>"></button>
                        </div>
                        <div class="modal-body">
                            <?= t('Are you sure you want to publish this translate to the original page? You can not undo.') ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal"><?= t('Close') ?></button>
                            <button type="submit" class="btn btn-success"><?= t('Publish') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}

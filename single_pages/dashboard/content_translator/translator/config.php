<?php

use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Macareux\ContentTranslator\Entity\Translator $translator */
?>
<form method="post" enctype="multipart/form-data" action="<?= $view->action('save', $translator->getId()) ?>" id="translator">
    <?php $token->output('content_translator_config') ?>
    <div class="form-group">
        <?php
        $element = Element::get('content_translator/translator/' . $translator->getHandle(), ['translator' => $translator, 'form' => $form], 'md_content_translator');
        $element->render();
        ?>
        <div class="form-check">
            <label>
                <?= $form->checkbox('active', 1, $translator->isActive()) ?>
                <?= t('Activate this translator') ?>
            </label>
        </div>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?= UrlFacade::to('/dashboard/content_translator/translator'); ?>"
               class="btn btn-secondary float-start"><?= t('Cancel'); ?></a>
            <?= $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end me-2']); ?>
        </div>
    </div>
</form>

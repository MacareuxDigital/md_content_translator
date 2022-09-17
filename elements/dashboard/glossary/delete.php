<?php

use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var int $termID */
?>
<a class="btn btn-danger" href="#" data-bs-toggle="modal" data-bs-target="#delete-glossary-term">
    <?= t('Delete Term') ?>
</a>
<div class="modal fade" id="delete-glossary-term" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="<?= UrlFacade::to('/dashboard/content_translator/glossary/delete') ?>">
            <?php $token->output('delete_glossary_term') ?>
            <?= $form->hidden('termID', $termID) ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=t('Delete Term')?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="<?= t('Close') ?>"></button>
                </div>
                <div class="modal-body">
                    <?=t('Are you sure you want to delete this glossary term?')?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger float-start"><?=t('Delete')?></button>
                </div>
            </div>
        </form>
    </div>
</div>
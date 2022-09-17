<?php

use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Page\View\PageView $view */
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var array $languages */

/** @var \Macareux\ContentTranslator\Entity\GlossaryTerm|null $term */
$term = $term ?? null;
$termID = $term ? $term->getId() : null;
$description = $description ?? null;
?>

<form method="post" action="<?= $view->action('submit') ?>">
    <?php
    $token->output('content_translation_glossary');
    echo $form->hidden('termID', $termID);
    foreach ($languages as $language) {
        $languageName = \Punic\Language::getName($language);
        $languageContent = null;
        if ($term) {
            $translation = $term->getTranslationByLanguage($language);
            if ($translation) {
                $languageContent = $translation->getContent();
            }
        }
        ?>
        <div class="form-group">
            <?= $form->label($language, $languageName) ?>
            <?= $form->text($language, $languageContent) ?>
        </div>
    <?php
    }
    ?>
    <div class="form-group">
        <?= $form->label('description', t('Description')) ?>
        <?= $form->textarea('description', $description) ?>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?= UrlFacade::to('/dashboard/content_translator/glossary'); ?>" class="btn btn-secondary float-start"><?=  t('Cancel'); ?></a>
            <?= $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end']); ?>
        </div>
    </div>
</form>

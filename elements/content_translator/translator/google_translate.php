<?php
defined('C5_EXECUTE') or die('Access Denied.');
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Macareux\ContentTranslator\Entity\Translator $translator */
$uploaded = 0;
if ($translator->getConfiguration()) {
    $uploaded = 1;
    ?>
    <div class="alert alert-primary">
        <?= t('config.json file is already uploaded.') ?>
    </div>
<?php
}
echo $form->hidden('uploaded', $uploaded);
?>
<h3><?= t('Create a Service Account and upload config.json key.') ?></h3>
<p><?= t('Google Translate requires a %sService Account Credentials%s to connect to the APIs.', '<strong>', '</strong>') ?></p>
<p><?= t('Please follow the %sofficial authentication guide%s to get the JSON key file.', '<a href="https://cloud.google.com/docs/authentication/production#manually" target="_blank" rel="nofollow">', '</a>') ?></p>
<div class="form-group">
    <?= $form->label('config_json', t('Upload config.json file')) ?>
    <?= $form->file('config_json', ['accept' => '.json']) ?>
</div>

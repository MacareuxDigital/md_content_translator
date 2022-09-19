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
<p><?= t('To start using Google Cloud Translation, you need to create a project, enable the Cloud Translation API, add a Service Account, grant permission to the service account, then get the service account key file.') ?></p>
<p><?= t('Please follow the %sofficial "Setup" guide%s to get the JSON key file.', '<a href="https://cloud.google.com/translate/docs/setup" target="_blank" rel="nofollow">', '</a>') ?></p>
<div class="form-group">
    <?= $form->label('config_json', t('Upload config.json file')) ?>
    <?= $form->file('config_json', ['accept' => '.json']) ?>
</div>

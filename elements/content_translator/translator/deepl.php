<?php
defined('C5_EXECUTE') or die('Access Denied.');
/** @var \Concrete\Core\Form\Service\Form $form */
/** @var \Macareux\ContentTranslator\Entity\Translator $translator */

$auth_key = null;
$configuration = $translator->getConfiguration();
if ($configuration) {
    $auth_key = json_decode($configuration, true)['auth_key'];
}

// @todo Make it control options like formality
?>
<p><?= t('To use DeepL machine translation, you need an API authentication key. To get a key, please %screate an account here%s.', '<a href="https://www.deepl.com/pro" target="_blank" rel="nofollow">', '</a>') ?></p>
<div class="form-group">
    <?= $form->label('auth_key', t('Authentication Key for DeepL API')) ?>
    <div class="input-group">
        <?= $form->password('auth_key', $auth_key) ?>
        <button id="showsecret" class="btn btn-outline-secondary" title="<?= t('Show secret key') ?>"><i
                    class="fas fa-eye"></i></button>
    </div>
</div>
<script>
    $('#showsecret').on('click', function (e) {
        e.preventDefault();
        let keyField = $('#auth_key');
        if (keyField.attr('type') === 'password') {
            keyField.attr('type', 'text');
            $('#showsecret')
                .attr('title', <?= json_encode(t('Hide secret key')) ?>)
                .html('<i class="fas fa-eye-slash"></i>')
            ;
        } else {
            keyField.attr('type', 'password');
            $('#showsecret')
                .attr('title', <?= json_encode(t('Show secret key')) ?>)
                .html('<i class="fas fa-eye"></i>')
            ;
        }
    });
</script>

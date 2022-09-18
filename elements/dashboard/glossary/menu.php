<?php

defined('C5_EXECUTE') or die('Access Denied.');

$menuItems = $menuItems ?? [];
?>
<div class="btn-group" role="group" aria-label="<?= t('Glossary Actions') ?>">
    <?php
    foreach ($menuItems as $menuItem) {
        echo $menuItem;
    }
    ?>
</div>

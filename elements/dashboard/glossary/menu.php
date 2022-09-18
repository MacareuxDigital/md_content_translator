<?php

defined('C5_EXECUTE') or die('Access Denied.');

$menuItems = $menuItems ?? [];
foreach ($menuItems as $menuItem) {
    echo $menuItem;
}

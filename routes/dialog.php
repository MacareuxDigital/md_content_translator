<?php

defined('C5_EXECUTE') or die('Access Denied.');
/**
 * @var \Concrete\Core\Routing\Router $router
 *                                    Base path: /ccm/md_content_translator/dialog
 *                                    Namespace: Concrete\Package\MdContentTranslator\Controller\Dialog\
 */
$router->get('/page/content_translator', 'Page\ContentTranslator::view');
$router->post('/page/content_translator/submit', 'Page\ContentTranslator::submit');

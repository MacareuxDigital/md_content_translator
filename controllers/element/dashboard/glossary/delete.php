<?php

namespace Concrete\Package\MdContentTranslator\Controller\Element\Dashboard\Glossary;

use Concrete\Core\Controller\ElementController;

class Delete extends ElementController
{
    public function getElement()
    {
        return 'dashboard/glossary/delete';
    }

    public function view()
    {
        $this->set('form', $this->app->make('helper/form'));
        $this->set('token', $this->app->make('helper/validation/token'));
    }
}
<?php

namespace AreaPhoto\AppBundle\Form;

use Symfony\Component\Form;
use AreaPhoto\AppBundle\Form\Type\LocationType;

class FormFactory
{

    protected $form_factory;

    public function __construct(Form\FormFactory $form_factory)
    {
        $this->form_factory = $form_factory;
    }

    public function createLocationForm($type, $name = null, array $option = [])
    {
        $form = $this->form_factory->createNamed($name, $type, null, $option);
        return new LocationForm($form);
    }
}

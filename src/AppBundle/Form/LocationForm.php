<?php

namespace AppBundle\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use AppBundle\Entity\Location;

class LocationForm implements MediaFormInterface
{

    private $form;
    private $location;

    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->location = new Location();
    }

    public function process(Request $request)
    {
        $this->form->setData($this->location);
        $this->form->handleRequest($request);

        return $this->form;
    }

    public function getData()
    {
        return $this->location;
    }
}

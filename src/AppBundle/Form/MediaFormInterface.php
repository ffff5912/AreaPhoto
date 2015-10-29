<?php

namespace AppBundle\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Form\Form;

interface MediaFormInterface
{
    public function __construct(Form $form);
    public function process(Request $request);
    public function getData();
}

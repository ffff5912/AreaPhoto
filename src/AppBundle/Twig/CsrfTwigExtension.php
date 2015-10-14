<?php

namespace AppBundle\Twig;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;

class CsrfTwigExtension extends \Twig_Extension
{

    protected $csrf_token_provider;

    public function __construct(CsrfProviderInterface $csrf_token_provider)
    {
        $this->csrf_token_provider = $csrf_token_provider;
    }

    public function getName()
    {
        return 'csrf_twig_extension';
    }

    public function getFunctions()
    {
        return array(
            'default_csrf_token' => new \Twig_Function_Method($this, 'getCsrfToken'),
        );
    }

    public function getCsrfToken()
    {
        return $this->csrf_token_provider->generateCsrfToken('default');
    }
}

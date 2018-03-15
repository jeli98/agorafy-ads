<?php

namespace Bundle\Extension;

class TwigFilters extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'imp' => new \Twig_Filter_Method($this, 'imp'),
        );
    }

    public function imp()
    {
		$httpKernel = $this->container->get('http_kernel');
		$route = $this->container->get('request')->attributes->get('_route');

		$response = $httpKernel->forward('Bundle:Ad:imp', array('route' => $route, 'source' => $source));
		return $response->getContent();
    }
}

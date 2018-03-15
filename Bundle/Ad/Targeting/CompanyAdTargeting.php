<?php

namespace Bundle\Ad\Targeting;

class CompanyAdTargeting implements AdTargetingInterface
{
	protected $container;

	protected $route;

	public function __construct($container, $route)
	{
		$this->container = $container;
		$this->route = $route;
	}

	public function apply()
	{
		// based on the current route, retrieve list of ads that can be shown on this page

		return $ads;
	}
}


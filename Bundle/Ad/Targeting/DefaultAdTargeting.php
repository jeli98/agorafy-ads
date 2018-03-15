<?php

namespace Bundle\Ad\Targeting;

class DefaultAdTargeting implements AdTargetingInterface
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function apply()
	{
		// get a list of default ads

		return $ads;
	}
}

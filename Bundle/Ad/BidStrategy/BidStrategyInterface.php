<?php

namespace Bundle\Ad\BidStrategy;

interface BidStrategyInterface
{
	public function sort($a, $b);
}

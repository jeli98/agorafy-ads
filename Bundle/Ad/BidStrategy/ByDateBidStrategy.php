<?php

namespace Bundle\Ad\BidStrategy;

class ByDateBidStrategy implements BidStrategyInterface
{
	public function sort($a, $b)
	{
		if (!array_key_exists('lastImpressionOn', $a) || !array_key_exists('lastImpressionOn', $b)) {
			return 0;
		}

		// regardless of the value of $b, if $a has not been shown yet, then it is smaller
		if (!$a['lastImpressionOn']) {
			return -1;
		}

		// $b has not been shown yet, but $a has so $b is smaller
		if ($a['lastImpressionOn'] && !$b['lastImpressionOn']) {
			return 1;
		}

		$timestampA = strtotime($a['lastImpressionOn']);
		$timestampB = strtotime($b['lastImpressionOn']);

		return $timestampA - $timestampB;
	}
}

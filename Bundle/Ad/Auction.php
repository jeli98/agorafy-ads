<?php

namespace Bundle\Ad;

class Auction
{
	protected $ads;

	protected $bidStrategy;

	public function __construct($ads, $bidStrategy)
	{
		$this->ads = $ads;
		$this->bidStrategy = $bidStrategy;
	}

	public function execute()
	{
		if (!count($this->ads)) {
            return null;
        }

		usort($this->ads, array($this->bidStrategy, 'sort'));

		return $this->ads[0]; // the first ad is the one we will show
	}
}

<?php

namespace Bundle\Controller;

use Bundle\Ad\Targeting\CompanyAdTargeting;
use Bundle\Ad\Targeting\DefaultAdTargeting;
use Bundle\Ad\Auction;
use Bundle\Ad\BidStrategy\ByDateBidStrategy;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdController extends Controller
{
	public function impAction($route, $source)
	{
		$ads = array();
		
		// get a list of company ads that can shown on this page
		$companyAdTargeting = new CompanyAdTargeting($this->container, $route);
		$ads = $companyAdTargeting->apply();

		if (empty($ads)) {
			// there are none, show a default ad instead
			$defaultAdTargeting = new DefaultAdTargeting($this->container);
			$ads = $defaultAdTargeting->apply();
		}

		// now that we have all ads that pass targeting, determine which to display
		$auction = new Auction($ads, new ByDateBidStrategy());
		$ad = $auction->execute();

        $this->updateImpressionDate($ad);

		return new Response($ad['content']);
	}

    protected function updateImpressionDate($ad)
    {
        // make a note of the current timestamp, when this ad was last shown
    }
}

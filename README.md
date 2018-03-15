# Behind the Scenes: Agorafy Ads
On some Agorafy pages, there are ads that show up on the right hand side. Ads for companies that offer real estate services and ads of agents that specialize in certain neighborhoods.

What decides which ad to show? 

Every time the page loads, an auction is conducted to determine which ad gets placement. 
Here is a snippet from AdController.php. Imp is short for impression, as in ad impression.
```php
public function impAction($route)
{
  $ads = array();
  
  $companyAdTargeting = new CompanyAdTargeting($this->container, $route);
  $ads = $companyAdTargeting->apply();
  
  if (empty($ads)) {
    $defaultAdTargeting = new DefaultAdTargeting($this->container);
    $ads = $defaultAdTargeting->apply();
  }
  
  $auction = new Auction($ads, new ByDateBidStrategy());
  $ad = $auction->execute();
  
  return new Response($ad['content']);
}
```

There are two concepts here: Targeting and Bid Strategy.

Targeting determines whether an ad qualifies to be shown on the given page. For example, a company might want to target their ad only on a residential listing page and not on any commercial listing pages. Or an agent might only want to target his or her profile ad on listing pages in particular neighborhoods. 

Each Targeting class takes an input `$route` which will tell it what the current page is. It will look up and return all the ads that pass the targeting criteria and can be shown on the page.

If there are no ads that pass the targeting criteria, then the `DefaultAdTargeting` class will return a list of ads that we have marked as "default" ads. This ensures there will always be something shown in place.

Next, the auction can be conducted. A Bid Strategy is passed in as an argument to determine which Ad will produce the winning bid. The `ByDateBidStrategy` is a simple strategy where the least recent ad that was shown wins. Other strategies may include the ad that is willing to pay the most money. The winning ad's content is returned and displayed.

To make it easier for developers, the imp action is wrapped inside a Twig function. Here is a snippet of the Twig Extension.
```php
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
      'imp' => new \Twig_Filter_Method($this, 'imp');
    );
  }
  
  public function imp()
  {
    $httpKernel = $this->container->get('http_kernel');
    $route = $this->container->get('request')->attributes->get('route');
  }
}
```
Once the Twig function is registered, all one has to do is add one line in the view wherever an ad needs to be shown. (Raw is necessary because the ad content is HTML and it tells Twig not to escape it)
```twig
{{ imp() | raw }}
```
And that's it!

<?php  namespace Logstats\Http\ViewComposers; 

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\View;
use Logstats\Services\Data\CurrentProjectProviderInterface;

class TimezoneViewComposer {

	private $config;

	public function __construct(Repository $config) {
		$this->config = $config;
	}

	public function compose(View $view) {
		$view->with('timezone', $this->config->get('app.timezone'));
	}
}
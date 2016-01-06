<?php  namespace Logstats\App\Http\ViewComposers; 
use Illuminate\Contracts\View\View;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;

class CurrentProjectComposer {

	private $currentProjectProvider;

	public function __construct(CurrentProjectProviderInterface $currentProjectProvider) {
		$this->currentProjectProvider = $currentProjectProvider;
	}

	public function compose(View $view)
	{
		$view->with('currentProject', $this->currentProjectProvider->get());
	}
}
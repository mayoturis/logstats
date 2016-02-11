<?php  namespace Logstats\App\Http\ViewComposers; 
use Illuminate\Contracts\View\View;
use Logstats\App\Installation\StepCollection;

class InstallationStepsViewComposer {

	private $stepCollection;

	public function __construct(StepCollection $stepCollection) {
		$this->stepCollection = $stepCollection;
	}

	public function compose(View $view) {
		$view->with('installationSteps', $this->stepCollection);
	}
}
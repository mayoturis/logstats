<?php  namespace Logstats\App\Http\Controllers; 

use Logstats\App\Providers\Project\CurrentProjectProviderInterface;

class InfoController extends Controller {

	private $currentProjectProvider;

	public function __construct(CurrentProjectProviderInterface $currentProjectProvider) {
		$this->currentProjectProvider = $currentProjectProvider;
	}

	public function howToSendLogs() {
		$project = $this->currentProjectProvider->get();

		return view('info.howToSendLogs');
	}

}
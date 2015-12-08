<?php  namespace Logstats\Http\Controllers; 

use Logstats\Services\Data\CurrentProjectProviderInterface;

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
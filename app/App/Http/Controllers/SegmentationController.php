<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Routing\Controller;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;

class SegmentationController extends Controller {

	private $currentProjectProvider;
	private $gate;

	public function __construct(Gate $gate,
								CurrentProjectProviderInterface $currentProjectProvider) {
		$this->currentProjectProvider = $currentProjectProvider;
		$this->gate = $gate;
	}

	public function index() {
		$project = $this->currentProjectProvider->get();
		if (!$this->gate->check('showSegmentation', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		return view('segmentation.indexx')->with('project', $project);
	}

}
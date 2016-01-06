<?php

namespace Logstats\App\Http\Controllers;

use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;

use Logstats\App\Http\Requests;
use Logstats\App\Http\Controllers\Controller;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;

class LogController extends Controller
{

	private $currentProjectProvider;
	private $gate;

	public function __construct(CurrentProjectProviderInterface $currentProjectProvider,
								Gate $gate) {
		$this->currentProjectProvider = $currentProjectProvider;
		$this->gate = $gate;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$project = $this->currentProjectProvider->get();
		if (!$this->gate->check('showRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		return view('log.index')->with('project', $project);
    }
}

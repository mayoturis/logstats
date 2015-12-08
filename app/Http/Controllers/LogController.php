<?php

namespace Logstats\Http\Controllers;

use Illuminate\Http\Request;

use Logstats\Http\Requests;
use Logstats\Http\Controllers\Controller;
use Logstats\Services\Data\CurrentProjectProviderInterface;

class LogController extends Controller
{

	private $currentProjectProvider;

	public function __construct(CurrentProjectProviderInterface $currentProjectProvider) {
		$this->currentProjectProvider = $currentProjectProvider;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$project = $this->currentProjectProvider->get();

		return view('log.index');
    }
}

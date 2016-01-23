<?php

namespace Logstats\App\Http\Controllers;

use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Logstats\App\Http\Requests;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;
use Logstats\Domain\Project\ProjectService;
use Logstats\App\Validators\ProjectValidator;
use Logstats\Domain\User\RoleTypes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{
	private $projectRepository;
	private $auth;
	private $currentProjectProvider;
	private $projectValidator;
	private $projectService;
	private $gate;

	public function __construct(ProjectRepository $projectRepository,
								Guard $auth,
								CurrentProjectProviderInterface $currentProjectProvider,
								ProjectValidator $projectValidator,
								ProjectService $projectService,
								Gate $gate) {
		$this->projectRepository = $projectRepository;
		$this->auth = $auth;
		$this->currentProjectProvider = $currentProjectProvider;
		$this->projectValidator = $projectValidator;
		$this->projectService = $projectService;
		$this->gate = $gate;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = $this->auth->user();

		if ($user->isGeneralVisitor()) {
			$projects = $this->projectRepository->findAllWithLatestRecord();
		} else {
			$allowedRoles = RoleTypes::allSuperRoles(RoleTypes::VISITOR);
			$projects = $this->projectRepository->findAllWithLatestRecord($allowedRoles, $user->getId());
		}
		return view('projects.index')->with('projectdtos', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		if (!$this->gate->check('create.project')) {
			throw new UnauthorizedException('Access denied');
		}

		return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if (!$this->gate->check('store.project')) {
			throw new UnauthorizedException('Access denied');
		}

		if (!$this->projectValidator->isValidForCreate($request->all())) {
			return redirect()->back()->withInput()->withErrors($this->projectValidator->getErrors(), 'createProject');
		}

		$user = $this->auth->user();
		$this->projectService->createProject($request->get('name'), $user);

		return redirect()->route('projects.index')->with([
			'flash_message' => 'Project successfully created',
			'flash_type' => 'success',
		]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = $this->projectRepository->findById($id);

		if ($project === null) {
			throw new NotFoundHttpException('Project not found');
		}
		if (!$this->gate->check('show', [$project])) {
			throw new UnauthorizedException('Access denied');
		}


		$this->currentProjectProvider->set($project);

		return redirect()->route('log');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$project = $this->projectRepository->findById($id);
		if (!$this->gate->check('delete', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$currentProject = $this->currentProjectProvider->get();
		// if deleted project is same as current project
		if ($currentProject !== null && $currentProject->getId() == $project->getId()) {
			$this->currentProjectProvider->unsetProject();
		}

		$this->projectService->deleteProject($project);

		return redirect()->route('projects.index')->with([
			'flash_message' => 'Project successfully deleted',
			'flash_type' => 'success'
		]);
    }
}

<?php  namespace Logstats\Services\Data; 

use Logstats\Entities\Project;
use Logstats\Repositories\Contracts\ProjectRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCurrentProjectProvider implements CurrentProjectProviderInterface{

	private $projectRepository;
	private $session;
	private $currentProject;

	public function __construct(ProjectRepository $projectRepository, SessionInterface $session) {
		$this->projectRepository = $projectRepository;
		$this->session = $session;
	}
	/**
	 * Set current project
	 *
	 * @param Project $project
	 * @return void
	 */
	public function set(Project $project) {
		$this->currentProject = $project;
		$this->session->set('current_project_id', $project->getId());
	}

	/**
	 * Get current project
	 *
	 * @return Project
	 */
	public function get() {
		if (!empty($this->currentProject)) {
			return $this->currentProject;
		}

		$projectId = $this->session->get('current_project_id');

		if ($projectId === null) {
			return null;
		}

		$project = $this->projectRepository->findById($projectId);
		$this->currentProject = $project;
		return $project;
	}

	/**
	 * If project is set
	 *
	 * @return bool
	 */
	public function isSetProject() {
		return !empty($this->currentProject)
			|| !empty($this->session->get('current_project_id'));
	}
}
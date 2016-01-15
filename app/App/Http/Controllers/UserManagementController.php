<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Logstats\App\Validators\UserManagementValidator;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Project\ProjectRoleList;
use Logstats\Domain\Project\ProjectServiceInterface;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\UserRepository;
use Logstats\Domain\User\UserServiceInterface;

class UserManagementController extends Controller {
	private $userRepository;
	private $projectRepository;
	private $projectService;
	private $userService;
	private $userManagementValidator;

	public function __construct(UserRepository $userRepository,
								ProjectRepository $projectRepository,
								ProjectServiceInterface $projectService,
								UserServiceInterface $userService,
								UserManagementValidator $userManagementValidator) {
		$this->userRepository = $userRepository;
		$this->projectRepository = $projectRepository;
		$this->projectService = $projectService;
		$this->userService = $userService;
		$this->userManagementValidator = $userManagementValidator;
	}

	public function index() {
		$users = $this->userRepository->getAll();
		$projectProjectRoleListDTOs = $this->projectRepository->getAllProjectsWithRoleLists();


		return view('usermanagement.indexx')->with([
			'users' => $users,
			'projectProjectRoleListDTOs' => $projectProjectRoleListDTOs
		]);
	}

	public function saveUsersRoles(Request $request) {
		if (!$this->userManagementValidator->isValidUsersRolesRoot($request->all())) {
			$errors = $this->userManagementValidator->getErrors();
			return $this->redirectToUserManagementWithErrors($errors);
		}

		foreach ($request->get('users') as $userId => $role) {
			$user = $this->userRepository->findById($userId);
			if ($user !== null && $this->userManagementValidator->isValidUserRole($role)) {
				$role = empty($role['role']) ? null : new Role($role['role']);
				$this->userService->setUserRole($user, $role);
			}
		}

		return $this->redirectToUserManagement('Successfuly updated');
	}

	public function saveProjectRoles(Request $request) {
		if (!$this->userManagementValidator->isValidUsersRolesRoot($request->all())) {
			$errors = $this->userManagementValidator->getErrors();
			return $this->redirectToUserManagementWithErrors($errors);
		}

		$project = $this->projectRepository->findById($request->get('project-id'));
		if ($project === null) {
			return $this->redirectToUserManagementWithErrors(['Invalid project id']);
		}

		$projectRoleList = $this->projectRoleListFromArray($request->get('users'));
		$this->projectRepository->saveProjectRoleList($projectRoleList, $project);

		return $this->redirectToUserManagement('Successfuly updated');
	}

	private function projectRoleListFromArray(array $users) {
		$projectRoleList = new ProjectRoleList();
		foreach ($users as $userId => $role) {
			$user = $this->userRepository->findById($userId);
			if ($user !== null && $this->userManagementValidator->isValidUserRole($role)) {
				if (!empty($role['role'])) {
					$projectRoleList->setRole($user, new Role($role['role']));
				}
			}
		}
		return $projectRoleList;
	}

	private function redirectToUserManagementWithErrors($errors) {
		return $this->userManagementRedirector()->withInput()->withErrors($errors, 'userManagement');
	}

	private function redirectToUserManagement($successMessage = null) {
		return $this->userManagementRedirector()->with([
			'flash_message' => $successMessage,
			'flash_type' => 'success'
		]);
	}

	private function userManagementRedirector() {
		return redirect()->route('user-management');
	}
}
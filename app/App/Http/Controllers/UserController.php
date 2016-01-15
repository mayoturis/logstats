<?php

namespace Logstats\App\Http\Controllers;

use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Logstats\App\Http\Requests;
use Logstats\App\Validators\UserValidator;
use Logstats\Domain\User\UserRepository;
use Logstats\Domain\User\UserServiceInterface;

class UserController extends Controller
{

	private $userService;
	private $userRepository;
	private $gate;
	private $userValidator;
	/**
	 *
	 */
	private $guard;

	public function __construct(UserServiceInterface $userService,
								UserRepository $userRepository,
								UserValidator $userValidator,
								Gate $gate,
								Guard $guard) {
		$this->userService = $userService;
		$this->userRepository = $userRepository;
		$this->gate = $gate;
		$this->userValidator = $userValidator;
		$this->guard = $guard;
	}


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if (! $this->userValidator->isValidForCreate($request->all())) {
			return redirect()
				->back()
				->withInput()
				->withErrors($this->userValidator->getErrors(), 'register');
		}

		$user = $this->userService->createUser(
			$request->get('name'),
			$request->get('password'),
			$request->get('email'));

		$this->guard->login($user);
		return redirect()->home();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
		if (!$this->gate->check('delete.user')) {
			throw new UnauthorizedException('Access denied');
		}
		$user = $this->userRepository->findById($id);
		if ($user === null) {
			abort(404);
		}

		$this->userService->delete($user);

		return redirect()->back()->with([
			'flash_message' => 'User successfully deleted',
			'flash_type' => 'success'
		]);
    }


}

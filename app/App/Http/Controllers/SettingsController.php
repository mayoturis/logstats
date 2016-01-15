<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\App\Validators\SettingsValidator;
use Mayoturis\Properties\RepositoryInterface;

class SettingsController extends Controller {

	private $envConfig;
	private $laravelConfig;
	private $settingsValidator;

	public function __construct(RepositoryInterface $envConfig,
								Repository $laravelConfig,
								SettingsValidator $settingsValidator) {
		$this->envConfig = $envConfig;
		$this->laravelConfig = $laravelConfig;
		$this->settingsValidator = $settingsValidator;
	}

	public function index() {
		$timezone = $this->laravelConfig->get('app.timezone');
		return view('settings.indexx')->with([
			'timezone' => $timezone
		]);
	}

	public function store(Request $request) {
		if (!$this->settingsValidator->isValidSettings($request->all())) {
			$errors = $this->settingsValidator->getErrors();
			return redirect()->route('settings')->withInput()->withErrors($errors, 'settings');
		}

		$this->envConfig->set('TIMEZONE', $request->get('timezone'));

		return redirect()->route('settings')->with([
			'flash_message' => 'Successfuly updated',
			'flash_type' => 'success'
		]);
	}
}
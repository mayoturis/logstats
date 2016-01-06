<?php  namespace Logstats\Domain\Services\Installation;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use Mayoturis\Properties\RepositoryInterface;

class InstallationService implements InstallationServiceInterface{

	private $laravelConfig;
	private $envConfig;
	private $str;
	private $steps;

	public function __construct(Repository $laravelConfig,
								RepositoryInterface $envConfig,
								Str $str,
								StepCollection $steps) {
		$this->laravelConfig = $laravelConfig;
		$this->envConfig = $envConfig;
		$this->str = $str;
		$this->steps = $steps;
	}

	public function setRandomAppKey() {
		$cipher = $this->laravelConfig->get('app.cipher');
		$key = $this->getRandomKey($cipher);
		$this->envConfig->set('APP_KEY', $key);
	}

	private function getRandomKey($cipher) {
		if ($cipher === 'AES-128-CBC') {
			return $this->str->random(16);
		}

		return $this->str->random(32);
	}

	public function setInstallationStep($step) {
		$this->envConfig->set('INSTALLATION_STEP', $this->steps->getKeyByShort($step));
	}

	public function setNextInstallationStep($currentStep) {
		$nextStep = $this->steps->nextStepForShort($currentStep);
		$this->setInstallationStep($nextStep['short']);
	}
}
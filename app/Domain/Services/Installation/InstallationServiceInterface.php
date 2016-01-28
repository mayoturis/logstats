<?php  namespace Logstats\Domain\Services\Installation;

interface InstallationServiceInterface {

	/**
	 * Sets application key to random string
	 */
	public function setRandomAppKey();

	/**
	 * @param string $step
	 */
	public function setNextInstallationStep($currentStep);

	/**
	 * @param string $currentStep
	 */
	public function setInstallationStep($step);
}
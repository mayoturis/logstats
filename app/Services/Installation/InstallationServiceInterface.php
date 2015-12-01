<?php  namespace Logstats\Services\Installation; 

interface InstallationServiceInterface {
	public function setRandomAppKey();
	public function setNextInstallationStep($currentStep);
	public function setInstallationStep($step);
}
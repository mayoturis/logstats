<?php  namespace Logstats\Http\Controllers; 


use Illuminate\Http\Request;
use Logstats\Services\Data\DataServiceInterface;

class ArrivedDataController extends Controller {

	/**
	 * @var DataServiceInterface
	 */
	private $dataService;

	/**
	 * @param DataServiceInterface $dataService
	 */
	public function __construct(DataServiceInterface $dataService) {
		$this->dataService = $dataService;
	}

	public function dataArrived(Request $request) {
		$jsonData = $request->get('data');
		$data = json_decode($jsonData);

		if (!is_array($data)) { // invalid data format
			throw new \UnexpectedValueException('Data has to be array');
		}

		$this->dataService->newData($data);
	}
}
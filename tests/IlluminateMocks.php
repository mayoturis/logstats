<?php 

class IlluminateMocks {
	public static function validator() {
		return Mockery::mock('\Illuminate\Contracts\Validation\Factory');
	}

	public static function consoleKernel() {
		return Mockery::mock('Illuminate\Contracts\Console\Kernel');
	}
}
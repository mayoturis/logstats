<?php  namespace Logstats\App\Http\ViewComposers; 
use Illuminate\Contracts\View\View;
use Logstats\Domain\User\User;

class EmptyUserViewComposer {

	public function compose(View $view)
	{
		$user = new User('','','','');
		$view->with('user', $user);
	}
}
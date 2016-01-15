<?php  namespace Logstats\App\Policies; 

use Illuminate\Auth\Access\HandlesAuthorization;
use Logstats\Domain\User\User;

class UserPolicy {
	use HandlesAuthorization;

	public function delete(User $user) {
		return $user->isGeneralAdmin();
	}
}
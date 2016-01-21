<?php

namespace Logstats\App\Exceptions;

use Exception;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mayoturis\Properties\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
		NotFoundHttpException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

		if (config('app.debug')) {
			return $this->renderExceptionWithWhoops($e);
		} else {
			if ($this->isShowableException($e)) {
				return response()->view('errors.installation', ['message' => $e->getMessage()]);
			}

			if ($e instanceof NotFoundHttpException) {
				return response()->view('errors.404');
			}

			if ($e instanceof UnauthorizedException) {
				return response()->view('errors.401');
			}


			return response()->view('errors.500');
		}


		return parent::render($request, $e);
    }

	/**
	 * Render an exception using Whoops.
	 *
	 * @param  \Exception $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderExceptionWithWhoops(Exception $e)
	{
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

		return new \Illuminate\Http\Response(
			$whoops->handleException($e),
			$e->getStatusCode(),
			$e->getHeaders()
		);
	}

	private function isShowableException($e) {
		return ($e instanceof \PDOException) ||
			($e instanceof \InvalidArgumentException && strrpos($e->getMessage(), 'Database', -strlen($e->getMessage())) !== FALSE);
	}
}

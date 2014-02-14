<?php namespace GovTribe\API;

use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$config = $this->app->config['gtapi'];

		$guzzleClient = new \Guzzle\Http\Client($config['uri']);
		$guzzleClient->setDefaultHeaders(array(
			'x-auth-key' => $config['key'],
			'Content-Type' => 'application/json'
		));

		$this->app['Client'] = $this->app->share(function($app) use ($guzzleClient)
		{
			return new Client($guzzleClient);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Client');
	}

}

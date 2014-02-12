<?php namespace GovTribe\Search;

use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider {

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
		$elastica = new \Elastica\Client(array(
			'url' => $this->app->config['elastica']['uri'],
		));

		$config = $this->app->config['elastica'];

		$this->app['Search'] = $this->app->share(function($app) use ($elastica, $config)
		{
			return new Search($elastica, $config);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Search');
	}

}

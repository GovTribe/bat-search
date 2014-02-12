<?php namespace GovTribe\Search;

use Elastica;
use Elastica\Query\Bool;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Facet;
use Carbon;
use Log;

class Search
{
	/**
	 * Elastica client instance.
	 *
	 * @var object
	 */
	protected $elastica;

	/**
	 * Construct an instance of the client.
	 *
	 * @param object Elastica\Client
	 * @param array  $config
	 *
	 * @return void
	 */
	public function __construct(Elastica\Client $elastica, array $config)
	{
		$this->setElastica($elastica);
		$this->setConfig($config);
	}

	/**
	 * Set the Elastica client instance.
	 *
	 * @param Elastica\Client  $elastica
	 *
	 * @return void
	 */
	protected function setElastica(Elastica\Client $elastica)
	{
		$this->elastica = $elastica;
	}

	/**
	 * Set the indexing configuration.
	 *
	 * @param  array $config
	 *
	 * @return void
	 */
	protected function setConfig(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Get the underlying index instance.
	 *
	 * @param  string  $indexName
	 *
	 * @return void
	 */
	public function getIndex($indexName)
	{
		return $this->elastica->getIndex($indexName);
	}

	/**
	 * Get an index's type.
	 *
	 * @param  string  $indexName
	 * @param  string  $typeName
	 *
	 * @return void
	 */
	public function getType($indexName, $typeName)
	{
		return $this->getIndex($indexName)->getType($typeName);
	}

	/**
	 * Perform a BAT query.
	 *
	 * @param  string  $queryString
	 * @param  string  $indexName
	 *
	 * @return void
	 */
	public function doBATQuery($searchString, $indexName = 'entity-name')
	{
		$index = $this->getIndex($indexName);

		$boolQuery = new Bool();

		$multiMatch = new MultiMatch();
		$multiMatch->setQuery($searchString);
		$multiMatch->setFields(array(
			'name.full^3',
			'name.front^2',
			'name.middle',
			'name.back',
			'mail',
			'synopsis',
			'acronym^3'
		));

		// $multiMatch->setParam('fuzziness', 0.8);
		// $multiMatch->setParam('prefix_length', 3);
		// $multiMatch->setParam('max_expansions', 5);
		// $multiMatch->setParam('type', 'bool');
		// $multiMatch->setParam('operator', 'AND');

		$boolQuery->addMust($multiMatch);

		$data = $index->search($boolQuery);

		d($data);

	}

}
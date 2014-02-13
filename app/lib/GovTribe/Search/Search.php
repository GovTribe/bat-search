<?php namespace GovTribe\Search;

use Elastica;
use Elastica\Query;
use Elastica\Query\Builder;
use Elastica\Query\Bool;
use Elastica\Query\MultiMatch;
use Elastica\Facet\Terms;
use Elastica\Query\Term;
use Elastica\Query\Filtered;
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
	 * @return void
	 */
	public function getType($indexName, $typeName)
	{
		return $this->getIndex($indexName)->getType($typeName);
	}

	/**
	 * Perform a BAT search query.
	 *
	 * @param  string  $queryString
	 * @param  string  $indexName
	 * @return void
	 */
	public function doBATQuery($searchString, $indexName = 'entity-name')
	{
		// Base query
		$query = new Query;
		$query->setSize(50);
		$query->setFields(array('name', 'open', 'mail', 'agencies', 'categories'));

		// Add facets
		foreach (array('agencies', 'categories', 'offices') as $value) 
		{
			$facet = new Terms($value);
			$facet->setSize(5);
			$facet->setNested($value);
			$facet->setField($value . '.name');
			$query->addFacet($facet);
		}

		// Setup actual query
		$multiMatch = new MultiMatch();
		$multiMatch->setQuery($searchString);
		$multiMatch->setFields(array(
			'name.full^3',
			'name.front^2',
			'name.middle',
			'name.back',
			'synopsis',
		));

		$boolQuery = new Bool();
		$boolQuery->addMust($multiMatch);

		$filter = new \Elastica\Filter\Term;
		$filter->setTerm('type', 'project');
		$filteredQuery = new Filtered($boolQuery, $filter);

		$query->setQuery($boolQuery);

		return $this->getIndex($indexName)->search($query);
	}

}
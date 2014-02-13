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
	 * @param  string  $searchString
	 * @param  string  $facet
	 * @param  string  $indexName
	 * @return array
	 */
	public function doBATQuery($searchString, $facet, $indexName = 'entity-name')
	{
		Log::info($searchString);

		// Base query
		$query = new Query;
		$query->setSize(50);
		$query->setFields(array('name', 'open', 'mail', 'agencies', 'categories'));

		// Add query facets
		foreach (array('agencies', 'categories', 'offices') as $value) 
		{
			$queryFacet = new Terms($value);
			$queryFacet->setSize(5);
			$queryFacet->setNested($value);
			$queryFacet->setField($value . '.name');
			$query->addFacet($queryFacet);
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

		// Facet filter
		$boolAndFilter = new \Elastica\Filter\BoolAnd;

		// Filter to projects
		$typeFilter = new \Elastica\Filter\Type;
		$typeFilter->setType('Project');
		$boolAndFilter->addFilter($typeFilter);

		// Optionally filter on facets
		if (!empty($facet))
		{
			$facet = explode('xxx', $facet);

			$termFilter = new \Elastica\Filter\Term;
			$termFilter->setTerm($facet[0] . '.name', str_replace('-', ' ', $facet[1]));
			$nestedFilter = new \Elastica\Filter\Nested;
			$nestedFilter->setPath($facet[0]);
			$nestedFilter->setFilter($termFilter);
			$boolAndFilter->addFilter($nestedFilter);
		}

		$query->setQuery($multiMatch);
		$query->setFilter($boolAndFilter);

		return $this->getIndex($indexName)->search($query);
	}

}
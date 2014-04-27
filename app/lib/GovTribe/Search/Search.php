<?php namespace GovTribe\Search;

use Elastica;
use Elastica\Query;
use Elastica\Query\QueryString;
use Elastica\Query\Fuzzy;
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
	 * @param  array   $filterFacets
	 * @param  array   $params
	 * @return array
	 */
	public function doBATQuery($searchString, array $filterFacets, array $params)
	{
		// Get $fields, $indexName, $queryFacets, $size and $from from $params.
		extract($params);

		// The base query.
		$query = new Elastica\Query;
		$query->setSize($size);
		$query->setFrom($from);
		$query->setFields(array_keys($fields));
		$boolQuery = new \Elastica\Query\Bool;

		// The base query's filter.
		$boolAndFilter = new \Elastica\Filter\BoolAnd;

		// Add a filter to only display projects to $boolAndFilter.
		$typeFilter = new \Elastica\Filter\Type;
		$typeFilter->setType('Project');
		$boolAndFilter->addFilter($typeFilter);

		// Apply any user provided filter facets to the$boolAndFilter.
		if (!empty($filterFacets)) $this->applyFilterFacets($boolAndFilter, $filterFacets, $queryFacets);

		// The base query's facets.
		$this->applyQueryFacets($query, $queryFacets);
		
		// Setup the actual query using a function score query
		$functionScoreQuery = new Elastica\Query\FunctionScore;
		$functionScoreQuery->setScoreMode('avg');
		$functionScoreQuery->setMaxBoost(1);

		// Set the function score query's query to a query string query
		$qsQuery = new Elastica\Query\QueryString;
		$qsQuery->setQuery($searchString);
		$qsQuery->setDefaultField('name.full');
		$qsQuery->setFields(array(
			'name.full^2',
			'synopsis',
		));
		$qsQuery->setDefaultOperator('and');
		$qsQuery->setAnalyzeWildcard(true);
		$qsQuery->setAutoGeneratePhraseQueries(true);

		$functionScoreQuery->setQuery($qsQuery);

		// Boot more recent items
		$functionScoreQuery->addDecayFunction('gauss', 'timestamp', date('Y-m-d'), '180d', '180d', 0.2);

		// Boost awarded items
		$boostAwarded = new Elastica\Query\Terms('status', array('Award Notice'));
		$boolQuery->addShould($boostAwarded);

		// Build a filter query that combines the function score query and the query's bool and filter
		$filteredQuery = new Elastica\Query\Filtered($functionScoreQuery, $boolAndFilter);
		$boolQuery->addMust($filteredQuery);

		return $this->getIndex($indexName)->search($query->setQuery($boolQuery));
	}

	/**
	 * Apply query facets.
	 *
	 * @param  Elastica\Query $query
	 * @param  array $queryFacets
	 * @return void
	 */
	protected function applyQueryFacets(Elastica\Query $query, array $queryFacets)
	{
		foreach ($queryFacets as $facetDescription) 
		{
			extract($facetDescription);

			$queryFacet = new Terms($path[0]);
			$queryFacet->setName($label);
			$queryFacet->setSize($limit);

			$isNested = false;
			if (count(explode('.', $path)) > 1) $isNested = true;

			if ($isNested)
			{
				$queryFacet->setNested(explode('.', $path)[0]);
				$queryFacet->setField($path);
			}
			else
			{
				$queryFacet->setField($path);
			}

			$query->addFacet($queryFacet);
		}
	}

	/**
	 * Apply filter facets.
	 *
	 * @param  Elastica\Filter\BoolAnd $boolAnd
	 * @param  array $filterFacets
	 * @param  array $queryFacets
	 * @return void
	 */
	protected function applyFilterFacets(\Elastica\Filter\BoolAnd $boolAndFilter, array $filterFacets, array $queryFacets)
	{
		if (empty($filterFacets)) return;

		$facetsToApply = array();

		// Lookup the description for the facets the user selected.
		foreach ($filterFacets as $filterFacet)
		{
			foreach ($queryFacets as $facetDescription)
			{
				if ($facetDescription['label'] === $filterFacet['type']) 
				{
					$facetsToApply[] = $facetDescription + $filterFacet;
				}
			}
		}

		// Apply the filter facets.
		foreach ($facetsToApply as $facetToApply)
		{
			$isNested = false;
			if (count(explode('.', $facetToApply['path'])) > 1) $isNested = true;

			$termFilter = new \Elastica\Filter\Term;
			$termFilter->setTerm($facetToApply['path'], $facetToApply['name']);

			if ($isNested)
			{
				$nestedFilter = new \Elastica\Filter\Nested;
				$nestedFilter->setPath(explode('.', $facetToApply['path'])[0]);
				$nestedFilter->setFilter($termFilter);
				$boolAndFilter->addFilter($nestedFilter);
			}
			else $boolAndFilter->addFilter($termFilter);
		}
	}

}
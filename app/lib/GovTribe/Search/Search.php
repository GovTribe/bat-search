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
	 * @param  string  $filterFacet
	 * @param  string  $params
	 * @return array
	 */
	public function doBATQuery($searchString, $filterFacet, array $params)
	{
		// Get $fields, $indexName, $queryFacets from $params.
		extract($params);

		// The base query.
		$query = new Query;
		$query->setSize(50);
		$query->setFields(array_keys($fields));

		// The base query's facets.
		$query = $this->applyQueryFacets($query, $queryFacets);

		// The base query's filter.
		$boolAndFilter = new \Elastica\Filter\BoolAnd;

		// Filter the query to only display projects.
		$typeFilter = new \Elastica\Filter\Type;
		$typeFilter->setType('Project');
		$boolAndFilter->addFilter($typeFilter);

		// Apply a user provided filter facet to the query's $boolAndFilter.
		if (!empty($filterFacet)) $this->applyFilterFacet($boolAndFilter, $filterFacet, $queryFacets);
		
		// Setup the actual query.
		$multiMatch = new MultiMatch();
		$multiMatch->setQuery($searchString);
		$multiMatch->setFields(array(
			'name.full^3',
			'name.front^2',
			'name.middle',
			'name.back',
			'synopsis',
		));

		// Apply the query.
		$query->setQuery($multiMatch);

		// Apply the filter.
		$query->setFilter($boolAndFilter);

		return $this->getIndex($indexName)->search($query);
	}

	/**
	 * Apply query facets.
	 *
	 * @param  Elastica\Query $query
	 * @param  array $queryFacets
	 * @return object
	 */
	protected function applyQueryFacets(Elastica\Query $query, $queryFacets)
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

		return $query;
	}

	/**
	 * Apply filter facets.
	 *
	 * @param  Elastica\Filter\BoolAnd $boolAnd
	 * @param  string $filterFacet
	 * @param  array  $queryFacets
	 * @return object
	 */
	protected function applyFilterFacet(\Elastica\Filter\BoolAnd $boolAndFilter,  $filterFacet, $queryFacets)
	{
		$label = explode('xxx', $filterFacet)[0];
		$value = str_replace('-', ' ', explode('xxx', $filterFacet)[1]);

		// Lookup the description for the facet the user selected.
		foreach ($queryFacets as $facetDescription)
		{
			if ($facetDescription['label'] === $label)
			{
				$facetToApply = $facetDescription;
				break;
			} 
		}

		$isNested = false;
		if (count(explode('.', $facetToApply['path'])) > 1) $isNested = true;

		$termFilter = new \Elastica\Filter\Term;
		$termFilter->setTerm($facetToApply['path'], $value);

		if ($isNested)
		{
			$nestedFilter = new \Elastica\Filter\Nested;
			$nestedFilter->setPath(explode('.', $facetToApply['path'])[0]);
			$nestedFilter->setFilter($termFilter);
			$boolAndFilter->addFilter($nestedFilter);
		}
		else
		{
			$boolAndFilter->addFilter($termFilter);
		}

		return $boolAndFilter;
	}

}
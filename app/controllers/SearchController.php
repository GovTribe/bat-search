<?php

class SearchController extends BaseController {

	/**
	 * Perform a search.
	 *
	 * @return Illuminate\Http\Response
	 */
	protected function query()
	{
		// Setup the search parameters
		$params = array(
			'indexName' => 'entity-name',
			'fields' => array(
				'name' => 'Not Listed', 
				'open' => false, 
				'protested' => false,
				'NAICS' => 'N/A',
				'synopsis' => 'No synopsis.',
				'setAsideType' => 'N/A',
				'awardValue' => 'N/A', 
				'goodsOrServices' => 'goods', 
				'people' => array(),
				'vendors' => array(), 
				'agencies' => array(), 
				'categories' => array(), 
				'offices' => array(), 
			),
			'queryFacets' => array(
				'agencies', 'categories', 
				'offices', 'vendors', 'people'	
			)
		);

		// Execute the search
		$result = Search::doBATQuery(Input::get('query'), Input::get('facet'), $params);
		
		// Get hits
		$hits = $this->formatResults($result->getResults(), $params['fields']);

		// Get facets
		$facets = $this->formatFacets($result->getFacets(), $params['queryFacets']);

		return Response::json(array(
			'facets' => (string) View::make('facets')->withFacets($facets),
			'results' => (string) View::make('results')->withHits($hits)
		));
	}

	/**
	 * Format an array of Elastica results.
	 *
	 * @param  array $results
	 * @param  array $fields
	 * @return array
	 */
	protected function formatResults(array $results, array $fields)
	{
		foreach ($results as &$result)
		{
			// Add missing keys and their default values to the result set.
			$result = $result->getData();
			$missing = array_diff_key ($fields, $result);
			$result = array_merge($result, $missing);

			// Clean up project names.
			$result['name'] = preg_replace("#^.*--#", '', $result['name']);
			if (mb_strlen($result['name']) > 100) $result['name'] = Str::limit($result['name'], 100);

			// Limit synopsis to 500 characters, strip tags.
			$result['synopsis'] = trim(strip_tags($result['synopsis']));
			if (mb_strlen($result['synopsis']) < 25 || empty($result['synopsis'])) $result['synopsis'] = $fields['synopsis'];
			$result['synopsis'] = Str::limit($result['synopsis'], 500);

		}

		return $results;
	}

	/**
	 * Format an array of Elastica facets.
	 *
	 * @param  array $facets
	 * @param  array $formatted
	 * @return array
	 */
	protected function formatFacets(array $facets, array $formatted = array())
	{
		// Add a default empty array to each facet type
		foreach ($formatted as $key => $value)
		{
			$formatted[$value] = array();
			unset($formatted[$key]);
		}

		foreach ($facets as $facetName => $facetItems)
		{
			foreach ($facetItems['terms'] as $facetItem) $formatted[$facetName][$facetItem['term']] = $facetItem['count'];
		}

		return $formatted;
	}

}
<?php

class SearchController extends BaseController {

	/**
	 * Perform a search.
	 *
	 * @return Illuminate\Http\Response
	 */
	public function query()
	{
		// Setup the search parameters
		$params = array(
			'indexName' => 'entity-name',
			'fields' => array(
				'_id'  => null,
				'name' => 'Not Listed', 
				'open' => false, 
				'protested' => false,
				'status' => 'Not Listed',
				'timestamp' => 'Not Listed',
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
					array('path' => 'agencies.name', 'label' => 'Agencies', 'limit' => 5, 'order' => 0),
					array('path' => 'categories.name', 'label' => 'Categories', 'limit' => 5, 'order' => 6),
					array('path' => 'offices.name', 'label' => 'Offices', 'limit' => 5, 'order' => 1),
					array('path' => 'vendors.name', 'label' => 'Vendors', 'limit' => 5, 'order' => 4),
					array('path' => 'people.name', 'label' => 'People', 'limit' => 5, 'order' => 3),
					array('path' => 'status', 'label' => 'Status', 'limit' => 5, 'order' => 2),
					array('path' => 'goodsOrServices', 'label' => 'Goods Or Services', 'limit' => 2, 'order' => 7),
					array('path' => 'setAsideType', 'label' => 'Set-Aside Type', 'limit' => 5, 'order' => 5),


				)
			);

		// Execute the search
		$result = Search::doBATQuery(Input::get('query'), Input::get('facet'), $params);
		
		// Get hits
		$hits = $this->formatResults($result->getResults(), $params['fields']);

		// Get facets
		$facets = $this->formatFacets($result->getFacets(), $params['queryFacets']);
		
		return Response::json(array(
			'facets' => (string) View::make('facets', array('facets' => $facets, 'activeFacet' => Input::get('facet'))),
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

			// Format the timestamp
			$date = new DateTime($result['timestamp']);
			$result['timestamp'] = $date->format('Y-m-d');
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
		if (empty($facets)) return array();

		// Sort facets by their 'formatted' key
		usort($formatted, function($a, $b){
			return $a['order'] > $b['order'];
		});
		
		foreach ($formatted as &$value) $value = $value['label'];

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
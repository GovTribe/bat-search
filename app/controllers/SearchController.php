<?php

class SearchController extends BaseController {

	/**
	 * Perform a search.
	 *
	 * @param  array
	 * @param  array
	 * @return void
	 */
	protected function query(array $hits = array(), array $facets = array(
		'agencies' => array(), 'offices' => array(), 'categories' => array()))
	{
		$result = Search::doBATQuery(Input::get('query'));
		
		// Get hits
		foreach ($result->getResults() as $hit) $hits[] = $hit->getData();

		// Get facets
		foreach ($result->getFacets() as $facetName => $facetItems)
		{
			foreach ($facetItems['terms'] as $facetItem) $facets[$facetName][$facetItem['term']] = $facetItem['count'];
		}

		//dd($facets['agencies']);
		$data = json_encode(array(
			'results' => (string) View::make('results')->with('hits', $hits),
			'facets' => (string) View::make('facets')->with('facets', array()),
		));

		dd($data);
		return $data;

	}

}
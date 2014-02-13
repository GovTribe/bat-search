<?php

class SearchController extends BaseController {

	/**
	 * Perform a search.
	 *
	 * @param  array
	 * @return void
	 */
	protected function query(array $hits = array())
	{
		$result = Search::doBATQuery(Input::get('query'));
		
		foreach ($result->getResults() as $hit) $hits[] = $hit->getData();

		return View::make('results')->with('hits', $hits);
	}

}
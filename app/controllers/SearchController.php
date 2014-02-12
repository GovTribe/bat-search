<?php

class SearchController extends BaseController {

	/**
	 * Perform a search.
	 *
	 * @return void
	 */
	protected function query()
	{
		$results = Search::doBATQuery(Input::get('query'));
		
		foreach ($results->getResults() as $item)
		{
			var_dump($item->getData()['name']);
		}
	}

}
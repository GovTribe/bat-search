<?php

class EntityController extends BaseController {

	/**
	 * Initializer.
	 *
	 * @return \EntityController
	 */
	public function __construct()
	{
		Guzzle\Http\StaticClient::mount();
	}

	/**
	 * Get the modal view of an entity.
	 *
	 * @param  string $type
	 * @param  string $id
	 * @return Illuminate\Http\Response
	 */
	protected function getModal($type, $id)
	{
		//Load the entity.
		$client = GTClient::getGuzzleClient();
		$request = $client->get('api/2.0/' . $type . '/' . $id);
		$entity = $request->send()->json();

		if ($type === 'project')
		{
			$entity = $this->formatProjectEntity($entity);
			return View::make('projectmodal')->withEntity($entity);
		}

		return View::make('entitymodal')->withEntity($entity);
	}

	/**
	 * Format a project entity.
	 *
	 * @param  array $project
	 * @return $project
	 */
	protected function formatProjectEntity(array $project)
	{
		// Format the place of performance.
		if (!empty($project['POPs']))
		{
			$extractedPOP = array('city' => null, 'state' => null, 'country' => null);

			foreach ($project['POPs'] as $item)
			{
				switch ($item['attributes']['type']) {
					case 'Country':
						$extractedPOP['country'] = $item['attributes']['name'];
						break;
					case 'ProvinceOrState':
						$extractedPOP['state'] = $item['attributes']['name'];
						break;
					case 'City':
						$extractedPOP['city'] = $item['attributes']['name'];
						break;
				}
			}

			$extractedPOP = array_values(array_filter($extractedPOP));
			$extractedPOP = implode(', ', $extractedPOP);
			$project['POPs'] = $extractedPOP;
		}

		return $project;
	}

}
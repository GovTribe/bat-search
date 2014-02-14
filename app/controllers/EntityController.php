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

		return View::make('entitymodal')->withEntity($entity);
	}
}
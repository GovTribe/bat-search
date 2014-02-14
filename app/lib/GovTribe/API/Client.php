<?php namespace GovTribe\API;

use Log;

class Client
{
	/**
	 * Guzzle client instance.
	 *
	 * @var object
	 */
	protected $guzzleClient;

	/**
	 * Construct an instance of the client.
	 *
	 * @param object Guzzle\Http\Client
	 *
	 * @return void
	 */
	public function __construct(\Guzzle\Http\Client $setGuzzleClient)
	{
		$this->setGuzzleClient($setGuzzleClient);
	}

	/**
	 * Set the Guzzle client instance.
	 *
	 * @param  Guzzle\Http\Client  $guzzleClient
	 * @return void
	 */
	protected function setGuzzleClient(\Guzzle\Http\Client $guzzleClient)
	{
		$this->guzzleClient = $guzzleClient;
	}

	/**
	 * Get the guzzle client instance.
	 *
	 * @return Guzzle\Http\Client
	 */
	public function getGuzzleClient()
	{
		return $this->guzzleClient;
	}
}
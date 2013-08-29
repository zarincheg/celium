<?php
namespace Communication;
/**
 * Describes interface for communication between Celium Clients and Celium Nodes
 * interface CeliumClient.
 * @author Kirill Zorin <zarincheg@gmail.com>
 */
interface CeliumClient {
	/**
	 * Sending request to Celium Node for run action
	 * @param string $request
	 * @return string Key of request. That's need for find results.
	 */
	public function sendRequest($request);

	/**
	 * Fetching result data from Celium Node, by the unique key
	 * @param string $key Unique data key
	 * @return array|null
	 */
	public function getData($key);

	/**
	 * Get notification about requested action complete. Returning request/data key for identify needed results.
	 * (which will used in getData() method)
	 * @return mixed|bool
	 */
	public function getNotify();
}
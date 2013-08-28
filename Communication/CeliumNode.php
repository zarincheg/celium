<?php
namespace Communication;
/**
 * Describes interface for Celium Nodes communication
 * Interface ServiceNode.
 * @author Kirill Zorin <zarincheg@gmail.com>
 */
interface CeliumNode {
	/**
	 * Returning request from service client. For run any actions.
	 * @return string
	 */
	public function request();
	/**
	 * Send notifications about completed action
	 * @param string $message Notification message
	 * @return bool
	 */
	public function notify($message);

	/**
	 * @param $key Unique key for data that is results of action
	 * @param array $data Results of actions
	 * @return bool
	 */
	public function saveData($key, array $data);

	/**
	 * Checking completion and data ready
	 * @param string $key Unique key for data that is results of action
	 * @return bool|array
	 */
	public function checkData($key);

	/**
	 * Add info about request in storage index.
	 * It's for clients with the same requests, that can await results, but task for this request will not duplicated.
	 * @param $key Unique key for request
	 * @return bool
	 */
	public function addToIndex($key);

	/**
	 * @param $key
	 * @return bool|array
	 */
	public function checkIndex($key);
}
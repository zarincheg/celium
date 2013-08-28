<?php
/**
 * Describes interface for Celium Nodes communication
 * Interface ServiceNode.
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
	 * @return bool
	 */
	public function checkData($key);
}
<?php

namespace Required\OpenInbound;

interface FormInterface {

	/**
	 * Method to hook into the form plugin and send the data to OpenInbound.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function sendFormData( $data );

	/**
	 * Method to display help section on the settings screen.
	 *
	 * @return mixed
	 */
	public function showHelpContent();

}
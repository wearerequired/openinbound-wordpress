<?php

namespace Required\OpenInbound;

use OI;

/**
 * Class FormAbstract
 * @package Required\OpenInbound
 */
abstract class FormAbstract implements FormInterface {

	/**
	 * @var OI API.
	 */
	protected $OI_API;

	/**
	 * Send the collected form data to OpenInbound
	 *
	 * @param mixed $data form data or object.
	 */
	public function sendFormData( $data ) {

		if ( ! class_exists( 'OI' ) ) {
			return;
		}

		$this->OI_API = new OI();
	}

	/**
	 * Show help section on the settings screen.
	 */
	public function showHelpContent() {
		// TODO: Implement showHelpContent() method.
	}
}
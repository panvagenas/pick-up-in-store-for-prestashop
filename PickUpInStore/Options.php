<?php
/**
 * pickupinstore
 * Options.php
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 12/12/2014
 * Time: 10:09 πμ
 * Copyright: 2014 Panagiotis Vagenas
 */

namespace PickUpInStore;

if (!defined('_PS_VERSION_'))
	exit;

class Options extends \XDaRk\Options{
	/**
	 *
	 *
	 * @param $defaults
	 * @param $validators
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since TODO ${VERSION}
	 */
	protected function setUp($defaults, $validators)
	{
		$options = array(
			'carrierList' => array(
				'PUIS_CLDE' => 'Pick Up In Store'
			),
			'freeShippingAbove' => 0,
		);
		$moduleValidators = array(
			'PUIS_CLDE' => array('array:!empty'),
			'freeShippingAbove' => array('string:numeric >=' => 0),
		);
		parent::setUp(array_merge($defaults, $options), array_merge($validators, $moduleValidators));
	}
} 
<?php
/**
 * pickupinstore
 * ${FILE_NAME}
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 12/12/2014
 * Time: 9:48 πμ
 * Copyright: 2014 Panagiotis Vagenas
 */

namespace PickUpInStore;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

require_once dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'CarrierModule.php';

class Module extends \XDaRk\CarrierModule {
	/**
	 * TODO
	 * @var string Name of this plugin
	 */
	public $name = 'pickupinstore';
	/**
	 * TODO
	 * @var string Description
	 */
	public $description = 'Pick Up In Module For PrestaShop';
	/**
	 * TODO
	 * @var string
	 */
	public $tab = 'shipping_logistics';
	/**
	 * TODO
	 * @var string
	 */
	public $version = '150202';
	/**
	 * TODO
	 * @var string
	 */
	public $author = 'Panagiotis Vagenas <pan.vagenas@gmail.com>';
	/**
	 * TODO
	 * @var int
	 */
	public $need_instance = 0;
	/**
	 * TODO
	 * @var array
	 */
	public $ps_versions_compliancy = array( 'min' => '1.5' );
	/**
	 * TODO
	 * @var array
	 */
	public $dependencies = array();
	/**
	 * TODO
	 * @var string
	 */
	public $displayName = 'Pick Up In Store';
	/**
	 * TODO
	 * @var bool
	 */
	public $bootstrap = true;

	/**
	 * @return string
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since 150202
	 */
	protected function xdGetContent() {
		return '';

	}

	/**
	 * @param $params
	 * @param $shipping_cost
	 *
	 * @return bool|float|int
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since 150202
	 */
	public function getOrderShippingCost( $params, $shipping_cost ) {
		return $this->getOrderShippingCostExternal( $params );
	}

	public function getContext() {
		return $this->context;
	}

	/**
	 * @param \Cart $cart
	 *
	 * @return bool|float|int
	 * @throws \Exception
	 *
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since 150202
	 */
	public function getOrderShippingCostExternal( $cart ) {
		$addressObj = new \Address( $cart->id_address_delivery );

		$country = mb_strtolower( $addressObj->country );
		$countryChecks = array(
			'greece',
			'ελλάδα',
			'ελλαδα',
			'ελλας',
			'ελλάς',
			'el',
			'el_gr',
			'ellada',
			'ellas',
			'hellas',
			'gr'
		);
		if ( ! in_array( $country, $countryChecks ) ) {
			return false;
		}

		return 0;
	}

	public function getOrderCarrierExtendedInfo(\Cart $cart){
		$carrier = new \Carrier($cart->id_carrier);
		$out = array();
		// FIXME Tranlations not working, maybe a core problem
		if(\Configuration::get('PUIS_CLDE') == $cart->id_carrier && $carrier){
			$out = array(
				$this->moduleInstance->l('Μέθοδος Αποστολής: ', __CLASS__) => $carrier->name,
				'Διεύθυνση: ' => 'Αρτοτίνης 44, 11633 Αθήνα, Κατόπιν Συνεννόησης'
			);
		}

		return $out;
	}

}

$GLOBALS['pickupinstore'] = array(
	'root_ns' => __NAMESPACE__,
	'var_ns'  => 'pis',
	'dir'     => dirname( dirname( __FILE__ ) )
);
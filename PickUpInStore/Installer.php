<?php
/**
 * pickupinstore
 * Installer.php
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 12/12/2014
 * Time: 10:02 πμ
 * Copyright: 2014 Panagiotis Vagenas
 */

namespace PickUpInStore;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class Installer extends \XDaRk\Installer {
	public function xdInstall() {
		foreach ( $this->Options->getValue( 'carrierList' ) as $carrier_key => $carrier_name ) {
			$carrierId = \Configuration::get( $carrier_key );
			$deleted = false;
			if($carrierId > 0){
				$carrier = new \Carrier($carrierId);
				$deleted = $carrier->deleted;
			}
			if ( $carrierId < 1 || $deleted) {
				// Create carrier
				$carrier                     = new \Carrier();
				$carrier->name               = $carrier_name;
				$carrier->id_tax_rules_group = 0;
				$carrier->active             = 1;
				$carrier->deleted            = 0;
				foreach ( \Language::getLanguages( true ) as $language ) {
					// TODO Carrier delay
					$carrier->delay[ (int) $language['id_lang'] ] = ' ';
				}
				$carrier->shipping_handling    = 0;
				$carrier->range_behavior       = 1;
				$carrier->is_module            = 1;
				$carrier->shipping_external    = 1;
				$carrier->external_module_name = $this->moduleInstance->name;
				$carrier->need_range           = 1;
				if ( ! $carrier->add() ) {
					return false;
				}
				// Associate carrier to all groups
				$groups = \Group::getGroups( true );
				foreach ( $groups as $group ) {
					\Db::getInstance()->insert( 'carrier_group', array(
						'id_carrier' => (int) $carrier->id,
						'id_group'   => (int) $group['id_group']
					) );
				}
				// Create price range
				$rangePrice             = new \RangePrice();
				$rangePrice->id_carrier = $carrier->id;
				$rangePrice->delimiter1 = '0';
				$rangePrice->delimiter2 = '10000';
				$rangePrice->add();
				// Create weight range
				$rangeWeight             = new \RangeWeight();
				$rangeWeight->id_carrier = $carrier->id;
				$rangeWeight->delimiter1 = '0';
				$rangeWeight->delimiter2 = '10000';
				$rangeWeight->add();
				// Associate carrier to all zones
				$zones = \Zone::getZones( true );
				foreach ( $zones as $zone ) {
					\Db::getInstance()->insert( 'carrier_zone', array(
						'id_carrier' => (int) $carrier->id,
						'id_zone'    => (int) $zone['id_zone']
					) );
					\Db::getInstance()->insert( 'delivery', array(
						'id_carrier'      => (int) $carrier->id,
						'id_range_price'  => (int) $rangePrice->id,
						'id_range_weight' => null,
						'id_zone'         => (int) $zone['id_zone'],
						'price'           => '0'
					) );
					\Db::getInstance()->insert( 'delivery', array(
						'id_carrier'      => (int) $carrier->id,
						'id_range_price'  => null,
						'id_range_weight' => (int) $rangeWeight->id,
						'id_zone'         => (int) $zone['id_zone'],
						'price'           => '0'
					) );
				}
				copy( self::$instanceBaseDir . '/img/logo.png', _PS_SHIP_IMG_DIR_ . '/' . (int) $carrier->id . '.png' );
				\Configuration::updateValue( $carrier_key, $carrier->id );
			}
		}

		return true;
	}

	public function xdUninstall() {
		foreach ( $this->Options->getValue( 'carrierList' ) as $carrier_key => $carrier_name ) {
			$carrierId = \Configuration::get( $carrier_key );
			if ( $carrierId > 0 ) {
				$carrier = new \Carrier( $carrierId );
				$carrier->delete();
			}
		}

		return true;
	}
} 
<?php
/**
 *	Dominion Simulator
 *  ------------------
 *	A short PHP script that will allow a basic PHP programmer to create
 *	Strategy classes for the board game Dominion and simulate win chances.
 *
 *  @package DominionSimulator
 *	@author Chris Gillis
 *	@license Affero GNU Public License
 */


/**
 * CardType Class
 * 
 * An enumeration of card types
 *
 * @package Dominion Simulator
 */
class CardType {
	const Victory = 0;
	const Treasure = 1;
	const NormalAction = 2;
}

/**
 * Card Class
 *
 * @package Dominion Simulator
 */
class Card {
	function __construct($p) {
		$this->name = array_key_exists('name', $p) ? $p['name'] : NULL;
		$this->cost = array_key_exists('cost',$p) ? $p['cost'] : NULL;
		$this->coin = array_key_exists('coin',$p) ? $p['coin'] : NULL;
		$this->type = array_key_exists('type',$p) ? $p['type'] : NULL;
		$this->vp   = array_key_exists('vp', $p) ? $p['vp'] : NULL;
		$this->effect   = array_key_exists('effect', $p) ? $p['effect'] : NULL;
	}
}

class CardManager {
	private static $cards;
	private static $instance;

	function __construct() {}

	public static function get_instance() {
		if(! isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
			self::$instance->build_cards();
		}
		return self::$instance;
	}

	function build_cards() {
		self::$cards['estate'] = new Card(array(
			'name' => 'Estate',
			'cost' => 2,
			'type' => CardType::Victory,
			'vp' => 1
		));

		self::$cards['duchy'] = new Card(array(
			'name' => 'Duchy',
			'cost' => 5,
			'type' => CardType::Victory,
			'vp' => 3
		));

		self::$cards['province'] = new Card(array(
			'name' => 'Province',
			'cost' => 8,
			'type' => CardType::Victory,
			'vp' => 6
		));

		self::$cards['copper'] = new Card(array(
			'name' => 'Copper',
			'cost' => 0,
			'type' => CardType::Treasure,
			'coin' => 1
		));

		self::$cards['silver'] = new Card(array(
			'name' => 'Silver',
			'cost' => 3,
			'type' => CardType::Treasure,
			'coin' => 2
		));

		self::$cards['gold'] = new Card(array(
			'name' => 'Gold',
			'cost' => 6,
			'type' => CardType::Treasure,
			'coin' => 3
		));

		self::$cards['smithy'] = new Card(array(
			'name' => 'Smithy',
			'cost' => 4,
			'type' => CardType::NormalAction,
			'effect' => function($player) {
				if(DEBUG) echo $player->name . ' doing smithy effect<br>';
				$player->draw(3);
			}
		));

		self::$cards['village'] = new Card(array(
			'name' => 'Village',
			'cost' => 3,
			'type' => CardType::NormalAction,
			'effect' => function($player) {
				if(DEBUG) echo $player->name . ' doing village effect<br>';
				$player->draw(1);
				$player->actions += 2;
			}
		));
	}

	public static function get_cards() {
		return self::$cards;
	}
}
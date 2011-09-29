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
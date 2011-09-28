<?php

class CardType {
	const Victory = 0;
	const Treasure = 1;
}


class Card {
	function __construct($p) {
		$this->name = array_key_exists('name', $p) ? $p['name'] : NULL;
		$this->cost = array_key_exists('cost',$p) ? $p['cost'] : NULL;
		$this->coin = array_key_exists('coin',$p) ? $p['coin'] : NULL;
		$this->type = array_key_exists('type',$p) ? $p['type'] : NULL;
		$this->vp   = array_key_exists('vp', $p) ? $p['vp'] : NULL;
	}
}
<?php

class Player {
	function __construct($p) {
		$this->D = GameState::get_instance();
		$this->name = array_key_exists('name', $p) ? $p['name'] : NULL;
		$this->strat = array_key_exists('strat', $p) ? new $p['strat']($this) : NULL;
		$this->deck = array(
			$this->D->cards['estate'],
			$this->D->cards['estate'],
			$this->D->cards['estate'],
			$this->D->cards['copper'],
			$this->D->cards['copper'],
			$this->D->cards['copper'],
			$this->D->cards['copper'],
			$this->D->cards['copper'],
			$this->D->cards['copper'],
			$this->D->cards['copper'],
		);
		shuffle($this->deck);
		$this->hand = array();
		$this->discard = array();
		$this->draw();
		$this->buys = 1;
		$this->actions = 1;
		$this->determineMoney();

	}

	function draw($num=5) {
		for($i = 0; $i < $num; $i++) {
			if(empty($this->deck)) {
				foreach($this->discard as $card) {
					array_push($this->deck, array_pop($this->discard));
					shuffle($this->deck);
				}
			}
			array_push($this->hand, array_pop($this->deck));
		}
	}

	function discard() {
		foreach($this->hand as $card) {
			array_push($this->discard, array_pop($this->hand));
		}
	}

	public function refresh() {
		if(DEBUG) echo 'refreshing<br><br>';
		$this->discard();
		$this->draw();
		$this->buys = 1;
		$this->actions = 1;
		$this->determineMoney();
	}

	function buy_if_possible($cardwanted) {
		if($this->buys) {
			if($this->money >= $this->D->cards[$cardwanted]->cost) {
				$this->buy($cardwanted);
				return true;
			}
		}
		return false;
	}

	function buy($cardwanted) {
		$this->D->supply[$cardwanted]--;
		$this->money -= $this->D->cards[$cardwanted]->cost;
		$this->buys--;
		array_push($this->discard, clone($this->D->cards[$cardwanted]));
		echo 'bought a ' . $cardwanted . '<br>';
	}

	function determineMoney() {
		foreach($this->hand as $card) {
			if($card->type == CardType::Treasure) {
				$this->money += $card->coin;
			}
		}
	}

	function showHand() {
		echo 'draws: ';
		array_map(function($card){echo $card->name.' ';},$this->hand);
		echo '<br>';
	}
	function showInfo() {
		echo 'Hand: ';
		array_map(function($card){echo $card->name.' ';},$this->hand);
		echo '<br>Discard: ';
		array_map(function($card){echo $card->name.' ';},$this->discard);
		echo '<br>Deck: ';
		array_map(function($card){echo $card->name.' ';},$this->deck);
		echo '<br><br>';
	}
}
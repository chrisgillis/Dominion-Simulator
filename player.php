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
		$this->determineMoney();
		if($this->buys) {
			if($this->money >= $this->D->cards[$cardwanted]->cost) {
				$this->buy($cardwanted);
				return true;
			}
		}
		return false;
	}

	function play_if_possible($card) {
		if($this->actions) {
			if($this->in_hand($card)) {
				call_user_func($this->D->cards[$card]->effect, $this);
				$this->showHand();
			}
		}
	}

	function in_hand($cardwanted) {
		foreach($this->hand as $card) {
			if($this->D->cards[$cardwanted]->name == $card->name){
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
		if(DEBUG) echo 'bought a ' . $cardwanted . '<br>';
	}

	function determineMoney() {
		$money = 0;
		foreach($this->hand as $card) {
			if($card->type == CardType::Treasure) {
				$money += $card->coin;
			}
		}
		$this->money = $money;
	}

	function showHand() {
		if(DEBUG) echo 'draws: ';
		if(DEBUG) array_map(function($card){echo $card->name.' ';},$this->hand);
		if(DEBUG) echo '<br>';
	}
	function showInfo() {
		if(DEBUG) echo 'Hand: ';
		if(DEBUG) array_map(function($card){echo $card->name.' ';},$this->hand);
		if(DEBUG) echo '<br>Discard: ';
		if(DEBUG) array_map(function($card){echo $card->name.' ';},$this->discard);
		if(DEBUG) echo '<br>Deck: ';
		if(DEBUG) array_map(function($card){echo $card->name.' ';},$this->deck);
		if(DEBUG) echo '<br><br>';
	}
}
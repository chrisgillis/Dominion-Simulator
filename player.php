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
 * Player Class
 *
 * The player class controls most player actions during a game. This includes
 * drawing, discarding, trashing, playing cards and buying cards.
 *
 * @package DominionSimulator
 */
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

	/**
	 * Draw Cards
	 *
	 * This function adds a number of cards to the players hand
	 *
	 * @param integer number of cards to draw
	 */
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

	/**
	 * Discard
	 *
	 * This function places all cards in hand to the discard pile.
	 */
	function discard() {
		foreach($this->hand as $card) {
			array_push($this->discard, array_pop($this->hand));
		}
	}

	/**
	 * Refresh
	 *
	 * At the end of each players turn, they will discard all of their cards.
	 * Then, draw 5 new cards. This resets the amount of default buys and
	 * actions, and then sets how much money they have.
	 */
	public function refresh() {
		if(DEBUG) echo 'refreshing<br><br>';
		$this->discard();
		$this->draw();
		$this->buys = 1;
		$this->actions = 1;
		$this->determineMoney();
	}

	/**
	 * Buy If Possible
	 *
	 * Used in a Strategy. Buys the card if it is possible.
	 *
	 * @return boolean whether the card is bought or not
	 */
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

	/** 
	 * Play If Possible
	 *
	 * Used in a Strategy. Plays the card if it is possible.
	 *
	 * @return boolean whether the card is played or not
	 */
	function play_if_possible($card) {
		if($this->actions) {
			if($this->in_hand($card)) {
				call_user_func($this->D->cards[$card]->effect, $this);
				$this->showHand();
			}
		}
	}

	/**
	 * In Hand
	 *
	 * Checks to see if the card is in the players hand
	 *
	 * @return boolean whether the card is in hand or not
	 */
	function in_hand($cardwanted) {
		foreach($this->hand as $card) {
			if($this->D->cards[$cardwanted]->name == $card->name){
				return true;
			} 
		}
		return false;
	}

	/** 
	 * Buy
	 *
	 * Buys a card. Reduces supply. Adjusts money. Reduces buys. 
	 * Puts card in discard pile.
	 *
	 * @see function buy_if_possible()
	 */
	function buy($cardwanted) {
		$this->D->supply[$cardwanted]--;
		$this->money -= $this->D->cards[$cardwanted]->cost;
		$this->buys--;
		array_push($this->discard, clone($this->D->cards[$cardwanted]));
		if(DEBUG) echo 'bought a ' . $cardwanted . '<br>';
	}

	/** 
	 * Determine Money
	 *
	 * Sets player property money.
	 */
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
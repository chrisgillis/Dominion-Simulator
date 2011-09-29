<?php

class GameState {

	private static $instance;

	function __construct() {}

	public static function get_instance() {
		if(! isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
			self::$instance->init();
		}
		return self::$instance;
	}

	public function init() {
		$this->cards = $this->build_cards();
		$this->players = array();
		$this->supply = array(
			'estate' => 12,
			'duchy' => 12,
			'province' => 12,
			'copper' => 30, // Idk
			'silver' => 30, // Idk
			'gold' => 30, // Idk

			'smithy' => 12
		);
		$this->trash = array();
		$this->turn = 1;
	}

	public function addPlayer($p) {
		array_push($this->players, $p);
	}

	public function simulateGame() {
		while($this->supply['province'] > 0) {
			foreach($this->players as $player) {
				if(DEBUG) echo $player->name . ' (Turn '.$this->turn.'): ';
				$player->showHand();
				$this->beforeActionPhase();
				$player->strat->actionPhase();
				$this->beforeBuyPhase();
				$player->strat->buyPhase();
				if($this->supply['province'] == 0) {
					break;
				}
				$player->refresh();
			}
			$this->turn++;
		}
		$this->findWinner();
	}

	public function reset() {
		$this->init();
	}

	public function get_active_player() {
		
	}

	private function beforeActionPhase() {
		if(DEBUG) echo 'taking action phase<br>';
	}

	private function beforeBuyPhase() {
		if(DEBUG) echo 'taking buy phase<br>';
	}

	private function findWinner() {
		if(DEBUG) echo '<br>';
		$scores = array();

		foreach($this->players as $player) {
			$vp = 0;
			$allcards = array_merge($player->hand, $player->deck, $player->discard);
			foreach($allcards as $card) {
				if($card->type == CardType::Victory){
					$vp += $card->vp;
				}
			}
			if(DEBUG) echo $player->name . ' has ' . $vp . ' VP!<br>';
			$scores[$player->name] = $vp;
		}
		if(DEBUG) echo '<br><br>';

		$max_score = max($scores);
		$winners = array_keys($scores, $max_score);

		foreach($winners as $winner) {
			Simulation::setWinner($winner);
		}
	}

	private function build_cards() {
		$cards['estate'] = new Card(array(
			'name' => 'Estate',
			'cost' => 2,
			'type' => CardType::Victory,
			'vp' => 1
		));

		$cards['duchy'] = new Card(array(
			'name' => 'Duchy',
			'cost' => 5,
			'type' => CardType::Victory,
			'vp' => 3
		));

		$cards['province'] = new Card(array(
			'name' => 'Province',
			'cost' => 8,
			'type' => CardType::Victory,
			'vp' => 6
		));

		$cards['copper'] = new Card(array(
			'name' => 'Copper',
			'cost' => 0,
			'type' => CardType::Treasure,
			'coin' => 1
		));

		$cards['silver'] = new Card(array(
			'name' => 'Silver',
			'cost' => 3,
			'type' => CardType::Treasure,
			'coin' => 2
		));

		$cards['gold'] = new Card(array(
			'name' => 'Gold',
			'cost' => 6,
			'type' => CardType::Treasure,
			'coin' => 3
		));

		$cards['smithy'] = new Card(array(
			'name' => 'Smithy',
			'cost' => 4,
			'type' => CardType::NormalAction,
			'effect' => function($player) {
				if(DEBUG) echo $player->name . ' doing smithy effect<br>';
				$player->draw(3);
			}
		));

		return $cards;
	}
}
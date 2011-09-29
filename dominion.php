<?php
error_reporting(E_ALL);
ini_set("display_errors","1");

define("DEBUG", TRUE);

require_once('cards.php');
require_once('gamestate.php');
require_once('player.php');
require_once('simulation.php');
require_once('strategy.php');

class BigMoney extends Strategy {
	function buyPhase() {
		$this->player->buy_if_possible('province');
		$this->player->buy_if_possible('gold');
		$this->player->buy_if_possible('silver');
	}
}

class BigMoneySmithy extends Strategy {

	function init() {
		$this->has_smithy = 0;
	}

	function actionPhase() {
		$this->player->play_if_possible('smithy');
	}

	function buyPhase() {
		if(! $this->has_smithy) {
			if($this->player->buy_if_possible('smithy')) {
				$this->has_smithy = 1;
			}
		}
		$this->player->buy_if_possible('province');
		$this->player->buy_if_possible('gold');
		$this->player->buy_if_possible('silver');
	}
}

$P1 = array(
	'name' => 'Player 1',
	'strat' => 'BigMoney'
);
$P2 = array(
	'name' => 'Player 2',
	'strat' => 'BigMoneySmithy'
);
$P3 = array(
	'name' => 'Player 3',
	'strat' => 'BigMoney'
);


new Simulation(array($P1, $P2, $P3), 100);
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

	public function actionPhase() {
		if(DEBUG) echo 'taking action phase<br>';
	}

	public function buyPhase() {
		if(DEBUG) echo 'taking buy phase<br>';
		$this->player->buy_if_possible('province');
		$this->player->buy_if_possible('gold');
		$this->player->buy_if_possible('silver');
	}
}

$P1 = new Player(array(
	'name' => 'Player 1',
	'strat' => 'BigMoney'
));
$P2 = new Player(array(
	'name' => 'Player 2',
	'strat' => 'BigMoney'
));

new Simulation(array($P1,$P2), 1);
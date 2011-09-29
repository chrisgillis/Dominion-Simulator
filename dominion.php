<?php

/**
 *	Dominion Simulator
 *  ------------------
 *	A short PHP script that will allow a basic PHP programmer to create
 *	Strategy classes for the board game Dominion and simulate win chances.
 *
 *	@author Chris Gillis
 *	@license Affero GNU Public License
 */
error_reporting(E_ALL);
ini_set("display_errors","1");

// This should probably be VERBOSE, not debug. 
// Toggles output!
define("DEBUG", TRUE);


// -------------------------------------------
// - Load Classes
// -------------------------------------------
require_once('cards.php');
require_once('gamestate.php');
require_once('player.php');
require_once('simulation.php');
require_once('strategy.php');


/**
 *  Big Money Strategy
 *  
 * @package Dominion Simulator
 */
class BigMoney extends Strategy {
	function buyPhase() {
		$this->player->buy_if_possible('province');
		$this->player->buy_if_possible('gold');
		$this->player->buy_if_possible('silver');
	}
}

/**
 * Big Money + Smithy Strategy
 *
 * @package Dominion Simulator
 */
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

/**
 * Big Money + Smithy + Duchy Strategy
 *
 * @package Dominion Simulator
 */
class BigMoneySmithyDuchy extends Strategy {
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
		if($this->D->supply['province'] <= 4) {
			$this->player->buy_if_possible('duchy');
		}
		$this->player->buy_if_possible('gold');
		$this->player->buy_if_possible('silver');
	}
}

// --------------------------------------
// - Create Players
// --------------------------------------

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
	'strat' => 'BigMoneySmithyDuchy'
);


// ---------------------------------------
// - Launch simulation
// -   Simulation( array Players, numOfGames)
// ---------------------------------------

new Simulation(array($P1, $P2, $P3), 100);
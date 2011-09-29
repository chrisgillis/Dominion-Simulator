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
class BigMoneyGrandSmithy extends Strategy {
	function init() {
		$this->first_smithy = 0;
		$this->smithies = 0;
	}

	function actionPhase() {
		$this->player->play_if_possible('smithy');
	}

	function buyPhase() {
		if(! $this->first_smithy) {
			if($this->player->buy_if_possible('smithy')) {
				$this->first_smithy++;
			}
		}

		if($this->smithies < 2 
		   && $this->player->has_at_least(1,'silver') 
		   && $this->player->has_at_least(1,'gold')) {

			   if($this->player->buy_if_possible('smithy')) {
			    	$this->smithies++;
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

/**
 * Big Money + Smithy + Village Strategy
 *
 * @package Dominion Simulator
 */
class BigMoneySmithyVillage extends Strategy {
	function init() {
		$this->smithies = 0;
		$this->villages = 0;
		$this->silvers = 0;
	}

	function actionPhase() {
		$this->player->play_if_possible('village');
		$this->player->play_if_possible('village');
		$this->player->play_if_possible('smithy');
		$this->player->play_if_possible('smithy');
		$this->player->play_if_possible('smithy');
		$this->player->play_if_possible('village');
		$this->player->play_if_possible('village');
	}

	function buyPhase() {
		
		$this->player->buy_if_possible('province');
		if($this->D->supply['province'] <= 4) {
			$this->player->buy_if_possible('duchy');
		}

		$this->player->buy_if_possible('gold');

		if($this->smithies < 1) {
			if($this->player->buy_if_possible('smithy')) {
				$this->smithies++;
			}
		}

		if(($this->silvers > 1) && ($this->villages <1) ) {
			if($this->player->buy_if_possible('village')) {
				$this->villages++;
			}
		}

		if($this->player->buy_if_possible('silver')) {
			$this->silvers++;
		}
	}
}

// --------------------------------------
// - Create Players
// --------------------------------------

$P1 = array(
	'name' => 'Player 1',
	'strat' => 'BigMoneyGrandSmithy'
);
$P2 = array(
	'name' => 'Player 2',
	'strat' => 'BigMoneySmithyDuchy'
);
$P3 = array(
	'name' => 'Player 3',
	'strat' => 'BigMoneySmithyVillage'
);



// ---------------------------------------
// - Launch simulation
// -   Simulation( array Players, numOfGames)
// ---------------------------------------

new Simulation(array($P1, $P2, $P3), 100);
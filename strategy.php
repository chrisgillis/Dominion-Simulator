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
 
class Strategy {
	function __construct($player) {
		$this->player = $player;
		$this->D = GameState::get_instance();
		$this->init();
	}
	public function init() {
		
	}

	public function actionPhase() {
	}

	public function buyPhase() {
	}
}
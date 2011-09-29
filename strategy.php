<?php

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
<?php

class Strategy {
	function __construct($player) {
		$this->player = $player;
		$this->D = GameState::get_instance();
	}

	public function actionPhase() {
	}

	public function buyPhase() {
	}
}
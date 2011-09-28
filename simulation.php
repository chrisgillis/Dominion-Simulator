<?php

class Simulation {
	public function __construct($players, $runs) {
		while($runs > 0) {
			$D = GameState::get_instance();
			foreach($players as $player) {
				$D->addPlayer($player);
			}
			$D->simulateGame();
			$runs--;
		}
	}
}
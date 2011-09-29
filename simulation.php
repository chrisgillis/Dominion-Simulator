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
 
class Simulation {

	public static $wins;

	public function __construct($players, $runs) {
		$this->initWins($players);
		$this->runs = $runs;
		
		echo '<div style="width: 700px; height: 600px; overflow: auto;">';
		while($runs > 0) {
			$D = GameState::get_instance();
			foreach($players as $player) {
				$D->addPlayer(new Player($player));
			}
			$D->simulateGame();
			$D->reset();
			$runs--;
		}
		echo '</div><br><br>';
		$this->displayResults($players);
	}

	public function displayResults($players) {
		$total_wins = array_sum(self::$wins);
		$total_ties = $total_wins - $this->runs;

		echo 'There were ' . $total_ties . ' ties<br>';

		foreach($players as $player) {
			$wins = self::$wins[$player['name']];
			echo $player['name'] . ' won ' . $wins . ' game(s) : '
				.( round( (($wins/$total_wins)*100),1 )).'%<br>';
		}

	}

	public static function getWins() {
		return self::$wins;
	}

	public static function setWinner($player) {
		self::$wins[$player] += 1;
	}

	private function initWins($players) {
		self::$wins = array();
		foreach($players as $player) {
			self::$wins[$player['name']] = 0;
		}
	}
}
<?php

function newBoard() {

	for ($i=1; $i<8; $i++) {

		$board[$i] = [];

	}

	return $board;

}

function showBoard($board) {

	$cols = [];

	foreach ($board as $col=>$array) {

		array_push($cols,$col);

		if (count($array) < 6) {

			$padded[$col] = array_pad($array,6,'.');

		} else {

			$padded[$col] = $array;

		}

	}

	print "\n";

	print implode(' ',$cols) . "\n";

	for ($i=5; $i>=0; $i--) {

		$tmp = [];

		foreach ($padded as $col=>$array) {

			array_push($tmp,$array[$i]);

		}

		print implode(' ',$tmp) . "\n";

	}

}

function cols($board) {

	$cols = [];

	foreach ($board as $col=>$array) {

		if (count($array) < 6)

			array_push($cols,$col);

	}

	return $cols;

}

function checkFour($line) {

	foreach ($line as $idx=>$colour) {

		if (isset($now)) {

			$last = $now;

		} else {

			$n = 1;

		}

		$now = $colour;

		if (isset($last)) {

			if ($now == $last) {

				$n++;

			} else {

				$n = 1;

			}

			if ($n > 3)

				return true;

		}

	}

	return false;

}

function go($board,$colour,$cpu) {

	$col = 0;

	showBoard($board);

	$cols =	cols($board);

	if ($cpu == 0) {

		while (!in_array($col, $cols)) {

			$msg = 'player ' . $colour . ' - please choose a column (' . implode(',',$cols) . '): ';

			$col = readline($msg);

		}

	} else {

		sleep(0.5);

		print 'cpu thinking... ' . "\n"; 

		$col = $cols[array_rand($cols,1)];

	}

	sleep(1);

	array_push($board[$col], $colour);

	if (check($board,$col)) {

		win($board,$colour);

		sleep(1);

		return 1;

	} else {

		return $board;

	}

}

function vert($board,$col) {

	$line = [];

	$len = count($board[$col]);

	for ($i=0; $i<$len; $i++) {

		if (isset($board[$col][$i]))

			array_push($line,$board[$col][$i]);

	}

	return $line;

}

function horiz($board,$col) {

	$line = [];

	$row = count($board[$col]) - 1;

	for ($i=1; $i<8; $i++) {

		if (isset($board[$i][$row])) {

			$term = $board[$i][$row];

		} else {

			$term = rand(0,1000);

		}

		array_push($line,$term);

	}

	return $line;

}

function diag($board,$col,$dir) {

	$line = [];

	$idx = count($board[$col]) - 1;

	$width = count($board);

	if ($dir == 1) {

		while (($idx > 0) && ($col > 1)) {

			$col--;

			$idx--;

		}

	} else {

		while (($idx > 0) && ($col < $width)) {

			$col++;

			$idx--;

		}

	}

	while (isset($board[$col][$idx])) {

		array_push($line,$board[$col][$idx]);

		if ($dir == 1) {

			$col++;

		} else {

			$col--;

		}

		$idx++;

	}

	return $line;

}

function check($board,$col) {

	// vertical

	if (checkFour(vert($board,$col)))

		return true;

	// horizontal

	if (checkFour(horiz($board,$col)))

		return true;

	// diagonal (right)

	if (checkFour(diag($board,$col,1)))

		return true;

	// diagonal (left)

	if (checkFour(diag($board,$col,0)))

		return true;

	return false;

}

function win($board,$colour) {

	print "\n";

	print 'connect 4! - ' . $colour . ' wins!' . "\n";

	showBoard($board);

}

// initialise

print 'new game!' . "\n";

$p1 = null;

$win = false;

// colours

while (($p1 != 'R') && ($p1 != 'Y')) {

	$p1 = strtoupper(readline("player 1, please choose a colour (R/Y): "));
}

if ($p1 == 'R') {

	$p2 = 'Y';

} else {

	$p2 = 'R';

}

// number of players

$players = 0;

while (($players != 1) && ($players != 2)) {

	$players = readline("number of players (1/2): ");

}

// game

$board = newBoard();

$colours = [$p1,$p2];

while (!$win) {

	foreach ($colours as $idx=>$colour) {

		if (($idx == 1) && ($players == 1)) {

			$cpu = 1;

		} else {

			$cpu = 0;

		}

		$board = go($board,$colour,$cpu);

		if ($board == 1) {

			$win = true;

			break;

		}

	}

}

// end

print "\n";
print 'thanks for playing!' . "\n";

?>

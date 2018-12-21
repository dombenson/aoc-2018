<?php

$str = file_get_contents("13.txt");
$lines = explode("\n", $str);

$baseGrid = [];
$gridState = [];

foreach ($lines as $line)
{
	$gridState[] = str_split($line);
}

$numCarts = 0;
$baseGrid = $gridState;
foreach($baseGrid as $row=>$line)
{
	foreach($line as $col=>$data)
	{
		switch ($data)
		{
			case "^":
			case "v":
				$baseGrid[$row][$col] = "|";
				$numCarts++;
				break;
			case "<":
			case ">":
				$baseGrid[$row][$col] = "-";
				$numCarts++;
		}
	}
}

$cartStates = [];

$steps = 0;

echo "Initially have $numCarts carts\n";

while(true)
{
	$steps++;
	$orderedCarts = [];
	foreach($gridState as $row=>$line)
	{
		foreach ($line as $col => $data)
		{
			switch ($data)
			{
				case "^":
				case "v":
				case "<":
				case ">":
					$orderedCarts[] = [$row,$col,$data];
			}
		}
	}

	$actNumCarts = count($orderedCarts);
	if($actNumCarts != $numCarts)
	{
		echo "Expected to find $numCarts but got $actNumCarts on step $steps\n";
		print_r($orderedCarts);
		break;
	}

	if(count($orderedCarts) == 1)
	{
		echo "Final cart at {$orderedCarts[0][1]},{$orderedCarts[0][0]}\n";
		break;
	}

	foreach($orderedCarts as $cart)
	{
		$row = $cart[0];
		$col = $cart[1];
		$data = $cart[2];
		$curData = $gridState[$row][$col];
		if($curData != $data)
		{
			echo "Skipping already crashed cart\n";
			continue;
		}
		$numTurns = 0;
		$stateKey = "{$row},{$col}";
		if(array_key_exists($stateKey, $cartStates))
		{
			$numTurns = $cartStates[$stateKey];
			unset($cartStates[$stateKey]);
		}
		$nextPos = null;
		$dir = null;
		$wasCrash = false;

		switch ($data)
		{
			case "^":
				$nextPos = [$row-1,$col];
				break;
			case "v":
				$nextPos = [$row+1,$col];
				break;
			case "<":
				$nextPos = [$row,$col-1];
				break;
			case ">":
				$nextPos = [$row,$col+1];
				break;
		}
		if(isset($nextPos))
		{
			$nextPos[2] = $data;
			$gridState[$row][$col] = $baseGrid[$row][$col];
			$trackData = $gridState[$nextPos[0]][$nextPos[1]];
			switch ($trackData)
			{
				case "^":
				case "v":
				case "<":
				case ">":
					// Crash
					$numCarts -= 2;
					echo "Crash at {$nextPos[1]},{$nextPos[0]} after $steps steps ($numCarts remaining)\nIncoming cart $col,$row,$data\n";
					echo "Removing cart ".($gridState[$nextPos[0]][$nextPos[1]])." to reset to track ".($baseGrid[$nextPos[0]][$nextPos[1]])."\n";
					$wasCrash = true;
					$gridState[$nextPos[0]][$nextPos[1]] = $baseGrid[$nextPos[0]][$nextPos[1]];
					$nextStateKey = "{$nextPos[0]},{$nextPos[1]}";
					unset($cartStates[$nextStateKey]);
					unset($nextPos);
					break;
				case "/":
					switch ($data)
					{
						case "^":
							$dir = ">";
							break;
						case "v":
							$dir = "<";
							break;
						case ">":
							$dir = "^";
							break;
						case "<":
							$dir = "v";
							break;
					}
					break;
				case "\\":
					switch ($data)
					{
						case "^":
							$dir = "<";
							break;
						case "v":
							$dir = ">";
							break;
						case ">":
							$dir = "v";
							break;
						case "<":
							$dir = "^";
							break;
					}
					break;
				case "+":
					$turnMode = $numTurns % 3;
					$numTurns++;
					switch ($turnMode)
					{
						case 0:
							// Left
							switch ($data)
							{
								case "^":
									$dir = "<";
									break;
								case "v":
									$dir = ">";
									break;
								case ">":
									$dir = "^";
									break;
								case "<":
									$dir = "v";
							}
							break;
						case 1:
							// Go straight
							break;
						case 2:
							// Right
							// Left
							switch ($data)
							{
								case "^":
									$dir = ">";
									break;
								case "v":
									$dir = "<";
									break;
								case ">":
									$dir = "v";
									break;
								case "<":
									$dir = "^";
							}
							break;
					}
			}
		}
		if(isset($nextPos))
		{
			if($wasCrash)
			{
				echo "Unexpectedly have a $nextPos after crash\n";
			}
			if(isset($dir))
			{
				$nextPos[2] = $dir;
			}
			$nextStateKey = "{$nextPos[0]},{$nextPos[1]}";
			$cartStates[$nextStateKey] = $numTurns;
			$gridState[$nextPos[0]][$nextPos[1]] = $nextPos[2];

		}
	}
}
